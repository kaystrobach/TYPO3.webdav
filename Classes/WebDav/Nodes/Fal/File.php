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


	/**
	 * Updates the data
	 *
	 * data is a readable stream resource.
	 *
	 * @param resource $data
	 * @return void
	 */
	public function put($data) {
		// @todo check if data needs to be converted to a string first
		$this->file->setContents($data);
	}

	/**
	 * Returns the data
	 *
	 * This method may either return a string or a readable stream resource
	 *
	 * @return mixed
	 */
	public function get() {
		return $this->file->getContents();
	}

	/**
	 * Returns the size of the file, in bytes.
	 *
	 * @return int
	 */
	public function getSize() {
		return $this->file->getSize();
	}

	/**
	 * Returns the ETag for a file
	 *
	 * An ETag is a unique identifier representing the current version of the file. If the file changes, the ETag MUST change.
	 * The ETag is an arbitrary string, but MUST be surrounded by double-quotes.
	 *
	 * Return null if the ETag can not effectively be determined
	 *
	 * @return string|null
	 */
	public function getETag() {
		return $this->file->getSha1();
	}

	/**
	 * Returns the mime-type for a file
	 *
	 * If null is returned, we'll assume application/octet-stream
	 *
	 * @return string|null
	 */
	public function getContentType() {
		return $this->file->getMimeType();
	}

}