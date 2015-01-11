<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
	'profile' => array(),
);

$conn = FHQHelpers::createConnection($config);

if (!FHQHelpers::issetParam('userid'))
  showerror(823, 'Error 823: not found parameter userid');

$userid = FHQHelpers::getParam('userid', 0);

if (!is_numeric($userid))
	showerror(825, 'Error 825: incorrect id');

$userid = intval($userid);

$bAllow = FHQSecurity::isAdmin() || FHQSecurity::isTester() || FHQSecurity::userid() == $userid;

$columns = array('iduser', 'email', 'password', 'role', 'nick', 'logo');

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
			 $result['data']['status'] = 'activated';
			 
			 if (strpos($row['password'], 'notactivated') !== FALSE)
				$result['data']['status'] = 'notactivated';
		}
	}
	$result['result'] = 'ok';
} catch(PDOException $e) {
	showerror(822, 'Error 822: ' + $e->getMessage());
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
	showerror(822, 'Error 822: ' + $e->getMessage());
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
	showerror(822, 'Error 822: ' + $e->getMessage());
}

echo json_encode($result);
