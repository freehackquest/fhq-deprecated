<?php

$updates['u0026'] = array(
	'to_version' => 'u0027',
	'name' => 'new table emails',
	'description' => 'new table emails',
);

function update_u0026($conn) {
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `activation_code`;')->execute();
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `date_activated`;')->execute();
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `rating`;')->execute();
	return true;
}
