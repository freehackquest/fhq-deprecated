<?php
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

$result = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$userid = APIHelpers::getParam('userid', APISecurity::userid());
if (!is_numeric($userid))
	APIHelpers::error(400, 'userid must be numeric '.$userid);
$userid = intval($userid);

if (!APISecurity::isAdmin() && $userid != APISecurity::userid()) 
	APIHelpers::error(403, 'you what change nick for another user, it can do only admin '.APISecurity::userid());

// todo check if changed is current user
// if (isset($config['profile']) && isset($config['profile']['change_nick']) && $config['profile']['change_nick'] == 'yes') {
/*include dirname(__FILE__)."/../config/config.php";
			if (isset($config['profile']) && isset($config['profile']['change_nick']) && $config['profile']['change_nick'] == 'no') {
				return;
			}*/
			
$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('nick'))
  APIHelpers::error(400, 'Not found parameter "nick"');

$nick = APIHelpers::getParam('nick', '');
$nick = htmlspecialchars($nick);
$oldnick = APISecurity::nick();

if ($nick == $oldnick) {
	APIHelpers::error(400, 'New nick equal with old nick');
}


$result['data']['nick'] = htmlspecialchars($nick);
$result['data']['userid'] = $userid;
$result['currentUser'] = $userid == APISecurity::userid();

if (strlen($nick) <= 3)
  APIHelpers::error(400, '"nick" must be more then 3 characters');

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
	APIHelpers::error(500, $e->getMessage());
}

echo json_encode($result);
