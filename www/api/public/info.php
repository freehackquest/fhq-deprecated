<?php
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
				count(*) cnt
			FROM
				users
			WHERE
				city IS NOT NULL
				AND city <> "" 
			GROUP BY
				city
			ORDER BY
				cnt DESC
		) as cities
		WHERE
			cities.cnt > ?
		LIMIT 0,'.$cities_limit.'
	');
 	$stmt->execute(array($more_than));
	
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
 	APIHelpers::error(500, $e->getMessage());
}

// quests
$response['data']['quests'] = array();

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM quest');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$response['data']['quests']['count'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::error(500, $e->getMessage());
}

// users_quests_answers
try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM users_quests_answers');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$response['data']['quests']['attempts'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::error(500, $e->getMessage());
}

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM users_quests');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$response['data']['quests']['solved'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::error(500, $e->getMessage());
}


try {
 	$stmt = $conn->prepare('
		SELECT
			u.nick,
			u.rating
		FROM
			users u
		WHERE
			u.role = "user"
		ORDER BY
			u.rating DESC
		LIMIT 0,10
	');
 	$stmt->execute();
	
	$response['data']['winners'] = array();
	$place = 1;
 	while ($row = $stmt->fetch()) {
		$response['data']['winners'][] = array(
			'place' => $place,
			'user' => htmlspecialchars($row['nick']),
			'rating' => $row['rating'],
		);
		$place++;
	}

} catch(PDOException $e) {
 	APIHelpers::error(500, $e->getMessage());
}

APIHelpers::endpage($response);
