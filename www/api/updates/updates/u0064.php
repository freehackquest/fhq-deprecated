<?php

$updates['u0064'] = array(
	'to_version' => 'u0065',
	'name' => 'update quests uuid',
	'description' => 'update quests uuid',
);



function update_u0064($conn) {
	$stmt_users_quests = $conn->prepare('
		SELECT
			idquest
		FROM
			quest
		WHERE
			isnull(quest_uuid)
	');
	
	function local_gen_guid() {
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12);
		return $uuid;	
	}

	$stmt_users_quests->execute();
	while ($row = $stmt_users_quests->fetch()) {
		$questid = intval($row['idquest']);
		$uuid = local_gen_guid();
		
		$conn->prepare('UPDATE quest SET quest_uuid = ? WHERE idquest = ? AND isnull(quest_uuid)')->execute(array($uuid, $questid));
	}
	return true;
}
