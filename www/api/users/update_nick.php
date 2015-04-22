<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Update User's Nick
 * API_DESCRIPTION: Method for update user avatar
 * API_ACCESS: admin, authorized user
 * API_INPUT: userid - integer, userid
 * API_INPUT: nick - string, new nick
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$userid = APIHelpers::getParam('userid', APISecurity::userid());
if (!is_numeric($userid))
	APIHelpers::showerror(1117, 'userid must be numeric '.$userid);
$userid = intval($userid);

if (!APISecurity::isAdmin() && $userid != APISecurity::userid()) 
	APIHelpers::showerror(1116, 'you what change nick for another user, it can do only admin '.APISecurity::userid());

$result = array(
	'result' => 'fail',
	'data' => array(),
);

// todo check if changed is current user
// if (isset($config['profile']) && isset($config['profile']['change_nick']) && $config['profile']['change_nick'] == 'yes') {
/*include dirname(__FILE__)."/../config/config.php";
			if (isset($config['profile']) && isset($config['profile']['change_nick']) && $config['profile']['change_nick'] == 'no') {
				return;
			}*/
			
$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('nick'))
  APIHelpers::showerror(1115, 'Not found parameter "nick"');

$nick = APIHelpers::getParam('nick', '');
$nick = htmlspecialchars($nick);
$oldnick = APISecurity::nick();

if ($nick == $oldnick) {
	APIHelpers::showerror(1112, 'New nick equal with old nick');
}


$result['data']['nick'] = htmlspecialchars($nick);
$result['data']['userid'] = $userid;
$result['currentUser'] = $userid == APISecurity::userid();

if (strlen($nick) <= 3)
  APIHelpers::showerror(1113, '"nick" must be more then 3 characters');

try {
	

	$query = 'UPDATE users SET nick = ? WHERE id = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($nick, $userid)))
	{
		$result['result'] = 'ok';
		if ($userid == APISecurity::userid()) {
			APISecurity::setNick($nick);
		}

		// add to public events
		if ($userid != APISecurity::userid())
			APIEvents::addPublicEvents($conn, 'users', 'Admin changed nick for user #'.$userid.' from {'.htmlspecialchars($oldnick).'} to {'.$nick.'} ');
		else
			APIEvents::addPublicEvents($conn, 'users', 'User #'.$userid.' changed nick from {'.htmlspecialchars($oldnick).'} to {'.$nick.'} ');
	}
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(1114, $e->getMessage());
}

echo json_encode($result);
