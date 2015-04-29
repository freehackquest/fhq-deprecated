<?php
/*
 * API_NAME: Get Game Info
 * API_DESCRIPTION: Mthod returned information about game
 * API_ACCESS: all
 * API_INPUT: gameid - integer, Identificator of the game (defualt current id)
 * API_INPUT: token - guid, token
 */

$curdir_games_get = dirname(__FILE__);
include_once ($curdir_games_get."/../api.lib/api.base.php");
include_once ($curdir_games_get."/../api.lib/api.game.php");
include_once ($curdir_games_get."/../../config/config.php");

$response = APIHelpers::startpage($config);

$conn = APIHelpers::createConnection($config);

$gameid = APIHelpers::getParam('gameid', APIGame::id());

if (!is_numeric($gameid))
	APIHelpers::showerror(1171, '"gameid" must be numeric');

try {

	$query = '
		SELECT *
		FROM
			games
		WHERE id = ?';

	$columns = array('id', 'type_game', 'state', 'form', 'title', 'date_start', 'date_stop', 'date_restart', 'description', 'logo', 'owner', 'organizators', 'rules', 'maxscore');

	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($gameid)));
	if($row = $stmt->fetch())
	{
		$response['data'] = array();
		foreach ( $columns as $k) {
			$response['data'][$k] = $row[$k];
		}
		$response['result'] = 'ok';
	} else {
		APIHelpers::showerror(1171, 'Did not found game with this id');
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1169, $e->getMessage());
}

APIHelpers::endpage($response);
