<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Install Updates
 * API_DESCRIPTION: Method for install updates for database
 * API_ACCESS: admin only
 * API_OKRESPONSE: { "result":"ok", "data" : { "u0010" : "installed" } }
 */
 
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../api.lib/api.updates.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1007, 'This function allowed only for admin');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$version = APIUpdates::getVersion($conn);
$result['version'] = $version;

$updates = array();

$curdir = dirname(__FILE__);
$filename = $curdir.'/updates/'.$version.'.php';

while (file_exists($filename)) {
	include_once ($filename);
	$function_update = 'update_'.$version;
	if (!function_exists($function_update)) {
		$result['data'][$version] = 'Not found function '.$function_update;
		break;
	}

	if ($function_update($conn)) {
		APIUpdates::insertUpdateInfo($conn,
			$version,
			$updates[$version]['to_version'],
			$updates[$version]['name'],
			$updates[$version]['description'],
			APISecurity::userid()
		);
		$result['data'][$version] = 'installed';
	} else {
		$result['data'][$version] = 'failed';
	}

	$new_version = APIUpdates::getVersion($conn);
	if ($new_version == $version)
		break;
	$version = $new_version;
	$result['version'] = $version;
	$filename = $curdir.'/updates/'.$version.'.php';
}

echo json_encode($result);
