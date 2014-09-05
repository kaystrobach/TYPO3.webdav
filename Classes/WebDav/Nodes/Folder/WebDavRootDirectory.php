<?php

namespace KayStrobach\Webdav\WebDav\Nodes\Folder;
/**
 * Class WebDavRootDirectory
 *
 * @package KayStrobach\Webdav\WebDav\Nodes
 */
class WebDavRootDirectory extends \Sabre_DAV_FS_Directory {
	/**
	 * @return string
	 */
	public function getName() {
		return $this->alias;
	}

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName($name) {
		//strip invalid chars for dir names
		// http://msdn.microsoft.com/en-us/library/aa365247%28VS.85%29.aspx#naming_conventions
		$name = str_replace('<', ' ', $name);
		$name = str_replace('>', ' ', $name);
		$name = str_replace(':', ' ', $name);
		$name = str_replace('"', ' ', $name);
		$name = str_replace('/', ' ', $name);
		$name = str_replace('\\', ' ', $name);
		$name = str_replace('|', ' ', $name);
		$name = str_replace('?', ' ', $name);
		$name = str_replace('*', ' ', $name);
		$this->alias = $name;
	}

	/**
	 * @return bool|void
	 */
	public function delete() {
		return FALSE;
	}
}