<?php

$updates['u0014'] = array(
	'to_version' => 'u0015',
	'name' => 'added columns',
	'description' => 'added column organizators, state and form to games',
);

function update_u0014($conn) {
	$conn->prepare('ALTER TABLE `games` ADD COLUMN `organizators` VARCHAR(255) DEFAULT "";')->execute();
	$conn->prepare('ALTER TABLE `games` ADD COLUMN `state` VARCHAR(255) DEFAULT "copy";')->execute();
	$conn->prepare('ALTER TABLE `games` ADD COLUMN `form` VARCHAR(255) DEFAULT "online";')->execute();
	return true;
}
