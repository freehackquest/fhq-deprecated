<?php
/*
 * API_NAME: Get event
 * API_DESCRIPTION: Method for get event info
 * API_ACCESS: all
 * API_INPUT: id - integer, id of event
 */

$curdir_events_get = dirname(__FILE__);
include_once ($curdir_events_get."/../api.lib/api.base.php");
include_once ($curdir_events_get."/../api.lib/api.security.php");
include_once ($curdir_events_get."/../api.lib/api.helpers.php");
include_once ($curdir_events_get."/../../config/config.php");

$response = APIHelpers::startpage($config);
$conn = APIHelpers::createConnection($config);

$params = array();
$where = array();

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
		$response['result'] = 'ok';
		$response['data']['id'] = $row['id'];
		$response['data']['type'] = htmlspecialchars($row['type']);
		$response['data']['message'] = htmlspecialchars($row['message']);
	} else {
		APIHelpers::showerror(1222, 'not found event with this id');
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1223, $e->getMessage());
}

APIHelpers::endpage($response);
