<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 05.09.14
 * Time: 12:29
 */

namespace KayStrobach\Webdav\WebDav\FileSystem;


use KayStrobach\Webdav\WebDav\Nodes\Fal\Folder;
use KayStrobach\Webdav\WebDav\Nodes\Fal\Home\GroupHome;
use KayStrobach\Webdav\WebDav\Nodes\Fal\Home\UserHome;
use KayStrobach\Webdav\WebDav\Nodes\Fal\Storage;
use KayStrobach\Webdav\WebDav\Nodes\Folder\WebDavRootDirectory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Root
 *
 * @package KayStrobach\Webdav\WebDav\FileSystem
 */
class Root {
	/**
	 * build the complete root FS
	 *
	 * @return \Sabre_DAV_SimpleCollection
	 */
	public static function buildRootFolders() {
		$mounts = array();

		// add administrator special folders
		if ($GLOBALS['BE_USER']->isAdmin()) {
			self::addIfNotNull(
				$mounts,
				self::getAdminFolder(),
				'Admin'
			);

			// add home path of users
			self::addIfNotNull(
				$mounts,
				self::getUserHomes(),
				'Userhomes'
			);
			// add home path of groups
			self::addIfNotNull(
				$mounts,
				self::getGroupHomes(),
				'Grouphomes'
			);
		}

		// add fileabstraction folders
		self::addIfNotNull(
			$mounts,
			self::getFileAbstractionFolders(),
			'FAL'
		);

		/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
		$signalSlotDispatcher = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\SignalSlot\Dispatcher');
		$signalSlotDispatcher->dispatch(
			__CLASS__,
			__METHOD__,
			array(
				'mounts' => &$mounts
			)
		);

		return $mounts;
	}

	/**
	 * if there is an element in the array, add it to the other array
	 *
	 * @param $array
	 * @param $value
	 * @param $name
	 * @return void
	 */
	public static function addIfNotNull(&$array, $value, $name) {
		if (is_array($value) && (count($value) > 0)) {
			$array[] = new \Sabre_DAV_SimpleCollection($name, $value);
		}
	}

	/**
	 * adds special shares for administrators
	 *
	 * @return array
	 */
	public static function getAdminFolder() {
		$mounts = array();

		if ($GLOBALS['BE_USER']->isAdmin()) {
			//------------------------------------------------------------------
			// add root folder
			if (is_dir(PATH_site)) {
				$mounts[] = $m = new WebDavRootDirectory(PATH_site);
				$m->setName('T3 - PATH_site');
			}
			//------------------------------------------------------------------
			// add extension folder
			if (is_dir(PATH_typo3conf . 'ext/')) {
				$mounts[] = $m = new WebDavRootDirectory(PATH_site . 'typo3conf/ext/');
				$m->setName('T3 - PATH_typo3conf-ext');
			}
			//------------------------------------------------------------------
			// add typo3conf folder
			if (is_dir(PATH_typo3conf)) {
				$mounts[] = $m = new WebDavRootDirectory(PATH_site . 'typo3conf/');
				$m->setName('T3 - PATH_typo3conf');
			}
			//------------------------------------------------------------------
			// add typo3 folder
			if (is_dir(PATH_typo3conf)) {
				$mounts[] = $m = new WebDavRootDirectory(PATH_typo3);
				$m->setName('T3 - PATH_typo3');
			}
			//------------------------------------------------------------------
			// add typo3 folder
			if (is_dir(PATH_typo3conf)) {
				$mounts[] = $m = new WebDavRootDirectory(PATH_tslib);
				$m->setName('T3 - PATH_tslib');
			}
			//------------------------------------------------------------------
			// add typical template folder
			if (is_dir(PATH_site . 'fileadmin/')) {
				$mounts[] = $m = new WebDavRootDirectory(PATH_site . 'fileadmin/');
				$m->setName('T3 - fileadmin');
			}
			//------------------------------------------------------------------
			// add typical template folder
			if (is_dir(PATH_site . 'fileadmin/templates/')) {
				$mounts[] = $m = new WebDavRootDirectory(PATH_site . 'fileadmin/templates/');
				$m->setName('T3 - fileadmin - templates');
			}
			//------------------------------------------------------------------
			// add group home path

			$groupHome = self::getGroupHomes();
			if($groupHome !== NULL) {
				$mounts[] = $groupHome;
			}

			//------------------------------------------------------------------
			// add user home path
			$userHome = self::getUserHomes();
			if($userHome !== NULL) {
				$mounts[] = $userHome;

			}

		}
		return $mounts;
	}

	/**
	 * Returns the grouphomes, instead of the uids
	 */
	public static function getGroupHomes() {
		/** @var $storage \TYPO3\CMS\Core\Resource\ResourceStorage */
		/** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $beUser */
		$beUser = $GLOBALS['BE_USER'];
		$storages = $beUser->getFileStorages();

		list($groupHomeStorageUid, $groupHomeFilter) = explode(':', $GLOBALS['TYPO3_CONF_VARS']['BE']['groupHomePath'], 2);
		$storage = $storages[$groupHomeStorageUid];

		if(array_key_exists($groupHomeStorageUid, $storages)) {
			$m = new GroupHome('T3 - GroupHome');
			$m->setStorage($storages[$groupHomeStorageUid]);
			$m->setFolder($storage->getFolder($groupHomeFilter));
			return $m;
		}
	}

	/**
	 * Returns the userhomes, translated instead of uids
	 *
	 * @return array
	 */
	public static function getUserHomes() {
		/** @var $storage \TYPO3\CMS\Core\Resource\ResourceStorage */
		/** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $beUser */
		$beUser = $GLOBALS['BE_USER'];
		$storages = $beUser->getFileStorages();

		list($userHomeStorageUid, $userHomeFilter) = explode(':', $GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'], 2);
		$storage = $storages[$userHomeStorageUid];

		if(array_key_exists($userHomeStorageUid, $storages)) {
			$m = new UserHome('T3 - UserHome');
			$m->setStorage($storages[$userHomeStorageUid]);
			$m->setFolder($storage->getFolder($userHomeFilter));
			return $m;
		}


	}

	/**
	 * @todo add correct subnode handling
	 *
	 * @return array
	 */
	public static function getFileAbstractionFolders() {
		/** @var $storage \TYPO3\CMS\Core\Resource\ResourceStorage */
		/** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $beUser */
		$beUser = $GLOBALS['BE_USER'];
		$beUser->fetchGroupData();
		$fileMounts = $beUser->getFileMountRecords();
		$storages = $beUser->getFileStorages();
		//--------------------------------------------------------------------------
		// create virtual directories for the filemounts in typo3
		$mounts = array();
		foreach ($fileMounts as $fileMount) {
			$storage = $storages[$fileMount['base']];
			try {
				$m = new Folder($fileMount['title']);
				$m->setStorage($storage);
				$m->setFolder($storage->getFolder($fileMount['path']));
				$mounts[] = $m;
			} catch(\Exception $e) {
				// do not add folder to list
			}
		}
		return $mounts;
	}
}