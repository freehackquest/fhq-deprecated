<?php
/*
 * API_NAME: Get Feedback Info
 * API_DESCRIPTION: Method will be returned id, type and text - need for editing
 * API_ACCESS: admin only
 * API_INPUT: id - string, Identificator of the feedback
 * API_INPUT: token - string, token
 */

$curdir_feedback_get = dirname(__FILE__);
include_once ($curdir_feedback_get."/../api.lib/api.base.php");
include_once ($curdir_feedback_get."/../api.lib/api.security.php");
include_once ($curdir_feedback_get."/../api.lib/api.helpers.php");
include_once ($curdir_feedback_get."/../api.lib/api.game.php");
include_once ($curdir_feedback_get."/../../config/config.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1265, 'access denie. you must be admin.');
 
if (!APIHelpers::issetParam('id'))
	APIHelpers::showerror(1266, 'not found parameter id');

$id = APIHelpers::getParam("id", 0);

if (!is_numeric($id))
	APIHelpers::showerror(1281, 'Parameter id must be numeric');

$conn = APIHelpers::createConnection($config);

$response['result'] = 'ok';

try {
	$stmt = $conn->prepare('
			SELECT
				*
			FROM 
				feedback fb
			WHERE 
				id = ?
	');
	$stmt->execute(array($id));
	if($row = $stmt->fetch()) {
		$response['data']['id'] = htmlspecialchars($row['id']);
		$response['data']['type'] = htmlspecialchars($row['type']);
		$response['data']['text'] = htmlspecialchars($row['text']);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1267, $e->getMessage());
}

// not needed here
// include_once ($curdir."/../api.lib/savetoken.php");

APIHelpers::endpage($response);
