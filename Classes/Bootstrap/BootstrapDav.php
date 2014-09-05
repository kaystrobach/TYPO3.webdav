<?php

namespace KayStrobach\Webdav\Bootstrap;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;


class BootstrapDav {
	public static function initialize() {
		require_once (ExtensionManagementUtility::extPath('webdav') . 'Resources/Contrib/SabreDav/lib/Sabre/autoload.php');
	}
} 