<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Get Quest Info (users method)
 * API_DESCRIPTION: Method will be returned quest info 
 * API_ACCESS: authorized users
 * API_INPUT: taskid - integer, Identificator of the quest (in future will be questid)
 * API_INPUT: token - string, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

if (!APIGame::checkGameDates($message) && !APISecurity::isAdmin())
	APIHelpers::showerror(1064, $message);

if (!APIHelpers::issetParam('taskid'))
	APIHelpers::showerror(1065, 'Not found parameter "taskid"');

$questid = APIHelpers::getParam('taskid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(1066, 'parameter "taskid" must be numeric');

$response['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$response['userid'] = APISecurity::userid();

$params = array();
$params[] = APISecurity::userid();
$params[] = intval($questid);

$filter_by_state = '';
$filter_by_score = '';
$filter_by_game = '';

if (!APISecurity::isAdmin()) {
	$filter_by_state = ' AND quest.state = ?';
	$params[] = 'open';
	
	$filter_by_score = ' AND quest.min_score <= ?';
	$params[] = APISecurity::score();
	
	$filter_by_game = ' AND quest.gameid = ? ';
	$params[] = APIGame::id();	
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
				quest.gameid,
				userquest.startdate,
				userquest.stopdate
			FROM 
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				quest.idquest = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				'.$filter_by_game.'
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);

	if($row = $stmt->fetch())
	{
		$status = '';
		if ($row['stopdate'] == null)
			$status = 'open';
		else if ($row['stopdate'] == '0000-00-00 00:00:00')
			$status = 'current';
		else
			$status = 'completed';

		$response['data'] = array(
			'questid' => $row['idquest'],
			'score' => $row['score'],
			'min_score' => $row['min_score'],
			'name' => $row['name'],
			'subject' => $row['subject'],
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
			'state' => $row['state'],
			'author' => $row['author'],
			'status' => $status,
		);
		$response['quest'] = $row['idquest'];
		$response['gameid'] = $row['gameid'];

		if ($status == 'current' || $status == 'completed')
		{
			$response['data']['text'] = $row['text'];
			
			$response['data']['files'] = array();
			$stmt_files = $conn->prepare('select * from quests_files WHERE questid = ?');
			$stmt_files->execute(array(intval($questid)));
			while ($row_files = $stmt_files->fetch())
				$response['data']['files'][] = array(
					'filename' => $row_files['filename'],
					'filepath' => $row_files['filepath'],
					'size' => $row_files['size'],
					'id' => $row_files['id'],
				);
		}

		if (isset($_SESSION['game']))
			$response['data']['game_title'] = $_SESSION['game']['title'];
	} else {
		APIHelpers::showerror(1148, 'Problem... may be incorrect game are selected?');
	}
	
	$response['result'] = 'ok';
	$response['permissions']['edit'] = APISecurity::isAdmin();
	$response['permissions']['delete'] = APISecurity::isAdmin();
} catch(PDOException $e) {
	APIHelpers::showerror(1067, $e->getMessage());
}

APIHelpers::endpage($response);
