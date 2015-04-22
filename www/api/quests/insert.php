<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Quest Insert
 * API_DESCRIPTION: Method will be add quest to the system
 * API_ACCESS: admin only
 * API_INPUT: quest_uuid - uuid, Global Identificator of the quest
 * API_INPUT: name - string, name of the quest
 * API_INPUT: text - string, description of the quest
 * API_INPUT: score - integer, how much +score for user after solve quest by user
 * API_INPUT: min_score - integer, condition for opened quest for user
 * API_INPUT: subject - string, subject msut be one from types (look types)
 * API_INPUT: idauthor - integer, will be depricated
 * API_INPUT: author - string, who make this quest
 * API_INPUT: answer - string, answer for this quest
 * API_INPUT: state - enum, state of the quest, can be: open, broken, closed
 * API_INPUT: description_state - string, you can add some descriptions for quest state
 * API_INPUT: token - string, token
 */

$curdir_quests_insert = dirname(__FILE__);
include_once ($curdir_quests_insert."/../api.lib/api.base.php");
include_once ($curdir_quests_insert."/../api.lib/api.game.php");
include_once ($curdir_quests_insert."/../api.lib/api.quest.php");
include_once ($curdir_quests_insert."/../../config/config.php");
include_once ($curdir_quests_insert."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(1164, $message);

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1165, 'Access denied. You are not admin.');

$params = array(
	'quest_uuid' => '',
	'name' => '',
	'text' => '',
	'score' => '',
	'min_score' => '',
	'subject' => '',
	'idauthor' => '',
	'author' => '',
	'answer' => '',
	'state' => '',
	'description_state' => '',
);

foreach( $params as $key => $val ) {
	if (!APIHelpers::issetParam($key))
		APIHelpers::showerror(1166, 'Not found parameter "'.$key.'"');
	$params[$key] = APIHelpers::getParam($key, '');
}

$questname = $params['name'];

$params['answer_upper_md5'] = md5(strtoupper($params['answer']));
$params['score'] = intval($params['score']);
$params['min_score'] = intval($params['min_score']);
$params['for_person'] = 0;
$params['gameid'] = APIGame::id();
$params['idauthor'] = intval($params['idauthor']);
$params['author'] = $params['author'];
// $params['state'] = $params['state'];
// $params['description_state'] = $params['description_state'];
// $params['subject'] = $params['subject'];
// $params['quest_uuid'] = $params['quest_uuid'];
$params['gameid'] = APIGame::id();
$params['userid'] = APISecurity::userid();
$params['count_user_solved'] = 0;

$conn = APIHelpers::createConnection($config);
$values_q = array();

foreach ( $params as $k => $v) {
  $values_q[] = '?';
}

$query = 'INSERT INTO quest('.implode(', ', array_keys($params)).', date_change, date_create) 
  VALUES('.implode(', ', $values_q).', NOW(), NOW());';

// echo $query;

// $result['params'] = $params;
// $result['query'] = $query;

try {
	$stmt = $conn->prepare($query);
	if($stmt->execute(array_values($params))) {
		$result['data']['quest']['id'] = $conn->lastInsertId();
		$result['result'] = 'ok';
		APIQuest::updateCountUserSolved($conn, $result['data']['quest']['id']);
		
		// to public evants
		if ($params['state'] == 'open') {
			APIEvents::addPublicEvents($conn, "quests", "New quest #".$result['data']['quest']['id']." ".$questname." (subject: ".$params['subject'].")");
		}
	} else {
		APIHelpers::showerror(1168,'Could not insert. PDO: '.$conn->errorInfo());
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1167,$e->getMessage());
}

include_once ($curdir_quests_insert."/../api.lib/savetoken.php");
echo json_encode($result);
