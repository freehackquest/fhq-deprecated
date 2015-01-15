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

if($security->isAdmin())
  showerror(746, 'Error 746: access denie. you must be admin.');

  /*
$columns = array(
  'global_id' => 'none',
  'game_name' => 'Unknown',
  'game_logo' => '',  
  'game_type' => 'jeopardy',
  'start_date' => '0000-00-00 00:00:00',
  'end_date' => '0000-00-00 00:00:00',
  'author_id' => $security->userId(),
);

$param_values = array(); 
$values_q = array();

foreach ( $columns as $k => $v) {
  $values_q[] = '?';
  if (issetParam($k))
    $param_values[$k] = getParam($k, $v);
  else
    showerror(748, 'Error 748: not found parameter "'.$k.'"');
}

if (!is_numeric($param_values['author_id']))
	showerror(745, 'Error 745: incorrect author_id');

$param_values['author_id'] = intval($param_values['author_id']);

$query = 'INSERT INTO games('.implode(',', array_keys($param_values)).', change_date) 
  VALUES('.implode(',', $values_q).', NOW());';

$values = array_values($param_values);

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($values);    
  $result['data']['game']['id'] = $conn->lastInsertId();
  $result['result'] = 'ok';
} catch(PDOException $e) {
  showerror(747, 'Error 747: ' + $e->getMessage());
}
*/

echo json_encode($result);
