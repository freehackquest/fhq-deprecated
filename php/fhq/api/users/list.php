<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$message = '';

if (!FHQSecurity::isAdmin())
	FHQHelpers::showerror(927, "This function allowed only for admin");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = FHQHelpers::createConnection($config);

$search = FHQHelpers::getParam('search', '');
$result['search'] = $search;
$search = '%'.$search.'%';

$page = FHQHelpers::getParam('page', 0);
$page = intval($page);
$result['page'] = $page;

$onpage = FHQHelpers::getParam('onpage', 15);
$onpage = intval($onpage);
$result['onpage'] = $onpage;

$start = $page * $onpage;


// calculate count users
try {
	$stmt = $conn->prepare('
			SELECT
				COUNT(iduser) as cnt
			FROM
				user
			WHERE 
				email LIKE ? OR nick LIKE ? OR role LIKE ?
	');
	$stmt->execute(array($search,$search,$search));
	if ($row = $stmt->fetch()) {
		$result['found'] = $row['cnt'];
	}
} catch(PDOException $e) {
	FHQHelpers::showerror(922, $e->getMessage());
}

try {
	$stmt2 = $conn->prepare('
			SELECT
				iduser, email, role,
				nick, logo, password,
				date_last_signup
			FROM
				user
			WHERE 
				email LIKE ? OR nick LIKE ? OR role LIKE ?
			ORDER BY
				date_last_signup DESC
			LIMIT '.$start.','.$onpage.'
	');
	$stmt2->execute(array($search,$search,$search));
	while ($row2 = $stmt2->fetch()) {
		$userid = $row2['iduser'];
		$result['data'][$userid] = array(
			'userid' => $userid,
			'email' => $row2['email'],
			'role' => $row2['role'],
			'nick' => $row2['nick'],
			'logo' => $row2['logo'],
			'date_last_signup' => $row2['date_last_signup'],
			'status' => (strpos($row2['password'], 'notactivated') !== FALSE) ? 'notactivated' : 'activated',
		);
	}
} catch(PDOException $e) {
	FHQHelpers::showerror(922, $e->getMessage());
}

echo json_encode($result);
