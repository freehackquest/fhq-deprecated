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

if (!issetParam('id')) {
  showerror(823, 'Error 823: not found parameter id');

$user_id = getParam('id', 0);

if (!is_numeric($user_id))
	showerror(825, 'Error 825: incorrect id');

$user_id = intval($user_id);

// todo: team
$columns = array('iduser', 'score', 'nick');

if($security->isAdmin() || $security->isTester() || $security->userId() == $user_id)
  $columns = array('iduser', 'username', 'score', 'role', 'nick');

$query = '
		SELECT '.implode(',', $columns).' FROM
			user
		WHERE iduser = ?
';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($game_id)));
	if($row = $stmt->fetch())
	{
		$result['data'] = array();
		foreach ( $columns as $k) {
			$result['data'][$k] = $row[$k];
		}
	}
	$result['result'] = 'ok';
} catch(PDOException $e) {
	showerror(822, 'Error 822: ' + $e->getMessage());
}
    
// TODO: added more information about user
		
echo json_encode($result);
