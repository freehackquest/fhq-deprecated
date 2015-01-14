<?php

$updates['u0012'] = array(
	'to_version' => 'u0013',
	'name' => 'added table games_rules',
	'description' => 'added table games_rules',
);

function update_u0012($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `games_rules` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `gameid` int(11) NOT NULL,
		  `ruleid` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
	')->execute();
	return true;
}
