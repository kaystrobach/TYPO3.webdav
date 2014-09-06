<?php

namespace KayStrobach\Webdav\Hook;

use KayStrobach\Webdav\Bootstrap\BootstrapDav;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class ExtensionmanagerHook
 *
 * @package KayStrobach\Webdav\Hook
 */
class ExtensionmanagerHook {
	/**
	 * @return string
	 */
	public function emMakeHeader() {
		BootstrapDav::initialize();
		$flashMessage = new FlashMessage(
			'Your SabreDav version is ' . \Sabre_DAV_Version::VERSION . ' (' . \Sabre_DAV_Version::STABILITY . ')',
			'',
			FlashMessage::INFO
		);
		return $flashMessage->render();
	}

	/**
	 * @return string
	 */
	public function checkSapi() {
		switch(php_sapi_name()) {
			case 'cgi-fcgi':
				// proceed in cgi
			case 'cgi';
				$flashMessage = new FlashMessage(
					'There is a known problem of authentication errors. Please refer to <a href="http://sabre.io/dav/webservers/">SabreDav FAQ Authentication</a> Section: Apache + (Fast)CGI',
					'Problematic php_sapi_name',
					FlashMessage::WARNING
				);
				break;
			case 'apache2handler';
				$flashMessage = new FlashMessage(
					'There should be no errors',
					'Perfect SAPI apache2handler, php runs as apache module',
					FlashMessage::OK
				);
				break;
			default:
				$flashMessage = new FlashMessage(
					'This script runs in an untested environment. If the webdav server doesn´t work as expected, please file a bugreport with your complete phpinfo()',
					'Unknown php sapi',
					FlashMessage::ERROR
				);
		}
		return $flashMessage->render();
	}
}