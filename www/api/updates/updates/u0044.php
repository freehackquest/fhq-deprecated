<?php

$updates['u0044'] = array(
	'to_version' => 'u0045',
	'name' => 'added table users',
	'description' => 'added table users',
);

function update_u0044($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `users` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`uuid` varchar(255) NOT NULL,
			`email` varchar(255) NOT NULL,
			`pass` varchar(255) NOT NULL,
			`role` varchar(255) NOT NULL,
			`nick` varchar(255) NOT NULL,
			`logo` varchar(255) NOT NULL,
			`dt_create` datetime NOT NULL,
			`dt_last_login` datetime NOT NULL,
			`last_ip` varchar(255) NOT NULL,
			`status` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
	')->execute();
	return true;
}
