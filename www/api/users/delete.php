<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Delete user
 * API_DESCRIPTION: Method for delete user
 * API_ACCESS: admin only
 * API_INPUT: userid - integer, user id
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir_users_delete = dirname(__FILE__);
include_once ($curdir_users_delete."/../api.lib/api.base.php");
include_once ($curdir_users_delete."/../../config/config.php");

$result = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$conn = APIHelpers::createConnection($config);

if (!APISecurity::isAdmin()) 
	APIHelpers::showerror(1107, 'access only for admin');

if (!APIHelpers::issetParam('userid'))
  APIHelpers::showerror(1108, 'Not found parameter "userid"');

$userid = APIHelpers::getParam('userid', 0);

if (!is_numeric($userid))
  APIHelpers::showerror(1109, 'userid must be numeric');

$nick = '';
// check user
try {
	$stmt = $conn->prepare('SELECT id, nick FROM users WHERE id = ?');
	$stmt->execute(array($userid));
	if ($row = $stmt->fetch()) {
		$nick = $row['nick'];
	} else {
		APIHelpers::showerror(1111, 'Userid did not found');
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1110, $e->getMessage());
}


try {
	$params = array($userid);
 	$conn->prepare('DELETE FROM users WHERE id = ?')->execute($params);
 	$conn->prepare('DELETE FROM users_games WHERE userid = ?')->execute($params);
 	$conn->prepare('DELETE FROM feedback WHERE userid = ?')->execute($params);
 	$conn->prepare('DELETE FROM feedback_msg WHERE userid = ?')->execute($params);
 	
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1147, $e->getMessage());
}

APIEvents::addPublicEvents($conn, 'users', 'User #'.$userid.' {'.htmlspecialchars($nick).'} was removed by admin!');

echo json_encode($result);
