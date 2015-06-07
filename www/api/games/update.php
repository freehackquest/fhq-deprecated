<?php
/*
 * API_NAME: Update Game Info
 * API_DESCRIPTION: Method for update game info
 * API_ACCESS: admin only
 * API_INPUT: token - guid, token
 * API_INPUT: id - string, Identificator of the game
 * API_INPUT: title - string, name of the game
 * API_INPUT: type_game - string, type of the game, currently only possible: jeopardy
 * API_INPUT: date_start - datetime, when game will be started
 * API_INPUT: date_stop - datetime, when game will be stoped
 * API_INPUT: date_restart - datetime, when game will be restarted
 * API_INPUT: description - string, some description of the game
 * API_INPUT: state - string, look types (copy, unlicensed copy and etc.)
 * API_INPUT: form - string, look types (online or offline)
 * API_INPUT: organizators - string, who make this game
 */

$curdir_games_update = dirname(__FILE__);
include_once ($curdir_games_update."/../api.lib/api.helpers.php");
include_once ($curdir_games_update."/../../config/config.php");
include_once ($curdir_games_update."/../api.lib/api.base.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

$conn = APIHelpers::createConnection($config);

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1159, 'Error 756: access denie. you must be admin.');

$columns = array(
  'title' => 'Unknown',
  'type_game' => 'jeopardy',
  'date_start' => '0000-00-00 00:00:00',
  'date_stop' => '0000-00-00 00:00:00',
  'date_restart' => '0000-00-00 00:00:00',
  'description' => '',
  'state' => 'Unlicensed copy',
  'form' => 'online',
  'organizators' => '',
);

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1155, 'not found parameter "id"');

$gameid = getParam('id', 0);

if (!is_numeric($gameid))
	APIHelpers::showerror(1156, 'incorrect "id"');

$gameid = intval($gameid);

$param_values = array(); 
$values_q = array();

foreach ( $columns as $k => $v) {
  $values_q[] = ' '.$k.' = ?';
  if (APIHelpers::issetParam($k))
    $param_values[$k] = APIHelpers::getParam($k, $v);
  else
    APIHelpers::showerror(1157, 'Does not found parameter "'.$k.'"');
}

// check game
try {
	$stmt = $conn->prepare('SELECT * FROM games WHERE id = ?');
	$stmt->execute(array(intval($gameid)));
	if ($row = $stmt->fetch()) {
		// $title = $row['title'];
	} else {
		APIHelpers::showerror(1324, 'Game #'.$gameid.' does not exists.');
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1325, $e->getMessage());
}

$query = 'UPDATE games SET '.implode(',', $values_q).', date_change = NOW()
  WHERE id = ?';

$values = array_values($param_values);
$values[] = $gameid;

// $result['query'] = $query;
// $result['values'] = $values;

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($values);
	$response['result'] = 'ok';
	APIEvents::addPublicEvents($conn, 'games', "Updated game #".$gameid.' '.htmlspecialchars($param_values['title']));
} catch(PDOException $e) {
	APIHelpers::showerror(1158, $e->getMessage());
}

APIHelpers::endpage($response);
