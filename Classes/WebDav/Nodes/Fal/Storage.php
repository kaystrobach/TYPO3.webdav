<?php

namespace KayStrobach\Webdav\WebDav\Nodes\Fal;


use KayStrobach\Webdav\WebDav\Nodes\Fal\Folder;

class Storage extends \Sabre_DAV_SimpleCollection {
	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceStorage
	 */
	protected $storage = NULL;

	/**
	 * @var \TYPO3\CMS\Core\Resource\Folder
	 */
	protected $folder = NULL;

	/**
	 *
	 * @return string
	 */
	public function getName() {
		//strip invalid chars for dir names
		// http://msdn.microsoft.com/en-us/library/aa365247%28VS.85%29.aspx#naming_conventions
		$name = str_replace('<', ' ', $this->name);
		$name = str_replace('>', ' ', $name);
		$name = str_replace(':', ' ', $name);
		$name = str_replace('"', ' ', $name);
		$name = str_replace('/', ' ', $name);
		$name = str_replace('\\', ' ', $name);
		$name = str_replace('|', ' ', $name);
		$name = str_replace('?', ' ', $name);
		$name = str_replace('*', ' ', $name);
		$name = str_replace('\\', ' ', $name);
		return $name;
	}

	public function getAlias() {
		return $this->getName();
	}

	public function log($message) {
		$GLOBALS['BE_USER']->writelog(
			255,
			2,
			0,
			1,
			'Message: %s',
			array(
				$message
			)
		);
	}

	/**
	 * @return array|void
	 */
	public function getChildren() {
		/** @var \TYPO3\CMS\Core\Resource\Folder $folder */
		if ($this->folder === NULL) {
			$this->folder = $this->storage->getFolder('/');
		}
		$subFolders = $this->folder->getSubfolders();
		$collection = array();
		foreach($subFolders as $folder) {
			$collection[] = $f = new Folder($folder->getName());
			$f->setStorage($this->storage);
			$f->setFolder($folder);
			$this->log('FOLDER:' . $folder->getCombinedIdentifier() . ' - ' . $f->getName());
		}

		$files = $this->folder->getFiles();
		foreach($files as $file) {
			$collection[] = $f = new File();
			$f->setFalFileObject($file);
			$this->log('FILE:' . $file->getCombinedIdentifier() . ' - ' . $file->getName());
		}

		return $collection;
	}

	/**
	 * @param \TYPO3\CMS\Core\Resource\ResourceStorage $storage
	 */
	public function setStorage(\TYPO3\CMS\Core\Resource\ResourceStorage $storage) {
		$this->storage = $storage;
	}

	/**
	 * @param \TYPO3\CMS\Core\Resource\Folder $folder
	 */
	public function setFolder(\TYPO3\CMS\Core\Resource\Folder $folder) {
		$this->folder = $folder;
	}
} 