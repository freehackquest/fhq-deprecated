<?php

$updates['u0009'] = array(
	'to_version' => 'u0010',
	'name' => 'added column team',
	'description' => 'added column team',
);

function update_u0009($conn) {
	$conn->prepare('ALTER TABLE `user` ADD COLUMN `team` INT(0) DEFAULT 0;')->execute();
	$conn->prepare('ALTER TABLE `users` ADD COLUMN `team` INT(0) DEFAULT 0;')->execute();
	return true;
}
