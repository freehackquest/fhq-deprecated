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

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(986, $message);

if (!APIHelpers::issetParam('taskid'))
	APIHelpers::showerror(987, 'Not found parameter "taskid"');

$taskid = APIHelpers::getParam('taskid', 0);

if (!is_numeric($taskid))
	APIHelpers::showerror(988, 'parameter "taskid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
if ($conn == null)
	$conn = APIHelpers::createConnection($config);

$result['gameid'] = APIGame::id(); 
$result['userid'] = APISecurity::userid();

$filter_by_state = APISecurity::isAdmin() ? '' : ' AND quest.state = "open" ';
$filter_by_score = APISecurity::isAdmin() ? '' : ' AND quest.min_score <= '.APISecurity::score().' ';

$params[] = APISecurity::userid();
$params[] = APIGame::id();
$params[] = intval($taskid);

$query = '
			SELECT 
				quest.idquest,
				quest.name,
				quest.score,
				quest.min_score,
				quest.text,
				quest.state,
				quest.tema,
				quest.author,
				userquest.startdate,
				userquest.stopdate
			FROM 
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				quest.id_game = ?
				AND quest.idquest = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
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
			'name' => base64_decode($row['name']),
			'subject' => base64_decode($row['tema']),
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
			'state' => $row['state'],
			'author' => base64_decode($row['author']),
			'status' => $status,
		);
		$result['quest'] = $row['idquest'];

		if ($status == 'current' || $status == 'completed')
		{
			$result['data']['text'] = base64_decode($row['text']);
		}

		if (isset($_SESSION['game']))
			$result['data']['game_title'] = $_SESSION['game']['title'];
	}
	$result['result'] = 'ok';
	$result['permissions']['edit'] = APISecurity::isAdmin();
	$result['permissions']['delete'] = APISecurity::isAdmin();
} catch(PDOException $e) {
	APIHelpers::showerror(822, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
