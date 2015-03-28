<?php

$updates['u0028'] = array(
	'to_version' => 'u0029',
	'name' => 'updated table games',
	'description' => 'updated table games',
);

function update_u0028($conn) {
	$conn->prepare('ALTER TABLE `games` DROP COLUMN `json_data`;')->execute();
	$conn->prepare('ALTER TABLE `games` DROP COLUMN `json_security_data`;')->execute();
	$conn->prepare('ALTER TABLE `games` DROP COLUMN `rating`;')->execute();
	return true;
}
