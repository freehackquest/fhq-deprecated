<?php

$updates['u0000'] = array(
	'to_version' => 'u0001',
	'name' => 'test',
	'description' => 'test',
);

function update_u0000($conn) {
	return true;
}
