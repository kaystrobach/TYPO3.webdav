<?php

namespace KayStrobach\Webdav\Bootstrap;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * initialize the sabredav autoloader
 *
 * @package KayStrobach\Webdav\Bootstrap
 */
class BootstrapDav {
	/**
	 * initialize the sabredav autoloader
	 */
	public static function initialize() {
		require_once (ExtensionManagementUtility::extPath('webdav') . 'Resources/Contrib/SabreDav/lib/Sabre/autoload.php');
	}
} 