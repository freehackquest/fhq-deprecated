<?php

$updates['u0038'] = array(
	'to_version' => 'u0039',
	'name' => 'removed user.ipserver and user.username',
	'description' => 'removed user.ipserver and user.username',
);

function update_u0038($conn) {
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `ipserver`;')->execute();
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `username`;')->execute();
	return true;
}
