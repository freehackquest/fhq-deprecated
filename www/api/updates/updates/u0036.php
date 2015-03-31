<?php

$updates['u0036'] = array(
	'to_version' => 'u0037',
	'name' => 'redesign table feedback',
	'description' => 'redesign table feedback',
);

function update_u0036($conn) {

	$conn->prepare('ALTER TABLE `feedback` ADD COLUMN `type` VARCHAR(255) DEFAULT "";')->execute();
	$conn->prepare('ALTER TABLE `feedback` ADD COLUMN `text` TEXT DEFAULT "";')->execute();
	$conn->prepare('ALTER TABLE `feedback` ADD COLUMN `userid` INT(11) DEFAULT 0;')->execute();
	
	$conn->prepare('UPDATE `feedback` SET userid = author;')->execute();
	$conn->prepare('UPDATE `feedback` SET type = typeFB;')->execute();
	$conn->prepare('UPDATE `feedback` SET text = full_text;')->execute();

	$conn->prepare('ALTER TABLE `feedback` DROP COLUMN `typeFB`;')->execute();
	$conn->prepare('ALTER TABLE `feedback` DROP COLUMN `full_text`;')->execute();
	$conn->prepare('ALTER TABLE `feedback` DROP COLUMN `author`;')->execute();
	return true;
}
