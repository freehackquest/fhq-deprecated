<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1091, "This function allowed only for admin");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

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
				COUNT(iduser) as cnt
			FROM
				user
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
	APIHelpers::showerror(1092, $e->getMessage());
}

try {
	$stmt2 = $conn->prepare('
			SELECT
				iduser, email, role,
				nick, logo, status,
				date_last_signup
			FROM
				user
			WHERE 
				(email LIKE ? OR nick LIKE ?)
				AND (role LIKE ?)
				AND (status LIKE ?)
			ORDER BY
				date_last_signup DESC
			LIMIT '.$start.','.$onpage.'
	');
	$stmt2->execute(array($search, $search, $role, $status));
	$i = 0;
	while ($row2 = $stmt2->fetch()) {
		$userid = $row2['iduser'];
		$result['data'][$i] = array(
			'userid' => $userid,
			'email' => $row2['email'],
			'role' => $row2['role'],
			'nick' => $row2['nick'],
			'logo' => $row2['logo'],
			'date_last_signup' => $row2['date_last_signup'],
			'status' => $row2['status'],
		);
    $i++;
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1093, $e->getMessage());
}

echo json_encode($result);
