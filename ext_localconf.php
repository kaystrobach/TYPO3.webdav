<?php

/**
 * Inject Webdav in Bootstrap of CMS
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/index_ts.php']['preprocessRequest'][] = 'KayStrobach\Webdav\Controller\WebdavController->main';

/**
 * Cyberduck Bookmark
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['docHeaderButtonsHook'][] = 'KayStrobach\Webdav\Hook\PagerendererHook->render';
?>