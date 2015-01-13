<?php

$updates['0_0_0_5'] = array(
	'to_version' => '0_0_0_6',
	'name' => 'update table user',
	'description' => 'added column status to user',
);

function update_0_0_0_5($conn) {
	$conn->prepare('ALTER TABLE `user` ADD COLUMN `status` VARCHAR(50);')->execute();
	return true;
}
