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

/*$errmsg = "";
if (!checkGameDates($security, &$message))
	showerror(709, 'Error 709: '.$errmsg);*/


$conn = createConnection($config);

if (issetParam('id')) {
	$game_id = getParam('id', 0);

	if (!is_numeric($game_id))
		showerror(705, 'Error 705: incorrect id');
		
	try {
		$where = ' AND games.date_start < NOW() ';
		if($security->isAdmin() || $security->isTester())
			$where = ' ';

		$query = '
			SELECT *
			FROM
				games
			WHERE id = ? '.$where.' ';

		$columns = array('id', 'type_game', 'title', 'date_start', 'date_stop', 'date_restart', 'description', 'logo', 'owner');

		$stmt = $conn->prepare($query);
		$stmt->execute(array(intval($game_id)));
		if($row = $stmt->fetch())
		{
			$_SESSION['game'] = array();
			$result['data'] = array();
			foreach ( $columns as $k) {
				$_SESSION['game'][$k] = $row[$k];
				$result['data'][$k] = $row[$k];
			}
			$result['result'] = 'ok';
		}
		else
		{
			showerror(702, 'Error 702: Game with id='.$game_id.' are not exists');
		}
		
	} catch(PDOException $e) {
		showerror(712, 'Error 712: ' + $e->getMessage());
	}
} else {
	showerror(713, 'Error 713: not found parameter id');
}
echo json_encode($result);
