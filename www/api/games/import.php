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
	APIHelpers::showerror(1053, 'This method only for admin');

if (count($_FILES) <= 0)
	APIHelpers::showerror(1054, 'Not found files '.count($_FILES));

$keys = array_keys($_FILES);
$response['result'] = 'ok';

// $prefix = 'quest'.$id.'_';
// $output_dir = 'files/';
for($i = 0; $i < count($keys); $i++)
{
	$filename = $keys[$i];
	if ($_FILES[$filename]['error'] > 0)
	{
		APIHelpers::showerror(1329, 'Error with files '.$_FILES[$filename]["error"]);
	}
	else
	{
		$response[$filename] = 'try';
		
		$zip = new ZipArchive();
		$filename = $_FILES[$filename]["tmp_name"];

		if ($zip->open($filename)!==TRUE) {
			APIHelpers::showerror(1329, 'Could not open zip-archive');
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


		// 

		/*$full_filename = $curdir_upload_logo.'/../../files/games/'.$gameid.'_orig.png'; 
		$full_filename_new = $curdir_upload_logo.'/../../files/games/'.$gameid.'.png';
		// chmod($curdir_upload_logo.'/../../files/games/',0755);
		
		move_uploaded_file($_FILES[$filename]["tmp_name"],$full_filename);
		if(!file_exists($full_filename))
			APIHelpers::showerror(1055, 'File was not loaded');
		else {
			if(mime_content_type($full_filename) != 'image/png') {
				unlink($full_filename);
				APIHelpers::showerror(1056, 'File are not png-image');
			}
				
			try {
				// set_error_handler("warning_handler", E_WARNING);

				// new size
				list($width, $height) = getimagesize($full_filename);
				$newwidth = 100;
				$newheight = 100;
				$source = imagecreatefrompng($full_filename);
				imagealphablending($source, true);
				
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				imagealphablending($thumb, true);
				$black = imagecolorallocate($thumb, 0, 0, 0);
				imagecolortransparent($thumb, $black);

				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagepng($thumb, $full_filename_new, 9 , PNG_NO_FILTER);
				imagedestroy($thumb);
				imagedestroy($source);

				unlink($full_filename);
			} catch(Exception $e) {
				unlink($full_filename);
				APIHelpers::showerror(1057, 'Problem with convert image: '.$e->getMessage());
			}
		}*/
	}
}



/*try {
	$query = 'UPDATE games SET logo = ? WHERE id = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array('files/games/'.$gameid.'.png', $gameid))) {
		$response['result'] = 'ok';
		$response['data']['logo'] = 'files/games/'.$gameid.'.png';
	} else
		$response['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(1058, $e->getMessage());
}*/

APIHelpers::endpage($response);
