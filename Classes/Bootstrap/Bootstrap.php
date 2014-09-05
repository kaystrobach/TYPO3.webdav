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
	 */
	public static function initBackendUser() {
		global $BE_USER, $TYPO3_CONF_VARS;
		// create a new backendusersession ;) need to use basic auth here
		$BE_USER = GeneralUtility::makeInstance('t3lib_tsfeBeUserAuth'); // New backend user object
		$BE_USER->warningEmail = $TYPO3_CONF_VARS['BE']['warning_email_addr'];
		$BE_USER->lockIP = $TYPO3_CONF_VARS['BE']['lockIP'];
		$BE_USER->auth_timeout_field = intval($TYPO3_CONF_VARS['BE']['sessionTimeout']);
		$BE_USER->OS = TYPO3_OS;
		// deactivate caching for be user
		if (version_compare(TYPO3_version,'4.5','<=')) {
			$BE_USER->userTS_dontGetCached = 1;
		}
		$BE_USER->start();
		$BE_USER->unpack_uc('');
	}

	public static function initDav() {

	}

}