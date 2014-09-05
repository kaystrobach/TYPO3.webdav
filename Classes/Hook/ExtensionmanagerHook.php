<?php

namespace KayStrobach\Webdav\Hook;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class ExtensionmanagerHook {
	function emMakeHeader() {
		include_once ExtensionManagementUtility::extPath('webdav').'Resources/Contrib/SabreDav/lib/Sabre/autoload.php';
		$flashMessage = new FlashMessage(
			'Your SabreDav version is ' . \Sabre_DAV_Version::VERSION . ' (' . \Sabre_DAV_Version::STABILITY . ')',
			'',
			FlashMessage::INFO
		);
		return $flashMessage->render();
	}
	function checkSapi() {
		switch(php_sapi_name()) {
			case 'cgi-fcgi':
			case 'cgi';
				$flashMessage = new FlashMessage(
					'There is a known problem of authentication errors.'
					 . 'Please refer to <a href="http://sabre.io/dav/webservers/">SabreDav FAQ Authentication</a> '
					 . 'Section: Apache + (Fast)CGI',
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
					'The return value of the php_sapi_name tells me, that you run this script in an untested '
					 . 'environment. If the webdav server doesn´t work as expected, please file a bugreport'
					 . 'with your complere phpinfo()',
					'Unknown php sapi',
					FlashMessage::ERROR
				);
			break;
		}
		return $flashMessage->render();
	}
}