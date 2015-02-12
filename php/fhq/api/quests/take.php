<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$message = '';

if (!FHQGame::checkGameDates($message))
	APIHelpers::showerror(986, $message);

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(987, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(988, 'parameter "questid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = APIHelpers::createConnection($config);

$result['gameid'] = FHQGame::id(); 
$result['userid'] = APISecurity::userid();

$filter_by_state = APISecurity::isAdmin() ? '' : ' AND quest.state = "open" ';
$filter_by_score = APISecurity::isAdmin() ? '' : ' AND quest.min_score <= '.APISecurity::score().' ';

$params[] = APISecurity::userid();
$params[] = FHQGame::id();
$params[] = intval($questid);

$query = '
			SELECT 
				quest.idquest,
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
			$status = 'in_progress';
		else
			$status = 'completed';

		$result['data'] = array(
			'questid' => $row['idquest'],
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
		);
		$result['quest'] = $row['idquest'];
		
		if ($status == 'open') {
			$result['result'] = 'ok';
			// echo 'INSERT INTO userquest(iduser,idquest,startdate,stopdate) (?,?,NOW(),\'0000-00-00 00:00:00\');';
			
			$stmt2 = $conn->prepare('INSERT INTO userquest(iduser,idquest,startdate,stopdate) VALUES (?,?,NOW(),\'0000-00-00 00:00:00\');');
			$params2 = array(
				APISecurity::userid(),
				intval($questid),
			);
			$stmt2->execute($params2);
		}
		else
		{
			APIHelpers::showerror(822, 'quest already takes');
		}

		/*if ($status == 'current' || $status == 'completed')
			$result['data']['text'] = base64_decode($row['text']);*/
	}
	else
	{
		APIHelpers::showerror(822, 'not found quest');
	}
	
} catch(PDOException $e) {
	APIHelpers::showerror(822, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
