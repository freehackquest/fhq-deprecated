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

$id = APIHelpers::getParam('id', -1);
if ($id != -1) {
	if (!is_numeric($id))
		APIHelpers::showerror(1228, 'Id must be integer');
	$params[] = $id;
	$where[] = 'id > ?';
}

$type = APIHelpers::getParam('type', '');

if ($type != '') {
	$params[] = $type;
	$where[] = 'type = ?';
}
	
try {
	$query = 'SELECT * FROM public_events';

	if (count($where) > 0)
		$query .= ' WHERE '.implode(' AND ', $where);
	$query .= ' ORDER BY id DESC LIMIT 0,50;'; 

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$bAdmin = APISecurity::isAdmin();

	$result['result'] = 'ok';
	$result['access'] = $bAdmin;
	$new_id = $id;
	$result['data']['events'] = array();
	while($row = $stmt->fetch())
	{
		if ($row['id'] > $new_id) {
			$new_id = $row['id'];
		}
		$result['data']['events'][] = array(
			'id' => $row['id'],
			'type' => $row['type'],
			'message' => $row['message'],
			'dt' => $row['dt'],
		);
	}
	$result['data']['new_id'] = $new_id;
} catch(PDOException $e) {
	APIHelpers::showerror(1229, $e->getMessage());
}

echo json_encode($result);
