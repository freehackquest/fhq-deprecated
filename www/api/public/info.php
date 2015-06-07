<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Public info
 * API_DESCRIPTION: this is method send public information, for example:
 * API_DESCRIPTION: cities, quests, winners
 * API_ACCESS: all
 */

$curdir_public_info = dirname(__FILE__);
include_once ($curdir_public_info."/../api.lib/api.base.php");
include_once ($curdir_public_info."/../api.lib/api.security.php");
include_once ($curdir_public_info."/../api.lib/api.helpers.php");
include_once ($curdir_public_info."/../../config/config.php");

$response = APIHelpers::startpage($config);

$response['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$more_than = 2;
$cities_limit = 20;

if (isset($config['public_info'])) {
	if (isset($config['public_info']['cities_more_than']))
		$more_than = intval($config['public_info']['cities_more_than']);
		
	if (isset($config['public_info']['cities_limit']))
		$cities_limit = intval($config['public_info']['cities_limit']);
}

// cities
try {
 	$stmt = $conn->prepare('
		SELECT * FROM (
			SELECT
				city,
				count(userid) cnt
			FROM
				users_ips
			GROUP BY
				city
			ORDER BY
				cnt DESC
		) as cities
		WHERE
			cities.cnt > ?
			AND cities.city <> ?
			AND cities.city <> ?
		LIMIT 0,'.$cities_limit.'
	');
 	$stmt->execute(array($more_than, 'localhost', ''));
	
	$response['data']['cities'] = array();
 	while ($row = $stmt->fetch()) {
		$result['result'] = 'ok';
		if ($row['city'] != "" && $row['city'] != "localhost") {
			$response['data']['cities'][] = array(
				"city" => htmlspecialchars($row['city']),
				"cnt" => htmlspecialchars($row['cnt']),
			);
		}
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

// quests
$response['data']['quests'] = array();

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM quest WHERE for_person = 0');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$response['data']['quests']['count'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1290, $e->getMessage());
}

// tryanswers and succsessuful
try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$response['data']['quests']['attempts'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1291, $e->getMessage());
}

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer_backup WHERE passed="No"');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$response['data']['quests']['attempts'] += intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1288, $e->getMessage());
}

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer_backup WHERE passed="Yes"');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$response['data']['quests']['solved'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1289, $e->getMessage());
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
		INNER JOIN users u0 ON t0.userid = u0.id
		WHERE
			t0.score = (
				SELECT
					max( score )
				FROM
					users_games t1
				INNER JOIN users u1 ON t1.userid = u1.id
				WHERE t1.gameid = t0.gameid
				AND u1.role = ?
			) AND t0.score > 0
			AND u0.role = ?
			AND ( g0.state = ? OR g0.state = ?)
	');
 	$stmt->execute(array('user','user', 'original', 'copy'));
	
	$response['data']['winners'] = array();

 	while ($row = $stmt->fetch()) {
		$response['data']['winners'][$row['title']][] = array(
			'game' => $row['title'],
			'user' => htmlspecialchars($row['nick']),
			'score' => $row['score'],
		);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1282, $e->getMessage());
}

APIHelpers::endpage($response);
