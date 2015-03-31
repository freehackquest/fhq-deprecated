<?php

$updates['u0037'] = array(
	'to_version' => 'u0038',
	'name' => 'redesign table feedback_msg',
	'description' => 'redesign table feedback_msg',
);

function update_u0037($conn) {

	$conn->prepare('ALTER TABLE `feedback_msg` ADD COLUMN `text` TEXT DEFAULT "";')->execute();
	$conn->prepare('ALTER TABLE `feedback_msg` ADD COLUMN `feedbackid` INT(11) DEFAULT 0;')->execute();
	$conn->prepare('ALTER TABLE `feedback_msg` ADD COLUMN `userid` INT(11) DEFAULT 0;')->execute();
	
	$conn->prepare('UPDATE `feedback_msg` SET userid = author;')->execute();
	$conn->prepare('UPDATE `feedback_msg` SET feedbackid = feedback_id;')->execute();
	$conn->prepare('UPDATE `feedback_msg` SET text = msg;')->execute();

	$conn->prepare('ALTER TABLE `feedback_msg` DROP COLUMN `feedback_id`;')->execute();
	$conn->prepare('ALTER TABLE `feedback_msg` DROP COLUMN `msg`;')->execute();
	$conn->prepare('ALTER TABLE `feedback_msg` DROP COLUMN `author`;')->execute();
	return true;
}
