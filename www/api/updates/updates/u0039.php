<?php

$updates['u0039'] = array(
	'to_version' => 'u0040',
	'name' => 'drop table advisers',
	'description' => 'drop table advisers',
);

function update_u0039($conn) {
	$conn->prepare('DROP TABLE `advisers`;')->execute();
	return true;
}
