<?php

class tx_webdav_rootDirs extends Sabre_DAV_FS_Directory {
	public function getName() {
		return $this->alias;
	}
	public function setName($name) {
		//strip invalid chars for dir names
		// http://msdn.microsoft.com/en-us/library/aa365247%28VS.85%29.aspx#naming_conventions
		$name = str_replace('<',  ' ', $name);
		$name = str_replace('>',  ' ', $name);
		$name = str_replace(':',  ' ', $name);
		$name = str_replace('"',  ' ', $name);
		$name = str_replace('/',  ' ', $name);
		$name = str_replace('\\', ' ', $name);
		$name = str_replace('|',  ' ', $name);
		$name = str_replace('?',  ' ', $name);
		$name = str_replace('*',  ' ', $name);
		$this->alias = $name;
	}
}