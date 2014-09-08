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
	 *
	 * @return void
	 */
	public static function initialize() {
		$autoloader = new BootstrapDav();
		spl_autoload_register(array($autoloader, 'registerAutoloader'));
	}

	/**
	 * @param $className
	 *
	 * @return void
	 */
	public function registerAutoloader($className) {
		if (strpos($className,'Sabre_') === 0) {
			include (self::getSabeDavBaseDir() . str_replace('_', '/', substr($className, 6)) . '.php');
		}
	}

	/**
	 * @return string
	 */
	public function getSabeDavBaseDir() {
		return ExtensionManagementUtility::extPath('webdav') . 'Resources/Contrib/SabreDav/lib/Sabre/';
	}
} 