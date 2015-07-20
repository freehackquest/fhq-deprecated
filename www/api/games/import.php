<?php
/*
 * API_NAME: Import game
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
		$pngfilename = '';
		
		for( $i = 0; $i < $zip->numFiles; $i++ ){
			$stat = $zip->statIndex( $i );
			$name = basename( $stat['name'] );
			if (substr($name, -strlen('.json')) === '.json') {
				$jsonfilename = $name;
			}
			if (substr($name, -strlen('.png')) === '.png') {
				$pngfilename = $name;
			}		
		}
		$pngdata = $zip->getFromName($pngfilename);
		$game = json_decode($zip->getFromName($jsonfilename), true);
		$zip->close();

		$conn = APIHelpers::createConnection($config);

		$stmt = $conn->prepare('SELECT id FROM games WHERE uuid = ?');
		$stmt->execute(array($game['uuid']));
		$gameid = 0;
		if ($row = $stmt->fetch()) {
			$gameid = $row['id'];
		}
		
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
			'organizators',
			'rules',
			'maxscore'
		);
	
		if ($gameid == 0) {
			$values = array();
			$values_q = array();
			foreach ( $columns as $k) {
				$values[] = $game[$k];
				$values_q[] = '?';
			}
			$columns[] = 'owner';
			$values_q[] = '?';
			$values[] = APISecurity::userid();
			
			$query = 'INSERT INTO games('.implode(',', $columns).', date_create, date_change) VALUES('.implode(',', $values_q).', NOW(), NOW());';		
			$stmt1 = $conn->prepare($query);
			$stmt1->execute($values);
			$gameid = $conn->lastInsertId();
			APIEvents::addPublicEvents($conn, 'games', "New game #".$gameid.' '.htmlspecialchars($game['title']));
		} else {
			$values = array();
			$values_q = array();
			foreach ( $columns as $k) {
				$values[] = $game[$k];
				$values_q[] = $k.' = ?';
			}
			$values_q[] = 'owner = ?';
			$values[] = APISecurity::userid();
			$query = 'UPDATE games SET '.implode(',', $values_q).', date_change = NOW() WHERE uuid = ?';
			$stmt2 = $conn->prepare($query);
			$values[] = $game['uuid'];
			$stmt2->execute($values);
			APIEvents::addPublicEvents($conn, 'games', "Updated game #".$gameid.' '.htmlspecialchars($game['title']));
		}

		// logo
		$fp = fopen($curdir_import_game.'/../../files/games/'.$gameid.'.png', 'w');
		fwrite($fp, $pngdata);
		fclose($fp);
		
		// update logo in db
		$stmt = $conn->prepare('UPDATE games SET logo = ? WHERE uuid = ?');
		$stmt->execute(array('files/games/'.$gameid.'.png', $game['uuid']));		
	}
}

APIHelpers::endpage($response);
