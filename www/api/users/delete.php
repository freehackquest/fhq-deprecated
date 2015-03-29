<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_users_delete = dirname(__FILE__);
include_once ($curdir_users_delete."/../api.lib/api.base.php");
include_once ($curdir_users_delete."/../../config/config.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APISecurity::isAdmin()) 
	APIHelpers::showerror(912, 'only for admin');

if (!APIHelpers::issetParam('userid'))
  APIHelpers::showerror(889, 'Error 889: not found parameter "userid"');

$userid = APIHelpers::getParam('userid', 0);

if (!is_numeric($userid))
  APIHelpers::showerror(885, 'userid must be numeric');

$nick = '';
// check user
try {
	$stmt = $conn->prepare('SELECT iduser, nick FROM user WHERE iduser = ?');
	$stmt->execute(array($userid));
	if ($row = $stmt->fetch()) {
		$nick = $row['nick'];
	} else {
		APIHelpers::showerror(822, "User with id ".$userid.' did not found' );
	}
} catch(PDOException $e) {
	APIHelpers::showerror(822, $e->getMessage());
}


try {
	$params = array($userid);
 	$conn->prepare('DELETE FROM user WHERE iduser = ?')->execute($params);
 	$conn->prepare('DELETE FROM users_games WHERE userid = ?')->execute($params);
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(882, $e->getMessage());
}

APIEvents::addPublicEvents($conn, 'users', 'User #'.$userid.' {'.htmlspecialchars($nick).'} was removed by admin!');

echo json_encode($result);
