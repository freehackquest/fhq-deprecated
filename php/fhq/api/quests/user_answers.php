<?php
$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.game.php");
include_once ($curdir."/../../config/config.php");

FHQHelpers::checkAuth();

$message = '';

if (!FHQGame::checkGameDates($message))
	FHQHelpers::showerror(986, $message);

if (!FHQHelpers::issetParam('questid'))
	FHQHelpers::showerror(987, 'Not found parameter "questid"');

$questid = FHQHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	FHQHelpers::showerror(988, 'parameter "questid" must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = FHQHelpers::createConnection($config);

$result['userid'] = FHQSecurity::userid();
$result['questid'] = $questid;

$params[] = FHQSecurity::userid();
$params[] = intval($questid);

$query = '
			SELECT 
				answer_try,
				datetime_try
			FROM 
				tryanswer
			WHERE
				iduser = ?
				AND idquest = ?
			ORDER BY
				datetime_try DESC
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	while($row = $stmt->fetch())
	{
		$result['data'][] = array(
			'datetime_try' => $row['datetime_try'],
			'answer_try' => base64_decode($row['answer_try']),
		);
	}
	$result['result'] = 'ok';
	
} catch(PDOException $e) {
	FHQHelpers::showerror(822, $e->getMessage());
}

echo json_encode($result);
