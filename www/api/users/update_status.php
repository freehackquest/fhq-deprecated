<?php
/*
 * API_NAME: Update User Status
 * API_DESCRIPTION: Method for update user status
 * API_ACCESS: admin only
 * API_INPUT: userid - integer, userid
 * API_INPUT: status - string, new user status ("activated" or "blocked")
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir_users_update_status = dirname(__FILE__);
include_once ($curdir_users_update_status."/../api.lib/api.base.php");
include_once ($curdir_users_update_status."/../api.lib/api.types.php");
include_once ($curdir_users_update_status."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if (APIHelpers::issetParam('userid') && !APISecurity::isAdmin()) 
	APIHelpers::error(403, 'you want change status for another user, it can do only admin');

$userid = APIHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	APIHelpers::error(400, 'userid must be numeric');

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('status'))
  APIHelpers::error(400, 'Not found parameter "status"');

$status = APIHelpers::getParam('status', '');

$response['data']['status'] = $status;
$response['data']['userid'] = $userid;

$response['data']['possible_status'] = array();
foreach (APITypes::$types['userStatuses'] as $key => $value) {
	$response['data']['possible_status'][] = APITypes::$types['userStatuses'][$key]['value'];
}

if (!in_array($status, $response['data']['possible_status'])) {
  APIHelpers::error(400, '"status" must have value from userStatuses: "'.implode('", "', $response['data']['possible_status']).'"');
}

$query = 'UPDATE users SET status = ? WHERE id = ?';
$stmt = $conn->prepare($query);
if ($stmt->execute(array($status, $userid)))
	$response['result'] = 'ok';
else
	$response['result'] = 'fail';


APIHelpers::endpage($response);
