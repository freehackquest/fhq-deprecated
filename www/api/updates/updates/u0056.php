<?php

$updates['u0056'] = array(
	'to_version' => 'u0057',
	'name' => 'added table users_token_invalid',
	'description' => 'added table users_token_invalid',
);

function update_u0056($conn) {
	$conn->prepare('
		CREATE TABLE IF NOT EXISTS `users_tokens_invalid` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `userid` int(11) NOT NULL,
		  `token` varchar(255) NOT NULL,
		  `status` varchar(255) NOT NULL,
		  `data` varchar(4048) NOT NULL,
		  `start_date` datetime NOT NULL,
		  `end_date` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	')->execute();
	return true;
}
