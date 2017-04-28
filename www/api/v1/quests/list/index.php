<?php
/*
 * API_NAME: Quest List
 * API_DESCRIPTION: Method will be returned quest list
 * API_ACCESS: authorized users
 * API_INPUT: token - string, token
 * API_INPUT: open - boolean, filter by open quests (it not taked)
 * API_INPUT: completed - boolean, filter by completed quest (finished quests)
 * API_INPUT: subjects - string, filter by subjects quests (for example: "hashes,trivia" and etc. also look types)
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../../../api.lib/api.base.php");
include_once ($curdir."/../../../api.lib/api.security.php");
include_once ($curdir."/../../../api.lib/api.helpers.php");
include_once ($curdir."/../../../../config/config.php");


$response = APIHelpers::startpage($config);

if(!APIHelpers::is_json_input()){
	APIHelpers::showerror2(2000, 400, "Expected application/json");
}

APIHelpers::checkAuth();

$conn = APIHelpers::createConnection($config);

$request = APIHelpers::read_json_input();

$gameid = isset($request['gameid']) ? intval($request['gameid']) : 0;

if ($gameid == 0)
	APIHelpers::showerror2(1094, 400, "Missing or wrong parameter gameid");

$message = '';
if (!APIHelpers::checkGameDates($conn, $gameid, $message))
	APIHelpers::showerror2(1095, 403, $message);



// TODO: must be added filters
$conn = APIHelpers::createConnection($config);

$response['result'] = 'ok';

$response['status']['open'] = 0;
$response['status']['completed'] = 0;

$response['filter']['open'] = APIHelpers::getParam('open', true);
$response['filter']['completed'] = APIHelpers::getParam('completed', false);

$response['filter']['open'] = filter_var($response['filter']['open'], FILTER_VALIDATE_BOOLEAN);
$response['filter']['completed'] = filter_var($response['filter']['completed'], FILTER_VALIDATE_BOOLEAN);

$response['filter']['name_contains'] = APIHelpers::getParam('name_contains', '');

$response['gameid'] = $gameid;
$response['userid'] = APISecurity::userid();

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
				gameid = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
	');
	$stmt->execute(array($gameid));
	if($row = $stmt->fetch())
		$response['status']['summary'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(1096, $e->getMessage());
}

// calculate open tasks
try {
	$query = '
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			LEFT JOIN users_quests ON users_quests.questid = quest.idquest AND users_quests.userid = ?
			WHERE
				gameid = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				AND isnull(users_quests.dt_passed)
	';
	// $response['query_open'] = $query;
	$stmt1 = $conn->prepare($query);
	$stmt1->execute(array(APISecurity::userid(),$gameid));
	if($row = $stmt1->fetch())
		$response['status']['open'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(1097, $e->getMessage());
}

// calculate completed tasks
try {
	$stmt = $conn->prepare('
			SELECT
				count(quest.idquest) as cnt
			FROM
				quest
			INNER JOIN 
				users_quests ON users_quests.questid = quest.idquest AND users_quests.userid = ?
			WHERE
				gameid = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
	');
	$stmt->execute(array(APISecurity::userid(),$gameid));
	if($row = $stmt->fetch())
		$response['status']['completed'] = $row['cnt'];
} catch(PDOException $e) {
	APIHelpers::showerror(1099, $e->getMessage());
}

// calculate count of types
try {
	$stmt = $conn->prepare('
			SELECT
				quest.subject,
				count(quest.idquest) as cnt
			FROM
				quest
			WHERE
				gameid = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
			GROUP BY
				quest.subject
	');
	$stmt->execute(array($gameid));
	while($row = $stmt->fetch())
	{
		$response['subjects'][$row['subject']] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1100, $e->getMessage());
}

/*$userid = APIHelpers::getParam('userid', 0);*/
$params = array(APISecurity::userid(), $gameid);

// filter by status
$arrWhere_status = array();

if ($response['filter']['open'])
	$arrWhere_status[] = '(isnull(users_quests.dt_passed))';

if ($response['filter']['completed'])
	$arrWhere_status[] = '(not isnull(users_quests.dt_passed))';

$where_status = '';

if (count($arrWhere_status) > 0)
	$where_status = ' AND ('.implode(' OR ', $arrWhere_status).')';

// filter by subjects
$filter_subjects = APIHelpers::getParam('subjects', '');
$filter_subjects = explode(',', $filter_subjects);
$arrWhere_subjects = array();
foreach ($filter_subjects as $k){
	if (strlen($k) > 0) {
		$arrWhere_subjects[] = 'quest.subject = ?';
		$params[] = $k;
	}
}
		
if (count($arrWhere_subjects) > 0)
	$where_status .= ' AND ('.implode(' OR ', $arrWhere_subjects).')';

$filter_name_contains = APIHelpers::getParam('name_contains', '');
$params[] = '%'.$filter_name_contains.'%';
$where_status .= ' AND quest.name LIKE ? ';

$query = '
			SELECT 
				quest.idquest,
				quest.name,
				quest.score,
				quest.subject,
				quest.state,
				quest.gameid,
				quest.author,
				quest.text,
				quest.count_user_solved,
				users_quests.dt_passed
			FROM 
				quest
			LEFT JOIN 
				users_quests ON users_quests.questid = quest.idquest AND users_quests.userid = ?
			WHERE
				quest.gameid = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
				'.$where_status.'
			ORDER BY
				quest.subject, quest.score ASC, quest.score
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	while($row = $stmt->fetch())
	{
		$status = '';
		
		if ($row['dt_passed'] == null)
			$status = 'open';
		else
			$status = 'completed';

		$response['data'][] = array(
			'questid' => $row['idquest'],
			'score' => $row['score'],
			'name' => $row['name'],
			'text' => $row['text'],
			'author' => $row['author'],
			'gameid' => $row['gameid'],
			'subject' => $row['subject'],
			'dt_passed' => $row['dt_passed'],
			'state' => $row['state'],
			'solved' => $row['count_user_solved'],
			'status' => $status,
		);
	}
	$response['result'] = 'ok';
	$response['permissions']['insert'] = APISecurity::isAdmin();
	
} catch(PDOException $e) {
	APIHelpers::showerror(1101, $e->getMessage());
}

APIHelpers::endpage($response);

