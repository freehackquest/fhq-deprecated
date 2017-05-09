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
include_once ($curdir_feedback_insert."/../../../api.lib/api.helpers.php");
include_once ($curdir_feedback_insert."/../../../api.lib/api.base.php");

$response = APIHelpers::startpage();

if(!APIHelpers::is_json_input()){
	APIHelpers::error(400, "Expected application/json");
}
$conn = APIHelpers::createConnection();
$request = APIHelpers::read_json_input();

if (!isset($request['type'])){
  APIHelpers::error(400, 'not found parameter type');
}

if (!isset($request['from'])){
  APIHelpers::error(400, 'not found parameter from');
}
  
if (!isset($request['text'])){
  APIHelpers::error(400, 'not found parameter text');
}

$type = $request['type'];
$from = $request['from'];
$text = $request['text'];

if (strlen($text) <= 10)
  APIHelpers::error(400, 'text must be informative! (more than 10 character)');

$msg = "Type: ".htmlspecialchars($type)."\r\n";
$msg .= "From: ".htmlspecialchars($from)."\r\n\r\n";
$msg .= htmlspecialchars($text);

$stmt = $conn->prepare('INSERT INTO feedback(type, text, userid, dt) VALUES(?,?,?,NOW());');
if($stmt->execute(array($type, $msg, APISecurity::userid()))) {
	$response['data']['feedback']['id'] = $conn->lastInsertId();
	$response['result'] = 'ok';
	
	// this option must be moved to db
	if (isset($config['mail']) && isset($config['mail']['allow']) && $config['mail']['allow'] == 'yes') {
		APIHelpers::sendMailToAdmin('Feedback from freehackquest', $msg, $error);
	}
	
} else {
	APIHelpers::error(400, 'Could not insert. PDO: '.$conn->errorInfo());
}


APIHelpers::endpage($response);
