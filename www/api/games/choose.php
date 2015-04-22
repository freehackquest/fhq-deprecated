<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Choose Game
 * API_DESCRIPTION: select game and it will be write to user session.
 * API_ACCESS: authorized users
 * API_INPUT: id - string, Identificator of the game
 * API_INPUT: token - string, token
 */

$curdir_games_choose = dirname(__FILE__);
include_once ($curdir_games_choose."/../api.lib/api.base.php");
include_once ($curdir_games_choose."/../api.lib/api.security.php");
include_once ($curdir_games_choose."/../api.lib/api.helpers.php");
include_once ($curdir_games_choose."/../../config/config.php");

include_once ($curdir_games_choose."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

/*$errmsg = "";
if (!checkGameDates($security, &$message))
	APIHelpers::showerror(1175, $errmsg);*/

$conn = APIHelpers::createConnection($config);

if (APIHelpers::issetParam('id')) {
	$game_id = APIHelpers::getParam('id', 0);

	if (!is_numeric($game_id))
		APIHelpers::showerror(1176, 'Error 705: incorrect id');

	// try {
		$query = '
			SELECT *
			FROM
				games
			WHERE id = ?';

		$columns = array('id', 'type_game', 'title', 'date_start', 'date_stop', 'date_restart', 'description', 'logo', 'owner');

		$stmt = $conn->prepare($query);
		$stmt->execute(array(intval($game_id)));
		if($row = $stmt->fetch())
		{
			$_SESSION['game'] = array();
			$result['data'] = array();
			foreach ( $columns as $k) {
				$_SESSION['game'][$k] = $row[$k];
				$result['data'][$k] = $row[$k];
			}
			$result['result'] = 'ok';
		}
		else
		{
			APIHelpers::showerror(1178, 'Game with id='.$game_id.' are not exists');
		}

		// loading score
		$stmt2 = $conn->prepare('select * from users_games where userid= ? AND gameid = ?');
		$stmt2->execute(array(intval(APISecurity::userid()), intval($game_id)));
		if($row2 = $stmt2->fetch())
		{
			$_SESSION['user']['score'] = $row2['score'];
			$result['user'] = array();
			$result['user']['score'] = $row2['score'];
		}
		else
		{
			// calculate score
			$query2 = '
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
			$score = 0;
			$stmt4 = $conn->prepare($query2);
			$stmt4->execute(array(intval($game_id), APISecurity::userid()));
			if ($row3 = $stmt4->fetch())
				$score = $row3['sum_score'];
			
			$stmt3 = $conn->prepare('INSERT INTO users_games (userid, gameid, score, date_change) VALUES(?,?,?,NOW())');
			$stmt3->execute(array(intval(APISecurity::userid()), intval($game_id), intval($score)));
			
			$_SESSION['user']['score'] = $score;
			$result['user'] = array();
			$result['user']['score'] = $score;
		}	
	// } catch(PDOException $e) {
//		APIHelpers::showerror(1179, $e->getMessage());
//	}
} else {
	APIHelpers::showerror(1180, 'not found parameter id');
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
