<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1090, "This function allowed only for admin");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);


if (!APIHelpers::issetParam('uuid'))
	APIHelpers::showerror(1029, "Not found parameter uuid");
	
if (!APIHelpers::issetParam('logo'))
	APIHelpers::showerror(1030, "Not found parameter logo");
	
if (!APIHelpers::issetParam('email'))
	APIHelpers::showerror(1031, "Not found parameter email");
	
if (!APIHelpers::issetParam('role'))
	APIHelpers::showerror(1032, "Not found parameter role");
	
if (!APIHelpers::issetParam('nick'))
	APIHelpers::showerror(1033, "Not found parameter nick");
	
if (!APIHelpers::issetParam('password'))
	APIHelpers::showerror(1034, "Not found parameter password");
	
if (!APIHelpers::issetParam('status'))
	APIHelpers::showerror(1035, "Not found parameter status");

$uuid = APIHelpers::getParam('uuid', APIHelpers::gen_guid());
$logo = APIHelpers::getParam('logo', 'files/users/0.png');
$email = APIHelpers::getParam('email', '1');
$role = APIHelpers::getParam('role', 'user');
$nick = APIHelpers::getParam('nick', '1');
$password = APIHelpers::getParam('password', '1');
$status = APIHelpers::getParam('status', 'activated');


if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	APIHelpers::showerror(1036, 'Invalid e-mail address.');

$stmt = $conn->prepare('select count(*) as cnt from user where email = ?');
$stmt->execute(array($email));
if ($row = $stmt->fetch())
{
	if (intval($row['cnt']) >= 1)
		APIHelpers::showerror(1037, 'This e-mail was already registered.');	
}

// same code exists in api/security/registration.php
$email = strtolower($email);
$username = base64_encode(strtoupper($email));

$password_hash = APISecurity::generatePassword2($email, $password);
				
$stmt_insert = $conn->prepare('
	INSERT INTO user(
		uuid_user,
		username,
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
	VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW());
');

$stmt_insert->execute(array(
	$uuid,
	$username,
	$password_hash, // pass
	$status,
	$email,
	$nick,
	$role,
	$logo,
	$_SERVER['REMOTE_ADDR'],
	'0000-00-00 00:00:00',
));

APIEvents::addPublicEvents($conn, 'users', 'Joined new user {'.htmlspecialchars($nick).'} by admin!');

$result['result'] = 'ok';

echo json_encode($result);
