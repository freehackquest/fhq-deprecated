<?php
/*
 * API_NAME: Import Quest
 * API_DESCRIPTION:
 * API_ACCESS: admin only
 * API_INPUT: files - POST-FILES, files
 * API_INPUT: token - guid, token
 */

$curdir_import_game = dirname(__FILE__);
include_once ($curdir_import_game."/../api.lib/api.base.php");
include_once ($curdir_import_game."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1345, 'This method only for admin');

if (count($_FILES) <= 0)
	APIHelpers::showerror(1346, 'Not found files '.count($_FILES));

$keys = array_keys($_FILES);
$response['result'] = 'ok';

// $prefix = 'quest'.$id.'_';
// $output_dir = 'files/';
for($i = 0; $i < count($keys); $i++)
{
	$filename = $keys[$i];
	if ($_FILES[$filename]['error'] > 0)
	{
		APIHelpers::showerror(1347, 'Error with files '.$_FILES[$filename]["error"]);
	}
	else
	{
		$response[$filename] = 'try';
		
		$zip = new ZipArchive();
		$filename = $_FILES[$filename]["tmp_name"];

		if ($zip->open($filename)!==TRUE) {
			APIHelpers::showerror(1348, 'Could not open zip-archive');
		}
		
		// print_r($zip);
		$jsonfilename = '';
		$files = array();
		
		for( $i = 0; $i < $zip->numFiles; $i++ ){
			$stat = $zip->statIndex( $i );
			$name = basename( $stat['name'] );
			if (substr($name, -strlen('.json')) === '.json') {
				$jsonfilename = $name;
			} else {
				$files[] = $name;
			}
		}
		// $pngdata = $zip->getFromName($pngfilename);
		$quest = json_decode($zip->getFromName($jsonfilename), true);
		$zip->close();

		$conn = APIHelpers::createConnection($config);

		// find gameid
		
		$stmt = $conn->prepare('SELECT id FROM games WHERE uuid = ?');
		$stmt->execute(array($quest['game']['uuid']));
		$gameid = 0;
		if ($row = $stmt->fetch()) {
			$gameid = $row['id'];
		}
		if ($gameid == 0) {
			APIHelpers::showerror(1348, 'Not found game');
		}

		$stmt = $conn->prepare('SELECT idquest FROM quest WHERE quest_uuid = ?');
		$stmt->execute(array($quest['uuid']));
		$questid = 0;
		if ($row = $stmt->fetch()) {
			$questid = $row['idquest'];
		}

		$columns = array(
			'quest_uuid',
			'name',
			'text',
			'answer',
			'score',
			'min_score',
			'author',
			'subject',
			'state',
			'description_state',
			'date_create'
		);

		if ($questid == 0) {
			$values = array();
			$values_q = array();
			foreach ( $columns as $k) {
				if ($k == 'quest_uuid')
					$values[] = $quest['uuid'];
				else
					$values[] = $quest[$k];
				$values_q[] = '?';
			}
			$columns[] = 'userid';
			$values_q[] = '?';
			$values[] = APISecurity::userid();
			
			$columns[] = 'gameid';
			$values_q[] = '?';
			$values[] = $gameid;

			$query = 'INSERT INTO quest('.implode(',', $columns).', date_change) VALUES('.implode(',', $values_q).', NOW());';
			$stmt1 = $conn->prepare($query);
			$stmt1->execute($values);
			$questid = $conn->lastInsertId();
			APIEvents::addPublicEvents($conn, 'quests', "New quest #".$questid.' '.htmlspecialchars($quest['name']).' into game '.htmlspecialchars($quest['game']['title']));
		} else {
			$values = array();
			$values_q = array();
			foreach ( $columns as $k) {
				if ($k == 'quest_uuid')
					$values[] = $quest['uuid'];
				else
					$values[] = $quest[$k];
				$values_q[] = $k.' = ?';
			}
			$values_q[] = 'userid = ?';
			$values[] = APISecurity::userid();

			$query = 'UPDATE quest SET '.implode(',', $values_q).', date_change = NOW() WHERE quest_uuid = ?';
			$stmt2 = $conn->prepare($query);
			$values[] = $quest['uuid'];
			$stmt2->execute($values);
			APIEvents::addPublicEvents($conn, 'quests', "Updated quest #".$questid.' from game '.htmlspecialchars($quest['game']['title']));
		}

		// TODO: insert or update files for quest
		// logo
		/*$fp = fopen($curdir_import_game.'/../../files/games/'.$gameid.'.png', 'w');
		fwrite($fp, $pngdata);
		fclose($fp);
		
		// update logo in db
		$stmt = $conn->prepare('UPDATE games SET logo = ? WHERE uuid = ?');
		$stmt->execute(array('files/games/'.$gameid.'.png', $game['uuid']));*/
	}
}

APIHelpers::endpage($response);
