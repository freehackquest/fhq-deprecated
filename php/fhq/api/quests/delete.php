<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

FHQHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$message = '';

if (!FHQGame::checkGameDates($message))
	FHQHelpers::showerror(986, $message);

if (!FHQHelpers::issetParam('questid'))
	FHQHelpers::showerror(987, 'Not found parameter "questid"');

if (!FHQSecurity::isAdmin())
	FHQHelpers::showerror(351, 'Access denied. You are not admin.');

$questid = FHQHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	FHQHelpers::showerror(988, 'parameter "questid" must be numeric');

$conn = FHQHelpers::createConnection($config);

$query = 'DELETE FROM quest WHERE idquest = ?';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($questid)));
	$result['result'] = 'ok';
} catch(PDOException $e) {
	FHQHelpers::showerror(822, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
