<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$message = '';

$result = array(
	'result' => 'fail',
	'data' => array(),
);

/*$errmsg = "";
if (!checkGameDates($security, &$message))
	showerror(709, 'Error 709: '.$errmsg);*/


$conn = APIHelpers::createConnection($config);

$gameid = APIGame::id();
if ($gameid == 0)
	APIHelpers::showerror(616, 'Please choose game');

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
		$result['user'] = array();
		$result['user']['score'] = $row2['score'];
	}
	else
	{
		$stmt3 = $conn->prepare('INSERT INTO users_games (userid, gameid, score, date_change) VALUES(?,?,0,NOW())');
		$stmt3->execute(array(intval(APISecurity::userid()), intval($gameid)));
		$_SESSION['user']['score'] = 0;
		$result['user'] = array();
		$result['user']['score'] = 0;
	}
		

	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($gameid), intval(APISecurity::userid())));
	if($row = $stmt->fetch())
	{
		$_SESSION['user']['score'] = $row['sum_score'];
		$result['user'] = array();
		$result['user']['score'] = $row['sum_score'];
		$result['result'] = 'ok';
		
		if ($row['sum_score'] != $score)
		{
			$stmt = $conn->prepare('UPDATE users_games SET score = ?, date_change = NOW() WHERE gameid = ? AND userid = ?');
			$stmt->execute(array(intval($row['sum_score']), intval($gameid), intval(APISecurity::userid())));
		}
	}
	else
	{
		APIHelpers::showerror(616, 'Game not found');
	}
} catch(PDOException $e) {
	APIHelpers::showerror(716, $e->getMessage());
}

echo json_encode($result);
