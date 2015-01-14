<?php

$updates['u0005'] = array(
	'to_version' => 'u0006',
	'name' => 'update table user',
	'description' => 'added column status to user',
);

function update_u0005($conn) {
	$conn->prepare('ALTER TABLE `user` ADD COLUMN `status` VARCHAR(50);')->execute();
	return true;
}
