<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include ($curdir."/../api.lib/api.helpers.php");
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");

$security = new fhq_security();
checkAuth($security);

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = FHQHelpers::createConnection($config);

if(!$security->isAdmin())
  showerror(756, 'Error 756: access denie. you must be admin.');

$columns = array(
  'title' => 'Unknown',
  'logo' => '',  
  'type_game' => 'jeopardy',
  'date_start' => '0000-00-00 00:00:00',
  'date_stop' => '0000-00-00 00:00:00',
  'date_restart' => '0000-00-00 00:00:00',
  'description' => '',
  'organizators' => '',
);

if (!issetParam('id'))
  showerror(759, 'Error 759: not found parameter "id"');

$game_id = getParam('id', 0);

if (!is_numeric($game_id))
	showerror(754, 'Error 754: incorrect "id"');

$game_id = intval($game_id);

$param_values = array(); 
$values_q = array();

foreach ( $columns as $k => $v) {
  $values_q[] = ' '.$k.' = ?';
  if (issetParam($k))
    $param_values[$k] = getParam($k, $v);
  else
    showerror(758, 'Error 758: not found parameter "'.$k.'"');
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
	showerror(747, 'Error 747: ' + $e->getMessage());
}


echo json_encode($result);
