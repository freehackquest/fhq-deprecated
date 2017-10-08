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
	}
}
