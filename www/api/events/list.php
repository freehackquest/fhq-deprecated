<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: List of events
 * API_DESCRIPTION: Method returned list of last events
 * API_ACCESS: authorized users
 * API_INPUT: token - string, access token for user
 * API_INPUT: id - integer, all events after 'id' (or -1 = last 50 records)
 * API_INPUT: type - string, filter by type (or empty)
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);
$conn = APIHelpers::createConnection($config);

$params = array();
$where = array();

$id = APIHelpers::getParam('id', -1);
if ($id != -1) {
	if (!is_numeric($id))
		APIHelpers::showerror(1228, 'Id must be integer');
	$params[] = $id;
	$where[] = 'id > ?';
}

$search = APIHelpers::getParam('search', '');
$response['data']['search'] = $search;
$search = '%'.$search.'%';

$where[] = 'message like ?';
$params[] = $search;


$page = APIHelpers::getParam('page', 0);
$page = intval($page);
$response['data']['page'] = $page;

$onpage = APIHelpers::getParam('onpage', 5);
$onpage = intval($onpage);
$response['data']['onpage'] = $onpage;

$start = $page * $onpage;

$type = APIHelpers::getParam('type', '');
if ($type != '') {
	$params[] = $type;
	$where[] = 'type = ?';
}

// count
try {
	$query = 'SELECT count(*) as cnt FROM public_events';

	if (count($where) > 0)
		$query .= ' WHERE '.implode(' AND ', $where);

	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	if($row = $stmt->fetch())
		$response['data']['found'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(1185, $e->getMessage());
}

try {
	$query = 'SELECT * FROM public_events';

	if (count($where) > 0)
		$query .= ' WHERE '.implode(' AND ', $where);
	$query .= ' ORDER BY id DESC LIMIT '.$start.','.$onpage; 

	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	$bAdmin = APISecurity::isAdmin();

	$response['result'] = 'ok';
	$response['access'] = $bAdmin;
	$response['data']['maxid'] = -1;
	$new_id = $id;
	$response['data']['events'] = array();
	while($row = $stmt->fetch())
	{
		if ($row['id'] > $new_id) {
			$new_id = $row['id'];
		}
		$response['data']['events'][] = array(
			'id' => $row['id'],
			'type' => $row['type'],
			'message' => $row['message'],
			'dt' => $row['dt'],
		);
	}
	$response['data']['maxid'] = $new_id;
} catch(PDOException $e) {
	APIHelpers::showerror(1229, $e->getMessage());
}

APIHelpers::endpage($response);
