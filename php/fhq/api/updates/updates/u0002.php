<?php

$updates['u0002'] = array(
	'to_version' => 'u0003',
	'name' => 'update table quest',
	'description' => 'state and description_state to "" if null',
);

function update_u0002($conn) {
	$stmt = $conn->prepare('UPDATE quest SET state = "open" WHERE ISNULL(state);');
	if ($stmt->execute() != 1) return false;
	
	$stmt = $conn->prepare('UPDATE quest SET description_state = "" WHERE ISNULL(description_state);');
	if ($stmt->execute() != 1) return false;

	return true;
}
