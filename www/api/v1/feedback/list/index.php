<?php
/*
 * API_NAME: Feedback List
 * API_DESCRIPTION: Method will be list feedbacks and feedback messages to them.
 * API_DESCRIPTION: For admin: will be returned all feedbacks for user only user's feedback
 * API_ACCESS: authorized users
 * API_INPUT: feedbackid - integer, type of feedback id
 * API_INPUT: text - string, text message
 * API_INPUT: token - string, token
 */

$curdir_statistics_list = dirname(__FILE__);
include_once ($curdir_statistics_list."/../../../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../../../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../../../api.lib/api.helpers.php");

$response = APIHelpers::startpage();

if(!APIHelpers::is_json_input()){
	APIHelpers::showerror2(2000, 400, "Expected application/json");
}
$conn = APIHelpers::createConnection();
$request = APIHelpers::read_json_input();

// page
$page = 0;
if(isset($request['page']))
	$page = $request['page'];
if (!is_numeric($page))
	APIHelpers::showerror2(1234, 400, 'parameter "page" must be numeric');
$response['data']['page'] = intval($page);

// onpage
$onpage = 25; 
if(isset($request['onpage']))
	$onpage = $request['onpage'];
if (!is_numeric($onpage))
	APIHelpers::showerror2(1235, 400, 'parameter "onpage" must be numeric');

$response['data']['onpage'] = intval($onpage);

$columns = array();
$columns[] = 'fb.id';
$columns[] = 'fb.type';
$columns[] = 'fb.text';
$columns[] = 'fb.dt';
$columns[] = 'u.nick';
$columns[] = 'u.logo';
$columns[] = 'fb.userid';

$conn = APIHelpers::createConnection($config);

$response['result'] = 'ok';

// count feedback
try {
	$stmt = $conn->prepare('
			SELECT count(*) as cnt FROM feedback fb LEFT JOIN users u ON fb.userid = u.id 
	');
	$stmt->execute();
	if($row = $stmt->fetch()) {
		$response['data']['count'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1236, $e->getMessage());
}

try {
	$stmt = $conn->prepare('
			SELECT
				'.implode(', ', $columns).'
			FROM 
				feedback fb
			LEFT JOIN
				users u ON fb.userid = u.id 
			ORDER BY
				fb.id DESC
			LIMIT '.($page*$onpage).','.$onpage.'
	');
	$stmt->execute();
	$response['data']['feedback'] = array();
	$id = -1;
	while ($row = $stmt->fetch()) {
		$id++;
		$feedbackid = $row['id'];
		$response['data']['feedback'][$id] = array(
			'id' => $row['id'],
			'type' => htmlspecialchars($row['type']),
			'text' => htmlspecialchars($row['text']),
			'nick' => htmlspecialchars($row['nick']),
			'userid' => htmlspecialchars($row['userid']),
			'logo' => htmlspecialchars($row['logo']),
			'dt' => $row['dt'],
			'messages' => array(),
		);
		
		// messages
		$stmt_messages = $conn->prepare('
			select 
				fbm.id,
				fbm.feedbackid,
				fbm.text,
				fbm.dt,
				fbm.userid,
				u.nick,
				u.logo
			from 
				feedback_msg fbm
			INNER JOIN users u ON fbm.userid = u.id
			WHERE
				feedbackid = ?
			ORDER BY id DESC
		');
		$stmt_messages->execute(array($feedbackid));
	
		while ($row_message = $stmt_messages->fetch()) {
			$response['data']['feedback'][$id]['messages'][] = array(
				'id' => htmlspecialchars($row_message['id']),
				'feedbackid' => htmlspecialchars($row_message['feedbackid']),
				'userid' => $row_message['userid'],
				'nick' => htmlspecialchars($row_message['nick']),
				'logo' => htmlspecialchars($row_message['logo']),
				'text' => htmlspecialchars($row_message['text']),
				'dt' => $row_message['dt'],
			);
		}
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1238, $e->getMessage());
}

APIHelpers::endpage($response);
