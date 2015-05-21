<?php

namespace KayStrobach\Webdav\WebDav\Nodes\Fal;

/**
 * Class File
 *
 * Represens a FAL file in webdav server
 *
 * @package KayStrobach\Webdav\WebDav\Nodes\Fal
 */
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
	public function getName() {
		return $this->file->getName();
	}

	/**
	 * @param \TYPO3\CMS\Core\Resource\File $file
	 */
	public function setFalFileObject($file) {
		$this->file = $file;
	}

	/**
	 * deletes a file
	 */
	public function delete() {
		$this->file->delete();
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

	// @codingStandardsIgnoreStart
	/**
	 * Returns the ETag for a file
	 *
	 * An ETag is a unique identifier representing the current version of the file. If the file changes, the ETag MUST change.
	 * The ETag is an arbitrary string, but MUST be surrounded by double-quotes.
	 *
	 * Return null if the ETag can not effectively be determined
	 *
	 * The function name is defined by SabreDav, so no way to fit the TYPO3 CGL ...
	 *
	 * @return string|null
	 */
	public function getETag() {
		return $this->file->getSha1();
	}
	// @codingStandardsIgnoreEnd


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

	/**
	 * sets the name of the file
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->file->rename($name);
	}

	/**
	 * Returns the last modification time
	 *
	 * In this case, it will simply return the current time
	 *
	 * @return int
	 */
	public function getLastModified() {
		return $this->file->getModificationTime();
	}
}
