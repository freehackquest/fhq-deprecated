<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

if (APIHelpers::issetParam('userid') && !APISecurity::isAdmin()) 
	APIHelpers::showerror(912, 'you what change nick for another user, it can do only admin');

$userid = APIHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	APIHelpers::showerror(912, 'userid must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('nick'))
  APIHelpers::showerror(912, 'Not found parameter "nick"');

$nick = APIHelpers::getParam('nick', '');

$result['data']['nick'] = $nick;
$result['data']['userid'] = $userid;

if (strlen($nick) <= 3)
  APIHelpers::showerror(912, '"nick" must be more then 3 characters');

try {
	$query = 'UPDATE user SET nick = ? WHERE iduser = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($nick, $userid)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(911, $e->getMessage());
}

echo json_encode($result);
