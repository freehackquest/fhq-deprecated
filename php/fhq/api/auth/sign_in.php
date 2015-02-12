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

if (FHQHelpers::issetParam('email') && FHQHelpers::issetParam('password')) {
	$email = FHQHelpers::getParam('email', '');
	$password = FHQHelpers::getParam('password', '');
	$conn = FHQHelpers::createConnection($config);
	$hash_password = APISecurity::generatePassword($config, $email, $password);
	
	if( APISecurity::login($conn, $email, $hash_password) ) {
		$result['result'] = 'ok';
		$result['token'] = FHQHelpers::gen_guid();
	} else {
		FHQHelpers::showerror(1002, 'email or password was not found in system ['.$email.']  ['.$password.'] ');
	}
} else {
	FHQHelpers::showerror(1001, 'parameters was not found email or password');
}

if ($result['result'] == 'ok') {
	
	APISecurity::insertLastIp($conn, FHQHelpers::getParam('client', 'none'));
	FHQUser::loadUserProfile($conn);
	// FHQUser::loadUserScore($conn);
	APISecurity::saveByToken($conn, $result['token']);
}

echo json_encode($result);
