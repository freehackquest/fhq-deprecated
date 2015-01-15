<?php

$updates['u0013'] = array(
	'to_version' => 'u0014',
	'name' => 'update logo for users',
	'description' => 'update logo for users',
);

function update_u0013($conn) {
	$stmt = $conn->prepare('UPDATE user SET logo = "files/users/0.png" WHERE ISNULL(logo) OR logo = "";');
	if ($stmt->execute() != 1) return false;
	return true;
}
