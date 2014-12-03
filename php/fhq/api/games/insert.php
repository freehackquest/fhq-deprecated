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

if ( issetParam('global_id') 
  && issetParam('game_name') 
  && issetParam('game_logo') 
  && issetParam('game_type')
  && issetParam('start_date') 
  && issetParam('end_date') 
  && issetParam('author_id') 
) {
	$global_id = getParam('global_id', 'none');
  $game_name = getParam('game_name', 'Unknown');
  $game_logo = getParam('game_logo', '');
  $game_type = getParam('game_type', 'jeopardy');
  $start_date = getParam('start_date', '0000-00-00 00:00:00');
  $end_date = getParam('end_date', '0000-00-00 00:00:00');
  $author_id = getParam('author_id', $security->userId());

  if (!is_numeric($author_id))
		showerror(745, 'Error 745: incorrect author_id');

  if($security->isAdmin())
  	showerror(746, 'Error 746: access denie. you must be admin.');

   $author_id = intval($author_id);
	
	try {
    $query = '
			INSERT INTO games(global_id, game_name, game_logo, start_date, end_date, change_date, author_id, change_date) 
      VALUES(?,?,?,?,?,?,?,NOW());
    ';

		$values = array($global_id, $game_name, $game_logo,$game_type,$start_date,$end_date,$author_id);

		$stmt = $conn->prepare($query);
		$stmt->execute($values);    
    $result['data']['game']['id'] = $conn->lastInsertId();
		$result['result'] = 'ok';
	} catch(PDOException $e) {
		showerror(747, 'Error 747: ' + $e->getMessage());
	}
} else {
	showerror(748, 'Error 748: not found parameter id');
}
echo json_encode($result);
