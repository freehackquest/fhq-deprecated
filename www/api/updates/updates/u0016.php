<?php

$updates['u0016'] = array(
	'to_version' => 'u0017',
	'name' => 'updated table games',
	'description' => 'updated table games',
);

function update_u0016($conn) {
	$conn->prepare('ALTER TABLE `games` DROP COLUMN `json_data`;')->execute();
	$conn->prepare('ALTER TABLE `games` DROP COLUMN `json_security_data`;')->execute();
	$conn->prepare('ALTER TABLE `games` DROP COLUMN `rating`;')->execute();
	$conn->prepare('ALTER TABLE `games` ADD COLUMN `rules` TEXT DEFAULT "";')->execute();
	return true;
}
