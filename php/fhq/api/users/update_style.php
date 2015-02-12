<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = FHQHelpers::createConnection($config);

$country = '';
$city = '';

if (!FHQHelpers::issetParam('style'))
  FHQHelpers::showerror(912, 'Not found parameter "style"');

$style = FHQHelpers::getParam('style', '');

try {
	$_SESSION['user']['profile']['template'] = $style;

	$query = 'UPDATE users_profile SET value = ?, date_change = NOW() WHERE name = ? AND userid = ?';
	$stmt = $conn->prepare($query);

	$stmt->execute(array($style, 'template', APISecurity::userid()));

	$result['result'] = 'ok';
} catch(PDOException $e) {
	showerror(911, 'Error 911: ' + $e->getMessage());
}

echo json_encode($result);
