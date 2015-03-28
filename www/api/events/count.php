<?php
header("Access-Control-Allow-Origin: *");

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
  APIHelpers::showerror(4101, 'Not found parameter "id"');

$type = APIHelpers::getParam('type', '');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(4102, 'id must be integer');

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
	APIHelpers::showerror(4103, $e->getMessage());
}

echo json_encode($result);