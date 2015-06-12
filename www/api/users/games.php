<?php
/*
 * API_NAME: Get user info
 * API_DESCRIPTION: Method returned user info
 * API_ACCESS: authorized user
 * API_INPUT: userid - integer, user id
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();
$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('userid'))
	APIHelpers::showerror(1177, 'Not found parameter userid');

$userid = APIHelpers::getParam('userid', 0);

if (!is_numeric($userid))
	APIHelpers::showerror(1181, 'Parameter userid must be integer');

$userid = intval($userid);

try {
	$stmt = $conn->prepare('
		SELECT
			score,
			title,
			maxscore,
			gameid,
			logo
		FROM users_games ug
		INNER JOIN games g ON ug.gameid = g.id
		WHERE ug.userid = ?
	');
	$stmt->execute(array($userid));
	
	while($row = $stmt->fetch())
	{
		$response['data'][] = array(
			'gameid' => $row['gameid'],
			'score' => $row['score'],
			'maxscore' => $row['maxscore'],
			'title' => $row['title'],
			'logo' => $row['logo'],
		);
	}
	$response['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1184, $e->getMessage());
}

APIHelpers::endpage($response);
