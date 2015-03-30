<?php

$updates['u0035'] = array(
	'to_version' => 'u0036',
	'name' => 'added table quests_files',
	'description' => 'added table quests_files',
);

function update_u0035($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `quests_files` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`uuid` varchar(255) NOT NULL,
			`questid` int(11) NOT NULL,
			`filename` varchar(255) NOT NULL,
			`size` int(11) NOT NULL,
			`dt` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
	')->execute();
	return true;
}
