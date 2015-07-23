<?php

class APIQuest {
	static function updateCountUserSolved($conn, $questid) {
		$query = '
			UPDATE
				quest q
			SET
				count_user_solved = (
					SELECT
						COUNT(*)
					FROM
						users_quests
					INNER JOIN users ON users.id = users_quests.userid
					WHERE
						questid = ?
						AND users.role = "user"
				)
			WHERE
				q.idquest = ?;';
		$params[] = intval($questid);
		$params[] = intval($questid);
		$stmt = $conn->prepare($query);
		$stmt->execute($params);

/*		$query = 'SELECT count_user_solved FROM quest WHERE idquest = ?;';
		$stmt = $conn->prepare($query);
		$stmt->execute($params);*/
	}

	static function updateMaxGameScore($conn, $gameid) {
		$query = '
			UPDATE
				games
			SET
				maxscore = (
					SELECT
						SUM(score)
					FROM
						quest
					WHERE
						state = "open"
						AND gameid = ?
				)
			WHERE
				id = ?;';
		$params[] = intval($gameid);
		$params[] = intval($gameid);
		$stmt = $conn->prepare($query);
		$stmt->execute($params);
	}
}
