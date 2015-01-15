<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../api.lib/api.security.php");
include ($curdir."/../../config/config.php");

$result = array(
	'result' => 'ok',
	'data' => array(),
);

if (FHQHelpers::issetParam('token')) {
	$token = FHQHelpers::getParam('token', '');
	$conn = FHQHelpers::createConnection($config);
	FHQSecurity::removeByToken($conn, $token);
	FHQSecurity::logout();
}

echo json_encode($result);
