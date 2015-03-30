<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
	'profile' => array(),
	'access' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('userid'))
	APIHelpers::showerror(1177, 'Not found parameter userid');

$userid = APIHelpers::getParam('userid', 0);

if (!is_numeric($userid))
	APIHelpers::showerror(1181, 'Parameter userid must be integer');

$userid = intval($userid);

$bAllow = APISecurity::isAdmin() || APISecurity::isTester() || APISecurity::userid() == $userid;

$result['access']['edit'] = $bAllow;
$result['currentUser'] = APISecurity::userid() == $userid;

$columns = array('iduser', 'email', 'status', 'role', 'nick', 'logo');

$query = '
		SELECT '.implode(', ', $columns).' FROM
			user
		WHERE iduser = ?
';

$result['userid'] = $userid;
// $result['query'] = $query;

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array($userid));
	if ($row = $stmt->fetch())
	{
		$result['data']['userid'] = $row['iduser'];
		$result['data']['nick'] = $row['nick'];
		$result['data']['logo'] = $row['logo'];
		
		if ($bAllow) {
			 $result['data']['email'] = $row['email'];
			 $result['data']['role'] = $row['role'];
			 $result['data']['status'] = $row['status'];
		}
	}
	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1184, 'Error 822: ' + $e->getMessage());
}

// users_profile
try {
	$stmt = $conn->prepare('SELECT * FROM users_profile WHERE userid = ?');
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch())
	{
		$result['profile'][$row['name']] = $row['value'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1183, 'Error 822: ' + $e->getMessage());
}

// users_games
try {
	$stmt = $conn->prepare('SELECT games.title, games.type_game, users_games.score FROM users_games INNER JOIN games ON users_games.gameid = games.id WHERE users_games.userid = ?');
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch())
	{
		$result['games'][] = array(
			'title' => $row['title'],
			'type_game' => $row['type_game'],
			'score' => $row['score'],
		);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1182, $e->getMessage());
}

echo json_encode($result);
