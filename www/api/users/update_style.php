<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Update User Style
 * API_DESCRIPTION: Method for update user status
 * API_ACCESS: authorized user
 * API_INPUT: style - string, new user style
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$country = '';
$city = '';

if (!APIHelpers::issetParam('style'))
  APIHelpers::showerror(1118, 'Not found parameter "style"');

$style = APIHelpers::getParam('style', '');

try {
	$_SESSION['user']['profile']['template'] = $style;

	$query = 'UPDATE users_profile SET value = ?, date_change = NOW() WHERE name = ? AND userid = ?';
	$stmt = $conn->prepare($query);

	$stmt->execute(array($style, 'template', APISecurity::userid()));

	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1119, $e->getMessage());
}

echo json_encode($result);
