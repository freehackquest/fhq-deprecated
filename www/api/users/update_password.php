<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

if (!APISecurity::isAdmin()) 
	APIHelpers::showerror(912, 'only for admin');

if (!APIHelpers::issetParam('userid'))
  APIHelpers::showerror(912, 'Not found parameter "userid"');

$userid = APIHelpers::getParam('userid', '');

if (!is_numeric($userid))
	APIHelpers::showerror(912, 'userid must be numeric');

if ($userid == APISecurity::userid())
	APIHelpers::showerror(912, 'Please use another function for change your password');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('password'))
  APIHelpers::showerror(912, 'Not found parameter "password"');
  
if (!APIHelpers::issetParam('email'))
  APIHelpers::showerror(912, 'Not found parameter "email"');

$password = APIHelpers::getParam('password', '');
$email = APIHelpers::getParam('email', '');

$password = APISecurity::generatePassword($config, $email, $password);

$result['data']['password'] = $password;
$result['data']['email'] = $email;
$result['data']['userid'] = $userid;

if (strlen($password) <= 3)
  APIHelpers::showerror(912, '"password" must be more then 3 characters');

try {
	$query = 'UPDATE user SET password = ? WHERE iduser = ? AND email = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($password, $userid, $email)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(911, $e->getMessage());
}

echo json_encode($result);