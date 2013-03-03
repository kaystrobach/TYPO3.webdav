<?php

/**
 * Documentation can be found here:
 * http://code.google.com/p/sabredav/wiki/WebDAVClient
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type:text/plain');

include('Resources/Contrib/SabreDav/lib/Sabre/autoload.php');

$client = new Sabre_DAV_Client(
	array(
		'userName' => 'admin',
		'password' => 'password',
		'baseUri'  => 'http://localhost/t3-trunk/index.php/dav/',
		'proxy' => null
	)
);
$url = '';
$properties = array(
	'{DAV:}displayname',
	'{DAV:}getcontenttype',
	'{DAV:}resourcetype',
	'{DAV:}getlastmodified',
	
);
print_r($client->propFind($url, $properties, 1));


// register the driver in t3lib_file_Service_Driver_DriverRegistry
// must be of type t3lib_file_Service_Driver_AbstractDriver