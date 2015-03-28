<?php

$updates['u0027'] = array(
	'to_version' => 'u0028',
	'name' => 'new table emails',
	'description' => 'new table emails',
);

function update_u0027($conn) {
	$conn->prepare('ALTER TABLE `quest` DROP COLUMN `short_text`;')->execute();
	$conn->prepare('ALTER TABLE `quest` DROP COLUMN `short_text_copy`;')->execute();
	return true;
}
