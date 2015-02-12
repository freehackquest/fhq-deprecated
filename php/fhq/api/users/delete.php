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

$conn = FHQHelpers::createConnection($config);

if (!APISecurity::isAdmin()) 
	FHQHelpers::showerror(912, 'only for admin');

if (!FHQHelpers::issetParam('userid'))
  FHQHelpers::showerror(889, 'Error 889: not found parameter "userid"');

$userid = FHQHelpers::getParam('userid', 0);

if (!is_numeric($userid))
  FHQHelpers::showerror(885, 'userid must be numeric');

try {
	$params = array($userid);
 	$conn->prepare('DELETE FROM user WHERE iduser = ?')->execute($params);
 	$conn->prepare('DELETE FROM users_games WHERE userid = ?')->execute($params);
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	FHQHelpers::showerror(882, $e->getMessage());
}

echo json_encode($result);
