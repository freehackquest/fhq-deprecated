<?php

$updates['u0023'] = array(
	'to_version' => 'u0024',
	'name' => 'remove table news',
	'description' => 'remove table news',
);

function update_u0023($conn) {
	$stmt = $conn->prepare('
			DROP TABLE news;
	');
	return ($stmt->execute() != 1);
}
