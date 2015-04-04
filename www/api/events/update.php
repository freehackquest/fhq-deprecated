<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Update event
 * API_DESCRIPTION: Method for update event
 * API_ACCESS: admin
 * API_INPUT: token - string, access token for user
 * API_INPUT: id - integer, identificztor of event
 * API_INPUT: type - string, type of event
 * API_INPUT: message - string, message of event
 */

$curdir_events_insert = dirname(__FILE__);
include_once ($curdir_events_insert."/../api.lib/api.helpers.php");
include_once ($curdir_events_insert."/../../config/config.php");
include_once ($curdir_events_insert."/../api.lib/api.base.php");

include_once ($curdir_events_insert."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1253, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1254, 'not found parameter id');
  
if (!APIHelpers::issetParam('type'))
  APIHelpers::showerror(1255, 'not found parameter type');

if (!APIHelpers::issetParam('message'))
  APIHelpers::showerror(1256, 'not found parameter message');

$id = APIHelpers::getParam('id', 0);
$type = APIHelpers::getParam('type', 'info');
$message = APIHelpers::getParam('message', '');

if (!is_numeric($id))
  APIHelpers::showerror(1257, 'incorrect id');

$conn = APIHelpers::createConnection($config);

try {
 	$stmt = $conn->prepare('UPDATE public_events SET type = ?, message = ? WHERE id = ?');
 	$stmt->execute(array($type, $message, intval($id)));
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1258, $e->getMessage());
}

echo json_encode($result);
