<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

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
  APIHelpers::showerror(4301, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('type'))
  APIHelpers::showerror(4302, 'not found parameter type');

if (!APIHelpers::issetParam('message'))
  APIHelpers::showerror(4303, 'not found parameter message');

$type = APIHelpers::getParam('type', 'info');
$message = APIHelpers::getParam('message', '???');

if (strlen($message) <= 3)
  APIHelpers::showerror(4304, 'message must be informative! (more than 3 character)');

$conn = APIHelpers::createConnection($config);
APIEvents::addPublicEvents($conn, $type, $message);
$result['result'] = 'ok';

echo json_encode($result);
