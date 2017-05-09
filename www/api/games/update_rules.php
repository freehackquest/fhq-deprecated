<?php
/*
 * API_NAME: Update Game Rules
 * API_DESCRIPTION: Method for update game rules
 * API_ACCESS: admin only
 * API_INPUT: id - string, Identificator of the game
 * API_INPUT: rules - string, some rules
 */

$curdir_games_update_rules = dirname(__FILE__);
include_once ($curdir_games_update_rules."/../api.lib/api.base.php");
include_once ($curdir_games_update_rules."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::error(403, 'access denie. you must be admin.');
  
$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('id'))
  APIHelpers::error(400, 'not found parameter "id"');

$gameid = APIHelpers::getParam('id', 0);

if (!is_numeric($gameid))
	APIHelpers::error(400, '"id" must be numeric');

$gameid = intval($gameid);

if (!APIHelpers::issetParam('rules'))
  APIHelpers::error(400, 'not found parameter "rules"');

$rules = APIHelpers::getParam('rules', '');

// check game
$title = '';
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
	$stmt = $conn->prepare('UPDATE games SET rules = ?, date_change = NOW() WHERE id = ?');
	$stmt->execute(array($rules, $gameid));
	$response['result'] = 'ok';
	
	APIEvents::addPublicEvents($conn, 'games', "Updated rules for game #".$gameid.' '.htmlspecialchars($title));
	
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

APIHelpers::endpage($response);
