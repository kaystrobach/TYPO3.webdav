<?php

namespace KayStrobach\Webdav\WebDav\Plugin;

use TYPO3\CMS\Core\Utility\File\BasicFileUtility;

/**
 * Checks the permission of the user during the requests
 *
 * @package KayStrobach\Webdav\WebDav\Plugin
 */
class PermissionPlugin extends \Sabre_DAV_ServerPlugin {
	/**
	 * @var \Sabre_DAV_Server
	 */
	public $server;

	/**
	 * get Features function
	 */
	function getFeatures() {
		return array();
	}


	/**
	 * get Features function
	 */
	public function initialize(\Sabre_DAV_Server $server) {
		$this->server = $server;
		$this->server->subscribeEvent('beforeBind', array($this, 'beforeBind'));
		$this->server->subscribeEvent('beforeUnbind', array($this, 'beforeUnbind'));
		$this->server->subscribeEvent('beforeWriteContent', array($this, 'beforeWriteContent'));
		#$this->server->subscribeEvent('afterGetProperties', array($this, 'afterGetProperties'));
	}
	//--------------------------------------------------------------------------
	/**
	 * decide wether the creation of a node is allowed 	 	 	 	 
	 */
	public function beforeBind($path) {
		// allow admins to create all filetypes ...
		if ($GLOBALS['BE_USER']->isAdmin()) {
			return true;
		}
		
		// allow only some filetypes for normal users.
		$t3File = new BasicFileUtility();
		$t3File->init(
			$GLOBALS['fileMounts'],
			$GLOBALS['TYPO3_CONF_VARS']['BE']['fileExtensions']
		);
		
		//check path in mount rules
		
		// explode by dot and get last chars after dot as extension
		$ext = array_pop(explode('.', $path));
		// check if it is allowed to change a specific file
		if (!$t3File->checkIfAllowed($ext, dirname($path), basename($path))) {
			throw new \Sabre_DAV_Exception_Forbidden('File extension "' . $ext . '" not allowed');
			// stop when filetype is false
			return FALSE;
		}
		//return false to allow operation
		return TRUE;
	}
	//--------------------------------------------------------------------------
	/**
	 * decide wether deletion of a node is allowed
	 */
	public function beforeUnbind($path) {
		return $this->beforeBind($path);
	}

	//--------------------------------------------------------------------------
	/**
	 * decide wether deletion of a node is allowed
	 */
	public function beforeWriteContent($path) {
		return $this->beforeBind($path);
	}
}
