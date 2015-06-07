<?php

$updates['u0058'] = array(
	'to_version' => 'u0059',
	'name' => 'change userid and questid in users_quests to INT',
	'description' => 'change userid and questid in users_quests to INT',
);

function update_u0058($conn) {
	$conn->prepare('ALTER TABLE users_quests CHANGE userid userid INT')->execute();
	$conn->prepare('ALTER TABLE users_quests CHANGE questid questid INT')->execute();
	return true;
}
