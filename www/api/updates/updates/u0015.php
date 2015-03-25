<?php

$updates['u0015'] = array(
	'to_version' => 'u0016',
	'name' => 'drop table teams and userteams',
	'description' => 'drop table teams and userteams',
);

function update_u0015($conn) {
	$conn->prepare('DROP TABLE `teams`;')->execute();
	$conn->prepare('DROP TABLE `userteams`;')->execute();
	return true;
}
