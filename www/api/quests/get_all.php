<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Get Quest All Info (admin's method)
 * API_DESCRIPTION: Method will be returned all quest info.
 * API_ACCESS: only admin
 * API_INPUT: questid - integer, Identificator of the quest
 * API_INPUT: token - string, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1080, 'Access denied');

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(1081, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(1082, 'parameter "questid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = APIHelpers::createConnection($config);

$result['gameid'] = APIGame::id(); 
$result['userid'] = APISecurity::userid();

$params[] = APIGame::id();
$params[] = intval($questid);

$query = '
			SELECT 
				*
			FROM 
				quest
			WHERE
				quest.gameid = ?
				AND quest.idquest = ?
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	if($row = $stmt->fetch())
	{
		$status = '';
		$result['data'] = array(
			'questid' => $row['idquest'],
			'subject' => $row['subject'],
			'name' => $row['name'],
			'text' => $row['text'],
			'score' => $row['score'],
			'min_score' => $row['min_score'],
			'for_person' => $row['for_person'],
			'authorid' => $row['idauthor'],
			'author' => $row['author'],
			'answer' => $row['answer'],
			'state' => $row['state'],
			'description_state' => $row['description_state'],
		);
		$result['quest'] = $row['idquest'];

		if (isset($_SESSION['game']))
			$result['data']['game_title'] = $_SESSION['game']['title'];
	}
	$result['result'] = 'ok';
	
} catch(PDOException $e) {
	APIHelpers::showerror(1083, $e->getMessage());
}

try {
	$stmt = $conn->prepare('SELECT * FROM quests_files WHERE questid = ?');
	$stmt->execute(array(intval($questid)));
	$result['data']['files'] = array();
	while($row = $stmt->fetch())
		$result['data']['files'][] = array(
			'id' => $row['id'],
			'size' => $row['size'],
			'filename' => $row['filename'],
			'filepath' => $row['filepath'],
		);
	$result['result'] = 'ok';
	
} catch(PDOException $e) {
	APIHelpers::showerror(1314, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
