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

$conn = createConnection($config);

if (isset($_GET['id']) || isset($_POST['id'])) {
	$game_id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0);

	if (!is_numeric($game_id))
		showerror(715, 'Error 715: incorrect id');
		
	try {
		$where = ' AND games.date_start < NOW() ';
		if($security->isAdmin() || $security->isTester())
			$where = ' ';

		$query = '
			SELECT *
			FROM
				games
			WHERE id = ? '.$where.' ';

		$columns = array('id', 'type_game', 'title', 'date_start', 'date_stop', 'logo', 'owner');

		$stmt = $conn->prepare($query);
		$stmt->execute(array(intval($game_id)));
		if($row = $stmt->fetch())
		{
			$result['data'] = array();
			foreach ( $columns as $k) {
				$_SESSION['game'][$k] = $row[$k];
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
