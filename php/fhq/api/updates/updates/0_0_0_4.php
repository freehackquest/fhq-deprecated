<?php

$updates['0_0_0_4'] = array(
	'to_version' => '0_0_0_5',
	'name' => 'update table users_ips',
	'description' => 'added column browser to users_ips',
);

function update_0_0_0_4($conn) {
	$conn->prepare('ALTER TABLE `users_ips` ADD COLUMN `browser` VARCHAR(1024);')->execute();
	return true;
}
