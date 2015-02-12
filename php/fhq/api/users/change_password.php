<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();




$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('old_password'))
  APIHelpers::showerror(7800, 'Not found parameter "old_password"');
  
if (!APIHelpers::issetParam('new_password'))
  APIHelpers::showerror(7801, 'Not found parameter "new_password"');
  
if (!APIHelpers::issetParam('new_password_confirm'))
  APIHelpers::showerror(7802, 'Not found parameter "new_password_confirm"');

$old_password = APIHelpers::getParam('old_password', '');
$new_password = APIHelpers::getParam('new_password', '');
$new_password_confirm = APIHelpers::getParam('new_password_confirm', '');

if (strlen($new_password) <= 3)
  APIHelpers::showerror(7803, '"New password" must be more then 3 characters');
  
$email = APISecurity::email();
$userid = APISecurity::userid();

if (md5($new_password) != md5($new_password_confirm))
  APIHelpers::showerror(7804, 'New password and New password confirm are not equals');
  
$old_password = APISecurity::generatePassword($config, $email, $old_password);
$new_password = APISecurity::generatePassword($config, $email, $new_password);

/*$result['data']['password'] = $password;
$result['data']['email'] = $email;
$result['data']['userid'] = $userid;*/

// check old password
try {
	$query = 'SELECT iduser FROM user WHERE iduser = ? AND email = ? AND password = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array($userid, $email, $old_password));
	if (!$row = $stmt->fetch()) {
		APIHelpers::showerror(7805, 'Old password are incorrect');
	}
} catch(PDOException $e) {
	APIHelpers::showerror(7806, $e->getMessage());
}

// set new password
try {
	$query = 'UPDATE user SET password = ? WHERE iduser = ? AND email = ? AND password = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array($new_password, $userid, $email, $old_password)))
		$result['result'] = 'ok';
	else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(7807, $e->getMessage());
}

echo json_encode($result);
