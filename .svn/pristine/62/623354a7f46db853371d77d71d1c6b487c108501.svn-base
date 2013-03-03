<?php

class tx_webdav_extmgm {
	function emMakeHeader() {
		include_once t3lib_extMgm::extPath('webdav').'Resources/Contrib/SabreDav/lib/Sabre/autoload.php';
		$flashMessage = new t3lib_FlashMessage(
							'Your SabreDav version is ' . Sabre_DAV_Version::VERSION . ' ('.Sabre_DAV_Version::STABILITY.')',
							'',
							t3lib_FlashMessage::INFO
						);
		return $flashMessage->render();
	}
	function checkSapi() {
		switch(php_sapi_name()) {
			case 'cgi-fcgi':
			case 'cgi';
				$flashMessage = new t3lib_FlashMessage(
					'There is a known problem of authentication errors.'
					 . 'Please refer to <a href="https://code.google.com/p/sabredav/wiki/Authentication">SabreDav FAQ</a> '
					 . 'Section: Apache + (Fast)CGI',
					'Problematic php_sapi_name',
					t3lib_FlashMessage::WARNING
				);
			break;
			case 'apache2handler';
				$flashMessage = new t3lib_FlashMessage(
					'There should be no errors',
					'Perfect SAPI apache2handler, php runs as apache module',
					t3lib_FlashMessage::OK
				);
			break;
			default:
				$flashMessage = new t3lib_FlashMessage(
					'The return value of the php_sapi_name tells me, that you run this script in an untested '
					 . 'environment. If the webdav server doesn´t work as expected, please file a bugreport'
					 . 'with your complere phpinfo()',
					'Unknown php sapi',
					t3lib_FlashMessage::ERROR
				);
			break;
		}
		return $flashMessage->render();
	}
}