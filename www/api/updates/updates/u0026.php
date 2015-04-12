<?php

$updates['u0026'] = array(
	'to_version' => 'u0027',
	'name' => 'drop columns user.activation_code, date_activated, rating',
	'description' => 'drop columns user.activation_code, date_activated, rating',
);

function update_u0026($conn) {
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `activation_code`;')->execute();
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `date_activated`;')->execute();
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `rating`;')->execute();
	return true;
}
