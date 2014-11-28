<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 27.11.14
 * Time: 19:28
 */

namespace KayStrobach\Webdav\WebDav\Nodes\Fal\Home;


use KayStrobach\Webdav\WebDav\Nodes\Fal\File;
use KayStrobach\Webdav\WebDav\Nodes\Fal\Storage;

class GroupHome extends Storage{
	/**
	 * contains the entities
	 * key => name to display
	 *
	 * @var array
	 */
	protected $entities = array();

	/**
	 * @var string
	 */
	protected $entityTable = 'be_groups';

	/**
	 * @var string
	 */
	protected $entityTitleField = 'title';

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
			$f = new Storage($this->mapFolderIdToEntityName($folder->getName()), array());
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
	 * @param int $uid
	 * @return string
	 */
	public function mapFolderIdToEntityName($uid) {
		$this->initializeEntities();
		if(array_key_exists($uid, $this->entities)) {
			return $this->entities[$uid][$this->entityTitleField];
		} else {
			return 'Not found - ' . $uid;
		}
	}

	/**
	 * @return void
	 */
	public function initializeEntities() {
		if(count($this->entities) > 0) {
			return;
		}
		/** @var \TYPO3\CMS\Core\Database\DatabaseConnection $db */
		$db = $GLOBALS['TYPO3_DB'];
		$this->entities = $db->exec_SELECTgetRows(
			'uid,' . $this->entityTitleField,
			$this->entityTable,
			'',
			'',
			'',
			'',
			'uid'
		);
	}
}