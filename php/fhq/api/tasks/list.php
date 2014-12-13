<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$message = '';

if (!FHQGame::checkGameDates($message))
	FHQHelpers::showerror(916, $message);

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = FHQHelpers::createConnection($config);

$result['status']['open'] = 0;
$result['status']['current'] = 0;
$result['status']['completed'] = 0;

$result['filter']['open'] = FHQHelpers::getParam('filter_open', true);
$result['filter']['current'] = FHQHelpers::getParam('filter_current', true);
$result['filter']['completed'] = FHQHelpers::getParam('filter_completed', false);

$result['filter']['open'] = filter_var($result['filter']['open'], FILTER_VALIDATE_BOOLEAN);
$result['filter']['current'] = filter_var($result['filter']['current'], FILTER_VALIDATE_BOOLEAN);
$result['filter']['completed'] = filter_var($result['filter']['completed'], FILTER_VALIDATE_BOOLEAN);


// calculate open tasks
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND iduser = ?
			WHERE
				id_game = ?
				AND isnull(userquest.startdate)
	');

	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id()));
	if($row = $stmt->fetch())
		$result['status']['open'] = $row['cnt'];
} catch(PDOException $e) {
	FHQHelpers::showerror(920, $e->getMessage());
}

// calculate current tasks
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND iduser = ?
			WHERE
				id_game = ?
				AND userquest.stopdate = \'0000-00-00 00:00:00\'
	');
	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id()));
	if($row = $stmt->fetch())
		$result['status']['current'] = $row['cnt'];
} catch(PDOException $e) {
	FHQHelpers::showerror(921, $e->getMessage());
}

// calculate completed tasks
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND iduser = ?
			WHERE
				id_game = ?
				AND userquest.stopdate <> \'0000-00-00 00:00:00\' 
				AND userquest.stopdate <> NULL
	');
	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id()));
	if($row = $stmt->fetch())
		$result['status']['completed'] = $row['cnt'];
} catch(PDOException $e) {
	FHQHelpers::showerror(922, $e->getMessage());
}

/*$userid = FHQHelpers::getParam('userid', 0);*/

$arrWhere_status = array();

if ($result['filter']['open'])
	$arrWhere_status[] = 'isnull(userquest.startdate)';
	
if ($result['filter']['current'])
	$arrWhere_status[] = 'userquest.stopdate = \'0000-00-00 00:00:00\'';

if ($result['filter']['completed'])
	$arrWhere_status[] = '(userquest.stopdate <> \'0000-00-00 00:00:00\' AND userquest.stopdate <> NULL)';

$where_status = implode(' OR ', $arrWhere_status);
if (strlen($where_status) > 0)
	$where_status = ' AND '.$where_status;

$query = '
			SELECT
				quest.idquest,
				quest.name,
				quest.score,
				quest.short_text,
				quest.tema,
				userquest.startdate,
				userquest.stopdate
			FROM
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND iduser = ?
			WHERE
				id_game = ?
				'.$where_status.'
			ORDER BY
				quest.score DESC, quest.tema, quest.score
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id()));
	while($row = $stmt->fetch())
	{
		$status = '';
		
		if ($row['stopdate'] == null)
			$status = 'open';
		else if ($row['stopdate'] == '0000-00-00 00:00:00')
			$status = 'current';
		else
			$status = 'completed';
			
		$result['data'][] = array(
			'questid' => $row['idquest'],
			'score' => $row['score'],
			'name' => base64_decode($row['name']),
			'short_text' => base64_decode($row['short_text']),
			'subject' => base64_decode($row['tema']),
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
			'status' => $status,
		);
	}
	$result['result'] = 'ok';
	$result['permissions']['insert'] = FHQSecurity::isAdmin();
	
} catch(PDOException $e) {
	showerror(822, 'Error 822: ' + $e->getMessage());
}
unset($SxGeo);
echo json_encode($result);
