<?php
/*
 * API_NAME: Get Quest Info (users method)
 * API_DESCRIPTION: Method will be returned quest info 
 * API_ACCESS: authorized users
 * API_INPUT: taskid - integer, Identificator of the quest (in future will be questid)
 * API_INPUT: token - string, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../../../api.lib/api.base.php");
include_once ($curdir."/../../../api.lib/api.helpers.php");

$response = APIHelpers::startpage();

if(!APIHelpers::is_json_input()){
	APIHelpers::error(400, "Expected application/json");
}
$conn = APIHelpers::createConnection();
$request = APIHelpers::read_json_input();


if (!isset($request['questid']))
	APIHelpers::error(400, 'Not found parameter "questid"');

$questid = $request['questid'];

if (!is_numeric($questid))
	APIHelpers::error(400, 'parameter "questid" must be numeric');

$response['result'] = 'ok';

$params = array();
$params[] = APISecurity::userid();
$params[] = intval($questid);

$filter_by_state = '';
$filter_by_score = '';

if (!APISecurity::isAdmin()) {
	$filter_by_state = ' AND quest.state = ?';
	$params[] = 'open';
}

$query = '
			SELECT 
				quest.idquest,
				quest.name,
				quest.score,
				quest.min_score,
				quest.text,
				quest.state,
				quest.subject,
				quest.author,
				quest.count_user_solved,
				quest.gameid,
				games.logo as game_logo,
				games.title as game_title,
				users_quests.dt_passed
			FROM
				quest
			LEFT JOIN 
				users_quests ON users_quests.questid = quest.idquest AND users_quests.userid = ?
			LEFT JOIN 
				games ON quest.gameid = games.id
			WHERE
				quest.idquest = ?
				'.$filter_by_state.'
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	if($row = $stmt->fetch()){
		$status = '';
		if ($row['dt_passed'] == null)
			$status = 'open';
		else
			$status = 'completed';

		$response['data'] = array(
			'questid' => $row['idquest'],
			'score' => $row['score'],
			'min_score' => $row['min_score'],
			'name' => $row['name'],
			'subject' => $row['subject'],
			'dt_passed' => $row['dt_passed'],
			'solved' => $row['count_user_solved'],
			'state' => $row['state'],
			'author' => $row['author'],
			'status' => $status,
			'gameid' => $row['gameid'],
			'game_logo' => $row['game_logo'],
			'game_title' => $row['game_title'],
			'text' => $row['text'],
		);
		$gameid = $row['gameid'];
		$response['quest'] = $row['idquest'];
		$response['gameid'] = $row['gameid'];
		
		$response['data']['hints'] = array();
		$q_hints = $conn->prepare('SELECT * FROM quests_hints WHERE questid=?');
		$q_hints->execute(array(intval($questid)));
		while ($row_hint = $q_hints->fetch()){
			$response['data']['hints'][] = array(
				'hintid' => $row_hint['id'],
				'text' => $row_hint['text'],
			);
		}

		$response['data']['files'] = array();
		$stmt_files = $conn->prepare('select * from quests_files WHERE questid = ?');
		$stmt_files->execute(array(intval($questid)));
		while ($row_files = $stmt_files->fetch()){
			$response['data']['files'][] = array(
				'filename' => $row_files['filename'],
				'filepath' => $row_files['filepath'],
				'size' => $row_files['size'],
				'id' => $row_files['id'],
			);
		}
	} else {
		APIHelpers::error(500, 'Problem... may be incorrect game are selected?');
	}
	
	$response['result'] = 'ok';
	$response['permissions']['edit'] = APISecurity::isAdmin();
	$response['permissions']['delete'] = APISecurity::isAdmin();
	
	if (!APIHelpers::checkGameDates($conn, $gameid, $message) && !APISecurity::isAdmin())
		APIHelpers::error(400, $message);
	
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

APIHelpers::endpage($response);
