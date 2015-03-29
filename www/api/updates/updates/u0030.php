<?php

$updates['u0030'] = array(
	'to_version' => 'u0031',
	'name' => 'migrate data and removed name',
	'description' => 'migrate data and removed name',
);

function update_u0030($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM quest
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$name = base64_decode($row['name']);
		$id = $row['idquest'];

		$stmt_update = $conn->prepare('
			UPDATE quest SET name = ? WHERE idquest = ?
		');
		if ($stmt_update->execute(array($name, $id)) != 1) return false;
	}
	return true;
}
