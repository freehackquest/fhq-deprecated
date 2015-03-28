<?php
header("Access-Control-Allow-Origin: *");
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.user.php");
include_once ($curdir."/../../config/config.php");
// include ($curdir."/../api.lib/api.user.php");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (APIHelpers::issetParam('email') && APIHelpers::issetParam('password')) {
	$email = APIHelpers::getParam('email', '');
	$password = APIHelpers::getParam('password', '');
	$conn = APIHelpers::createConnection($config);
	$hash_password2 = APISecurity::generatePassword2($email, $password);
	if( APISecurity::login($conn, $email, $hash_password2)) {
		$result['result'] = 'ok';
		$result['token'] = APIHelpers::gen_guid();
	} else {
		APIHelpers::showerror(1002, 'email {'.$email.'} and password was not found in system ');
	}
} else {
	APIHelpers::showerror(1001, 'parameters was not found email or password');
}

if ($result['result'] == 'ok') {
	
	APISecurity::insertLastIp($conn, APIHelpers::getParam('client', 'none'));
	APIUser::loadUserProfile($conn);
	// APIUser::loadUserScore($conn);
	APISecurity::saveByToken($conn, $result['token']);
}

echo json_encode($result);
