<?php

namespace KayStrobach\Webdav\ViewHelpers;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class BreadcrumbViewHelper
 *
 * @package KayStrobach\Webdav\ViewHelpers
 */
class BreadcrumbViewHelper extends AbstractViewHelper {
	/**
	 * @param string $path
	 * @param string $base
	 * @param string $separator
	 *
	 * @return string
	 */
	public function render($path, $base, $separator = ' / ') {
		$name = basename($path);
		$rest = dirname($path);
		$return = $separator . '<a href="' . $base . $path . '">' . $name . '</a>';
		if ($rest !== '.') {
			$return = $this->render($rest, $base) . $return;
		} else {
			$return = '<a href="' . $base . '">Rootfolder</a>' . $return;
		}
		return $return;
	}
}