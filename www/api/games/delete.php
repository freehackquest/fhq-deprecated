<?php

/*
 * API_NAME: Delete Game
 * API_DESCRIPTION: remove game from system (and all requaried records)
 * API_ACCESS: admin only
 * API_INPUT: token - guid, token
 * API_INPUT: gameid - integer, Identificator of the game
 */

$curdir_games_delete = dirname(__FILE__);
include_once ($curdir_games_delete."/../api.lib/api.helpers.php");
include_once ($curdir_games_delete."/../../config/config.php");
include_once ($curdir_games_delete."/../api.lib/api.base.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();
$conn = APIHelpers::createConnection($config);

if(!APISecurity::isAdmin())
  APIHelpers::error(403, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::error(400, 'not found parameter "id"');

$gameid = APIHelpers::getParam('id', 0);

if (!is_numeric($gameid))
  APIHelpers::error(400, 'incorrect id');

$title = '';

// check game
try {
	$stmt = $conn->prepare('SELECT * FROM games WHERE id = ?');
	$stmt->execute(array(intval($gameid)));
	if ($row = $stmt->fetch()) {
		$title = $row['title'];
	} else {
		APIHelpers::error(404, 'Game #'.$gameid.' does not exists.');
	}
} catch(PDOException $e) {
 	APIHelpers::error(500, $e->getMessage());
}

try {
 	$stmt_games = $conn->prepare('DELETE FROM games WHERE id = ?');
 	$stmt_games->execute(array(intval($gameid)));

	// remove from users_games
 	$stmt_users_games = $conn->prepare('DELETE FROM users_games WHERE gameid = ?');
 	$stmt_users_games->execute(array(intval($gameid)));

	// remove from users_quests_answers
	$stmt_users_quests_answers = $conn->prepare('DELETE FROM users_quests_answers WHERE idquest IN (SELECT idquest FROM quest q WHERE q.gameid = ?)');
 	$stmt_users_quests_answers->execute(array(intval($gameid)));

 	// remove from users_quests
	$stmt_users_quests = $conn->prepare('DELETE FROM users_quests WHERE questid IN (SELECT idquest FROM quest q WHERE q.gameid = ?)');
 	$stmt_users_quests->execute(array(intval($gameid)));

	// remove from quest
	$stmt_quest = $conn->prepare('DELETE FROM quest WHERE gameid = ?');
 	$stmt_quest->execute(array(intval($gameid)));

 	$response['result'] = 'ok';
 	APIEvents::addPublicEvents($conn, 'games', "Removed game #".$gameid.' '.htmlspecialchars($title));
} catch(PDOException $e) {
 	APIHelpers::error(500, $e->getMessage());
}

APIHelpers::endpage($response);
