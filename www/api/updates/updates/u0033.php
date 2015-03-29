<?php

$updates['u0033'] = array(
	'to_version' => 'u0034',
	'name' => 'migrate data in tryanswer, tryanswer_backup',
	'description' => 'migrate data in tryanswer, tryanswer_backup',
);

function update_u0033($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM tryanswer
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$answer_try = base64_decode($row['answer_try']);
		$answer_real = base64_decode($row['answer_real']);

		$id = $row['id'];

		$stmt_update = $conn->prepare('
			UPDATE tryanswer SET answer_try = ?, answer_real = ? WHERE id = ?
		');
		if ($stmt_update->execute(array($answer_try, $answer_real, $id)) != 1) return false;
	}
	
	$stmt2 = $conn->prepare('
			SELECT * FROM tryanswer_backup
	');
	if ($stmt2->execute() != 1) return false;
	while ($row = $stmt2->fetch()) {
		$answer_try = base64_decode($row['answer_try']);
		$answer_real = base64_decode($row['answer_real']);
		$id = $row['id'];
		$stmt_update = $conn->prepare('
			UPDATE tryanswer_backup SET answer_try = ?, answer_real = ? WHERE id = ?
		');
		if ($stmt_update->execute(array($answer_try, $answer_real, $id)) != 1) return false;
	}	
	return true;
}
