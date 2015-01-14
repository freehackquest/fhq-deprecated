<?php

$updates['u0008'] = array(
	'to_version' => 'u0009',
	'name' => 'added table users_achievements',
	'description' => 'added table users_achievements',
);

function update_u0008($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `users_achievements` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `userid` int(11) NOT NULL,
		  `type` varchar(255) NOT NULL,
		  `text` varchar(1024) NOT NULL,
		  `receipt_date` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
	')->execute();
	return true;
}
