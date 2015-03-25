<?php

$updates['u0018'] = array(
	'to_version' => 'u0019',
	'name' => 'updated games',
	'description' => 'updated games',
);

function update_u0018($conn) {
	$conn->prepare('ALTER TABLE `games` ADD UNIQUE (`uuid_game`);')->execute();
	return true;
}
