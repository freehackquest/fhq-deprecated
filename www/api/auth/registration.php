<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$httpname = 'http://'.$_SERVER['HTTP_HOST'].dirname(dirname(dirname($_SERVER['PHP_SELF']))).'/';

$curdir_registration = dirname(__FILE__);
include_once ($curdir_registration."/../api.lib/api.base.php");
include_once ($curdir_registration."/../api.lib/api.helpers.php");
include_once ($curdir_registration."/../api.lib/api.security.php");
include_once ($curdir_registration."/../../config/config.php");
include_once ($curdir_registration."/../api.lib/api.mail.php");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (!APIHelpers::issetParam('email'))
	APIHelpers::showerror(1013, 'Parameter email was not found');

if (!APIHelpers::issetParam('captcha'))
	APIHelpers::showerror(1013, 'Parameter captcha was not found');


$email = APIHelpers::getParam('email', '');
$captcha = APIHelpers::getParam('captcha', '');
$orig_captcha = $_SESSION['captcha_reg'];

// cleanup captcha
$_SESSION['captcha_reg'] = md5(rand().rand());

if (strtoupper($captcha) != strtoupper($orig_captcha))
	APIHelpers::showerror(1012, '[Registration] Captcha is not correct, please "Refresh captcha" and try again');

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	APIHelpers::showerror(1011, '[Registration] Invalid e-mail address. ');

$conn = APIHelpers::createConnection($config);
$stmt = $conn->prepare('select count(*) as cnt from user where email = ?');
$stmt->execute(array($email));
if ($row = $stmt->fetch())
{
	if (intval($row['cnt']) >= 1)
		APIHelpers::showerror(702, '[Registration] This e-mail was already registered.');	
}

$nickname = "hacker-".substr(md5(rand().rand()), 0, 7);
$email = strtolower($email);
$username = base64_encode(strtoupper($email));

$uuid = APIHelpers::gen_guid();

$password = substr(md5(rand().rand()), 0, 8);
$password_hash = APISecurity::generatePassword2($email, $password);
			
// same code exists in api/users/insert.php
				
$stmt_insert = $conn->prepare('
	INSERT INTO user(
		uuid_user,
		username,
		password,
		pass,
		status,
		email,
		nick,
		role,
		logo,
		last_ip,
		date_last_signup,
		date_create
	)
	VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW());
');

$stmt_insert->execute(array(
	$uuid,
	$username,
	'', // password
	$password_hash, // pass
	'activated',
	$email,
	$nickname,
	'user',
	'files/users/0.png',
	$_SERVER['REMOTE_ADDR'],
	'0000-00-00 00:00:00',
));

$email_subject = "Registration on FreeHackQuest.";

$email_message = '
	Registration:

	If you was not tried registering on '.$httpname.' just remove this email.

	Welcome to FreeHackQuest!

	Your login: '.$email.'
	Your password: '.$password.' (You must change it)
	Link: '.$httpname.'index.php
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
APIEvents::addPublicEvents($conn, 'users', 'Joined new user {'.htmlspecialchars($nickname).'}!');

$error = '';
// this option must be moved to db
if (isset($config['mail']) && isset($config['mail']['allow']) && $config['mail']['allow'] == 'yes') {
	APIMail::send($config, $email, '', '', $email_subject, $email_message, $error);
}

$result['result'] = 'ok';
$result['data']['message'] = 'Check your your e-mail (also please check spam).';

echo json_encode($result);
