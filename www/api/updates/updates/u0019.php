<?php

$updates['u0019'] = array(
	'to_version' => 'u0020',
	'name' => 'removed users_achievements, users_skills',
	'description' => 'removed users_achievements, users_skills',
);

function update_u0019($conn) {
	$conn->prepare('DROP TABLE `users_achievements`;')->execute();
	$conn->prepare('DROP TABLE `users_skills`;')->execute();
	$conn->prepare('ALTER TABLE `user` DROP COLUMN `team`')->execute();
	return true;
}

/*SELECT *
FROM `user`
WHERE ISNULL( uuid_user ) */
