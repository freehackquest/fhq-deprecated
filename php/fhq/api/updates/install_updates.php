<?php

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../api.lib/api.updates.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

if (!FHQSecurity::isAdmin())
	FHQHelpers::showerror(10927, "This function allowed only for admin");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = FHQHelpers::createConnection($config);

$version = FHQUpdates::getVersion($conn);
$result['version'] = $version;

$updates = array();

$curdir = dirname(__FILE__);
include_once ($curdir."/updates/0_0_0_0.php");
include_once ($curdir."/updates/0_0_0_1.php");
include_once ($curdir."/updates/0_0_0_2.php");
include_once ($curdir."/updates/0_0_0_3.php");

while (isset($updates[$version])) {
	$function_update = 'update_'.$version;
	if ($function_update($conn)) {
		FHQUpdates::insertUpdateInfo($conn,
			$version,
			$updates[$version]['to_version'],
			$updates[$version]['name'],
			$updates[$version]['description'],
			FHQSecurity::userid()
		);
		$result['data'][$version] = 'installed';
	} else {
		$result['data'][$version] = 'failed';
	}

	$new_version = FHQUpdates::getVersion($conn);
	if ($new_version == $version)
		break;
	$version = $new_version;
	$result['version'] = $version;
}

echo json_encode($result);
