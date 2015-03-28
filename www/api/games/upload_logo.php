<?php
header("Access-Control-Allow-Origin: *");

$curdir_upload_logo = dirname(__FILE__);
include_once ($curdir_upload_logo."/../api.lib/api.base.php");
include_once ($curdir_upload_logo."/../../config/config.php");

APIHelpers::checkAuth();

if (!APIHelpers::issetParam('gameid'))
	APIHelpers::showerror(912, 'Not found parameter gameid');

$gameid = APIHelpers::getParam('gameid', 0);
// $userid = intval($userid);
if (!is_numeric($gameid))
	APIHelpers::showerror(912, 'gameid must be numeric');

if (!APISecurity::isAdmin())
	APIHelpers::showerror(912, 'This method only for admin');

if (count($_FILES) <= 0)
	APIHelpers::showerror(3010, 'Not found files '.count($_FILES));

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$keys = array_keys($_FILES);

// $prefix = 'quest'.$id.'_';
// $output_dir = 'files/';
for($i = 0; $i < count($keys); $i++)
{
	$filename = $keys[$i];
	if ($_FILES[$filename]['error'] > 0)
	{
		echo "Error: " . $_FILES[$filename]["error"] . "<br>";
	}
	else
	{
		$full_filename = $curdir_upload_logo.'/../../files/games/'.$gameid.'_orig.png'; 
		$full_filename_new = $curdir_upload_logo.'/../../files/games/'.$gameid.'.png';
		// chmod($curdir_upload_logo.'/../../files/games/',0755);
		
		move_uploaded_file($_FILES[$filename]["tmp_name"],$full_filename);
		if(!file_exists($full_filename))
			APIHelpers::showerror(3010, 'File was not loaded');
		else {
			if(mime_content_type($full_filename) != 'image/png') {
				unlink($full_filename);
				APIHelpers::showerror(3010, 'File are not png-image');
			}
				
			try {
				// set_error_handler("warning_handler", E_WARNING);
				
				// получение нового размера
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
				APIHelpers::showerror(3011, 'Problem with convert image: '.$e->getMessage());
			}
		}
	}
}

$conn = APIHelpers::createConnection($config);

try {
	$query = 'UPDATE games SET logo = ? WHERE id = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array('files/games/'.$gameid.'.png', $gameid))) {
		$result['result'] = 'ok';
		$result['data']['logo'] = 'files/games/'.$gameid.'.png';
	} else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(3012, $e->getMessage());
}

echo json_encode($result);
