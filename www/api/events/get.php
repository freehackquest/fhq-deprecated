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
  APIHelpers::showerror(4402, 'not found parameter id');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(4403, 'incorrect id');

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
		APIHelpers::showerror(4403, 'not found event with this id');
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(4404, $e->getMessage());
}

echo json_encode($result);
