<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

// FHQHelpers::checkAuth();

$message = '';

$gameid = FHQHelpers::getParam('gameid', 0);

if (!FHQHelpers::issetParam('gameid'))
	$gameid = FHQGame::id();

if (!is_numeric($gameid))
	FHQHelpers::showerror(988, 'parameter "gameid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = FHQHelpers::createConnection($config);

$result['gameid'] = $gameid;

$params[] = intval($gameid);

$filter_by_role = FHQSecurity::isAdmin() == false ? ' AND user.role = "user" ' : '';

$query = '
			SELECT 
				user.iduser,
				user.nick,
				user.role,
				users_games.score
			FROM 
				users_games
			LEFT JOIN 
				user ON user.iduser = users_games.userid
			WHERE
				users_games.gameid = ?
				'.$filter_by_role.'
			ORDER BY
				users_games.score DESC
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
			'nick' => $row['nick'],
			'score' => $row['score'],
			// 'role' => $row['role'],
		);
	}
	
} catch(PDOException $e) {
	FHQHelpers::showerror(822, $e->getMessage());
}

echo json_encode($result);
