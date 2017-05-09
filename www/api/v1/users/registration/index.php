<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

/*
 * API_NAME: Registration
 * API_DESCRIPTION: Method for registration in the system.
 * API_ACCESS: all
 * API_INPUT: email - string, user's email
 * API_INPUT: client - string, indentifier of frontend
 * API_INPUT: captcha - string, here -> api/captcha.php
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../../../api.lib/api.base.php");
include_once ($curdir."/../../../api.lib/api.helpers.php");
include_once ($curdir."/../../../api.lib/api.security.php");
include_once ($curdir."/../../../api.lib/api.user.php");

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT);

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

$conn = APIHelpers::createConnection();
$orig_captcha = APIHelpers::find_captcha($conn, $captcha_uuid);

if (strtoupper($captcha) != strtoupper($orig_captcha))
	APIHelpers::error(400, '[Registration] Captcha is not correct, please "Refresh captcha" and try again');

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	APIHelpers::error(400, '[Registration] Invalid e-mail address.');


$stmt = $conn->prepare('select count(*) as cnt from users where email = ?');
$stmt->execute(array($email));
if ($row = $stmt->fetch())
{
	if (intval($row['cnt']) >= 1)
		APIHelpers::error(400, '[Registration] This e-mail was already registered.');	
}

$nick = "hacker-".substr(md5(rand().rand()), 0, 7);
$email = strtolower($email);
$uuid = APIHelpers::gen_guid();

$password = substr(md5(rand().rand()), 0, 8);
$password_hash = APISecurity::generatePassword2($email, $password);

// same code exists in api/users/insert.php
// same code exists in google_auth.php
$query = '
        INSERT INTO users(
                uuid,
                pass,
                status,
                email,
                nick,
                role,
                logo,
		last_ip,
                dt_last_login,
                dt_create
        )
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, NOW(),NOW());
';

$stmt_insert = $conn->prepare($query);

$new_user = array(
        $uuid,
        $password_hash, // pass
        'activated',
        $email,
        $nick,
        'user',
        'files/users/0.png',
	''
);

$r = $stmt_insert->execute($new_user);
$error = print_r($conn->errorInfo(),true);

if( !APISecurity::login($conn, $email, $password_hash)) {
	APIEvents::addPublicEvents($conn, 'errors', 'Alert! Admin, registration is broken!');
	error_log("1287: ".$error);
	APIHelpers::error(500, '[Registration] Sorry registration is broken. Please send report to the admin about this.');
} else {
	APISecurity::updateLastDTLogin($conn);
	APIUser::loadUserProfile($conn);
	APISecurity::logout();
}

$email_subject = "Registration on FreeHackQuest.";

$email_message = '
	Registration:

	If you was not tried registering on '.APIHelpers::$CONFIG['hostname'].' just remove this email.

	Welcome to FreeHackQuest!

	Your login: '.$email.'
	Your password: '.$password.' (You must change it)
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


// $nick
APIEvents::addPublicEvents($conn, 'users', 'New player {'.htmlspecialchars($nick).'}. Welcome!');

$error = '';

// this option must be moved to db
if (isset(APIHelpers::$CONFIG['mail']) && isset(APIHelpers::$CONFIG['mail']['allow']) && APIHelpers::$CONFIG['mail']['allow'] == 'yes') {
	APIHelpers::sendMail($email, '', '', $email_subject, $email_message, $error);
}

$response['result'] = 'ok';
$response['data']['message'] = 'Check your your e-mail (also please check spam).';

APIHelpers::endpage($response);
