<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Login
 * API_DESCRIPTION: Method for login user in the system
 * API_ACCESS: all
 * API_INPUT: email - string, Identificator of the user
 * API_INPUT: password - string, Password of a user
 * API_INPUT: client - string, Indentifier for frontend
 * API_OKRESPONSE: { "result":"ok", "token":"76558894-0AA9-11E4-09F0-D353D3CF86D5" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.user.php");
include_once ($curdir."/../../config/config.php");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$token = '';

if (!APIHelpers::issetParam('email'))
	APIHelpers::showerror(1001, 'Parameter email was not found');

if (!APIHelpers::issetParam('password'))
	APIHelpers::showerror(1316, 'Parameter password was not found');

$email = APIHelpers::getParam('email', '');
$password = APIHelpers::getParam('password', '');
$conn = APIHelpers::createConnection($config);
$hash_password2 = APISecurity::generatePassword2($email, $password);
if( APISecurity::login($conn, $email, $hash_password2)) {
	$result['result'] = 'ok';
	APIHelpers::$TOKEN = APIHelpers::gen_guid();
	$result['data']['token'] = APIHelpers::$TOKEN;
	$result['data']['session'] = APIHelpers::$FHQSESSION;
} else {
	APIHelpers::showerror(1002, 'email or/and password was not found in system');
}


if ($result['result'] == 'ok') {
	APISecurity::insertLastIp($conn, APIHelpers::getParam('client', 'none'));
	APIUser::loadUserProfile($conn);
	// APIUser::loadUserScore($conn);
	APISecurity::saveByToken();
}

echo json_encode($result);
