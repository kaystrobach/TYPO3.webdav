<?php

namespace KayStrobach\Webdav\WebDav\Plugin;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class BrowserPlugin
 *
 * @package KayStrobach\Webdav\WebDav\Plugin
 */
class BrowserPlugin extends \Sabre_DAV_Browser_Plugin {
	/**
	 * Generates the html directory index for a given url
	 *
	 * @param string $path
	 * @return string
	 */
	public function generateDirectoryIndex($path) {
		$base            = dirname(dirname($this->server->getBaseUri())) == '/' ? '/' : dirname(dirname($this->server->getBaseUri())) . '/';
		$this->extRoot   = $base . ExtensionManagementUtility::siteRelPath('webdav');
		$this->typo3root = $base . 'typo3/';

		$tempFiles = $this->server->getPropertiesForPath(
			$path,
			array(
				'{DAV:}resourcetype',
				'{DAV:}getcontenttype',
				'{DAV:}getcontentlength',
				'{DAV:}getlastmodified',
			),
			1
		);

		$k = 0;
		$files = array();
		foreach ($tempFiles as $file) {
			// This is the current directory, we can skip it
			if (rtrim($file['href'], '/') == $path) {
				continue;
			}
			$files[] = $this->renderFile($file, $path);
		}

		// Render template with fluid
		$view = GeneralUtility::makeInstance('TYPO3\CMS\Fluid\View\StandaloneView');
		$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('webdav') . 'Resources/Public/Templates/filelist.html');

		//asign
		$view->assign('files', $files);
		$view->assign('extRoot', $this->extRoot);
		$view->assign('typo3Root', $this->typo3root);
		$view->assign('davRoot', $this->server->getBaseUri());
		$view->assign('path', $path);
		$view->assign(
			'sabre',
			array(
				'version'   => \Sabre_DAV_Version::VERSION,
				'stability' => \Sabre_DAV_Version::STABILITY,
			)
		);

		//render
		return $view->render();
	}

	/**
	 * @param $file
	 * @param $path
	 * @return array
	 */
	public function renderFile($file, $path) {
		$name = $this->escapeHTML(basename($file['href']));
		$type = NULL;
		if (isset($file[200]['{DAV:}resourcetype'])) {
			$type = $file[200]['{DAV:}resourcetype']->getValue();
			// resourcetype can have multiple values
			if (is_array($type)) {
				$type = implode(', ', $type);
			}
			// Some name mapping is preferred
			switch($type) {
				case '{DAV:}collection':
					$type = 'Collection';
					break;
				default:
					// do nothing
			}
		}
		// If no resourcetype was found, we attempt to use
		// the contenttype property
		if (!$type && isset($file[200]['{DAV:}getcontenttype'])) {
			$type = $file[200]['{DAV:}getcontenttype'];
		}
		if (!$type) {
			$type = 'Object';
		}
		$type = $this->escapeHTML($type);
		$size = isset($file[200]['{DAV:}getcontentlength']) ? (int)$file[200]['{DAV:}getcontentlength']:'';
		$lastmodified = isset($file[200]['{DAV:}getlastmodified']) ? $file[200]['{DAV:}getlastmodified']->getTime()->format('d.m.Y H:i:s'):'';
		$fullPath = '/' . trim($this->server->getBaseUri() . ($path ? $this->escapeHTML($path) . '/':'') . $name, '/');
		$isImage = FALSE;
		if ($type == 'Collection') {
			$icon = $this->typo3root . 'sysext/t3skin/icons/gfx/i/_icon_webfolders.gif';
			$fullPath .= '/';
		} else {
			$extension = array_pop(explode('.', $name));
			if (file_exists(TYPO3_mainDir . 'gfx/fileicons/' . $extension . '.gif')) {
				$icon = $this->typo3root . 'gfx/fileicons/' . $extension . '.gif';
				if (strpos(',' . $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] . ',', $extension)) {
					$isImage = TRUE;
				}
			} else {
				$icon = $this->typo3root . 'gfx/fileicons/default.gif';
			}
		}

		return array(
			'type' => $type,
			'lastchange' => $lastmodified,
			'name'       => $name,
			'size'       => $size,
			'icon'       => $icon,
			'fullpath'   => $fullPath,
			'isImage'    => $isImage,
		);
	}
}