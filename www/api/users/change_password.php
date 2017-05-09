<?php
/*
 * API_NAME: Change password
 * API_DESCRIPTION: Method for change password
 * API_ACCESS: authorized users
 * API_INPUT: old_password - string, old password
 * API_INPUT: new_password - string, new password
 * API_INPUT: new_password_confirm - string, new password confirm
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../../config/config.php");

$result = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('old_password'))
  APIHelpers::error(400, 'Not found parameter "old_password"');
  
if (!APIHelpers::issetParam('new_password'))
  APIHelpers::error(400, 'Not found parameter "new_password"');
  
if (!APIHelpers::issetParam('new_password_confirm'))
  APIHelpers::error(400, 'Not found parameter "new_password_confirm"');

$old_password = APIHelpers::getParam('old_password', '');
$new_password = APIHelpers::getParam('new_password', '');
$new_password_confirm = APIHelpers::getParam('new_password_confirm', '');

if (strlen($new_password) <= 3)
  APIHelpers::error(400, '"New password" must be more then 3 characters');
  
$email = APISecurity::email();
$userid = APISecurity::userid();

if (md5($new_password) != md5($new_password_confirm))
  APIHelpers::error(400, 'New password and New password confirm are not equals');
  
// temporary double passwords 
$hash_old_password = APISecurity::generatePassword2($email, $old_password);
$hash_new_password = APISecurity::generatePassword2($email, $new_password);

/*$result['data']['password'] = $password;
$result['data']['email'] = $email;
$result['data']['userid'] = $userid;*/

// check old password
try {
	$query = 'SELECT id FROM users WHERE id = ? AND email = ? AND pass = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array($userid, $email, $hash_old_password));
	if (!$row = $stmt->fetch()) {
		APIHelpers::error(400, 'Old password are incorrect');
	}
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

// set new password
try {
	$query = 'UPDATE users SET pass = ? WHERE id = ? AND email = ? AND pass = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($hash_new_password, $userid, $email, $hash_old_password)))
		$result['result'] = 'ok';
	else
		APIHelpers::error(500, 'Problem with set new password');
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

echo json_encode($result);
