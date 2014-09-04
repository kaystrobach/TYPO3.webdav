<?php

class Tx_Webdav_ViewHelpers_FilesizeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {
	/**
	 * @return string
	 */
	public function render() {
		$value = $this->renderChildren();
		if($value == 0) {
			$return = '';
		} else {
			if($value > 1024 * 1024 * 1024 * 1024) {
				$return = (int)($value / 1024 / 1024 / 1024 / 1024) . ' TB';
			} elseif($value > 1024 * 1024 * 1024) {
				$return = (int)($value / 1024 / 1024 / 1024) . ' GB';
			} elseif($value > 1024 * 1024) {
				$return = (int)($value / 1024 / 1024) . ' MB';
			} elseif($value > 1024) {
				$return = (int)($value / 1024) . ' KB';
			} else {
				$return = (int)($value) . ' &nbsp;B';
			}
		}
		return $return;
	}
}