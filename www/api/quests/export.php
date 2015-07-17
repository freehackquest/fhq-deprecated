<?php
/*
 * API_NAME: Export Quest Info With Files
 * API_DESCRIPTION: Method will be returned zip-archive 
 * API_ACCESS: admin
 * API_INPUT: questid - integer, Identificator of the quest (in future will be questid)
 * API_INPUT: token - string, token
 */

$curdir_quests_export = dirname(__FILE__);
include_once ($curdir_quests_export."/../api.lib/api.base.php");
include_once ($curdir_quests_export."/../api.lib/api.game.php");
include_once ($curdir_quests_export."/../../config/config.php");

$response = APIHelpers::startpage($config);
$conn = APIHelpers::createConnection($config);

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1337, 'Denie access (only for admin)');

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(1338, 'Not found parameter "taskid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(1339, 'parameter "questid" must be numeric');

$zipfile = tempnam(sys_get_temp_dir(), 'fhq-export-quest-');
$response['zipfile'] = $zipfile;
$zip = new ZipArchive();
if ($zip->open($zipfile,  ZIPARCHIVE::CREATE) !== TRUE)
	APIHelpers::showerror(1342, 'Could not create zip-file (Please check access t folder files/dumps/)');

//$zip->addEmptyDir('files');
//$zip->addEmptyDir('files/users');
$zip->close();

if (!file_exists($zipfile))
	APIHelpers::showerror(1343, 'Could not create zip-file');

$zip->open($zipfile,  ZIPARCHIVE::CREATE);

$info = array();

$filename = "quest.zip";
$uuid = '?';

try {
	$stmt = $conn->prepare('
			SELECT 
				*
			FROM 
				quest
			WHERE
				quest.idquest = ?
	');
	$stmt->execute(array(intval($questid)));

	if($row = $stmt->fetch())
	{
		$info['uuid'] = $row['quest_uuid'];
		$info['score'] = $row['score'];
		$info['min_score'] = $row['min_score'];
		$info['name'] = $row['name'];
		$info['subject'] = $row['subject'];
		$info['state'] = $row['state'];
		$info['author'] = $row['author'];
		$info['text'] = $row['text'];
		$info['description_state'] = $row['description_state'];
		$info['answer'] = $row['answer'];
		$info['date_create'] = $row['date_create'];
		$info['date_change'] = $row['date_change'];

		//todo game uuid and game name// $response['gameid'] = $row['gameid'];

		$stmt_game = $conn->prepare('select * from games WHERE id = ?');
		$stmt_game->execute(array(intval($row['gameid'])));
		if ($row_game = $stmt_game->fetch()) {
			$info['game']['uuid'] = $row_game['uuid'];
			$info['game']['title'] = $row_game['title'];
		}

		$stmt_files = $conn->prepare('select * from quests_files WHERE questid = ?');
		$stmt_files->execute(array(intval($questid)));
		$info['files'] = array();
		while ($row_files = $stmt_files->fetch()) {
			$info['files'][] = array(
				'uuid' => $row_files['uuid'],
				'filename' => $row_files['filename'],
				'filepath' => $row_files['filepath'],
				'size' => $row_files['size'],
			);
			
			$oldfilename = $curdir_quests_export.'/../../'.$row_files['filepath'];
			if (file_exists($oldfilename)) {
				$newfilename = $row_files['uuid'];
				$zip->addFile($oldfilename, $newfilename);
			}
		}

	} else {
		APIHelpers::showerror(1340, 'Problem... may be incorrect game are selected?');
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1341, $e->getMessage());
}

// normalize filename
$title = preg_replace("([^A-Za-z0-9])", '', $info['name']);
$filename = 'quest_'.$title.'_'.$info['uuid'].'.zip';

$zip->addFromString($info['uuid'].'.json',json_encode($info));
$zip->close();

header_remove('Content-Type');

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$filename."\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($zipfile));
ob_end_flush();
@readfile($zipfile);

unlink($zipfile);

// APIHelpers::endpage($response);
