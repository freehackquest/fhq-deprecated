<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

if (APIHelpers::issetParam('userid') && !APISecurity::isAdmin()) 
	APIHelpers::showerror(1134, 'you what change status for another user, it can do only admin');

$userid = APIHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	APIHelpers::showerror(1135, 'userid must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('status'))
  APIHelpers::showerror(1136, 'Not found parameter "status"');

$status = APIHelpers::getParam('status', '');

$result['data']['status'] = $status;
$result['data']['userid'] = $userid;

if (strlen($status) <= 3)
  APIHelpers::showerror(1137, '"status" must be more then 3 characters');

try {
	$query = 'UPDATE user SET status = ? WHERE iduser = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($status, $userid)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(1138, $e->getMessage());
}

echo json_encode($result);
