<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Update Game Info
 * API_DESCRIPTION: Method for update game info
 * API_ACCESS: admin only
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

include_once ($curdir_games_update."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

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

$game_id = getParam('id', 0);

if (!is_numeric($game_id))
	APIHelpers::showerror(1156, 'incorrect "id"');

$game_id = intval($game_id);

$param_values = array(); 
$values_q = array();

foreach ( $columns as $k => $v) {
  $values_q[] = ' '.$k.' = ?';
  if (APIHelpers::issetParam($k))
    $param_values[$k] = APIHelpers::getParam($k, $v);
  else
    APIHelpers::showerror(1157, 'Error 758: not found parameter "'.$k.'"');
}

$query = 'UPDATE games SET '.implode(',', $values_q).', date_change = NOW()
  WHERE id = ?';

$values = array_values($param_values);
$values[] = $game_id; 

// $result['query'] = $query;
// $result['values'] = $values;

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($values);
	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1158, $e->getMessage());
}


echo json_encode($result);
