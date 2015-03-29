<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(917, $message);

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

if (APIGame::id() == 0)
	APIHelpers::showerror(926, "Game was not selected.");

// TODO: must be added filters
$conn = APIHelpers::createConnection($config);

$result['status']['open'] = 0;
$result['status']['current'] = 0;
$result['status']['completed'] = 0;

$result['filter']['open'] = APIHelpers::getParam('filter_open', true);
$result['filter']['current'] = APIHelpers::getParam('filter_current', true);
$result['filter']['completed'] = APIHelpers::getParam('filter_completed', false);

$result['filter']['open'] = filter_var($result['filter']['open'], FILTER_VALIDATE_BOOLEAN);
$result['filter']['current'] = filter_var($result['filter']['current'], FILTER_VALIDATE_BOOLEAN);
$result['filter']['completed'] = filter_var($result['filter']['completed'], FILTER_VALIDATE_BOOLEAN);

$result['gameid'] = APIGame::id();
$result['userid'] = APISecurity::userid();

$filter_by_state = APISecurity::isAdmin() ? '' : ' AND quest.state = "open" ';

$filter_by_score = APISecurity::isAdmin() ? '' : ' AND quest.min_score <= '.APISecurity::score().' ';

// calculate count summary
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			WHERE
				id_game = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				AND (quest.for_person = 0 OR quest.for_person = ?)
			
	');
	$stmt->execute(array(APIGame::id(),APISecurity::userid()));
	if($row = $stmt->fetch())
		$result['status']['summary'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(922, $e->getMessage());
}

// calculate open tasks
try {
	$query = '
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			LEFT JOIN userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				id_game = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				AND (quest.for_person = 0 OR quest.for_person = ?)
				AND isnull(userquest.stopdate)
				AND isnull(userquest.startdate)
	';
	// $result['query_open'] = $query;
	$stmt1 = $conn->prepare($query);
	$stmt1->execute(array(APISecurity::userid(),APIGame::id(), APISecurity::userid()));
	if($row = $stmt1->fetch())
		$result['status']['open'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(920, $e->getMessage());
}

// calculate current tasks
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			INNER JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				id_game = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				AND (quest.for_person = 0 OR quest.for_person = ?)
				AND userquest.startdate <> \'0000-00-00 00:00:00\'
				AND userquest.stopdate = \'0000-00-00 00:00:00\'
	');
	$stmt->execute(array(APISecurity::userid(),APIGame::id(),APISecurity::userid()));
	if($row = $stmt->fetch())
		$result['status']['current'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(921, $e->getMessage());
}

// calculate completed tasks
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			INNER JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				id_game = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				AND (quest.for_person = 0 OR quest.for_person = ?)
				AND userquest.startdate <> \'0000-00-00 00:00:00\'
				AND userquest.stopdate <> \'0000-00-00 00:00:00\' 
	');
	$stmt->execute(array(APISecurity::userid(),APIGame::id(), APISecurity::userid()));
	if($row = $stmt->fetch())
		$result['status']['completed'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(922, $e->getMessage());
}

// calculate count of types
try {
	$stmt = $conn->prepare('
			SELECT
				quest.tema,
				count(quest.idquest) as cnt
			FROM
				quest
			WHERE
				id_game = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				AND (quest.for_person = 0 OR quest.for_person = ?)
			GROUP BY
				quest.tema
	');
	$stmt->execute(array(APIGame::id(), APISecurity::userid()));
	while($row = $stmt->fetch())
	{
		$result['subjects'][base64_decode($row['tema'])] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(922, $e->getMessage());
}

/*$userid = APIHelpers::getParam('userid', 0);*/
$params = array(APISecurity::userid(), APIGame::id());

// filter by status
$arrWhere_status = array();

if ($result['filter']['open'])
	$arrWhere_status[] = '(isnull(userquest.startdate) AND isnull(userquest.stopdate))';
				
if ($result['filter']['current'])
	$arrWhere_status[] = '(userquest.startdate <> \'0000-00-00 00:00:00\' AND userquest.stopdate = \'0000-00-00 00:00:00\')';

if ($result['filter']['completed'])
	$arrWhere_status[] = '(userquest.stopdate <> \'0000-00-00 00:00:00\' AND userquest.stopdate <> \'0000-00-00 00:00:00\')';

$where_status = '';

if (count($arrWhere_status) > 0)
	$where_status = ' AND ('.implode(' OR ', $arrWhere_status).')';

// filter by subjects
$filter_subjects = getParam('filter_subjects', '');
$filter_subjects = explode(',', $filter_subjects);
$arrWhere_subjects = array();
foreach ($filter_subjects as $k)
{
	if (strlen($k) > 0) {
		$arrWhere_subjects[] = 'quest.tema = ?';
		$params[] = base64_encode($k);
	}
}
if (count($arrWhere_subjects) > 0)
	$where_status .= ' AND ('.implode(' OR ', $arrWhere_subjects).')';

$query = '
			SELECT 
				quest.idquest,
				quest.name,
				quest.score,
				quest.tema,
				quest.state,
				quest.count_user_solved,
				userquest.startdate,
				userquest.stopdate
			FROM 
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				quest.id_game = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				'.$where_status.'
				AND (quest.for_person = 0 OR quest.for_person = ?)
			ORDER BY
				quest.tema, quest.score ASC, quest.score
		';

// $result['where_status'] = $where_status;
// $result['params'] = $params;
// $result['query'] = $query;
$params[] =  APISecurity::userid();
try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
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
			'subject' => base64_decode($row['tema']),
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
			'state' => $row['state'],
			'count_user_solved' => $row['count_user_solved'],
			'status' => $status,
		);
	}
	$result['result'] = 'ok';
	$result['permissions']['insert'] = APISecurity::isAdmin();
	
} catch(PDOException $e) {
	APIHelpers::showerror(822, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
