<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 05.09.14
 * Time: 10:50
 */

namespace KayStrobach\WebDav\Utilities;


class CyberDuckUtility {
	public static function getBookmark() {
		global $BE_USER;
		$buffer = '<?xml version="1.0" encoding="UTF-8"?>
		<plist>
		  <dict>
			<key>Protocol</key>
			<string>dav</string>
			<key>Nickname</key>
			<string>' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] . '</string>
			<key>Hostname</key>
			<string>' . t3lib_div::getIndpEnv('HTTP_HOST') .'</string>
			<key>Port</key>
			<string>80</string>
			<key>Username</key>
			<string>' . $BE_USER->user['username'] . '</string>
			<key>Path</key>
			<string>' . dirname(t3lib_div::getIndpEnv('REQUEST_URI')) .'/dav/</string>
			<key>Access Timestamp</key>
			<string>' . time() . '</string>
		  </dict>
		</plist>
		';
		return $buffer;
	}

	public static function sendBookmark() {
		header('Content-Type:application/octet-stream');
		header('Content-Disposition: attachment;filename="cyber.duck"');
		echo self::getBookmark();
	}
}