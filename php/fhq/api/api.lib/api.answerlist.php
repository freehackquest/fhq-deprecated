<?

class FHQAnswerList {

	static function addTryAnswer($conn, $questid, $user_answer, $real_answer,  $passed) {
		$answer_try = base64_encode(htmlspecialchars($user_answer));
		$answer_real = base64_encode(htmlspecialchars($real_answer));
		$query = 'INSERT INTO tryanswer(iduser, idquest, answer_try, answer_real, passed, datetime_try) VALUES (?, ?, ?, ?, ?, NOW());';
		$params[] = FHQSecurity::userid();
		$params[] = intval($questid);
		$params[] = $answer_try;
		$params[] = $answer_real;
		$params[] = $passed;
		$stmt = $conn->prepare($query);
		$stmt->execute($params);
		return true;
	}
	
	static function movedToBackup($conn, $questid) {
		$query = 'INSERT INTO tryanswer_backup (iduser, idquest, answer_try, answer_real, passed, datetime_try) 
				SELECT iduser, idquest, answer_try, answer_real, passed, datetime_try FROM tryanswer WHERE iduser = ? and idquest = ?';
		$params[] = FHQSecurity::userid();
		$params[] = intval($questid);
		$stmt = $conn->prepare($query);
		if ($stmt->execute($params)) {
			$query1 = 'DELETE FROM tryanswer WHERE iduser = ? and idquest = ?';
			$stmt1 = $conn->prepare($query1);
			$stmt1->execute($params);
			return true;
		}
		return false;
	}
}
