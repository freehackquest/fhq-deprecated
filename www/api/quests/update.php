<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../api.lib/api.quest.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(350, $message);

if (!APISecurity::isAdmin())
	APIHelpers::showerror(351, 'Access denied. You are not admin.');

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(987, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(988, 'parameter "questid" must be numeric');

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
		APIHelpers::showerror(352, 'Not found parameter "'.$key.'"');
	$params[$key] = APIHelpers::getParam($key, '');
}

$questname = $params['name'];

$params['tema'] = base64_encode($params['subject']);
$params['name'] = base64_encode($params['name']);
$params['text_copy'] = $params['text'];
$params['text'] = base64_encode($params['text']);
$params['answer_copy'] = $params['answer'];
$params['answer_upper_md5'] = md5(strtoupper($params['answer']));
$params['answer'] = base64_encode($params['answer']);
$params['score'] = intval($params['score']);
$params['min_score'] = intval($params['min_score']);
$params['for_person'] = 0;
$params['id_game'] = APIGame::id();
$params['idauthor'] = intval($params['idauthor']);
$params['author'] = base64_encode($params['author']);
// $params['state'] = $params['state'];
// $params['description_state'] = $params['description_state'];
// $params['subject'] = $params['subject'];
// $params['quest_uuid'] = $params['quest_uuid'];
$params['gameid'] = APIGame::id();
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
//	APIHelpers::showerror(747,$e->getMessage());
//}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
