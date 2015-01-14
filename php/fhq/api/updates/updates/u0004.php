<?php

$updates['u0004'] = array(
	'to_version' => 'u0005',
	'name' => 'update table users_ips',
	'description' => 'added column browser to users_ips',
);

function update_u0004($conn) {
	$conn->prepare('ALTER TABLE `users_ips` ADD COLUMN `browser` VARCHAR(1024);')->execute();
	return true;
}
