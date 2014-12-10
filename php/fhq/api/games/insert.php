<?php
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

$conn = createConnection($config);

if(!$security->isAdmin())
  showerror(746, 'Error 746: access denie. you must be admin.');

$columns = array(
  'uuid_game' => 'none',
  'title' => 'Unknown',
  'logo' => '',
  'type_game' => 'jeopardy',
  'date_start' => '0000-00-00 00:00:00',
  'date_stop' => '0000-00-00 00:00:00',
  'date_restart' => '0000-00-00 00:00:00',
  'description' => '',
  'owner' => $security->userId(),
);

$param_values = array(); 
$values_q = array();

foreach ( $columns as $k => $v) {
  $values_q[] = '?';
  if ($k == 'owner')
	$param_values[$k] = $v;
  else if (issetParam($k))
    $param_values[$k] = getParam($k, $v);
  else
    showerror(748, 'Error 748: not found parameter "'.$k.'"');
}

if (!is_numeric($param_values['owner']))
	showerror(745, 'Error 745: incorrect owner');

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
	showerror(747, 'Error 747: ' + $e->getMessage());
}

echo json_encode($result);
