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
		/** @var \t3lib_tsfeBeUserAuth $beUser */
		$beUser = GeneralUtility::makeInstance('t3lib_tsfeBeUserAuth');
		$beUser->warningEmail = $GLOBALS['TYPO3_CONF_VARS']['BE']['warning_email_addr'];
		$beUser->lockIP = $GLOBALS['TYPO3_CONF_VARS']['BE']['lockIP'];
		$beUser->auth_timeout_field = intval($GLOBALS['TYPO3_CONF_VARS']['BE']['sessionTimeout']);
		$beUser->OS = TYPO3_OS;
		$beUser->start();
		$beUser->unpack_uc('');
		$beUser->fetchGroupData();
		$GLOBALS['BE_USER'] = $beUser;
	}

	/**
	 * @return void
	 */
	public static function initDav() {

	}

}