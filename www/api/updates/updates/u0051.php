<?php

$updates['u0051'] = array(
	'to_version' => 'u0052',
	'name' => 'calculate levenshtein distance',
	'description' => 'calculate levenshtein distance',
);

function update_u0051($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM tryanswer
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$id = $row['id'];
		$answer_try = $row['answer_try'];
		$answer_real = $row['answer_real'];
		$levenshtein = levenshtein(strtoupper($answer_real), strtoupper($answer_try));

		$stmt_update = $conn->prepare('
			UPDATE tryanswer SET levenshtein = ? WHERE id = ?
		');
		if ($stmt_update->execute(array($levenshtein, $id)) != 1) return false;
	}
	return true;
}
