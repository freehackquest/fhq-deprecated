<?php

$updates['0_0_0_1'] = array(
	'to_version' => '0_0_0_2',
	'name' => 'emails',
	'description' => 'unpack mails from user.username to user.email',
);

function update_0_0_0_1($conn) {
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
