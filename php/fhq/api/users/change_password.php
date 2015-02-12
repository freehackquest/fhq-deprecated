<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();




$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = FHQHelpers::createConnection($config);

if (!FHQHelpers::issetParam('old_password'))
  FHQHelpers::showerror(7800, 'Not found parameter "old_password"');
  
if (!FHQHelpers::issetParam('new_password'))
  FHQHelpers::showerror(7801, 'Not found parameter "new_password"');
  
if (!FHQHelpers::issetParam('new_password_confirm'))
  FHQHelpers::showerror(7802, 'Not found parameter "new_password_confirm"');

$old_password = FHQHelpers::getParam('old_password', '');
$new_password = FHQHelpers::getParam('new_password', '');
$new_password_confirm = FHQHelpers::getParam('new_password_confirm', '');

if (strlen($new_password) <= 3)
  FHQHelpers::showerror(7803, '"New password" must be more then 3 characters');
  
$email = APISecurity::email();
$userid = APISecurity::userid();

if (md5($new_password) != md5($new_password_confirm))
  FHQHelpers::showerror(7804, 'New password and New password confirm are not equals');
  
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
		FHQHelpers::showerror(7805, 'Old password are incorrect');
	}
} catch(PDOException $e) {
	FHQHelpers::showerror(7806, $e->getMessage());
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
	FHQHelpers::showerror(7807, $e->getMessage());
}

echo json_encode($result);
