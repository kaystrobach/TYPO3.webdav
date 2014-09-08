<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 05.09.14
 * Time: 16:05
 */

namespace KayStrobach\Webdav\WebDav\Nodes\Fal;


/**
 * Class Folder
 *
 * Represents a FAL folder in the webdav server
 *
 * @package KayStrobach\Webdav\WebDav\Nodes\Fal
 */
class Folder extends Storage {
	/**
	 * delete directory recursivly
	 *
	 * @return void
	 */
	public function delete() {
		$this->folder->delete(TRUE);
	}

	/**
	 * creates a directory
	 *
	 * @param string $name
	 * @return void
	 */
	public function createDirectory($name) {
		$this->folder->createFolder($name);
	}

	/**
	 * creates a file
	 *
	 * @param string $name
	 * @param null $data
	 * @return null|string|void
	 */
	public function createFile($name, $data = NULL) {
		$file = $this->folder->createFile($name);
		$file->setContents($data);
	}

	/**
	 * Sets the name of the folder
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->folder->setName($name);
	}
}