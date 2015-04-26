<?php

$updates['u0050'] = array(
	'to_version' => 'u0051',
	'name' => 'add column levenshtein',
	'description' => 'add column levenshtein',
);

function update_u0050($conn) {
	$conn->prepare('ALTER TABLE `tryanswer` ADD COLUMN `levenshtein` INT DEFAULT 100;')->execute();
	$conn->prepare('ALTER TABLE `tryanswer_backup` ADD COLUMN `levenshtein` INT DEFAULT 100;')->execute();
	return true;
}
