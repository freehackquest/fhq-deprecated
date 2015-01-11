<?php

$updates['0_0_0_2'] = array(
	'to_version' => '0_0_0_3',
	'name' => 'update table quest',
	'description' => 'state and description_state to "" if null',
);

function update_0_0_0_2($conn) {
	$stmt = $conn->prepare('UPDATE quest SET state = "open" WHERE ISNULL(state);');
	if ($stmt->execute() != 1) return false;
	
	$stmt = $conn->prepare('UPDATE quest SET description_state = "" WHERE ISNULL(description_state);');
	if ($stmt->execute() != 1) return false;

	return true;
}
