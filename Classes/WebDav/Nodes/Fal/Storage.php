<?php

namespace KayStrobach\Webdav\WebDav\Nodes\Fal;


use KayStrobach\Webdav\WebDav\Nodes\Fal\Folder;

/**
 * Class Storage
 *
 * Represents a FAL Storage for the webdav server
 *
 * @package KayStrobach\Webdav\WebDav\Nodes\Fal
 */
class Storage extends \Sabre_DAV_SimpleCollection implements \Sabre_DAV_ICollection{
	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceStorage
	 */
	protected $storage = NULL;

	/**
	 * @var bool
	 */
	protected $initialized = FALSE;

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

	/**
	 * returns a child node by name (can be file or folder)
	 *
	 * @param string $name
	 * @return \Sabre_DAV_INode
	 */
	public function getChild($name) {
		$this->initializeChildren();
		return parent::getChild($name);
	}

	/**
	 * takes care of loading and returning children
	 *
	 * @return array|\Sabre_DAV_INode[]
	 */
	public function getChildren() {
		$this->initializeChildren();
		return parent::getChildren();
	}

	/**
	 * loads children, is called on demand
	 *
	 * @return void
	 */
	public function initializeChildren() {
		if ($this->initialized === TRUE) {
			return;
		}
		/** @var \TYPO3\CMS\Core\Resource\Folder $folder */
		$subFolders = $this->folder->getSubfolders();
		foreach ($subFolders as $folder) {
			$f = new Folder($folder->getName(), array());
			$f->setStorage($this->storage);
			$f->setFolder($folder);
			$this->addChild($f);
		}

		$files = $this->folder->getFiles();
		foreach ($files as $file) {
			$f = new File();
			$f->setFalFileObject($file);
			$this->addChild($f);
		}
		$this->initialized = TRUE;
	}

	/**
	 * @param \TYPO3\CMS\Core\Resource\ResourceStorage $storage
	 *
	 * @return void
	 */
	public function setStorage(\TYPO3\CMS\Core\Resource\ResourceStorage $storage) {
		$this->storage = $storage;
		if ($this->folder === NULL) {
			$this->folder = $this->storage->getFolder('/');
		}
	}

	/**
	 * @param \TYPO3\CMS\Core\Resource\Folder $folder
	 *
	 * @return void
	 */
	public function setFolder(\TYPO3\CMS\Core\Resource\Folder $folder) {
		$this->folder = $folder;
	}
} 