<?php

$updates['u0022'] = array(
	'to_version' => 'u0023',
	'name' => 'moved news to public events',
	'description' => 'moved news to public events',
);

function update_u0022($conn) {
	$stmt = $conn->prepare('
			SELECT * FROM news
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$message = base64_decode($row['text']);
		$type = "info";
		$dt = $row['datetime_'];
		$stmt_insert = $conn->prepare('
			INSERT INTO public_events(type, message, dt) VALUES(?,?,?)
		');
		if ($stmt_insert->execute(array($type, $message, $dt)) != 1) return false;
	}
	return true;
}
