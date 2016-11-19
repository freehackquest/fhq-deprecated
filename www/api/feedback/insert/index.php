<?php
/*
 * API_NAME: Feedback Insert
 * API_DESCRIPTION: Method will be add feedback
 * API_ACCESS: authorized users
 * API_INPUT: type - string, type of feedback - look in types
 * API_INPUT: text - string, text message
 * API_INPUT: token - string, token
 */
 
$curdir_feedback_insert = dirname(__FILE__);
include_once ($curdir_feedback_insert."/../../api.lib/api.helpers.php");
include_once (dirname(__FILE__)."/../../../config/config.php");
include_once ($curdir_feedback_insert."/../../api.lib/api.base.php");
include_once ($curdir_feedback_insert."/../../api.lib/api.mail.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

$conn = APIHelpers::createConnection($config);

if (!APIHelpers::issetParam('type'))
  APIHelpers::showerror(1237, 'not found parameter type');

if (!APIHelpers::issetParam('text'))
  APIHelpers::showerror(1242, 'not found parameter text');

$type = APIHelpers::getParam('type', 'complaint');
$text = APIHelpers::getParam('text', '');

if (strlen($text) <= 3)
  APIHelpers::showerror(1239, 'text must be informative! (more than 3 character)');

try {
	// TODO send mail to admin

	$stmt = $conn->prepare('INSERT INTO feedback(type, text, userid, dt) VALUES(?,?,?,NOW());');
	if($stmt->execute(array($type, $text, APISecurity::userid()))) {
		$response['data']['feedback']['id'] = $conn->lastInsertId();
		$response['result'] = 'ok';
		
		// this option must be moved to db
		if (isset($config['mail']) && isset($config['mail']['allow']) && $config['mail']['allow'] == 'yes') {
			APIMail::send($config, $config['mail']['system_message_admin_email'], '', '', 'Feedback from freehackquest', $text, $error);
		}
		
	} else {
		APIHelpers::showerror(1240,'Could not insert. PDO: '.$conn->errorInfo());
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1241,$e->getMessage());
}

APIHelpers::endpage($response);
