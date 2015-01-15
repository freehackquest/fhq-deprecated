<?php

$updates['u0006'] = array(
	'to_version' => 'u0007',
	'name' => 'update state for user',
	'description' => 'update column status in user',
);

function update_u0006($conn) {
	$conn->prepare('UPDATE user SET status = "" WHERE ISNULL(status);')->execute();
	return true;
}
