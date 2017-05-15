<?php
/*
 * API_NAME: Scoreboard
 * API_DESCRIPTION: Method will be returned scoreboard for game
 * API_ACCESS: all
 * API_INPUT: gameid - string, Identificator of the game
 * API_INPUT: token - guid, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");

$response = APIHelpers::startpage();

$message = '';

$response['result'] = 'ok';

// TODO: must be added filters
$conn = APIHelpers::createConnection();

$filter_by_role = APISecurity::isAdmin() == false ? 'WHERE u.role = "user" ' : '';

$query = '
			SELECT
				u.id,
				u.nick,
				u.role,
				u.logo,
				u.rating
			FROM 
				users u
				'.$filter_by_role.'
			ORDER BY
				u.rating DESC
		';

$stmt = $conn->prepare($query);
$stmt->execute(array());
$i = 1;
$score = 0;
$response['data'] = array();

while($row = $stmt->fetch()) {
	$user_score = $row['rating'];
	if ($i == 1 && $score == 0)
		$score = $row['rating'];

	if ($score != $user_score)
	{
		$score = $user_score;
		$i++;
	}

	$response['data'][$i][] = array(
		'id' => $row['id'],
		'nick' => htmlspecialchars($row['nick']),
		'logo' => $row['logo'],
		'rating' => $row['rating'],
		// 'role' => $row['role'],
	);
}

APIHelpers::endpage($response);
