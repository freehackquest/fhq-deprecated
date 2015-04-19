<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Update Logo
 * API_DESCRIPTION: Method for update user avatar
 * API_ACCESS: admin only
 * API_INPUT: userid - integer, userid
 * API_INPUT: logo - string, link to logo
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

if (!APISecurity::isAdmin()) 
	APIHelpers::showerror(1139, 'only for admin');

$userid = APIHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	APIHelpers::showerror(1140, 'userid must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('logo'))
  APIHelpers::showerror(1141, 'Not found parameter "logo"');

$logo = APIHelpers::getParam('logo', '');

$result['data']['logo'] = $logo;
$result['data']['userid'] = $userid;

try {
	$query = 'UPDATE users SET logo = ? WHERE id = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($logo, $userid)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(1142, $e->getMessage());
}

echo json_encode($result);
