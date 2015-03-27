<?php

$updates['u0021'] = array(
	'to_version' => 'u0022',
	'name' => 'new table public_events',
	'description' => 'new table public_events',
);

function update_u0021($conn) {
	$stmt = $conn->prepare('		
		CREATE TABLE IF NOT EXISTS `public_events` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `type` varchar(255) NOT NULL,
		  `message` TEXT NOT NULL,
		  `dt` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
	');
	$stmt->execute();
	return true;
}
