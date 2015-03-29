<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_games_delete = dirname(__FILE__);
include_once ($curdir_games_delete."/../api.lib/api.helpers.php");
include_once ($curdir_games_delete."/../../config/config.php");
include_once ($curdir_games_delete."/../api.lib/api.base.php");

include_once ($curdir_games_delete."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

if(!APISecurity::isAdmin())
  showerror(786, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(789, 'not found parameter "id"');

if (!APIHelpers::issetParam('captcha'))
  APIHelpers::showerror(788, 'not found parameter "captcha"');

$captcha = APIHelpers::getParam('captcha', '');
$orig_captcha = $_SESSION['captcha_reg'];
$_SESSION['captcha_reg'] = md5(rand().rand());

if( strtoupper($captcha) != strtoupper($orig_captcha))
	APIHelpers::showerror(787, 'captcha incorrect');

$game_id = APIHelpers::getParam('id', 0);

if (!is_numeric($game_id))
  APIHelpers::showerror(785, 'incorrect id');
		
$query = 'DELETE FROM games WHERE id = ?';

try {
 	$stmt = $conn->prepare($query);
 	$stmt->execute(array(intval($game_id)));
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(782, $e->getMessage());
}

echo json_encode($result);
