<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$message = '';

if (!FHQGame::checkGameDates($message))
	FHQHelpers::showerror(986, $message);

if (!FHQHelpers::issetParam('taskid'))
	FHQHelpers::showerror(987, 'Not found parameter "taskid"');

$taskid = FHQHelpers::getParam('taskid', 0);

if (!is_numeric($taskid))
	FHQHelpers::showerror(988, 'parameter "taskid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = FHQHelpers::createConnection($config);

$result['gameid'] = FHQGame::id(); 
$result['userid'] = FHQSecurity::userid();

$filter_by_state = FHQSecurity::isAdmin() ? '' : ' AND quest.state = "open" ';
$filter_by_score = FHQSecurity::isAdmin() ? '' : ' AND quest.min_score <= '.FHQSecurity::score().' ';

$params[] = FHQSecurity::userid();
$params[] = FHQGame::id();
$params[] = intval($taskid);

$query = '
			SELECT 
				quest.idquest,
				quest.name,
				quest.score,
				quest.min_score,
				quest.short_text,
				quest.text,
				quest.state,
				quest.tema,
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
			'short_text' => base64_decode($row['short_text']),
			'subject' => base64_decode($row['tema']),
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
			'state' => $row['state'],
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
	$result['permissions']['edit'] = FHQSecurity::isAdmin();
	$result['permissions']['delete'] = FHQSecurity::isAdmin();
	
} catch(PDOException $e) {
	showerror(822, 'Error 822: ' + $e->getMessage());
}

echo json_encode($result);
