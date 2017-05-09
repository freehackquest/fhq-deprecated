<?php
/*
 * API_NAME: Create user (by admin)
 * API_DESCRIPTION: Method will be add new user
 * API_ACCESS: admin only
 * API_INPUT: uuid - guid, uniq identifier
 * API_INPUT: logo - string, link to user logo
 * API_INPUT: email - string, email
 * API_INPUT: role - string, role (user/admin)
 * API_INPUT: nick - string, nick
 * API_INPUT: password - string, password
 * API_INPUT: status - string, status (activated, blocked)
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

$result = APIHelpers::startpage();

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::error(403, 'This function allowed only for admin');

$conn = APIHelpers::createConnection($config);


if (!APIHelpers::issetParam('uuid'))
	APIHelpers::error(400, 'Not found parameter uuid');
	
if (!APIHelpers::issetParam('logo'))
	APIHelpers::error(400, 'Not found parameter logo');
	
if (!APIHelpers::issetParam('email'))
	APIHelpers::error(400, 'Not found parameter email');
	
if (!APIHelpers::issetParam('role'))
	APIHelpers::error(400, 'Not found parameter role');
	
if (!APIHelpers::issetParam('nick'))
	APIHelpers::error(400, 'Not found parameter nick');
	
if (!APIHelpers::issetParam('password'))
	APIHelpers::error(400, 'Not found parameter password');
	
if (!APIHelpers::issetParam('status'))
	APIHelpers::error(400, 'Not found parameter status');

$uuid = APIHelpers::getParam('uuid', APIHelpers::gen_guid());
$logo = APIHelpers::getParam('logo', 'files/users/0.png');
$email = APIHelpers::getParam('email', '1');
$role = APIHelpers::getParam('role', 'user');
$nick = APIHelpers::getParam('nick', '1');
$password = APIHelpers::getParam('password', '1');
$status = APIHelpers::getParam('status', 'activated');


if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	APIHelpers::error(400, 'Invalid e-mail address.');

$stmt = $conn->prepare('select count(*) as cnt from users where email = ?');
$stmt->execute(array($email));
if ($row = $stmt->fetch())
{
	if (intval($row['cnt']) >= 1)
		APIHelpers::error(400, 'This e-mail was already registered.');	
}

// same code exists in api/security/registration.php
$email = strtolower($email);
$password_hash = APISecurity::generatePassword2($email, $password);
				
$stmt_insert = $conn->prepare('
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
	VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW());
');

$stmt_insert->execute(array(
	$uuid,
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
