<?php

$updates['u0031'] = array(
	'to_version' => 'u0032',
	'name' => 'migrate data and removed text_copy',
	'description' => 'migrate data and removed text_copy',
);

function update_u0031($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM quest
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$text = base64_decode($row['text']);
		$id = $row['idquest'];

		$stmt_update = $conn->prepare('
			UPDATE quest SET text = ? WHERE idquest = ?
		');
		if ($stmt_update->execute(array($text, $id)) != 1) return false;
		$conn->prepare('ALTER TABLE quest DROP COLUMN `text_copy`')->execute();
	}
	return true;
}
