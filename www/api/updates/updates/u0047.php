<?php

$updates['u0047'] = array(
	'to_version' => 'u0048',
	'name' => 'change message in events (for indexing and search)',
	'description' => 'change message in events (for indexing and search)',
);

function update_u0047($conn) {
	
	$conn->prepare('ALTER TABLE public_events CHANGE message message_old TEXT')->execute();
	$conn->prepare('ALTER TABLE `public_events` ADD COLUMN `message` VARCHAR(2048) DEFAULT "";')->execute();
	$conn->prepare('UPDATE `public_events` SET message = message_old;')->execute();
	$conn->prepare('ALTER TABLE `public_events` DROP COLUMN `message_old`;')->execute();
	$conn->prepare('ALTER TABLE `public_events` ADD INDEX (`message`) ;')->execute();
	return true;
}
