<?php
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

if (!filter_var($from, FILTER_VALIDATE_EMAIL))
	APIHelpers::error(400, '[Feedback] Invalid e-mail address.');

$nick = 'Guest';

if(APISecurity::nick() != ''){
	$nick = APISecurity::nick();
}

$from = $nick." (".$from.")";

if(APIHelpers::email() != ''){
	$from = APIHelpers::email();
}

if (strlen($text) <= 10)
  APIHelpers::error(400, 'text must be informative! (more than 10 character)');

$email_msg = "Type: ".htmlspecialchars($type)."\r\n";
$email_msg .= "From: ".htmlspecialchars($from)."\r\n\r\n";
$email_msg .= htmlspecialchars($text);

$msg = "Type: ".htmlspecialchars($type)."\r\n";
$msg .= "From: ".htmlspecialchars($nick)."\r\n\r\n";
$msg .= htmlspecialchars($text);

$stmt = $conn->prepare('INSERT INTO feedback(`type`, `from`, `text`, `userid`, `dt`) VALUES(?,?,?,?,NOW());');
if($stmt->execute(array($type, $from, $msg, APISecurity::userid()))){
	$response['data']['feedback']['id'] = $conn->lastInsertId();
	$response['result'] = 'ok';
	
	// this option must be moved to db
	if (isset(APIHelpers::$CONFIG['mail']) && isset(APIHelpers::$CONFIG['mail']['allow']) && APIHelpers::$CONFIG['mail']['allow'] == 'yes') {
		APIHelpers::sendMailToAdmin('Feedback from freehackquest', $email_msg, $error);
	}
} else {
	APIHelpers::error(400, 'Could not insert. PDO: '.print_r($conn->errorInfo(),true));
}


APIHelpers::endpage($response);
