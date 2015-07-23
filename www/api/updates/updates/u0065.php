<?php

$updates['u0065'] = array(
	'to_version' => 'u0066',
	'name' => 'update quests uuid',
	'description' => 'update quests uuid',
);

function update_u0065($conn) {
	$conn->prepare('ALTER TABLE quest DROP COLUMN for_person')->execute();
	return true;
}
