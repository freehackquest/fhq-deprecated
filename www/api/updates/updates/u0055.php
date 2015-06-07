<?php

$updates['u0055'] = array(
	'to_version' => 'u0056',
	'name' => 'added cleanup users_games',
	'description' => 'added cleanup users_games',
);

function update_u0055($conn) {
	$stmt_games = $conn->prepare('SELECT id, title FROM games');
	$stmt_games->execute();
	$games = [];
	while ($row_games = $stmt_games->fetch()) {
		$games[intval($row_games['id'])] = $row_games['title'];
	}

	$stmt_users_games = $conn->prepare('SELECT id, gameid FROM users_games');
	$stmt_users_games->execute();
	while ($row = $stmt_users_games->fetch()) {
		$id = intval($row['id']);
		$gameid = intval($row['gameid']);
		if (!isset($games[$gameid])) {
			$conn->prepare('DELETE FROM users_games WHERE id = ?')->execute(array($id));
		}
	}
	return true;
}
