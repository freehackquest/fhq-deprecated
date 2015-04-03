<?php

$updates['u0043'] = array(
	'to_version' => 'u0044',
	'name' => 'add quests_files.filepath',
	'description' => 'add quests_files.filepath',
);

function update_u0043($conn) {
	$conn->prepare('ALTER TABLE `quests_files` ADD COLUMN `filepath` VARCHAR(255) DEFAULT "";')->execute();
	return true;
}
