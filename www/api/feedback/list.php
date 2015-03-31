<?php
$curdir_statistics_list = dirname(__FILE__);
include_once ($curdir_statistics_list."/../api.lib/api.base.php");
include_once ($curdir_statistics_list."/../api.lib/api.security.php");
include_once ($curdir_statistics_list."/../api.lib/api.helpers.php");
include_once ($curdir_statistics_list."/../api.lib/api.game.php");
include_once ($curdir_statistics_list."/../../config/config.php");

APIHelpers::startpage($config);
/*
	$conn = null;
	$session = null;
*/
include_once ($curdir_statistics_list."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);


// page
$page = APIHelpers::getParam('page', 0);
if (!is_numeric($page))
	APIHelpers::showerror(1234, 'parameter "page" must be numeric');
$result['data']['page'] = intval($page);

// onpage
$onpage = APIHelpers::getParam('onpage', 25);
if (!is_numeric($onpage))
	APIHelpers::showerror(1235, 'parameter "onpage" must be numeric');
$result['data']['onpage'] = intval($onpage);

$filter_where = [];
$filter_values = [];

if (!APISecurity::isAdmin()) {
	$filter_where[] = 'fb.author = ?';
	$filter_values[] = APISecurity::userid();
}

$result['access'] = APISecurity::isAdmin();

$where = '';
if (count($filter_where) > 0) {
	$where = ' WHERE '.implode(' AND ', $filter_where);
}

$conn = APIHelpers::createConnection($config);

$result['result'] = 'ok';

// count feedback
try {
	$stmt = $conn->prepare('
			SELECT
				count(*) as cnt
			FROM 
				feedback fb
			INNER JOIN user u ON fb.userid = u.iduser 
			'.$where.'
	');
	$stmt->execute($filter_values);
	if($row = $stmt->fetch()) {
		$result['data']['count'] = $row['cnt'];
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
				u.iduser
			FROM 
				feedback fb
			INNER JOIN user u ON fb.userid = u.iduser 
				'.$where.'
			ORDER BY
				fb.id DESC
			LIMIT '.($page*$onpage).','.$onpage.'
	');
	$stmt->execute($filter_values);
	$result['data']['feedback'] = array();
	$id = -1;
	while ($row = $stmt->fetch()) {
		$id++;
		$feedbackid = $row['id'];
		$result['data']['feedback'][$id] = array(
			'id' => $row['id'],
			'type' => htmlspecialchars($row['type']),
			'text' => htmlspecialchars($row['text']),
			'email' => htmlspecialchars($row['email']),
			'nick' => htmlspecialchars($row['nick']),
			'userid' => htmlspecialchars($row['iduser']),
			'logo' => htmlspecialchars($row['logo']),
			'dt' => $row['dt'],
			'messages' => array(),
		);
		
		// messages
		$stmt_messages = $conn->prepare('
			select 
				fbm.id,
				fbm.feedback_id,
				fbm.msg,
				fbm.dt,
				u.iduser,
				u.nick,
				u.logo,
				u.email
			from 
				feedback_msg fbm
			INNER JOIN user u ON fbm.author = u.iduser
			WHERE
				feedback_id = ?
			ORDER BY id DESC
		');
		$stmt_messages->execute(array($feedbackid));
	
		while ($row_message = $stmt_messages->fetch()) {
			$result['data']['feedback'][$id]['messages'][] = array(
				'id' => htmlspecialchars($row_message['id']),
				'feedbackid' => htmlspecialchars($row_message['feedback_id']),
				'userid' => $row_message['iduser'],
				'nick' => htmlspecialchars($row_message['nick']),
				'logo' => htmlspecialchars($row_message['logo']),
				'email' => htmlspecialchars($row_message['email']),
				'text' => htmlspecialchars($row_message['msg']),
				'dt' => $row_message['dt'],
			);
		}
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1238, $e->getMessage());
}

// not needed here
// include_once ($curdir."/../api.lib/savetoken.php");

APIHelpers::endpage($result);
