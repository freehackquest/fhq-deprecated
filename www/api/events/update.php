<?php
header("Access-Control-Allow-Origin: *");

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
  APIHelpers::showerror(4501, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(4502, 'not found parameter id');
  
if (!APIHelpers::issetParam('type'))
  APIHelpers::showerror(4503, 'not found parameter type');

if (!APIHelpers::issetParam('message'))
  APIHelpers::showerror(4504, 'not found parameter message');

$id = APIHelpers::getParam('id', 0);
$type = APIHelpers::getParam('type', 'info');
$message = APIHelpers::getParam('message', 0);

if (!is_numeric($id))
  APIHelpers::showerror(4505, 'incorrect id');

$conn = APIHelpers::createConnection($config);

try {
 	$stmt = $conn->prepare('UPDATE public_events SET type = ?, message = ? WHERE id = ?');
 	$stmt->execute(array($type, $message, intval($id)));
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(4506, $e->getMessage());
}

echo json_encode($result);
