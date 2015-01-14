<?php

$updates['u0001'] = array(
	'to_version' => 'u0002',
	'name' => 'emails',
	'description' => 'unpack mails from user.username to user.email',
);

function update_u0001($conn) {
	$stmt = $conn->prepare('
			SELECT iduser, username FROM user
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$id = $row['iduser'];
		$username = $row['username'];
		$email = strtolower(base64_decode($username));
		$stmt_update = $conn->prepare('
			UPDATE user SET email = ? WHERE iduser = ?
		');
		if ($stmt_update->execute(array($email, $id)) != 1) return false;
	}
	return true;
}
