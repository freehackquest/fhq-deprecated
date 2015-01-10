<?php

class FHQQuest {
	static function updateCountUserSolved($conn, $questid) {
		$query = 'UPDATE quest SET count_user_solved = (SELECT COUNT(*) FROM userquest WHERE idquest = ? AND stopdate <> \'0000-00-00 00:00:00\') WHERE idquest = ?;';
		$params[] = intval($questid);
		$params[] = intval($questid);
		$stmt = $conn->prepare($query);
		$stmt->execute($params);
	}
}
