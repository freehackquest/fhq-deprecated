<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: List of users
 * API_DESCRIPTION: Method returned page with users
 * API_ACCESS: admin only
 * API_INPUT: search - string, filter by email and nick
 * API_INPUT: page - integer, current page
 * API_INPUT: onpage - integer, users on page
 * API_INPUT: role - string, filter by role (""/"user"/"admin")
 * API_INPUT: status - string, filter by status (""/"activated"/"blocked")
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir_users_list = dirname(__FILE__);
include_once ($curdir_users_list."/../api.lib/api.base.php");
include_once ($curdir_users_list."/../../config/config.php");

$result = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::error(403, 'This function allowed only for admin');

$result['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$search = APIHelpers::getParam('search', '');
$result['search'] = $search;
$search = '%'.$search.'%';

$page = APIHelpers::getParam('page', 0);
$page = intval($page);
$result['page'] = $page;

$onpage = APIHelpers::getParam('onpage', 5);
$onpage = intval($onpage);
$result['onpage'] = $onpage;

$start = $page * $onpage;

$role = APIHelpers::getParam('role', '');
$status = APIHelpers::getParam('status', '');

$role = '%'.$role.'%';
$status = '%'.$status.'%';

// calculate count users
try {
	$stmt = $conn->prepare('
			SELECT
				COUNT(id) as cnt
			FROM
				users
			WHERE 
				(email LIKE ? OR nick LIKE ?)
				AND (role LIKE ?)
				AND (status LIKE ?)
	');
	$stmt->execute(array($search, $search, $role, $status));
	if ($row = $stmt->fetch()) {
		$result['found'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

try {
	$stmt2 = $conn->prepare('
			SELECT
				id, email, role,
				nick, logo, status,
				country, city, region,
				last_ip,
				dt_last_login
			FROM
				users
			WHERE 
				(email LIKE ? OR nick LIKE ?)
				AND (role LIKE ?)
				AND (status LIKE ?)
			ORDER BY
				dt_last_login DESC
			LIMIT '.$start.','.$onpage.'
	');
	$stmt2->execute(array($search, $search, $role, $status));
	$i = 0;
	while ($row2 = $stmt2->fetch()) {
		$userid = $row2['id'];
		$result['data'][$i] = array(
			'userid' => $userid,
			'email' => $row2['email'],
			'role' => $row2['role'],
			'nick' => $row2['nick'],
			'logo' => $row2['logo'],
			'last_ip' => $row2['last_ip'],
			'country' => $row2['country'],
			'region' => $row2['region'],
			'city' => $row2['city'],
			'dt_last_login' => $row2['dt_last_login'],
			'status' => $row2['status'],
		);
    $i++;
	}
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

$dir = $curdir_users_list."/../../files/dumps/";
$dh  = opendir($dir);
$result['dumps'] = array();
while (false !== ($filename = readdir($dh))) {
	if (preg_match('/^users\_.*\.zip$/', $filename)) {
		$result['dumps'][] = $filename;
	}
	sort($result['dumps']);
}

echo json_encode($result);
