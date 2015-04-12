<?php

$updates['u0025'] = array(
	'to_version' => 'u0026',
	'name' => 'new table email_delivery',
	'description' => 'new table email_delivery',
);

function update_u0025($conn) {
	$stmt = $conn->prepare('		
		CREATE TABLE IF NOT EXISTS `email_delivery` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `to_email` varchar(255) NOT NULL,
		  `subject` varchar(255) NOT NULL,
		  `message` text NOT NULL,
		  `priority` varchar(255) NOT NULL,
		  `status` varchar(255) NOT NULL,
		  `dt` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
	');
	$stmt->execute();
	return true;
}
