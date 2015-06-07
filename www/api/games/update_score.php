<?php
/*
 * API_NAME: Recalculate Score (helpers method)
 * API_DESCRIPTION: It's helpers method for update score for current user for selected game
 * API_ACCESS: authorized users
 * API_INPUT: token - guid, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

/*
// TODO
$errmsg = "";
if (!checkGameDates($security, &$message))
	APIHelpers::showerror(1191, $errmsg);
*/

$conn = APIHelpers::createConnection($config);

$gameid = APIGame::id();
if ($gameid == 0)
	APIHelpers::showerror(1172, 'Please choose game');

$query = '
	SELECT 
		ifnull(SUM(quest.score),0) as sum_score 
	FROM 
		userquest 
	INNER JOIN 
		quest ON quest.idquest = userquest.idquest AND quest.gameid = ?
	WHERE 
		(userquest.iduser = ?) 
		AND ( userquest.stopdate <> \'0000-00-00 00:00:00\' );
';

try {

	$score = 0;
	// loading score
	$stmt2 = $conn->prepare('select * from users_games where userid= ? AND gameid = ?');
	$stmt2->execute(array(intval(APISecurity::userid()), intval($gameid)));
	if($row2 = $stmt2->fetch())
	{
		$_SESSION['user']['score'] = $row2['score'];
		$response['user'] = array();
		$response['user']['score'] = $row2['score'];
	}
	else
	{
		$stmt3 = $conn->prepare('INSERT INTO users_games (userid, gameid, score, date_change) VALUES(?,?,0,NOW())');
		$stmt3->execute(array(intval(APISecurity::userid()), intval($gameid)));
		$_SESSION['user']['score'] = 0;
		$response['user'] = array();
		$response['user']['score'] = 0;
	}

	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($gameid), intval(APISecurity::userid())));
	if($row = $stmt->fetch())
	{
		$_SESSION['user']['score'] = $row['sum_score'];
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
		APIHelpers::showerror(1173, 'Game #'.$gameid.' does not exists');
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1174, $e->getMessage());
}

APIHelpers::endpage($response);
