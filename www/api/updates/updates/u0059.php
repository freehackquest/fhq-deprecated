<?php

$updates['u0059'] = array(
	'to_version' => 'u0060',
	'name' => 'moved data from userquest to users_quests',
	'description' => 'moved data from userquest to users_quests',
);

function update_u0059($conn) {

	$stmt_userquest = $conn->prepare('SELECT * FROM userquest');
	$stmt_userquest->execute();
	while ($row = $stmt_userquest->fetch()) {
		$userid = intval($row['iduser']);
		$questid = intval($row['idquest']);
		$stopdate = $row['stopdate'];
		
		if ($stopdate != '0000-00-00 00:00:00') {
			$stmt_users_quests = $conn->prepare('SELECT * FROM users_quests WHERE userid = ? AND questid = ?');
			$stmt_users_quests->execute(array($userid, $questid));
			if ($row_users_quests = $stmt_users_quests->fetch()) {
				// already exists
			} else {
				$conn->prepare('INSERT INTO users_quests(userid, questid, dt_passed) VALUES(?,?,?)')->execute(array($userid, $questid, $stopdate));
			}
		}
	}
	return true;
}
