<?php

namespace KayStrobach\Webdav\ViewHelpers;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class FilesizeViewHelper
 *
 * @package KayStrobach\Webdav\ViewHelpers
 */
class FilesizeViewHelper extends AbstractViewHelper {
	/**
	 * @return string
	 */
	public function render() {
		$value = $this->renderChildren();
		if ($value == 0) {
			$return = '';
		} else {
			if ($value > 1024 * 1024 * 1024 * 1024) {
				$return = (int)($value / 1024 / 1024 / 1024 / 1024) . ' TB';
			} elseif ($value > 1024 * 1024 * 1024) {
				$return = (int)($value / 1024 / 1024 / 1024) . ' GB';
			} elseif ($value > 1024 * 1024) {
				$return = (int)($value / 1024 / 1024) . ' MB';
			} elseif ($value > 1024) {
				$return = (int)($value / 1024) . ' KB';
			} else {
				$return = (int)($value) . ' &nbsp;B';
			}
		}
		return $return;
	}
}