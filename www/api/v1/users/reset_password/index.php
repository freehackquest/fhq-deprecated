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
include_once ($curdir_security_restore."/../../../../config/config.php");
include_once ($curdir_security_restore."/../../../api.lib/api.mail.php");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (!APIHelpers::issetParam('email'))
	APIHelpers::showerror(1038, 'Parameter email was not found');

if (!APIHelpers::issetParam('captcha'))
	APIHelpers::showerror(1039, 'Parameter captcha was not found');

$conn = APIHelpers::createConnection($config);

$email = APIHelpers::getParam('email', '');
$captcha = APIHelpers::getParam('captcha', '');
$captcha_uuid = APIHelpers::getParam('captcha_uuid', '');

$orig_captcha = APIHelpers::find_captcha($conn, $captcha_uuid);

if (strtoupper($captcha) != strtoupper($orig_captcha))
	APIHelpers::showerror(1040, '[Restore] Captcha is not correct, please "Refresh captcha" and try again');

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	APIHelpers::showerror(1041, '[Restore] Invalid e-mail address. ');


$stmt = $conn->prepare('select id, nick from users where email = ?');
$stmt->execute(array($email));
$nick = '';
$userid = 0;

if ($row = $stmt->fetch()) {
	$nick = $row['nick'];
	$userid = $row['id'];
} else {
	APIHelpers::showerror(1042, '[Restore] This e-mail was not registered.');
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
	APIHelpers::showerror(1315, '[Reset] Sorry restore is broken. Please send report to the admin about this.');
} else {
	APISecurity::updateLastDTLogin($conn);
	APIUser::loadUserProfile($conn);
	APISecurity::logout();
}

$email_subject = "Reset password to your account for FreeHackQuest.";

$email_message = '
	Restore:

	Somebody (may be you) reseted your password on '.$config['hostname'].'
	Your login: '.$email.'
	Your new password: '.$password.' (You must change it)
	Link: '.$config['hostname'].'
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
if (isset($config['mail']) && isset($config['mail']['allow']) && $config['mail']['allow'] == 'yes') {
	$error = '';
	APIMail::send($config, $email, '', '', $email_subject, $email_message, $error);
}

$result['result'] = 'ok';
$result['data']['message'] = 'Check your your e-mail (also please check spam).';

echo json_encode($result);
