<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$message = '';

if (!FHQSecurity::isAdmin())
	FHQHelpers::showerror(986, 'Access denied');

if (!FHQHelpers::issetParam('questid'))
	FHQHelpers::showerror(987, 'Not found parameter "questid"');

$questid = FHQHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	FHQHelpers::showerror(988, 'parameter "questid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = FHQHelpers::createConnection($config);

$result['gameid'] = FHQGame::id(); 
$result['userid'] = FHQSecurity::userid();

$params[] = FHQGame::id();
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
			'short_text' => base64_decode($row['short_text']),
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
	FHQHelpers::showerror(822, $e->getMessage());
}

echo json_encode($result);
