<?php

$updates['u0020'] = array(
	'to_version' => 'u0021',
	'name' => 'generate user uuids',
	'description' => 'generate user uuids',
);

function update_u0020($conn) {
	$stmt = $conn->prepare('
			SELECT iduser FROM user WHERE ISNULL(uuid_user)
	');
	if ($stmt->execute() != 1) return false;
	while ($row = $stmt->fetch()) {
		$id = $row['iduser'];
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12);	
		$stmt_update = $conn->prepare('
			UPDATE user SET uuid_user = ? WHERE iduser = ?
		');
		if ($stmt_update->execute(array($uuid, $id)) != 1) return false;
	}
	return true;
}
