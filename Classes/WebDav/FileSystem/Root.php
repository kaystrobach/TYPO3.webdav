<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 05.09.14
 * Time: 12:29
 */

namespace KayStrobach\Webdav\WebDav\FileSystem;


use KayStrobach\Webdav\WebDav\Nodes\Fal\Storage;
use KayStrobach\Webdav\WebDav\Nodes\Folder\WebDavRootDirectory;

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
		}
		return $mounts;
	}

	/**
	 * Returns the grouphomes, instead of the uids
	 *
	 * @return array
	 */
	public static function getGroupHomes() {
		$groupDirs     = array();
		if (is_dir($GLOBALS['TYPO3_CONF_VARS']['BE']['groupHomePath'])) {
			$groupDirArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'uid,title',
					'be_groups',
					'',
					'',
					'title'
			);
			foreach ($groupDirArray as $groupDir) {
				if (is_dir($GLOBALS['TYPO3_CONF_VARS']['BE']['groupHomePath'] . '/' . $groupDir['uid'])) {
					$groupDirs[] = $m = new WebDavRootDirectory($GLOBALS['TYPO3_CONF_VARS']['BE']['groupHomePath'] . '/' . $groupDir['uid']);
					$m->setName($groupDir['title']);
				}
			}
		}
		return $groupDirs;
	}

	/**
	 * Returns the userhomes, translated instead of uids
	 *
	 * @return array
	 */
	public static function getUserHomes() {
		$userDirs     = array();
		if (is_dir($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'])) {
			$userDirArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'uid,username',
					'be_users',
					'',
					'',
					'username'
			);

			foreach ($userDirArray as $userDir) {
				if (is_dir($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'] . '/' . $userDir['uid'])) {
					$userDirs[] = $m = new WebDavRootDirectory($GLOBALS['TYPO3_CONF_VARS']['BE']['userHomePath'] . '/' . $userDir['uid']);
					$m->setName($userDir['username']);
				}
			}
			unset($userDirArray);
		}
		return $userDirs;
	}

	/**
	 * @todo add correct subnode handling
	 *
	 * @return array
	 */
	public static function getFileAbstractionFolders() {
		/** @var  $fileMount \TYPO3\CMS\Core\Resource\ResourceStorage */
		$fileMounts = $GLOBALS['BE_USER']->getFileStorages();
		//--------------------------------------------------------------------------
		// create virtual directories for the filemounts in typo3
		$mounts = array();
		foreach ($fileMounts as $fileMount) {
			$mounts[] = $m = new Storage($fileMount->getName());
			$m->setStorage($fileMount);
		}
		return $mounts;
	}
}