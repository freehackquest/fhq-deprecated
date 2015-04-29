<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Enums
 * API_DESCRIPTION: list of some types, for example:
 * API_DESCRIPTION: questTypes, questStates, userStatuses and etc.
 * API_ACCESS: all
 */

$curdir_public_types = dirname(__FILE__);
include_once ($curdir_public_types."/../api.lib/api.types.php");

$result = array(
	'result' => 'ok',
	'data' => APITypes::$types,
);

echo json_encode($result);
