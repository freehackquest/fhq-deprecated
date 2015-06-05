<?php

$updates['u0054'] = array(
	'to_version' => 'u0055',
	'name' => 'added new table users_quests',
	'description' => 'added new table users_quests',
);

function update_u0054($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `users_quests` (
			`userid` varchar(255) NOT NULL,
			`questid` varchar(255) NOT NULL,
			`dt_passed` datetime NOT NULL,
		  UNIQUE KEY (`userid`,`questid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8	
	')->execute();
	return true;
}
