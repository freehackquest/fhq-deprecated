<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

// TODO only for admins

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$country = '';
$city = '';

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1501, 'Not found parameter "id"');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(1502, 'id must be integer');

try {
	$_SESSION['user']['profile']['events_last_id'] = $id;
	
	$query = 'UPDATE users_profile SET value = ?, date_change = NOW() WHERE name = ? AND userid = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array(htmlspecialchars($id), 'events_last_id', APISecurity::userid()));

	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1503, $e->getMessage());
}

echo json_encode($result);
