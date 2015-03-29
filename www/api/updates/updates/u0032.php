<?php

$updates['u0032'] = array(
	'to_version' => 'u0033',
	'name' => 'migrate data and removed answer_copy',
	'description' => 'migrate data and removed answer_copy',
);

function update_u0032($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM quest
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$answer = base64_decode($row['answer']);
		$author = base64_decode($row['author']);
		
		$id = $row['idquest'];

		$stmt_update = $conn->prepare('
			UPDATE quest SET answer = ?, author = ? WHERE idquest = ?
		');
		if ($stmt_update->execute(array($answer, $author, $id)) != 1) return false;
		$conn->prepare('ALTER TABLE quest DROP COLUMN `answer_copy`')->execute();
	}
	return true;
}
