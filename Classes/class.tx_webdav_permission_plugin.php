<?php

class tx_webdav_permission_plugin extends Sabre_DAV_ServerPlugin {
	public $server;
	//--------------------------------------------------------------------------
	/**
	 * get Features function
	 */ 	 	
	function getFeatures() {
		return array();
	}
	//--------------------------------------------------------------------------
	/**
	 * get Features function
	 */
	function initialize(Sabre_DAV_Server $server) {
		$this->server = $server;
		$this->server->subscribeEvent('beforeBind'        ,array($this,'beforeBind'));
		$this->server->subscribeEvent('beforeUnbind'      ,array($this,'beforeUnbind'));
		$this->server->subscribeEvent('beforeWriteContent',array($this,'beforeWriteContent'));
		#$this->server->subscribeEvent('afterGetProperties',array($this,'afterGetProperties'));
	}
	//--------------------------------------------------------------------------
	/**
	 * decide wether the creation of a node is allowed 	 	 	 	 
	 */
	function beforeBind($path) {
		global $BE_USER;
		global $fileMounts;
		global $TYPO3_CONF_VARS;
		
		// allow admins to create all filetypes ...
		//*
		if ($BE_USER->isAdmin()) {
			return true;
		}//*/
		
		// allow only some filetypes for normal users.
		$t3File = new t3lib_basicFileFunctions();
		$t3File->init($fileMounts,$TYPO3_CONF_VARS['BE']['fileExtensions']);
		
		//check path in mount rules
		
		// explode by dot and get last chars after dot as extension
		$ext = array_pop(explode('.',$path));
		// check if it is allowed to change a specific file
		if(!$t3File->checkIfAllowed($ext,dirname($path),basename($path))) {
			throw new Sabre_DAV_Exception_Forbidden('File extension "'.$ext.'" not allowed');
			// stop when filetype is false
			return false;
		}
		//return false to allow operation
		return true;
	}
	//--------------------------------------------------------------------------
	/**
	 * decide wether deletion of a node is allowed 	 	 	 	 
	 */
	function beforeUnbind($path) {
		return $this->beforeBind($path);
	}
	//--------------------------------------------------------------------------
	/**
	 * decide wether deletion of a node is allowed 	 	 	 	 
	 */
	function beforeWriteContent($path) {
		return $this->beforeBind($path);
	}
	//--------------------------------------------------------------------------
	/**
	 * add additional permissions 	 	 	 	 
	 */
	/*
	function afterGetProperties($uri, &$properties) {
		try{
			$this->beforeBind($uri);
		} catch(Sabre_DAV_Exception_Forbidden $e) {
			$properties[200]['{DAV:}can_write']            = false;
			$properties[200]['{DAV:}can_write_properties'] = false;
			$properties[200]['{DAV:}can_write_content']    = false;
			$properties[200]['{DAV:}can_bind']             = false;
			$properties[200]['{DAV:}can_unbind']           = false;
			$properties[200]['{DAV:}can_read']             = true;
		}
		return true;
	}//*/
}
