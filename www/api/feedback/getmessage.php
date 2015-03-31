<?php
$curdir_statistics_list = dirname(__FILE__);
include_once ($curdir_statistics_list."/../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../api.lib/api.helpers.php");
include_once ($curdir_statistics_list."/../api.lib/api.game.php");
include_once ($curdir_statistics_list."/../../config/config.php");

APIHelpers::startpage($config);
include_once ($curdir_statistics_list."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1272, 'access denie. you must be admin.');
 
$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1273, 'not found parameter id');

$id = APIHelpers::getParam("id", 0);

$conn = APIHelpers::createConnection($config);

$result['result'] = 'ok';

try {
	$stmt = $conn->prepare('
			SELECT
				*
			FROM 
				feedback_msg
			WHERE 
				id = ?
	');
	$stmt->execute(array($id));
	if($row = $stmt->fetch()) {
		$result['data']['id'] = htmlspecialchars($row['id']);
		$result['data']['text'] = htmlspecialchars($row['msg']);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1274, $e->getMessage());
}

// not needed here
// include_once ($curdir."/../api.lib/savetoken.php");

APIHelpers::endpage($result);