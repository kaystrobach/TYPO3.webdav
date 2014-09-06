<?php

namespace KayStrobach\Webdav\Hook;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PagerendererHook
 *
 * @package KayStrobach\Webdav\Hook
 */
class PagerendererHook {
	/**
	 * @param $params
	 *
	 * @return void
	 */
	public function render(&$params) {
		return;
		if (strpos(GeneralUtility::getIndpEnv('SCRIPT_NAME'), 'file_list.php')) {
			$buffer = '';
			$settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['webdav']);
			if ($settings['addCyberbuckInFileList']) {
				$buffer = '
					<a href="' . GeneralUtility::getIndpEnv('TYPO3_SITE_PATH') . 'index.php/cyberduck.duck" title="Cyberduck bookmarkfile for webdav">
						<img src="../../../../' . GeneralUtility::siteRelPath('webdav') . 'Resources/Public/Images/Cyberduck.png">
					</a>';
			}
			if ($settings['addWebdavInFileList']) {
				$buffer .= '
						<a href="' . str_replace('http:', 'webdav:', GeneralUtility::getIndpEnv('TYPO3_SITE_URL')) . 'index.php/dav/" title="">
						<span class="t3-icon t3-icon-apps t3-icon-apps-filetree t3-icon-filetree-mount">&nbsp;</span>
					</a>';
			}
			$params['markers']['BUTTONLIST_LEFT'] = substr($params['markers']['BUTTONLIST_LEFT'], 0, -6) . $buffer . '</div>';
		}
	}
}