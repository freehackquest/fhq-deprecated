<?php

$updates['u0061'] = array(
	'to_version' => 'u0062',
	'name' => 'remove table user_old',
	'description' => 'remove table user_old',
);

function update_u0061($conn) {
	$conn->prepare('DROP TABLE user_old')->execute();
	return true;
}
