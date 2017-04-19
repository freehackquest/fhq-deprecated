<?php
/*
 * API_NAME: Get user info
 * API_DESCRIPTION: Method returned user info
 * API_ACCESS: admin, tester and authorized user
 * API_INPUT: userid - integer, user id
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir_users_get = dirname(__FILE__);
include_once ($curdir_users_get."/../api.lib/api.base.php");
include_once ($curdir_users_get."/../api.lib/api.game.php");
include_once ($curdir_users_get."/../../config/config.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

$response['profile'] = array();
$response['access'] = array();

$conn = APIHelpers::createConnection($config);

/*if (!APIHelpers::issetParam('userid'))
	APIHelpers::showerror(1177, 'Not found parameter userid');*/

$userid = APIHelpers::getParam('userid', APISecurity::userid());

if (!is_numeric($userid))
	APIHelpers::showerror(1181, 'Parameter userid must be integer');

$userid = intval($userid);

$bAllow = APISecurity::isAdmin() || APISecurity::isTester() || APISecurity::userid() == $userid;

$response['access']['edit'] = $bAllow;
$response['currentUser'] = APISecurity::userid() == $userid;

$columns = array('id', 'email', 'dt_last_login', 'uuid', 'status', 'role', 'nick', 'logo', 'country', 'region', 'city');

$query = '
		SELECT '.implode(', ', $columns).' FROM
			users
		WHERE id = ?
';

$result['userid'] = $userid;
// $result['query'] = $query;

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array($userid));
	if ($row = $stmt->fetch())
	{
		$response['data']['userid'] = $row['id'];
		$response['data']['nick'] = $row['nick'];
		$response['data']['logo'] = $row['logo'];
		$response['data']['uuid'] = $row['uuid'];
		$response['data']['dt_last_login'] = $row['dt_last_login'];

		if ($bAllow) {
			 $response['data']['email'] = $row['email'];
			 $response['data']['role'] = $row['role'];
			 $response['data']['status'] = $row['status'];
			 $response['data']['country'] = $row['country'];
			 $response['data']['region'] = $row['region'];
			 $response['data']['city'] = $row['city'];
		}
	}
	$response['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1184, $e->getMessage());
}

// users_profile
try {
	$stmt = $conn->prepare('SELECT * FROM users_profile WHERE userid = ?');
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch()){
		$response['profile'][$row['name']] = $row['value'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1183, $e->getMessage());
}

if(isset($_SESSION['game'])){
	$response['profile']['game'] = $_SESSION['game'];
}else{
	unset($response['profile']['game']);
}

// users_games
try {
	$stmt = $conn->prepare('
		SELECT
			g.id as gameid,
			g.maxscore as game_maxscore,
			g.logo,
			g.title,
			g.type_game,
			ug.score as user_score
		FROM users_games ug
		INNER JOIN games g ON ug.gameid = g.id
		WHERE ug.userid = ?
	');
	$stmt->execute(array($userid));
	while ($row = $stmt->fetch())
	{
		$response['games'][] = array(
			'gameid' => $row['gameid'],
			'title' => $row['title'],
			'type_game' => $row['type_game'],
			'maxscore' => $row['game_maxscore'],
			'logo' => $row['logo'],
			'score' => $row['user_score'],
		);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1182, $e->getMessage());
}

APIHelpers::endpage($response);
