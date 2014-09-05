<?php

namespace KayStrobach\Webdav\WebDav\Nodes\Fal;


class File extends \Sabre_DAV_File {
	/**
	 * @var string
	 */
	protected $fileName = '';

	/**
	 * @var \TYPO3\CMS\Core\Resource\File
	 */
	protected $file;

	/**
	 * Returns the name of the node
	 *
	 * @return string
	 */
	function getName() {
		return $this->file->getName();
	}

	/**
	 * @param \TYPO3\CMS\Core\Resource\File $file
	 */
	public function setFalFileObject($file) {
		$this->file = $file;
	}
}