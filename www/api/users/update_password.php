<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Update User's Password
 * API_DESCRIPTION: Method for update user avatar
 * API_ACCESS: admin only
 * API_INPUT: userid - integer, userid
 * API_INPUT: email - string, user email
 * API_INPUT: password - string, new password
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../../config/config.php");

$result = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if (!APISecurity::isAdmin()) 
	APIHelpers::error(403, 'only for admin');

if (!APIHelpers::issetParam('userid'))
  APIHelpers::error(400, 'Not found parameter "userid"');

$userid = APIHelpers::getParam('userid', '');

if (!is_numeric($userid))
	APIHelpers::error(400, 'userid must be numeric');

if ($userid == APISecurity::userid())
	APIHelpers::error(403, 'Please use another function for change your password');

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('password'))
  APIHelpers::error(400, 'Not found parameter "password"');

// TODO must be get email by iduser!!!!  
if (!APIHelpers::issetParam('email'))
  APIHelpers::error(400, 'Not found parameter "email"');

$password = APIHelpers::getParam('password', '');
$email = APIHelpers::getParam('email', '');

$password = APISecurity::generatePassword2($email, $password);

$result['data']['password'] = $password;
$result['data']['email'] = $email;
$result['data']['userid'] = $userid;

if (strlen($password) <= 3)
  APIHelpers::error(400, '"password" must be more then 3 characters');

$query = 'UPDATE users SET pass = ? WHERE id = ? AND email = ?';
$stmt = $conn->prepare($query);
if ($stmt->execute(array($password, $userid, $email)))
	$result['result'] = 'ok';
else
	$result['result'] = 'fail';


echo json_encode($result);
