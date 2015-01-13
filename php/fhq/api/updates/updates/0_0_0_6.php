<?php

$updates['0_0_0_6'] = array(
	'to_version' => '0_0_0_7',
	'name' => 'update state for user',
	'description' => 'update column status in user',
);

function update_0_0_0_6($conn) {
	$conn->prepare('UPDATE user SET status = "" WHERE ISNULL(status);')->execute();
	return true;
}
