<?php

$updates['u0007'] = array(
	'to_version' => 'u0008',
	'name' => 'added table users_skills',
	'description' => 'update column status in user',
);

function update_u0007($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `users_skills` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `userid` int(11) NOT NULL,
		  `skill` varchar(255) NOT NULL,
		  `value` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;	
	')->execute();
	return true;
}
