<?php
/*
 * API_NAME: Recalculate Score (helpers method)
 * API_DESCRIPTION: It's helpers method for update score for current user for selected game
 * API_ACCESS: authorized users
 * API_INPUT: gameid - Integer, Identificator of game
 * API_INPUT: token - guid, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

if (!APIHelpers::issetParam('gameid'))
	APIHelpers::error(400, 'Not found parameter "gameid"');

$conn = APIHelpers::createConnection($config);
$gameid = APIHelpers::getParam('gameid', 0);

if (!is_numeric($gameid))
	APIHelpers::error(400, 'gameid must be numeric');

/*
// TODO
$errmsg = "";
if (!checkGameDates($security, &$message))
	APIHelpers::error(403, $errmsg);
*/


$query = '
	SELECT 
		ifnull(SUM(quest.score),0) as sum_score 
	FROM 
		users_quests
	INNER JOIN
		quest ON quest.idquest = users_quests.questid AND quest.gameid = ?
	WHERE
		users_quests.userid = ?
';

try {

	$score = 0;
	// loading score
	$stmt2 = $conn->prepare('select * from users_games where userid = ? AND gameid = ?');
	$stmt2->execute(array(intval(APISecurity::userid()), intval($gameid)));
	if($row2 = $stmt2->fetch())
	{
		$response['user'] = array();
		$response['user']['score'] = $row2['score'];
	}
	else
	{
		$stmt3 = $conn->prepare('INSERT INTO users_games (userid, gameid, score, date_change) VALUES(?,?,0,NOW())');
		$stmt3->execute(array(intval(APISecurity::userid()), intval($gameid)));
		$response['user'] = array();
		$response['user']['score'] = 0;
	}

	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($gameid), intval(APISecurity::userid())));
	if($row = $stmt->fetch())
	{
		$response['user'] = array();
		$response['user']['score'] = $row['sum_score'];
		$response['result'] = 'ok';
		
		if ($row['sum_score'] != $score)
		{
			$stmt = $conn->prepare('UPDATE users_games SET score = ?, date_change = NOW() WHERE gameid = ? AND userid = ?');
			$stmt->execute(array(intval($row['sum_score']), intval($gameid), intval(APISecurity::userid())));
		}
	}
	else
	{
		APIHelpers::error(404, 'Game #'.$gameid.' does not exists');
	}
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

APIHelpers::endpage($response);
