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
  APIHelpers::showerror(1275, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1276, 'not found parameter id');
  
if (!APIHelpers::issetParam('text'))
  APIHelpers::showerror(1278, 'not found parameter text');

$id = APIHelpers::getParam('id', 0);
$text = APIHelpers::getParam('text', '');

if (!is_numeric($id))
  APIHelpers::showerror(1279, 'incorrect feedbackid');

$id = intval($id);

$conn = APIHelpers::createConnection($config);

try {
 	$stmt = $conn->prepare('UPDATE feedback_msg SET msg = ? WHERE id = ?');
 	$stmt->execute(array($text, intval($id)));
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1277, $e->getMessage());
}

echo json_encode($result);
