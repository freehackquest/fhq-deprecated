<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$userid = APIHelpers::getParam('userid', APISecurity::userid());
if (!is_numeric($userid))
	APIHelpers::showerror(912, 'userid must be numeric '.$userid);
$userid = intval($userid);

if (!APISecurity::isAdmin() && $userid != APISecurity::userid()) 
	APIHelpers::showerror(912, 'you what change nick for another user, it can do only admin '.APISecurity::userid());

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
  APIHelpers::showerror(912, 'Not found parameter "nick"');

$nick = APIHelpers::getParam('nick', '');

$result['data']['nick'] = htmlspecialchars($nick);
$result['data']['userid'] = $userid;
$result['currentUser'] = $userid == APISecurity::userid();

if (strlen($nick) <= 3)
  APIHelpers::showerror(912, '"nick" must be more then 3 characters');

try {
	$oldnick = APISecurity::nick();

	$query = 'UPDATE user SET nick = ? WHERE iduser = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array(htmlspecialchars($nick), $userid)))
	{
		$result['result'] = 'ok';

		if ($userid == APISecurity::userid()) {
			APISecurity::setNick(htmlspecialchars($nick));
		}

		// add to public events
		if ($userid != APISecurity::userid())
			APIEvents::addPublicEvents($conn, 'users', 'Admin changed nick for user #'.$userid.' from {'.htmlspecialchars($oldnick).'} to {'.htmlspecialchars($nick).'} ');
		else
			APIEvents::addPublicEvents($conn, 'users', 'User changed nick #'.$userid.' from {'.htmlspecialchars($oldnick).'} to {'.htmlspecialchars($nick).'} ');
	}
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(911, $e->getMessage());
}

echo json_encode($result);
