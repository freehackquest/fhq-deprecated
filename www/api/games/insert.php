<?php
/*
 * API_NAME: Insert Game Info
 * API_DESCRIPTION: Method for insert game
 * API_ACCESS: admin only
 * API_INPUT: token - guid, token
 * API_INPUT: uuid_game - string, Global Identificator of the game
 * API_INPUT: title - string, name of the game
 * API_INPUT: logo - string, link to the picture (will be depricated)
 * API_INPUT: type_game - string, type of the game, currently only possible: jeopardy
 * API_INPUT: date_start - datetime, when game will be started
 * API_INPUT: date_stop - datetime, when game will be stoped
 * API_INPUT: date_restart - datetime, when game will be restarted
 * API_INPUT: description - string, some description of the game
 * API_INPUT: state - string, look types (copy, unlicensed copy and etc.)
 * API_INPUT: form - string, look types (online or offline)
 * API_INPUT: organizators - string, who make this game
 */
  
$curdir_games_insert = dirname(__FILE__);
include_once ($curdir_games_insert."/../api.lib/api.helpers.php");
include_once ($curdir_games_insert."/../../config/config.php");
include_once ($curdir_games_insert."/../api.lib/api.base.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();
$conn = APIHelpers::createConnection($config);

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1160, 'access denie. you must be admin.');

$columns = array(
  'uuid_game' => 'none',
  'title' => 'Unknown',
  'logo' => '',
  'type_game' => 'jeopardy',
  'date_start' => '0000-00-00 00:00:00',
  'date_stop' => '0000-00-00 00:00:00',
  'date_restart' => '0000-00-00 00:00:00',
  'description' => '',
  'state' => 'Unlicensed copy',
  'form' => 'online',
  'owner' => APISecurity::userid(),
  'organizators' => '',
);

$param_values = array(); 
$values_q = array();
$title = '';

foreach ( $columns as $k => $v) {
  $values_q[] = '?';
  if ($k == 'owner')
	$param_values[$k] = $v;
  else if (APIHelpers::issetParam($k)) {
    $param_values[$k] = APIHelpers::getParam($k, $v);
  } else
    APIHelpers::showerror(1161, 'not found parameter "'.$k.'"');
}

if (!is_numeric($param_values['owner']))
	APIHelpers::showerror(1162, 'incorrect owner');

$param_values['owner'] = intval($param_values['owner']);

$query = 'INSERT INTO games('.implode(',', array_keys($param_values)).', date_change, date_create) 
  VALUES('.implode(',', $values_q).', NOW(), NOW());';

$values = array_values($param_values);
// $response['param_values'] = $param_values;
// $response['query'] = $query;

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($values);
	$response['data']['game']['id'] = $conn->lastInsertId();
	$response['result'] = 'ok';
	APIEvents::addPublicEvents($conn, 'games', "New game #".$response['data']['game']['id'].' '.htmlspecialchars($param_values['title']));
} catch(PDOException $e) {
	APIHelpers::showerror(1163, $e->getMessage());
}

APIHelpers::endpage($response);
