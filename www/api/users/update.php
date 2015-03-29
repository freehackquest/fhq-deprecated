<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include ($curdir."/../api.lib/api.helpers.php");
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");

$security = new fhq_security();
checkAuth($security);


// TODO 
/*
$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if($security->isAdmin())
  showerror(756, 'Error 756: access denie. you must be admin.');

$columns = array(
  'global_id' => 'none',
  'game_name' => 'Unknown',
  'game_logo' => '',  
  'game_type' => 'jeopardy',
  'start_date' => '0000-00-00 00:00:00',
  'end_date' => '0000-00-00 00:00:00',
  'author_id' => $security->userId(),
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
  $values_q[] = $k.' = ?';
  if (issetParam($k))
    $param_values[$k] = getParam($k, $v);
  else
    showerror(758, 'Error 758: not found parameter "'.$k.'"');
}

if (!is_numeric($param_values['author_id']))
	showerror(755, 'Error 755: incorrect author_id');

$param_values['author_id'] = intval($param_values['author_id']);

$query = 'UPDATE games SET '.implode(',', $values_q).', change_date = NOW()
  WHERE id = ?';

$values = array_values($param_values);
$values[] = $game_id; 

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($values);
  $result['result'] = 'ok';
} catch(PDOException $e) {
  showerror(747, 'Error 747: ' + $e->getMessage());
}

*/
echo json_encode($result);
