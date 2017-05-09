<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Delete File
 * API_DESCRIPTION: Method for delete file from the quest
 * API_ACCESS: admin only
 * API_INPUT: fileid - string, Identificator of the file
 * API_INPUT: token - string, token
 */

$curdir_quests_files_remove = dirname(__FILE__);
include_once ($curdir_quests_files_remove."/../api.lib/api.base.php");
include_once ($curdir_quests_files_remove."/../../config/config.php");

APIHelpers::checkAuth();

if (!APISecurity::isAdmin())
	APIHelpers::error(403, 'it can do only admin');
	
if (!APIHelpers::issetParam('fileid'))
	APIHelpers::error(400, 'Parameter fileid did not found');

$fileid = APIHelpers::getParam('fileid', 0);
if (!is_numeric($fileid))
	APIHelpers::error(400, 'Parameter fileid must be numeric');

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

$filepath = '';
try {
	$query = 'SELECT * FROM quests_files WHERE id = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array($fileid));
	if ($row = $stmt->fetch()) {
		$filepath = $row['filepath'];
	} else {
		APIHelpers::error(404, 'File with id '.$fileid.' did not found.');
	}
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

if (file_exists($curdir_quests_files_remove.'/../../'.$filepath))
	unlink($curdir_quests_files_remove.'/../../'.$filepath);

$filepath = '';
try {
	$query = 'DELETE FROM quests_files WHERE id = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array($fileid));
	$result['result'] = 'ok';
	$result['data']['id'] = $fileid;
	$result['data']['filepath'] = $filepath;
} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

echo json_encode($result);
