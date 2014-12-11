<?php
$curdir = dirname(__FILE__);
include ($curdir."/../api.lib/api.helpers.php");
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");

$security = new fhq_security();
checkAuth($security);

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = FHQHelpers::createConnection($config);

if (issetParam('id')) {
	$game_id = getParam('id', 0);

	if (!is_numeric($game_id))
		showerror(715, 'Error 715: incorrect id');
		
	try {

		$query = '
			SELECT *
			FROM
				games
			WHERE id = ?';

		$columns = array('id', 'type_game', 'title', 'date_start', 'date_stop', 'date_restart', 'description', 'logo', 'owner');

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
		showerror(722, 'Error 722: ' + $e->getMessage());
	}
} else {
	showerror(723, 'Error 723: not found parameter id');
}
echo json_encode($result);
