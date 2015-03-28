<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");
include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(986, 'Access denied');

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(987, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(988, 'parameter "questid" must be numeric');

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
				quest.id_game = ?
				AND quest.idquest = ?
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	if($row = $stmt->fetch())
	{
		$status = '';
		// $result['data'] = 
		/*$result['data']['subject'] = base64_decode($result['data']['tema']);
		$result['data']['tema'] = base64_decode($result['data']['tema']);*/
		
		$result['data'] = array(
			'questid' => $row['idquest'],
			'subject' => base64_decode($row['tema']),
			'name' => base64_decode($row['name']),
			'text' => base64_decode($row['text']),
			'score' => $row['score'],
			'min_score' => $row['min_score'],
			'for_person' => $row['for_person'],
			'authorid' => $row['idauthor'],
			'author' => base64_decode($row['author']),
			'answer' => base64_decode($row['answer']),
			'state' => $row['state'],
			'description_state' => $row['description_state'],
		);
		$result['quest'] = $row['idquest'];

		if (isset($_SESSION['game']))
			$result['data']['game_title'] = $_SESSION['game']['title'];
	}
	$result['result'] = 'ok';
	
} catch(PDOException $e) {
	APIHelpers::showerror(822, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
