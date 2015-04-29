<?php
/*
 * API_NAME: Update User's Role
 * API_DESCRIPTION: Method for update user's role
 * API_ACCESS: admin only
 * API_INPUT: token - guid, secret token
 * API_INPUT: userid - integer, userid
 * API_INPUT: role - string, new user role ("user"/"admin"/"tester")
 */

$curdir_users_update_role = dirname(__FILE__);
include_once ($curdir_users_update_role."/../api.lib/api.base.php");
include_once ($curdir_users_update_role."/../api.lib/api.types.php");
include_once ($curdir_users_update_role."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if (APIHelpers::issetParam('userid') && !APISecurity::isAdmin()) 
	APIHelpers::showerror(1128, 'you what change role for another user, it can do only admin');

$userid = APIHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	APIHelpers::showerror(1129, 'userid must be numeric');

if (!APIHelpers::issetParam('role'))
  APIHelpers::showerror(1131, 'Not found parameter "role"');

if (APISecurity::isAdmin() && APISecurity::userid() == $userid)
	APIHelpers::showerror(1130, 'you are administrator and you cannot change role for self');

$conn = APIHelpers::createConnection($config);

$role = APIHelpers::getParam('role', '');

$response['data']['role'] = $role;
$response['data']['userid'] = $userid;

$response['data']['possible_roles'] = array();
foreach (APITypes::$types['userRoles'] as $key => $value)
{
	$response['data']['possible_roles'][] = APITypes::$types['userRoles'][$key]['value'];
}

if (!in_array($role, $response['data']['possible_roles'])) {
	APIHelpers::showerror(1132, '"role" must have value from userRoles: "'.implode('", "', $response['data']['possible_roles']).'"');
}

try {
	$query = 'UPDATE users SET role = ? WHERE id = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($role, $userid)))
		$response['result'] = 'ok';
	else
		$response['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(1133, $e->getMessage());
}

APIHelpers::endpage($response);
