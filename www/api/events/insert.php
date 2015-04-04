<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Insert event
 * API_DESCRIPTION: Method for insert event
 * API_ACCESS: admin
 * API_INPUT: token - string, token
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
  APIHelpers::showerror(1230, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('type'))
  APIHelpers::showerror(1231, 'not found parameter type');

if (!APIHelpers::issetParam('message'))
  APIHelpers::showerror(1232, 'not found parameter message');

$type = APIHelpers::getParam('type', 'info');
$message = APIHelpers::getParam('message', '???');

if (strlen($message) <= 3)
  APIHelpers::showerror(1233, 'message must be informative! (more than 3 character)');

$conn = APIHelpers::createConnection($config);
APIEvents::addPublicEvents($conn, $type, $message);
$result['result'] = 'ok';

echo json_encode($result);
