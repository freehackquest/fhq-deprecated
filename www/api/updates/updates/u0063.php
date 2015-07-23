<?php

$updates['u0063'] = array(
	'to_version' => 'u0064',
	'name' => 'remove personal quests which passed',
	'description' => 'remove personal quests which passed',
);

function update_u0063($conn) {
	$stmt_users_quests = $conn->prepare('SELECT idquest
		FROM quest
		LEFT JOIN users_quests ON users_quests.questid = quest.idquest
		WHERE for_person <> 0'
	);
	$stmt_users_quests->execute();
	while ($row = $stmt_users_quests->fetch()) {
		$questid = intval($row['idquest']);
		$conn->prepare('DELETE FROM quest WHERE idquest = ?')->execute(array($questid));
	}
	return true;
}
