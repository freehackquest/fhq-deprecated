<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Quest Update
 * API_DESCRIPTION: Method will be update quest info
 * API_ACCESS: admin only
 * API_INPUT: questid - integer, Identificator of the quest
 * API_INPUT: name - string, name of the quest
 * API_INPUT: text - string, description of the quest
 * API_INPUT: score - string, how much +score for user after solve quest by user
 * API_INPUT: min_score - string, condition for opened quest for user
 * API_INPUT: subject - string, subject msut be one from types (look types)
 * API_INPUT: idauthor - integer, will be depricated
 * API_INPUT: author - string, who make this quest
 * API_INPUT: answer - string, answer for this quest
 * API_INPUT: state - string, state of the quest, can be: open, broken, closed
 * API_INPUT: description_state - string, you can add some descriptions for quest state
 * API_INPUT: token - string, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.quest.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

$result = APIHelpers::startPage($config);

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::error(403, 'Access denied. You are not admin.');

if (!APIHelpers::issetParam('questid'))
	APIHelpers::error(400, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::error(400, 'parameter "questid" must be numeric');

$params = array(
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
$params['idauthor'] = intval($params['idauthor']);
// $params['state'] = $params['state'];
// $params['description_state'] = $params['description_state'];
// $params['quest_uuid'] = $params['quest_uuid'];
$params['userid'] = APISecurity::userid();

$conn = APIHelpers::createConnection($config);
$values_q = array();

foreach ( $params as $k => $v) {
  $values_q[] = $k.' = ?';
}

$query = 'UPDATE quest SET '.implode(', ', $values_q).', date_change = NOW() WHERE idquest = ?';

$values = array_values($params);
$values[] = $questid;

// echo $query;

// try {
	$stmt = $conn->prepare($query);
	if($stmt->execute(array_values($values))) {
		$result['result'] = 'ok';
		APIQuest::updateCountUserSolved($conn, $questid);

		// add to public events
		if ($params['state'] == 'open') {
			APIEvents::addPublicEvents($conn, "quests", "Updated quest #".$questid." ".$questname.' (subject: '.$params['subject'].')');
		}
	} else {
		$result['error']['pdo'] = $conn->errorInfo();
		$result['error']['code'] = 304;
		$result['error']['message'] = 'Could not insert';
	}
// } catch(PDOException $e) {
//	APIHelpers::error(500,$e->getMessage());
//}

$stmt = $conn->prepare('SELECT gameid FROM quest WHERE idquest = ?');
$stmt->execute(array($questid);
if($row = $stmt->fetch()){
	APIQuest::updateMaxGameScore($conn, $row['gameid']);	
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
