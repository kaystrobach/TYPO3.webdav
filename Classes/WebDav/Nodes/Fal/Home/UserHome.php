<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 27.11.14
 * Time: 19:28
 */

namespace KayStrobach\Webdav\WebDav\Nodes\Fal\Home;

class UserHome extends GroupHome {
	/**
	 * @var string
	 */
	protected $entityTable = 'be_users';

	/**
	 * @var string
	 */
	protected $entityTitleField = 'username';
}