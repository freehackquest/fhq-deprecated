<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$message = '';

if (!APIGame::checkGameDates($message) && !APISecurity::isAdmin())
	APIHelpers::showerror(986, $message);

if (!APIHelpers::issetParam('taskid'))
	APIHelpers::showerror(987, 'Not found parameter "taskid"');

$questid = APIHelpers::getParam('taskid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(988, 'parameter "taskid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
if ($conn == null)
	$conn = APIHelpers::createConnection($config);

$result['userid'] = APISecurity::userid();

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

		$result['data'] = array(
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
		$result['quest'] = $row['idquest'];
		$result['gameid'] = $row['gameid'];

		if ($status == 'current' || $status == 'completed')
		{
			$result['data']['text'] = $row['text'];
		}

		if (isset($_SESSION['game']))
			$result['data']['game_title'] = $_SESSION['game']['title'];
	} else {
		APIHelpers::showerror(822, 'Problem... may be incorrect game are selected?');
	}
	
	$result['result'] = 'ok';
	$result['permissions']['edit'] = APISecurity::isAdmin();
	$result['permissions']['delete'] = APISecurity::isAdmin();
} catch(PDOException $e) {
	APIHelpers::showerror(822, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
