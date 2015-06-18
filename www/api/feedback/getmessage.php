<?php
/*
 * API_NAME: Get Feedback Message Info
 * API_DESCRIPTION: Method will be returned id, text for feedback message - need for editing
 * API_ACCESS: admin only
 * API_INPUT: id - string, Identificator of the feedback message
 * API_INPUT: token - string, token
 */

$curdir_feedback_getmessage = dirname(__FILE__);
include_once ($curdir_feedback_getmessage."/../api.lib/api.base.php");
include_once ($curdir_feedback_getmessage."/../api.lib/api.security.php");
include_once ($curdir_feedback_getmessage."/../api.lib/api.helpers.php");
include_once ($curdir_feedback_getmessage."/../api.lib/api.game.php");
include_once ($curdir_feedback_getmessage."/../../config/config.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1272, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1273, 'not found parameter id');

$id = APIHelpers::getParam("id", 0);

$conn = APIHelpers::createConnection($config);

$response['result'] = 'ok';

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
		$response['data']['id'] = htmlspecialchars($row['id']);
		$response['data']['text'] = htmlspecialchars($row['text']);
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1274, $e->getMessage());
}

APIHelpers::endpage($response);
