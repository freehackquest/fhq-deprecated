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
include_once ($curdir_statistics_list."/../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../api.lib/api.helpers.php");
include_once ($curdir_statistics_list."/../api.lib/api.game.php");
include_once ($curdir_statistics_list."/../../config/config.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

// page
$page = APIHelpers::getParam('page', 0);
if (!is_numeric($page))
	APIHelpers::showerror(1234, 'parameter "page" must be numeric');
$response['data']['page'] = intval($page);

// onpage
$onpage = APIHelpers::getParam('onpage', 25);
if (!is_numeric($onpage))
	APIHelpers::showerror(1235, 'parameter "onpage" must be numeric');
$response['data']['onpage'] = intval($onpage);

$filter_where = array();
$filter_values = array();

if (!APISecurity::isAdmin()) {
	$filter_where[] = 'fb.userid = ?';
	$filter_values[] = APISecurity::userid();
}

$response['access'] = APISecurity::isAdmin();

$where = '';
if (count($filter_where) > 0) {
	$where = ' WHERE '.implode(' AND ', $filter_where);
}

$conn = APIHelpers::createConnection($config);

$response['result'] = 'ok';

// count feedback
try {
	$stmt = $conn->prepare('
			SELECT
				count(*) as cnt
			FROM 
				feedback fb
			INNER JOIN users u ON fb.userid = u.id 
			'.$where.'
	');
	$stmt->execute($filter_values);
	if($row = $stmt->fetch()) {
		$response['data']['count'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1236, $e->getMessage());
}

try {
	$stmt = $conn->prepare('
			SELECT
				fb.id,
				fb.type,
				fb.text,
				fb.dt,
				u.email,
				u.nick,
				u.logo,
				fb.userid
			FROM 
				feedback fb
			INNER JOIN users u ON fb.userid = u.id 
				'.$where.'
			ORDER BY
				fb.id DESC
			LIMIT '.($page*$onpage).','.$onpage.'
	');
	$stmt->execute($filter_values);
	$response['data']['feedback'] = array();
	$id = -1;
	while ($row = $stmt->fetch()) {
		$id++;
		$feedbackid = $row['id'];
		$response['data']['feedback'][$id] = array(
			'id' => $row['id'],
			'type' => htmlspecialchars($row['type']),
			'text' => htmlspecialchars($row['text']),
			'email' => htmlspecialchars($row['email']),
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
				u.logo,
				u.email
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
				'email' => htmlspecialchars($row_message['email']),
				'text' => htmlspecialchars($row_message['text']),
				'dt' => $row_message['dt'],
			);
		}
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1238, $e->getMessage());
}

APIHelpers::endpage($response);
