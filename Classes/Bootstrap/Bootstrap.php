<?php

namespace KayStrobach\Webdav\Bootstrap;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * General Bootstrap
 *
 * @package KayStrobach\Webdav\Bootstrap
 */
class Bootstrap {
	/**
	 * initialize the table configuration and the TSFE
	 *
	 * @return void
	 */
	public static function initTcaAndTsfe() {
		\TYPO3\CMS\Core\Core\Bootstrap::getInstance()->loadCachedTca();
		if (is_null($GLOBALS['TSFE']->sys_page)) {

			// needed to get the abstract repo call for enable fields working
			$GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Page\PageRepository');
		}
	}

	/**
	 * initialize Backenduser
	 * create a new backendusersession ;) need to use basic auth here
	 *
	 * @return void
	 */
	public static function initBackendUser() {
		// New backend user object
		$GLOBALS['BE_USER'] = GeneralUtility::makeInstance('t3lib_tsfeBeUserAuth');
		$GLOBALS['BE_USER']->warningEmail = $GLOBALS['TYPO3_CONF_VARS']['BE']['warning_email_addr'];
		$GLOBALS['BE_USER']->lockIP = $GLOBALS['TYPO3_CONF_VARS']['BE']['lockIP'];
		$GLOBALS['BE_USER']->auth_timeout_field = intval($GLOBALS['TYPO3_CONF_VARS']['BE']['sessionTimeout']);
		$GLOBALS['BE_USER']->OS = TYPO3_OS;
		$GLOBALS['BE_USER']->start();
		$GLOBALS['BE_USER']->unpack_uc('');
	}

	/**
	 * @return void
	 */
	public static function initDav() {

	}

}