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
include_once ($curdir."/../../../api.lib/api.helpers.php");

$response = APIHelpers::startpage();

if(!APIHelpers::is_json_input()){
	APIHelpers::error(400, "Expected application/json");
}
$conn = APIHelpers::createConnection();
$request = APIHelpers::read_json_input();

$columns = array();
$filter_conditions = array();
$filter_values = array(); 

$filter_values[] = APISecurity::userid();

if(isset($request['subject'])){
	$filter_conditions[] = 'quest.subject = ?';
	$filter_values[] = $request['subject'];
}

if(!APISecurity::isAdmin()){
	$filter_conditions[] = 'quest.state = ?';
	$filter_values[] = "open";
}

if(isset($request['name_contains'])){
	$filter_conditions[] = 'quest.name LIKE ?';
	$filter_values[] = '%'.$request['name_contains'].'%';
}

$columns[] = "quest.idquest";
$columns[] = "quest.name";
$columns[] = "quest.score";
$columns[] = "quest.subject";
$columns[] = "quest.gameid";
$columns[] = "quest.count_user_solved";
$columns[] = "users_quests.dt_passed";

if(isset($request['details'])){
	$columns[] = "quest.state";
	$columns[] = "quest.author";
	$columns[] = "quest.text";
}

$stmt = $conn->prepare('
	SELECT 
		'.implode(', ', $columns).'
	FROM
		quest
	LEFT JOIN 
		users_quests ON users_quests.questid = quest.idquest AND users_quests.userid = ?
	WHERE
		'.implode(' AND ', $filter_conditions).'
	ORDER BY
		quest.subject, quest.score ASC, quest.score
');

$stmt->execute($filter_values);

while($row = $stmt->fetch()){
	if ($row['dt_passed'] == null)
		$status = 'open';
	else
		$status = 'completed';

	$questinfo = array(
		'questid' => $row['idquest'],
		'score' => $row['score'],
		'name' => $row['name'],
		'gameid' => $row['gameid'],
		'subject' => $row['subject'],
		'dt_passed' => $row['dt_passed'],
		'solved' => $row['count_user_solved'],
		'status' => $status,
	);
	
	if(isset($request['details'])){
		$questinfo['author'] = $row['author'];
		$questinfo['state'] = $row['state'];
		$questinfo['text'] = $row['text'];
	}

	$response['data'][] = $questinfo;
}

$response['result'] = 'ok';

APIHelpers::endpage($response);
