<?php

$updates['u0034'] = array(
	'to_version' => 'u0035',
	'name' => 'removed user.password',
	'description' => 'removed user.password',
);

function update_u0034($conn) {
	$conn->prepare('ALTER TABLE user DROP COLUMN `password`')->execute();
	return true;
}
