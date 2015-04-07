<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Calculate events after some id
 * API_DESCRIPTION: Method for calculate last events
 * API_ACCESS: all
 * API_INPUT: id - integer, after this id will be calculate count of events
 * API_INPUT: type - string, filter by type
 */
 
$curdir_events_count = dirname(__FILE__);
include_once ($curdir_events_count."/../api.lib/api.base.php");
include_once ($curdir_events_count."/../api.lib/api.security.php");
include_once ($curdir_events_count."/../api.lib/api.helpers.php");
include_once ($curdir_events_count."/../../config/config.php");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1225, 'Not found parameter "id"');

$type = APIHelpers::getParam('type', '');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(1226, 'id must be integer');

try {
	$params = [];
	$params[] = $id;
	$query = 'SELECT count(*) as cnt FROM public_events WHERE id > ?';
	if ($type != '') {
		$query .= ' AND type = ?';
		$params[] = $type;
	}

	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	
	if($row = $stmt->fetch())
	{
		$count = $row['cnt'];
		$result['data']['count'] = $count;
		$result['result'] = 'ok';		
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1227, $e->getMessage());
}

echo json_encode($result);
