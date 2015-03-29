<?php

$updates['u0029'] = array(
	'to_version' => 'u0030',
	'name' => 'migrate data and removed id_game, tema',
	'description' => 'migrate data and removed id_game, tema',
);

function update_u0029($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM quest
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$subject = base64_decode($row['tema']);
		$id = $row['idquest'];

		$stmt_update = $conn->prepare('
			UPDATE quest SET gameid = id_game, subject = ? WHERE idquest = ?
		');
		if ($stmt_update->execute(array($subject, $id)) != 1) return false;
	}
	$conn->prepare('ALTER TABLE quest DROP COLUMN `id_game`')->execute();
	$conn->prepare('ALTER TABLE quest DROP COLUMN `tema`')->execute();
	
	return true;
}
