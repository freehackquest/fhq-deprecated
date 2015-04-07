<?php

$updates['u0045'] = array(
	'to_version' => 'u0046',
	'name' => 'rename table user => user_old',
	'description' => 'rename table user => user_old',
);

function update_u0045($conn) {
	$conn->prepare('RENAME TABLE user TO user_old')->execute();
	return true;
}
