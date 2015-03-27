<?php
header("Access-Control-Allow-Origin: *");

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
	APIHelpers::showerror(350, $message);

if (!APISecurity::isAdmin())
	APIHelpers::showerror(351, 'Access denied. You are not admin.');

$params = array(
	'quest_uuid' => '',
	'name' => '',
	'short_text' => '',
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
$params['short_text_copy'] = $params['short_text'];
$params['short_text'] = base64_encode($params['short_text']);
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
		$result['error']['pdo'] = $conn->errorInfo();
		$result['error']['code'] = 304;
		$result['error']['message'] = 'Could not insert';
	}
} catch(PDOException $e) {
	APIHelpers::showerror(747,$e->getMessage());
}

include_once ($curdir_quests_insert."/../api.lib/savetoken.php");
echo json_encode($result);
