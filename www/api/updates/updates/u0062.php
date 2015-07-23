<?php

$updates['u0062'] = array(
	'to_version' => 'u0063',
	'name' => 'remove table userquest',
	'description' => 'remove table userquest',
);

function update_u0062($conn) {
	$conn->prepare('DROP TABLE userquest')->execute();
	return true;
}
