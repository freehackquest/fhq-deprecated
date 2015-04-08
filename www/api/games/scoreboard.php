<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

// APIHelpers::checkAuth();

$message = '';

$gameid = APIHelpers::getParam('gameid', 0);

if (!APIHelpers::issetParam('gameid'))
	$gameid = APIGame::id();

if (!is_numeric($gameid))
	APIHelpers::showerror(1088, 'parameter "gameid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = APIHelpers::createConnection($config);

$result['gameid'] = $gameid;

$params[] = intval($gameid);

$filter_by_role = APISecurity::isAdmin() == false ? ' AND u.role = "user" ' : '';

$query = '
			SELECT 
				u.nick,
				u.role,
				u.logo,
				ug.userid,
				ug.score
			FROM 
				users_games ug
			LEFT JOIN 
				users u ON u.id = ug.userid
			WHERE
				ug.gameid = ?
				'.$filter_by_role.'
			ORDER BY
				ug.score DESC
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	$i = 1;
	$score = 0;
	$result['data'] = array();
	
	while($row = $stmt->fetch())
	{
		$user_score = $row['score'];
		if ($i == 1 && $score == 0)
			$score = $row['score'];

		if ($score != $user_score)
		{
			$score = $user_score;
			$i++;
		}
		
		$result['data'][$i][] = array(
			'userid' => $row['userid'],
			'nick' => htmlspecialchars($row['nick']),
			'logo' => $row['logo'],
			'score' => $row['score'],
			// 'role' => $row['role'],
		);
	}
	
} catch(PDOException $e) {
	APIHelpers::showerror(1089, $e->getMessage());
}

echo json_encode($result);
