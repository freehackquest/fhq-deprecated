<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

header("Access-Control-Allow-Origin: *");
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.user.php");
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");
// include ($curdir."/../api.lib/api.user.php");

$security = new fhq_security();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (FHQHelpers::issetParam('email') && FHQHelpers::issetParam('password')) {
	$email = FHQHelpers::getParam('email');
	$password = FHQHelpers::getParam('password');
	
	if( $security->login($email, $password) ) {
		$result['result'] = 'ok';
		$result['token'] = FHQHelpers::gen_guid();
		$_COOKIE['token'] = $result['token'];
	} else {
		FHQHelpers::showerror(1002, 'email or password was not found in system');
	}
} else {
	FHQHelpers::showerror(1001, 'parameters was not found email or password');
}

if ($result['result'] == 'ok') {
	$conn = FHQHelpers::createConnection($config);
	FHQSecurity::insertLastIp($conn, FHQHelpers::getParam('client', FHQHelpers::getParam('client', 'none')));
	FHQUser::loadUserProfile($conn);
	// FHQUser::loadUserScore($conn);
}

echo json_encode($result);
