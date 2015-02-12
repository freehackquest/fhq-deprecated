<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

if (FHQHelpers::issetParam('userid') && !APISecurity::isAdmin()) 
	FHQHelpers::showerror(912, 'you what change status for another user, it can do only admin');

$userid = FHQHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	FHQHelpers::showerror(912, 'userid must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = FHQHelpers::createConnection($config);

if (!FHQHelpers::issetParam('status'))
  FHQHelpers::showerror(912, 'Not found parameter "status"');

$status = FHQHelpers::getParam('status', '');

$result['data']['status'] = $status;
$result['data']['userid'] = $userid;

if (strlen($status) <= 3)
  FHQHelpers::showerror(912, '"status" must be more then 3 characters');

try {
	$query = 'UPDATE user SET status = ? WHERE iduser = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($status, $userid)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	FHQHelpers::showerror(911, $e->getMessage());
}

echo json_encode($result);
