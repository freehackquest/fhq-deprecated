<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$message = '';

if (!FHQGame::checkGameDates($message))
	FHQHelpers::showerror(916, $message);

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

// TODO: must be added filters
$conn = FHQHelpers::createConnection($config);

/*$userid = FHQHelpers::getParam('userid', 0);*/

$query = '
			SELECT
				quest.idquest,
				quest.name,
				quest.score,
				quest.short_text,
				quest.tema,
				userquest.startdate,
				userquest.stopdate
			FROM
				quest
			LEFT JOIN 
				userquest ON userquest.idquest = quest.idquest AND iduser = ?
			WHERE
				id_game = ?
			ORDER BY
				quest.score DESC, quest.tema, quest.score
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array(FHQSecurity::userid(),FHQGame::id()));
	while($row = $stmt->fetch())
	{
		$status = '';
		
		if ($row['stopdate'] == null)
			$status = 'open';
		else if ($row['stopdate'] == '0000-00-00 00:00:00')
			$status = 'current';
		else
			$status = 'completed';
			
		$result['data'][] = array(
			'questid' => $row['idquest'],
			'score' => $row['score'],
			'name' => base64_decode($row['name']),
			'short_text' => base64_decode($row['short_text']),
			'subject' => base64_decode($row['tema']),
			'date_start' => $row['startdate'],
			'date_stop' => $row['stopdate'],
			'status' => $status,
		);
	}
	$result['result'] = 'ok';
	$result['permissions']['insert'] = FHQSecurity::isAdmin();
	
} catch(PDOException $e) {
	showerror(822, 'Error 822: ' + $e->getMessage());
}
unset($SxGeo);
echo json_encode($result);
