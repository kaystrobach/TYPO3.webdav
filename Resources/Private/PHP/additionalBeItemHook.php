<?php
if(TYPO3_MODE == 'BE') {
	/*
	 * @var
	 */
	$GLOBALS['TYPO3backend']->addBodyContent('<h1>test</h1>');
	echo '<h4>dada</h4>';
	$r = new t3lib_PageRenderer();
}
