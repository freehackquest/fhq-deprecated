<?php

$updates['u0042'] = array(
	'to_version' => 'u0043',
	'name' => 'drop table services',
	'description' => 'drop table services',
);

function update_u0042($conn) {
	$conn->prepare('DROP TABLE `services`;')->execute();
	return true;
}
