<?php

$updates['u0052'] = array(
	'to_version' => 'u0053',
	'name' => 'calculate levenshtein distance (backup)',
	'description' => 'calculate levenshtein distance (backup)',
);

function update_u0052($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM tryanswer_backup
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$id = $row['id'];
		$answer_try = $row['answer_try'];
		$answer_real = $row['answer_real'];
		$levenshtein = levenshtein(strtoupper($answer_real), strtoupper($answer_try));

		$stmt_update = $conn->prepare('
			UPDATE tryanswer_backup SET levenshtein = ? WHERE id = ?
		');
		if ($stmt_update->execute(array($levenshtein, $id)) != 1) return false;
	}
	return true;
}
