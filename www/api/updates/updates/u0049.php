<?php

$updates['u0049'] = array(
	'to_version' => 'u0050',
	'name' => 'add column maxscore',
	'description' => 'add column maxscore',
);

function update_u0049($conn) {
	$conn->prepare('
		UPDATE
			games
		SET
			maxscore = (
				SELECT
					SUM(score)
				FROM
					quest
				WHERE
					state = "open"
					AND for_person = 0
					AND gameid = games.id
			);
	')->execute();
	return true;
}
