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
  APIHelpers::showerror(1149, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1150, 'not found parameter "id"');

$gameid = APIHelpers::getParam('id', 0);

if (!is_numeric($gameid))
  APIHelpers::showerror(1153, 'incorrect id');

$title = '';

// check game
try {
	$stmt = $conn->prepare('SELECT * FROM games WHERE id = ?');
	$stmt->execute(array(intval($gameid)));
	if ($row = $stmt->fetch()) {
		$title = $row['title'];
	} else {
		APIHelpers::showerror(1200, 'Game #'.$gameid.' does not exists.');
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1151, $e->getMessage());
}

try {
 	$stmt_games = $conn->prepare('DELETE FROM games WHERE id = ?');
 	$stmt_games->execute(array(intval($gameid)));

	// remove from users_games
 	$stmt_users_games = $conn->prepare('DELETE FROM users_games WHERE gameid = ?');
 	$stmt_users_games->execute(array(intval($gameid)));

	// remove from tryanswer
	$stmt_tryanswer = $conn->prepare('DELETE FROM tryanswer WHERE idquest IN (SELECT idquest FROM quest q WHERE q.gameid = ?)');
 	$stmt_tryanswer->execute(array(intval($gameid)));
 	
	// remove from tryanswer_backup
	$stmt_tryanswer_backup = $conn->prepare('DELETE FROM tryanswer_backup WHERE idquest IN (SELECT idquest FROM quest q WHERE q.gameid = ?)');
 	$stmt_tryanswer_backup->execute(array(intval($gameid)));

	// remove from userquest
	$stmt_userquest = $conn->prepare('DELETE FROM userquest WHERE idquest IN (SELECT idquest FROM quest q WHERE q.gameid = ?)');
 	$stmt_userquest->execute(array(intval($gameid)));
 	
 	// remove from users_quests
	$stmt_users_quests = $conn->prepare('DELETE FROM users_quests WHERE questid IN (SELECT idquest FROM quest q WHERE q.gameid = ?)');
 	$stmt_users_quests->execute(array(intval($gameid)));

	// remove from quest
	$stmt_quest = $conn->prepare('DELETE FROM quest WHERE gameid = ?');
 	$stmt_quest->execute(array(intval($gameid)));

 	$response['result'] = 'ok';
 	APIEvents::addPublicEvents($conn, 'games', "Removed game #".$gameid.' '.htmlspecialchars($title));
} catch(PDOException $e) {
 	APIHelpers::showerror(1154, $e->getMessage());
}

APIHelpers::endpage($response);
