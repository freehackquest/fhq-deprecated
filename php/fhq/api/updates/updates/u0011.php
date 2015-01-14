<?php

$updates['u0011'] = array(
	'to_version' => 'u0012',
	'name' => 'added table rules',
	'description' => 'added table rules',
);

function update_u0011($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `rules` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `userid` int(11) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `text` varchar(4048) NOT NULL,
		  `created_date` datetime NOT NULL,
		  `updated_date` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
	')->execute();
	return true;
}
