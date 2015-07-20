<?php
/*
 * API_NAME: Export Game Info with logo
 * API_DESCRIPTION: Method returned zip-archive
 * API_ACCESS: admin
 * API_INPUT: token - guid, token
 * API_INPUT: gameid - integer, Identificator of the game (defualt current id)
 */

$curdir_games_export = dirname(__FILE__);
include_once ($curdir_games_export."/../api.lib/api.base.php");
include_once ($curdir_games_export."/../api.lib/api.game.php");
include_once ($curdir_games_export."/../../config/config.php");

$response = APIHelpers::startpage($config);

$conn = APIHelpers::createConnection($config);

$gameid = APIHelpers::getParam('gameid', 0);

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1194, 'Denie access (only for admin)');

if (!is_numeric($gameid))
	APIHelpers::showerror(1333, '"gameid" must be numeric');

$gameid = intval($gameid);

$zipfile = tempnam(sys_get_temp_dir(), 'fhq-export-game-');

$zip = new ZipArchive();
if ($zip->open($zipfile,  ZIPARCHIVE::CREATE) !== TRUE)
	APIHelpers::showerror(1334, 'Could not create zip-file (Please check access t folder files/dumps/)');

//$zip->addEmptyDir('files');
//$zip->addEmptyDir('files/users');
$zip->close();

if (!file_exists($zipfile))
	APIHelpers::showerror(1335, 'Could not create zip-file');

$zip->open($zipfile,  ZIPARCHIVE::CREATE);

$info = array();

$filename = "game.zip";
$uuid = '?';
try {

	$query = '
		SELECT *
		FROM
			games
		WHERE id = ?';

	$columns = array(
		'uuid',
		'type_game',
		'state',
		'form',
		'title',
		'date_start',
		'date_stop',
		'date_restart',
		'description',
		'logo',
		'organizators',
		'rules',
		'maxscore'
	);
	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($gameid)));
	if($row = $stmt->fetch())
	{
		foreach ( $columns as $k) {
			$info[$k] = $row[$k];
		}
		
		$oldlogoname = $curdir_games_export.'/../../'.$row['logo'];
		if (file_exists($oldlogoname)) {
			$newlogoname = $row['uuid'].'.png';
			$zip->addFile($oldlogoname, $newlogoname);
			$info['logo'] = $row['uuid'].'.png';
		} else {
			$info['logo'] = "";
		}
		
	} else {
		APIHelpers::showerror(1336, 'Does not found game with this id');
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1332, $e->getMessage());
}

// normalize filename
$title = preg_replace("([^A-Za-z0-9])", '', $info['title']);
$filename = 'game_'.$title.'_'.$info['uuid'].'.zip';
		
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
