<?php

class APIQuest {
	static function updateCountUserSolved($conn, $questid) {
		$query = '
			UPDATE
				quest
			SET
				count_user_solved = (
					SELECT
						COUNT(*)
					FROM
						userquest
					INNER JOIN users ON users.id = userquest.iduser
					WHERE
						idquest = ? AND stopdate <> \'0000-00-00 00:00:00\'
						AND users.role = "user"
				)
			WHERE
				idquest = ?;';
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
						AND for_person = 0
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
