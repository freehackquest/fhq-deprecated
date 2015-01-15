<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

if (!FHQSecurity::isAdmin()) 
	FHQHelpers::showerror(912, 'only for admin');

if (!FHQHelpers::issetParam('userid'))
  FHQHelpers::showerror(912, 'Not found parameter "userid"');

$userid = FHQHelpers::getParam('userid', '');

if (!is_numeric($userid))
	FHQHelpers::showerror(912, 'userid must be numeric');

if ($userid == FHQSecurity::userid())
	FHQHelpers::showerror(912, 'Please use another function for change your password');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = FHQHelpers::createConnection($config);

if (!FHQHelpers::issetParam('password'))
  FHQHelpers::showerror(912, 'Not found parameter "password"');
  
if (!FHQHelpers::issetParam('email'))
  FHQHelpers::showerror(912, 'Not found parameter "email"');

$password = FHQHelpers::getParam('password', '');
$email = FHQHelpers::getParam('email', '');

$password = FHQSecurity::generatePassword($config, $email, $password);

$result['data']['password'] = $password;
$result['data']['email'] = $email;
$result['data']['userid'] = $userid;

if (strlen($password) <= 3)
  FHQHelpers::showerror(912, '"password" must be more then 3 characters');

try {
	$query = 'UPDATE user SET password = ? WHERE iduser = ? AND email = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($password, $userid, $email)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	FHQHelpers::showerror(911, $e->getMessage());
}

echo json_encode($result);
