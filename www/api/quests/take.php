<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Take Quest 
 * API_DESCRIPTION: Method for take quest to in progress
 * API_ACCESS: authorized users
 * API_INPUT: questid - integer, Identificator of the quest
 * API_INPUT: token - string, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(1210, $message);

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(1205, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(1206, 'parameter "questid" must be numeric');

$response['result'] = 'ok';

// TODO: must be added filters
$conn = APIHelpers::createConnection($config);

$response['gameid'] = APIGame::id(); 
$response['userid'] = APISecurity::userid();

$filter_by_state = APISecurity::isAdmin() ? '' : ' AND quest.state = "open" ';
$filter_by_score = APISecurity::isAdmin() ? '' : ' AND quest.min_score <= '.APISecurity::score().' ';

$params[] = APISecurity::userid();
$params[] = APIGame::id();
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
				quest.gameid = ?
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

		$response['data'] = array(
			'questid' => $row['idquest'],
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
		);
		$response['quest'] = $row['idquest'];
		
		if ($status == 'open') {
			$response['result'] = 'ok';
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
			APIHelpers::showerror(1207, 'quest already takes');
		}

		/*if ($status == 'current' || $status == 'completed')
			$response['data']['text'] = $row['text'];*/
	}
	else
	{
		APIHelpers::showerror(1208, 'not found quest');
	}
	
} catch(PDOException $e) {
	APIHelpers::showerror(1209, $e->getMessage());
}

APIHelpers::endpage($response);
