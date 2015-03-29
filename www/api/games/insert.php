<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_games_insert = dirname(__FILE__);
include_once ($curdir_games_insert."/../api.lib/api.helpers.php");
include_once ($curdir_games_insert."/../../config/config.php");
include_once ($curdir_games_insert."/../api.lib/api.base.php");

include_once ($curdir_games_insert."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if(!APISecurity::isAdmin())
  APIHelpers::showerror(746, 'access denie. you must be admin.');

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
  'rules' => '',
);

$param_values = array(); 
$values_q = array();

foreach ( $columns as $k => $v) {
  $values_q[] = '?';
  if ($k == 'owner')
	$param_values[$k] = $v;
  else if (APIHelpers::issetParam($k))
    $param_values[$k] = APIHelpers::getParam($k, $v);
  else
    APIHelpers::showerror(748, 'not found parameter "'.$k.'"');
}

if (!is_numeric($param_values['owner']))
	APIHelpers::showerror(745, 'incorrect owner');

$param_values['owner'] = intval($param_values['owner']);

$query = 'INSERT INTO games('.implode(',', array_keys($param_values)).', date_change, date_create) 
  VALUES('.implode(',', $values_q).', NOW(), NOW());';

$values = array_values($param_values);
$result['param_values'] = $param_values;
$result['query'] = $query;

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($values);
	$result['data']['game']['id'] = $conn->lastInsertId();
	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(747, $e->getMessage());
}

echo json_encode($result);
