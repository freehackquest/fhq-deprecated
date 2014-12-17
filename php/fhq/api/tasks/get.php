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

$result['gameid'] = FHQGame::id(); 
$result['userid'] = FHQSecurity::userid();

// calculate count summary
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			WHERE
				id_game = ?
				AND (quest.for_person = 0 OR quest.for_person = ?)
			
	');
	$stmt->execute(array(FHQGame::id(),FHQSecurity::userid()));
	if($row = $stmt->fetch())
		$result['status']['summary'] = $row['cnt'];
} catch(PDOException $e) {
	FHQHelpers::showerror(922, $e->getMessage());
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
				AND (quest.for_person = 0 OR quest.for_person = ?)
				AND isnull(userquest.stopdate)
				AND isnull(userquest.startdate)
				AND quest.state = "open"
	';
	// $result['query_open'] = $query;
	$stmt1 = $conn->prepare($query);
	$stmt1->execute(array(FHQSecurity::userid(),FHQGame::id(), FHQSecurity::userid()));
	if($row = $stmt1->fetch())
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
			INNER JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				id_game = ?
				AND (quest.for_person = 0 OR quest.for_person = ?)
				AND userquest.startdate <> \'0000-00-00 00:00:00\'
				AND userquest.stopdate = \'0000-00-00 00:00:00\'
				AND quest.state = "open"
	');
	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id(),FHQSecurity::userid()));
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
			INNER JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				id_game = ?
				AND (quest.for_person = 0 OR quest.for_person = ?)
				AND userquest.startdate <> \'0000-00-00 00:00:00\'
				AND userquest.stopdate <> \'0000-00-00 00:00:00\'
				AND quest.state = "open"
	');
	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id(), FHQSecurity::userid()));
	if($row = $stmt->fetch())
		$result['status']['completed'] = $row['cnt'];
} catch(PDOException $e) {
	FHQHelpers::showerror(922, $e->getMessage());
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
				(quest.for_person = 0 OR quest.for_person = ?)
				AND id_game = ?
				AND quest.state = "open"
			GROUP BY
				quest.tema
	');
	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id()));
	while($row = $stmt->fetch())
	{
		$result['subjects'][base64_decode($row['tema'])] = $row['cnt'];
	}
} catch(PDOException $e) {
	FHQHelpers::showerror(922, $e->getMessage());
}

/*$userid = FHQHelpers::getParam('userid', 0);*/
$params = array(FHQSecurity::userid(), FHQGame::id());

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
				quest.short_text,
				quest.tema,
				userquest.startdate,
				userquest.stopdate
			FROM 
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND userquest.iduser = ?
			WHERE
				quest.id_game = ?
				'.$where_status.'
			ORDER BY
				quest.score ASC, quest.tema, quest.score
		';

// $result['where_status'] = $where_status;
// $result['params'] = $params;
// $result['query'] = $query;

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
