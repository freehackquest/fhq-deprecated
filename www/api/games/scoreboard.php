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
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);

$message = '';

if (!APIHelpers::issetParam('gameid'))
	APIHelpers::error(400, 'Parameter "gameid" does not found');

$gameid = APIHelpers::getParam('gameid', 0);

if (!is_numeric($gameid))
	APIHelpers::error(400, 'Parameter "gameid" must be numeric');


$response['result'] = 'ok';

// TODO: must be added filters
$conn = APIHelpers::createConnection($config);

$response['gameid'] = $gameid;

$params[] = $gameid;

$filter_by_role = APISecurity::isAdmin() == false ? ' AND u.role = "user" ' : '';

$query = '
			SELECT 
				u.nick,
				u.role,
				u.logo,
				ug.userid,
				ug.score
			FROM 
				users_games ug
			LEFT JOIN 
				users u ON u.id = ug.userid
			WHERE
				ug.gameid = ?
				'.$filter_by_role.'
			ORDER BY
				ug.score DESC
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	$i = 1;
	$score = 0;
	$response['data'] = array();
	
	while($row = $stmt->fetch())
	{
		$user_score = $row['score'];
		if ($i == 1 && $score == 0)
			$score = $row['score'];

		if ($score != $user_score)
		{
			$score = $user_score;
			$i++;
		}

		$response['data'][$i][] = array(
			'userid' => $row['userid'],
			'nick' => htmlspecialchars($row['nick']),
			'logo' => $row['logo'],
			'score' => $row['score'],
			// 'role' => $row['role'],
		);
	}
	
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

APIHelpers::endpage($response);
