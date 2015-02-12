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

if (APIHelpers::issetParam('token')) {
	$token = APIHelpers::getParam('token', '');
	$conn = APIHelpers::createConnection($config);
	APISecurity::removeByToken($conn, $token);
	APISecurity::logout();
}

echo json_encode($result);
