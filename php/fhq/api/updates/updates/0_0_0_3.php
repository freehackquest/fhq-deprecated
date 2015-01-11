<?php

$updates['0_0_0_3'] = array(
	'to_version' => '0_0_0_4',
	'name' => 'drop user.score',
	'description' => 'drop column score from user ',
);

function update_0_0_0_3($conn) {
	$stmt = $conn->prepare('ALTER TABLE `user` DROP COLUMN `score`;');

	return true;
}
