<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

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

try {
	$query = 'SELECT distinct type FROM public_events';
	$stmt = $conn->prepare($query);
	$stmt->execute();

	$result['result'] = 'ok';
	while($row = $stmt->fetch())
	{
		$result['data'][] = $row['type'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1194, $e->getMessage());
}

echo json_encode($result);
