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

$curdir = dirname(__FILE__);
include_once ($curdir."/../../api.lib/api.base.php");
include_once ($curdir."/../../api.lib/api.game.php");
include_once ($curdir."/../../api.lib/api.quest.php");
include_once ($curdir."/../../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::error(403, $message);

if (!APISecurity::isAdmin())
	APIHelpers::error(403, 'Access denied. You are not admin.');

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
		APIHelpers::error(400, 'Not found parameter "'.$key.'"');
	$params[$key] = APIHelpers::getParam($key, '');
}

$questname = $params['name'];

$params['answer_upper_md5'] = md5(strtoupper($params['answer']));
$params['score'] = intval($params['score']);
$params['min_score'] = intval($params['min_score']);
$params['gameid'] = APIGame::id();
$params['idauthor'] = intval($params['idauthor']);
$params['author'] = $params['author'];
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

try {
	$stmt = $conn->prepare($query);
	if($stmt->execute(array_values($params))) {
		$response['data']['quest']['id'] = $conn->lastInsertId();
		$response['result'] = 'ok';
		APIQuest::updateCountUserSolved($conn, $response['data']['quest']['id']);

		// to public evants
		if ($params['state'] == 'open') {
			APIEvents::addPublicEvents($conn, "quests", "New quest #".$response['data']['quest']['id']." ".$questname." (subject: ".$params['subject'].")");
		}
	} else {
		APIHelpers::error(500,'Could not insert. PDO: '.$conn->errorInfo());
	}
} catch(PDOException $e) {
	APIHelpers::error(500,$e->getMessage());
}

APIQuest::updateMaxGameScore($conn, APIGame::id());

APIHelpers::endpage($response);
