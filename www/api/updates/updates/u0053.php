<?php

$updates['u0053'] = array(
	'to_version' => 'u0054',
	'name' => 'recalculate user scoreboards',
	'description' => 'recalculate user scoreboards',
);

function update_u0053($conn) {
	$stmt = $conn->prepare('SELECT games.id as gameid, users.id as userid FROM users, games;');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		
		$gameid = intval($row['gameid']);
		$userid = intval($row['userid']);

		$score = 0;
		$stmt_score = $conn->prepare('
			SELECT 
				ifnull(SUM(quest.score),0) as sum_score 
			FROM 
				userquest 
			INNER JOIN 
				quest ON quest.idquest = userquest.idquest AND quest.gameid = ?
			WHERE 
				(userquest.iduser = ?) 
				AND ( userquest.stopdate <> \'0000-00-00 00:00:00\' );
		');

		$stmt_score->execute(array($gameid, $userid));
		if($row_score = $stmt_score->fetch())
			$score = intval($row_score['sum_score']);
	
		if ($score != 0) {
			$user_score = 0;
			$row_exists = false;
			$stmt_users_games = $conn->prepare('select score from users_games where gameid = ? and userid = ?;');
			$stmt_users_games->execute(array($gameid, $userid));
			if($row_users_games = $stmt_users_games->fetch()) {
				$user_score = intval($row_users_games['score']);
				$row_exists = true;
			}

			if ($user_score != $score) {
				// echo "userid: $userid; gameid: $gameid; new user score: $score; old score: $user_score<br>";
				$query_update_or_insert = 'INSERT INTO users_games(score,date_change,userid,gameid) VALUES(?,NOW(),?,?)';
				if ($row_exists) {
					$query_update_or_insert = 'UPDATE users_games SET score = ?, date_change = NOW() WHERE userid = ? and gameid = ?';
				}
				$stmt_update_or_insert = $conn->prepare($query_update_or_insert);
				$stmt_update_or_insert->execute(array($score, $userid, $gameid));
				
			}
		}
	}
	return true;
}
