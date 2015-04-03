<?php

$updates['u0041'] = array(
	'to_version' => 'u0042',
	'name' => 'drop table scoreboard',
	'description' => 'drop table scoreboard',
);

function update_u0041($conn) {
	$conn->prepare('DROP TABLE `scoreboard`;')->execute();
	return true;
}
