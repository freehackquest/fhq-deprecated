<?php

$updates['u0040'] = array(
	'to_version' => 'u0041',
	'name' => 'drop table flags and flags_live',
	'description' => 'drop table flags and flags_live',
);

function update_u0040($conn) {
	$conn->prepare('DROP TABLE `flags`;')->execute();
	$conn->prepare('DROP TABLE `flags_live`;')->execute();
	return true;
}
