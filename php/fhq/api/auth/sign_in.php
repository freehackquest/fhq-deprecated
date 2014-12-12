<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../api.lib/api.security.php");
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

		// FHQUser::loadUserProfile($conn);
		// FHQUser::loadUserScore($conn);
	} else {
		$result['error']['code'] = '102';
		$result['error']['message'] = 'Error 102: it was not found login or password';
	}
} else {
	$result['error']['code'] = '101';
	$result['error']['message'] = 'Error 101: it was not found login or password';
}

if ($result['result'] == 'ok') {
	$conn = FHQHelpers::createConnection($config);
	FHQSecurity::insertLastIp($conn, FHQHelpers::getParam('client', 'none'));
}

echo json_encode($result);
