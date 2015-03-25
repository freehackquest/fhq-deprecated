<?php
header("Access-Control-Allow-Origin: *");

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

if (!APIHelpers::issetParam('id'))
	APIHelpers::showerror(723, 'not found parameter id');

$game_id = APIHelpers::getParam('id', 0);

if (!is_numeric($game_id))
	APIHelpers::showerror(715, 'incorrect id');
	
try {

	$query = '
		SELECT *
		FROM
			games
		WHERE id = ?';

	$columns = array('id', 'type_game', 'state', 'form', 'title', 'date_start', 'date_stop', 'date_restart', 'description', 'logo', 'owner', 'organizators');

	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($game_id)));
	if($row = $stmt->fetch())
	{
		$result['data'] = array();
		foreach ( $columns as $k) {
			$result['data'][$k] = $row[$k];
		}
	}
	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(722, $e->getMessage());
}

include_once ($curdir."/../api.lib/savetoken.php");
echo json_encode($result);
