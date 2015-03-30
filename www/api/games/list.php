<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../../config/config.php");

include_once ($curdir."/../api.lib/loadtoken.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if ($conn == null)
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
				user.nick
			FROM
				games
			INNER JOIN user ON games.owner = user.iduser
			ORDER BY games.date_start
			DESC LIMIT 0,10;';

	$columns = array('id', 'title', 'state', 'form', 'type_game', 'date_start', 'date_stop', 'date_restart', 'description', 'logo', 'owner', 'nick', 'organizators');

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$i = 0;
	while($row = $stmt->fetch())
	{
		$id = $row['uuid_game'];
		$result['data'][$id] = array();
		foreach ( $columns as $k) {
			$result['data'][$id][$k] = $row[$k];
		}

		$bAllows = APISecurity::isAdmin();
		$result['data'][$id]['permissions']['delete'] = $bAllows;
		$result['data'][$id]['permissions']['update'] = $bAllows;
	}
	$result['current_game'] = isset($_SESSION['game']) ? $_SESSION['game']['id'] : 0;
	
	$result['permissions']['insert'] = APISecurity::isAdmin();
	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1193, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");

echo json_encode($result);
