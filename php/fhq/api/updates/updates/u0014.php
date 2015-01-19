<?php

$updates['u0014'] = array(
	'to_version' => 'u0015',
	'name' => 'added column',
	'description' => 'added column organizators to games',
);

function update_u0014($conn) {
	$conn->prepare('ALTER TABLE `organizators` ADD COLUMN `games` VARCHAR(255) DEFAULT "";')->execute();
	return true;
}
