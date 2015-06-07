<?php

$updates['u0057'] = array(
	'to_version' => 'u0058',
	'name' => 'rename games.uuid_game games.uuid',
	'description' => 'rename games.uuid_game games.uuid',
);

function update_u0057($conn) {
	$conn->prepare('
		ALTER TABLE games CHANGE uuid_game uuid varchar(255)
	')->execute();
	return true;
}
