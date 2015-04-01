<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_settings_get = dirname(__FILE__);
include_once ($curdir_settings_get."/../api.lib/api.base.php");
include_once ($curdir_settings_get."/../api.lib/api.security.php");
include_once ($curdir_settings_get."/../api.lib/api.helpers.php");
include_once ($curdir_settings_get."/../../config/config.php");


$result = array(
	'result' => 'ok',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

// cities
try {
 	$stmt = $conn->prepare('SELECT city, count(userid) cnt FROM users_ips GROUP BY city ORDER BY cnt DESC');
 	$stmt->execute();
	
	$result['data']['cities'] = array();
 	while ($row = $stmt->fetch()) {
		$result['result'] = 'ok';
		if ($row['city'] != "" && $row['city'] != "localhost") {
			$result['data']['cities'][] = array(
				"city" => htmlspecialchars($row['city']),
				"cnt" => htmlspecialchars($row['cnt']),
			);
		}
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

// quests
$result['data']['quests'] = array();

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM quest WHERE for_person = 0');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['count'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

// tryanswers and succsessuful
try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['attempts'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer_backup WHERE passed="No"');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['attempts'] += intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer_backup WHERE passed="Yes"');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['solved'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}


try {
 	$stmt = $conn->prepare('
		SELECT
			t0.userid,
			t0.gameid,
			t0.score,
			u0.nick,
			g0.title
		FROM
			users_games t0
		INNER JOIN games g0 ON g0.id = t0.gameid
		INNER JOIN user u0 ON t0.userid = u0.iduser
		WHERE
			t0.score = (
				SELECT
					max( score )
				FROM
					users_games t1
				INNER JOIN user u1 ON t1.userid = u1.iduser
				WHERE t1.gameid = t0.gameid
				AND u1.role = ?
			) AND t0.score > 0
			AND u0.role = ?
			AND ( g0.state = ? OR g0.state = ?)
	');
 	$stmt->execute(array('user','user', 'original', 'copy'));
	
	$result['data']['winners'] = array();

 	while ($row = $stmt->fetch()) {
		$result['data']['winners'][$row['title']][] = array(
			'game' => $row['title'],
			'user' => htmlspecialchars($row['nick']),
			'score' => $row['score'],
		);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

echo json_encode($result);