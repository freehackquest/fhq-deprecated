<?php

$updates['u0024'] = array(
	'to_version' => 'u0025',
	'name' => 'new column pass',
	'description' => 'new column pass',
);

function update_u0024($conn) {
	$res = $conn->prepare('ALTER TABLE `user` ADD COLUMN `pass` VARCHAR(255) DEFAULT "";')->execute();
	return ($res != 1);
}
