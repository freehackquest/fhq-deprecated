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
  APIHelpers::showerror(1264, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1269, 'not found parameter id');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(1270, 'incorrect id');

$conn = APIHelpers::createConnection($config);

try {
 	$conn->prepare('DELETE FROM feedback_msg WHERE id = ?')->execute(array(intval($id)));
 	
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1271, $e->getMessage());
}

echo json_encode($result);
