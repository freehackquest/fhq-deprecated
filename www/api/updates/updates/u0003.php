<?php

$updates['u0003'] = array(
	'to_version' => 'u0004',
	'name' => 'drop user.score',
	'description' => 'drop column score from user ',
);

function update_u0003($conn) {
	$stmt = $conn->prepare('ALTER TABLE `user` DROP COLUMN `score`;');

	return true;
}
