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
					INNER JOIN user ON user.iduser = userquest.iduser
					WHERE
						idquest = ? AND stopdate <> \'0000-00-00 00:00:00\'
						AND user.role = "user"
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
}
