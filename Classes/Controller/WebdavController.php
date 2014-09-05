<?php

namespace KayStrobach\Webdav\Controller;

use KayStrobach\Webdav\Bootstrap\Bootstrap;
use KayStrobach\Webdav\Bootstrap\BootstrapDav;
use KayStrobach\Webdav\Utilities\CyberDuckUtility;
use KayStrobach\Webdav\WebDav\Plugin\BrowserPlugin;
use KayStrobach\Webdav\WebDav\FileSystem\Root;
use KayStrobach\Webdav\WebDav\Plugin\PermissionPlugin;
use KayStrobach\Webdav\WebDav\Nodes\Folder\WebDavRootDirectory;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class WebdavController
 *
 * @package KayStrobach\Webdav\Controller
 */
class WebdavController {
	/**
	 * @var string The base uri of the server
	 */
	private $baseUri = NULL;

	/**
	 * @var \Sabre_HTTP_BasicAuth
	 */
	private $auth;

	/**
	 * @var \Sabre_DAV_ObjectTree
	 */
	private $objectTree;

	/**
	 * Main function of the Controller / main entry point
	 *
	 * @return void
	 */
	public function main() {
		Bootstrap::initTcaAndTsfe();
		Bootstrap::initBackendUser();

		$extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['webdav']);
		if (substr($_SERVER['PATH_INFO'], 0, 4) === '/dav') {
			$this->baseUri = $_SERVER['SCRIPT_NAME'] . '/dav';
			BootstrapDav::initialize();
			if ($this->authenticate()) {
				$this->buildVirtualFilesystem();
				$this->handleRequest();
			}
			die();
		} elseif ($_SERVER['SERVER_NAME'] === $extConfig['davOnlyHostname']) {
			$this->baseUri = dirname($_SERVER['SCRIPT_NAME']);
			BootstrapDav::initialize();
			if ($this->authenticate()) {
				$this->buildVirtualFilesystem();
				$this->handleRequest();
			}
			die();
		} elseif (substr($_SERVER['PATH_INFO'], 0, 15) === '/cyberduck.duck') {
			CyberDuckUtility::sendBookmark();
			die();
		}
	}

	/**
	 * Authenticate the user
	 *
	 * @return bool
	 */
	public function authenticate() {
		$this->auth = new \Sabre_HTTP_BasicAuth();
		$result     = $this->auth->getUserPass();
		$GLOBALS['BE_USER']->setBeUserByName($result[0]);

		if (!$result || !$this->checkUserCredentials($GLOBALS['BE_USER']->user, $result[1])) {
			$this->auth->setRealm('WebDav for TYPO3');
			$this->auth->requireLogin();

				// Render template with fluid
			$base      = dirname(dirname($this->baseUri)) == '/' ? '/' : dirname(dirname($this->baseUri)) . '/';
			$extRoot   = $base . ExtensionManagementUtility::siteRelPath('webdav');
			$typo3root = $base . 'typo3/';
			$view = GeneralUtility::makeInstance('Tx_Fluid_View_StandaloneView');
			$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('webdav') . 'Resources/Public/Templates/accessdenied.html');
				//asign
			$view->assign('extRoot', $extRoot);
			$view->assign('typo3Root', $typo3root);
			$view->assign('sabre', array(
					'version'   => \Sabre_DAV_Version::VERSION,
					'stability' => \Sabre_DAV_Version::STABILITY,
				)
			);
			echo $view->render();
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Check password of user with a given one
	 *
	 * Thanks to Georg Ringer (typo3.dev mailinglist 15.02.2012)
	 *
	 * @param array $userRecord
	 * @param string $password
	 * @return boolean
	 */
	private function checkUserCredentials(array $userRecord, $password) {
		if (ExtensionManagementUtility::isLoaded('saltedpasswords', FALSE)) {
			$this->objInstanceSaltedPW = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance($userRecord['password'], 'BE');
			if (is_object($this->objInstanceSaltedPW)) {
				return $this->objInstanceSaltedPW->checkPassword($password, $userRecord['password']);
			}
		}
		return md5($password) == $userRecord['password'];
	}

	/**
	 * build the virtual file system
	 *
	 * @return void
	 */
	public function buildVirtualFilesystem() {
		//--------------------------------------------------------------------------
		// create virtual directories for the filemounts in typo3
		$mounts = Root::buildRootFolders();

		$root = new \Sabre_DAV_SimpleCollection('root', $mounts);
		$this->objectTree = new \Sabre_DAV_ObjectTree($root);
	}

	/**
	 * handle the request
	 *
	 * @return void
	 */
	public function handleRequest() {
		// configure dav server
		$server = new \Sabre_DAV_Server($this->objectTree);

		$server->setBaseUri($this->baseUri);
		//$server->setBaseUri('typo3conf/ext/ks_sabredav/webdavserver.php/');
		//----------------------------------------------------------------------
		// add plugins
		$lockBackend = new \Sabre_DAV_Locks_Backend_FS('data');
		$server->addPlugin(new \Sabre_DAV_Mount_Plugin());
		$server->addPlugin(new \Sabre_DAV_Locks_Plugin($lockBackend));
		//$server->addPlugin(new BrowserPlugin());
		$server->addPlugin(new PermissionPlugin());

		// for 1.2.x alpha only
		//$server->addPlugin(new Sabre_DAV_Browser_GuessContentType());

		//----------------------------------------------------------------------
		// start server
		$server->exec();
	}
}
