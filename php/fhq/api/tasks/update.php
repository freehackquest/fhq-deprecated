<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$message = '';

if (!FHQGame::checkGameDates($message))
	FHQHelpers::showerror(350, $message);

if (!FHQSecurity::isAdmin())
	FHQHelpers::showerror(351, 'Access denied. You are not admin.');

if (!FHQHelpers::issetParam('questid'))
	FHQHelpers::showerror(987, 'Not found parameter "questid"');

$questid = FHQHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	FHQHelpers::showerror(988, 'parameter "questid" must be numeric');

$params = array(
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
	if (!FHQHelpers::issetParam($key))
		FHQHelpers::showerror(352, 'Not found parameter "'.$key.'"');
	$params[$key] = FHQHelpers::getParam($key, '');
}

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
$params['id_game'] = FHQGame::id();
$params['idauthor'] = intval($params['idauthor']);
$params['author'] = base64_encode($params['author']);
// $params['state'] = $params['state'];
// $params['description_state'] = $params['description_state'];
// $params['subject'] = $params['subject'];
// $params['quest_uuid'] = $params['quest_uuid'];
$params['gameid'] = FHQGame::id();
$params['userid'] = FHQSecurity::userid();

$conn = FHQHelpers::createConnection($config);
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
	} else {
		$result['error']['pdo'] = $conn->errorInfo();
		$result['error']['code'] = 304;
		$result['error']['message'] = 'Could not insert';
	}
// } catch(PDOException $e) {
//	FHQHelpers::showerror(747,$e->getMessage());
//}

echo json_encode($result);
