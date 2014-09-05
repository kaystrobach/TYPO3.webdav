<?php

namespace KayStrobach\WebDav\Utilities;

/**
 * Class CyberDuckUtility
 *
 * @package KayStrobach\WebDav\Utilities
 */
class CyberDuckUtility {
	/**
	 * return the bookmark as string
	 *
	 * @return string
	 */
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

	/**
	 * send the bookmark with correct header
	 */
	public static function sendBookmark() {
		header('Content-Type:application/octet-stream');
		header('Content-Disposition: attachment;filename="cyber.duck"');
		echo self::getBookmark();
	}
}