<?php
/*
 * API_NAME: User Answer List
 * API_DESCRIPTION: Method will be returned answer list for current user and questid
 * API_ACCESS: authorized users
 * API_INPUT: token - string, token
 * API_INPUT: questid - integer, Identificator of the quest
 */

$curdir_statistics_user_answers = dirname(__FILE__);
include_once ($curdir_statistics_user_answers."/../../api.lib/api.base.php");
include_once ($curdir_statistics_user_answers."/../../api.lib/api.game.php");
include_once ($curdir_statistics_user_answers."/../../../config/config.php");
include_once ($curdir_statistics_user_answers."/../../api.lib/loadtoken.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(1085, $message);

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(1086, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(1087, 'parameter "questid" must be numeric');

$response['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$response['userid'] = APISecurity::userid();
$response['questid'] = $questid;

$params[] = APISecurity::userid();
$params[] = intval($questid);

$query = '
			SELECT 
				answer_try,
				datetime_try,
				levenshtein
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
		$response['data'][] = array(
			'datetime_try' => $row['datetime_try'],
			'answer_try' => htmlspecialchars($row['answer_try']),
			'levenshtein' => $row['levenshtein'],
		);
	}
	$response['result'] = 'ok';
	
} catch(PDOException $e) {
	APIHelpers::showerror(1084, $e->getMessage());
}

APIHelpers::endpage($response);
