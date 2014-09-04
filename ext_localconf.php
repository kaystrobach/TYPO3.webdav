<?php
	if(version_compare(TYPO3_version,'4.5.999','<=')) {
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/index_ts.php']['preBeUser'][]         = 'EXT:webdav/Classes/Controller/WebdavController.php:tx_Webdav_Controller_WebdavController->main';
	} else {
		$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/index_ts.php']['preprocessRequest'][] = 'EXT:webdav/Classes/Controller/WebdavController.php:tx_Webdav_Controller_WebdavController->main';
	}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['docHeaderButtonsHook'][] = 'EXT:webdav/Classes/class.tx_webdav_pagerendererHook.php:tx_webdav_pagerendererHook->render';
?>