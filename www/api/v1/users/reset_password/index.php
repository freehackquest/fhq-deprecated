<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

/*
 * API_NAME: Restore password
 * API_DESCRIPTION: Methods for restore user password.
 * API_ACCESS: all
 * API_INPUT: email - string, User's email
 * API_INPUT: captcha - string, here -> api/captcha.php
 * API_INPUT: client - string, indentifier of frontend
 */

$curdir_security_restore = dirname(__FILE__);
include_once ($curdir_security_restore."/../../../api.lib/api.base.php");
include_once ($curdir_security_restore."/../../../api.lib/api.helpers.php");
include_once ($curdir_security_restore."/../../../api.lib/api.security.php");
include_once ($curdir_security_restore."/../../../api.lib/api.user.php");

$response = APIHelpers::startpage();

if(!APIHelpers::is_json_input()){
	APIHelpers::error(400, "Expected application/json");
}
$conn = APIHelpers::createConnection();
$request = APIHelpers::read_json_input();

if (!isset($request['email'])){
	APIHelpers::error(400, 'Parameter email was not found');
}

if (!isset($request['captcha'])){
	APIHelpers::error(400, 'Parameter captcha was not found');
}

if (!isset($request['captcha_uuid'])){
	APIHelpers::error(400, 'Parameter captcha_uuid was not found');
}

$email = $request['email'];
$captcha = $request['captcha'];
$captcha_uuid = $request['captcha_uuid'];

$orig_captcha = APIHelpers::find_captcha($conn, $captcha_uuid);

if (strtoupper($captcha) != strtoupper($orig_captcha))
	APIHelpers::error(400, '[Restore] Captcha is not correct, please "Refresh captcha" and try again');

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	APIHelpers::error(400, '[Restore] Invalid e-mail address. ');


$stmt = $conn->prepare('select id, nick from users where email = ?');
$stmt->execute(array($email));
$nick = '';
$userid = 0;

if ($row = $stmt->fetch()) {
	$nick = $row['nick'];
	$userid = $row['id'];
} else {
	APIHelpers::error(400, '[Restore] This e-mail was not registered.');
}

$password = substr(md5(rand().rand()), 0, 8);
$password_hash = APISecurity::generatePassword2($email, $password);

$query = "";
$stmt_update = $conn->prepare('
	UPDATE users SET
		pass = ?
	WHERE email = ?;
');

$stmt_update->execute(array(
	$password_hash,
	$email,
));

if( !APISecurity::login($conn, $email, $password_hash)) {
	APIEvents::addPublicEvents($conn, 'errors', 'Admin, restore password is broken!');
	APIHelpers::error(500, '[Reset] Sorry restore is broken. Please send report to the admin about this.');
} else {
	APISecurity::updateLastDTLogin($conn);
	APIUser::loadUserProfile($conn);
	APISecurity::logout();
}

$email_subject = "Reset password to your account for FreeHackQuest.";

$email_message = '
	Restore:

	Somebody (may be you) reseted your password on '.APIHelpers::$CONFIG['hostname'].'
	Your login: '.$email.'
	Your new password: '.$password.' (You must change it)
	Link: '.APIHelpers::$CONFIG['hostname'].'
	';

$stmt_insert2 = $conn->prepare('
	INSERT INTO email_delivery(
		to_email,
		subject,
		message,
		priority,
		status,
		dt
	)
	VALUES ( ?, ?, ?, ?, ?, NOW());
');

$stmt_insert2->execute(array(
	$email,
	$email_subject,
	$email_message,
	'high',
	'sending',
));

// $nickname
APIEvents::addPublicEvents($conn, 'users', 'The user #'.$userid.' {'.htmlspecialchars($nick).'} is returned to us! Welcome!');

// this option must be moved to db
if (isset(APIHelpers::$CONFIG['mail']) && isset(APIHelpers::$CONFIG['mail']['allow']) && APIHelpers::$CONFIG['mail']['allow'] == 'yes') {
	$error = '';
	APIHelpers::sendMail($email, '', '', $email_subject, $email_message, $error);
}

$response['result'] = 'ok';
$response['data']['message'] = 'Check your your e-mail (also please check spam).';

APIHelpers::endpage($response);
