<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: List Game Info
 * API_DESCRIPTION: Method will be returned list of the games
 * API_ACCESS: authorized users
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$conn = APIHelpers::createConnection($config);

try {
  // TODO paging
	$query = 'SELECT 
				games.id,
				games.uuid_game,
				games.title,
				games.type_game,
				games.date_start,
				games.date_stop,
				games.date_restart,
				games.description,
				games.state,
				games.form,
				games.logo,
				games.owner,
				games.organizators,
				games.maxscore,
				users.nick
			FROM
				games
			INNER JOIN users ON games.owner = users.id
			ORDER BY games.date_start
			DESC LIMIT 0,10;';

	$columns = array('id', 'title', 'state', 'form', 'type_game', 'date_start', 'date_stop', 'date_restart', 'description', 'logo', 'owner', 'nick', 'organizators', 'maxscore');

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$i = 0;
	while($row = $stmt->fetch())
	{
		$id = $row['uuid_game'];
		$response['data'][$id] = array();
		foreach ( $columns as $k) {
			$response['data'][$id][$k] = $row[$k];
		}

		$bAllows = APISecurity::isAdmin();
		$response['data'][$id]['permissions']['delete'] = $bAllows;
		$response['data'][$id]['permissions']['update'] = $bAllows;
	}
	$response['current_game'] = isset($_SESSION['game']) ? $_SESSION['game']['id'] : 0;
	
	$response['permissions']['insert'] = APISecurity::isAdmin();
	$response['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1193, $e->getMessage());
}

APIHelpers::endpage($response);
