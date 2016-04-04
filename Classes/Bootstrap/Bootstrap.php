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
		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',
			$GLOBALS['$TYPO3_CONF_VARS'],
			\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id'),
			\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('type'),
			\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('no_cache'),
			\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('cHash'),
			\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('jumpurl'),
			\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('MP'),
			\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('RDCT')
		);
		\TYPO3\CMS\Core\Core\Bootstrap::getInstance()->loadCachedTca();
		if (! ($GLOBALS['TSFE']->sys_page instanceof  \TYPO3\CMS\Frontend\Page\PageRepository)) {
			// needed to get the abstract repo call for enable fields working
			$GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Page\PageRepository');
			$GLOBALS['TSFE']->sys_page->init(TRUE);
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
		/** @var \TYPO3\CMS\Backend\FrontendBackendUserAuthentication $beUser */
		$beUser = GeneralUtility::makeInstance('TYPO3\CMS\Backend\FrontendBackendUserAuthentication');
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