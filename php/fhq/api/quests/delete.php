<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(986, $message);

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(987, 'Not found parameter "questid"');

if (!APISecurity::isAdmin())
	APIHelpers::showerror(351, 'Access denied. You are not admin.');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(988, 'parameter "questid" must be numeric');

$conn = APIHelpers::createConnection($config);

$query = 'DELETE FROM quest WHERE idquest = ?';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($questid)));
	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(822, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
