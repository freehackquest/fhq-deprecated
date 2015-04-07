<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

if (APIHelpers::issetParam('userid') && !APISecurity::isAdmin()) 
	APIHelpers::showerror(1128, 'you what change role for another user, it can do only admin');

$userid = APIHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	APIHelpers::showerror(1129, 'userid must be numeric');

if (APISecurity::isAdmin() && APISecurity::userid() == $userid)
	APIHelpers::showerror(1130, 'you are administrator and you cannot change role for self');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('role'))
  APIHelpers::showerror(1131, 'Not found parameter "role"');

$role = APIHelpers::getParam('role', '');

$result['data']['role'] = $role;
$result['data']['userid'] = $userid;

if (strlen($role) <= 3)
  APIHelpers::showerror(1132, '"role" must be more then 3 characters');

try {
	$query = 'UPDATE users SET role = ? WHERE id = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($role, $userid)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(1133, $e->getMessage());
}

echo json_encode($result);
