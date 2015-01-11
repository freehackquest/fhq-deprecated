<?php

$updates['0_0_0_0'] = array(
	'to_version' => '0_0_0_1',
	'name' => 'test',
	'description' => 'test',
);

function update_0_0_0_0($conn) {
	return true;
}
