<?php
/*
 * API_NAME: Import Quest
 * API_DESCRIPTION:
 * API_ACCESS: admin only
 * API_INPUT: files - POST-FILES, files
 * API_INPUT: token - guid, token
 */

$curdir_import_quest = dirname(__FILE__);
include_once ($curdir_import_quest."/../../api.lib/api.base.php");
include_once ($curdir_import_quest."/../../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1344, 'This method only for admin');

if (count($_FILES) <= 0)
	APIHelpers::showerror(1349, 'Not found files '.count($_FILES));

$keys = array_keys($_FILES);
$response['result'] = 'ok';
$response['data']['quest'] = array();

// $prefix = 'quest'.$id.'_';
// $output_dir = 'files/';
for($i = 0; $i < count($keys); $i++)
{
	$filename = $keys[$i];
	if ($_FILES[$filename]['error'] > 0)
	{
		APIHelpers::showerror(1350, 'Error with files '.$_FILES[$filename]["error"]);
	}
	else
	{
		$response[$filename] = 'try';
		
		$zip = new ZipArchive();
		$filename = $_FILES[$filename]["tmp_name"];

		if ($zip->open($filename)!==TRUE) {
			APIHelpers::showerror(1351, 'Could not open zip-archive');
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
				$files[$name] = $zip->getFromName($name);
			}
		}
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
			APIHelpers::showerror(1352, 'Not found game');
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
			$response['data']['quest']['id'] = $questid;
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
			$response['data']['quest']['id'] = $questid;
			APIEvents::addPublicEvents($conn, 'quests', "Updated quest #".$questid.' from game '.htmlspecialchars($quest['game']['title']));
		}

		// remove all files from quest
		$stmt = $conn->prepare('SELECT id, filepath FROM quests_files WHERE questid = ?');
		$stmt->execute(array($questid));
		while ($row = $stmt->fetch()) {
			$filepath = $curdir_import_quest.'/../../../'.$row['filepath'];
			if (file_exists($filepath)) {
				unlink($filepath);
			}
			$conn->prepare('DELETE FROM quests_files WHERE id = ?')->execute(array($row['id']));
		}

		foreach ($quest['files'] as $file) {
			$fileid = 0;
			$file_uuid = $file['uuid'];
			$file_path = $file['filepath'];
			$stmt = $conn->prepare('SELECT id FROM quests_files WHERE uuid = ?');
			$stmt->execute(array($file_uuid));
			if ($row = $stmt->fetch()) {
				$fileid = $row['id'];
			}
			
			if ($fileid == 0) {
				if (isset($files[$file_uuid])) {
					$fp = fopen($curdir_import_quest.'/../../../'.$file_path, 'w');
					fwrite($fp, $files[$file_uuid]);
					fclose($fp);
				}
				$stmt2 = $conn->prepare('INSERT INTO quests_files(uuid, questid, filename, size, dt, filepath) VALUES(?,?,?,?,NOW(),?)');
				$stmt2->execute(array($file_uuid, $questid, $file['filename'], $file['size'], $file['filepath']));				
			}
		}
	}
}

APIHelpers::endpage($response);
