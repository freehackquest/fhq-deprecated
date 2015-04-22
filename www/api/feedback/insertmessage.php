<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Feedback Message Insert
 * API_DESCRIPTION: Method will be add feedback message to feedback
 * API_ACCESS: authorized users
 * API_INPUT: feedbackid - integer, type of feedback id
 * API_INPUT: text - string, text message
 * API_INPUT: token - string, token
 */

$curdir_events_insert = dirname(__FILE__);
include_once ($curdir_events_insert."/../api.lib/api.helpers.php");
include_once ($curdir_events_insert."/../../config/config.php");
include_once ($curdir_events_insert."/../api.lib/api.base.php");

include_once ($curdir_events_insert."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (!APIHelpers::issetParam('feedbackid'))
  APIHelpers::showerror(1248, 'not found parameter feedbackid');

if (!APIHelpers::issetParam('text'))
  APIHelpers::showerror(1250, 'not found parameter text');

$feedbackid = APIHelpers::getParam('feedbackid', 0);
$text = APIHelpers::getParam('text', '');

if (!is_numeric($feedbackid))
  APIHelpers::showerror(1251, 'incorrect feedbackid');

$feedbackid = intval($feedbackid);

$conn = APIHelpers::createConnection($config);


$author = 0;
// check access user
if(!APISecurity::isAdmin()) {
	try {
		$stmt = $conn->prepare('SELECT count(*) as cnt FROM feedback WHERE id = ? and userid = ?');
		$stmt->execute(array($feedbackid, APISecurity::userid()));
		if($row = $stmt->fetch()) {
			if (intval($row['cnt']) != 1) {
				APIHelpers::showerror(1247, " added message can only admin and author of topic.");
			}
		}
	} catch(PDOException $e) {
		APIHelpers::showerror(1252, $e->getMessage());
	}
}

try {
	// TODO send mail
	/*
	 *           $msg = "
Answer On Feedback
Feedback Text: 
  ".$text."
Feedback Answer: 
  ".$answer_text."";
	 * */
 	$stmt = $conn->prepare('INSERT INTO feedback_msg(feedbackid, text, userid, dt) VALUES(?,?,?,NOW())');
 	$stmt->execute(array($feedbackid, $text, APISecurity::userid()));
 	$result['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1249, $e->getMessage());
}

echo json_encode($result);
