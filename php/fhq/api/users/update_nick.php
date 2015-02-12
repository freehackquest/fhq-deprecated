<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

if (FHQHelpers::issetParam('userid') && !APISecurity::isAdmin()) 
	FHQHelpers::showerror(912, 'you what change nick for another user, it can do only admin');

$userid = FHQHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	FHQHelpers::showerror(912, 'userid must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = FHQHelpers::createConnection($config);

if (!FHQHelpers::issetParam('nick'))
  FHQHelpers::showerror(912, 'Not found parameter "nick"');

$nick = FHQHelpers::getParam('nick', '');

$result['data']['nick'] = $nick;
$result['data']['userid'] = $userid;

if (strlen($nick) <= 3)
  FHQHelpers::showerror(912, '"nick" must be more then 3 characters');

try {
	$query = 'UPDATE user SET nick = ? WHERE iduser = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($nick, $userid)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	FHQHelpers::showerror(911, $e->getMessage());
}

echo json_encode($result);
