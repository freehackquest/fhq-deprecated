<?php

$updates['u0046'] = array(
	'to_version' => 'u0047',
	'name' => 'added table users',
	'description' => 'added table users',
);

function update_u0046($conn) {
	
	$stmt_delete = $conn->prepare('
			DELETE FROM users;
	');

	$stmt_delete->execute();
	
	$stmt = $conn->prepare('
			SELECT * FROM user_old
	');
	if ($stmt->execute() != 1) return false;
	
	$stmt_insert = $conn->prepare('
			INSERT INTO users(id,uuid,email,pass,role,nick,logo,dt_create,dt_last_login,last_ip,status)
				VALUES(?,?,?,?,?,?,?,?,?,?,?)
	');

	while ($row = $stmt->fetch()) {
		$params = array(
				$row['iduser'],
				$row['uuid_user'],
				$row['email'],
				$row['pass'],
				$row['role'],
				$row['nick'],
				$row['logo'],
				$row['date_create'] == null ? '0000-00-00 00:00:00' : $row['date_create'],
				$row['date_last_signup'] == null ? '0000-00-00 00:00:00' : $row['date_last_signup'],
				$row['last_ip'] == null ? '0.0.0.0' : $row['last_ip'],
				$row['status'] == '' ? 'activated' : $row['status'],
			);
		// print_r($conn->errorInfo());
		// print_r($params);
		
		if ($stmt_insert->execute($params) != 1) return false;
	}
	
	return true;
}
