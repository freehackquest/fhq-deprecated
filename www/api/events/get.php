<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../../config/config.php");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

$params = [];
$where = [];

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1220, 'not found parameter id');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(1221, 'incorrect id');

$conn = APIHelpers::createConnection($config);

try {
 	$stmt = $conn->prepare('SELECT * FROM public_events WHERE id = ?');
 	$stmt->execute(array(intval($id)));
 	
 	if ($row = $stmt->fetch()) {
		$result['result'] = 'ok';
		$result['data']['id'] = $row['id'];
		$result['data']['type'] = htmlspecialchars($row['type']);
		$result['data']['message'] = htmlspecialchars($row['message']);
		
	} else {
		APIHelpers::showerror(1222, 'not found event with this id');
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1223, $e->getMessage());
}

echo json_encode($result);
