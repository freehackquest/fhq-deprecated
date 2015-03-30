<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_logout = dirname(__FILE__);
include_once ($curdir_logout."/../api.lib/api.base.php");
include_once ($curdir_logout."/../api.lib/api.helpers.php");
include_once ($curdir_logout."/../api.lib/api.security.php");
include ($curdir_logout."/../../config/config.php");

$result = array(
	'result' => 'ok',
	'data' => array(),
);

if (APIHelpers::issetParam('token')) {
	$token = APIHelpers::getParam('token', '');
	$conn = APIHelpers::createConnection($config);
	APISecurity::removeByToken($conn, $token);
}

APISecurity::logout();

echo json_encode($result);
