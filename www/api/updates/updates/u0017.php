<?php

$updates['u0017'] = array(
	'to_version' => 'u0018',
	'name' => 'drop table games_rules',
	'description' => 'drop table games_rules',
);

function update_u0017($conn) {
	$conn->prepare('DROP TABLE `games_rules`;')->execute();
	$conn->prepare('DROP TABLE `rules`;')->execute();
	return true;
}
