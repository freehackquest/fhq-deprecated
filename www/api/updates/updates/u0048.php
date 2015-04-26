<?php

$updates['u0048'] = array(
	'to_version' => 'u0049',
	'name' => 'add column maxscore',
	'description' => 'add column maxscore',
);

function update_u0048($conn) {
	$conn->prepare('ALTER TABLE `games` ADD COLUMN `maxscore` INT DEFAULT 0;')->execute();
	return true;
}
