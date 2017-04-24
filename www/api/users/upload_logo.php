<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Upload user logo 
 * API_DESCRIPTION: Method for upload user logo (only POST request with file)
 * API_ACCESS: admin, authorized user
 * API_INPUT: userid - integer, default value: current user
 * API_INPUT: file - file, default value: current user
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir_upload_logo = dirname(__FILE__);
include_once ($curdir_upload_logo."/../api.lib/api.base.php");
include_once ($curdir_upload_logo."/../../config/config.php");

$result = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$userid = APIHelpers::getParam('userid', APISecurity::userid());
// $userid = intval($userid);
if (!is_numeric($userid))
	APIHelpers::showerror(1044, 'userid must be numeric');

if (!APISecurity::isAdmin() && $userid != APISecurity::userid())
	APIHelpers::showerror(1045, 'you what change logo for another user, it can do only admin');

if (count($_FILES) <= 0)
	APIHelpers::showerror(1046, 'Not found file');

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
		$full_filename = $curdir_upload_logo.'/../../files/users/'.$userid.'_orig.png'; 
		$full_filename_new = $curdir_upload_logo.'/../../files/users/'.$userid.'.png';
		// chmod($curdir_upload_logo.'/../../files/users/',0755);
		
		move_uploaded_file($_FILES[$filename]["tmp_name"],$full_filename);
		if(!file_exists($full_filename))
			APIHelpers::showerror(1047, 'File was not loaded');
		else {
			if(mime_content_type($full_filename) != 'image/png') {
				unlink($full_filename);
				APIHelpers::showerror(1048, 'File are not png-image');
			}
				
			try {
				// set_error_handler("warning_handler", E_WARNING);
				
				// получение нового размера
				list($width, $height) = getimagesize($full_filename);
				$newwidth = 100;
				$newheight = 100;
				$source = imagecreatefrompng($full_filename);
				imagealphablending($source, true);
				imagesavealpha($source, true);
				
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				imagealphablending($thumb, true);
				// imagesavealpha($thumb, true);
				// $black = imagecolorallocate($thumb, 0, 0, 0);
				// imagecolortransparent($thumb, $black);
				imagecolortransparent($thumb);
				
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				imagepng($thumb, $full_filename_new, 9 , PNG_NO_FILTER);
				imagedestroy($thumb);
				imagedestroy($source);

				unlink($full_filename);
			} catch(Exception $e) {
				unlink($full_filename);
				APIHelpers::showerror(1049, 'Problem with convert image: '.$e->getMessage());
			}
		}
	}
}

$conn = APIHelpers::createConnection($config);

try {
	$query = 'UPDATE users SET logo = ? WHERE id = ?';
	$stmt = $conn->prepare($query);
	if ($stmt->execute(array('files/users/'.$userid.'.png', $userid))) {
		$result['result'] = 'ok';
		$result['data']['logo'] = 'files/users/'.$userid.'.png';
	} else
		$result['result'] = 'fail';
} catch(PDOException $e) {
	APIHelpers::showerror(1050, $e->getMessage());
}

echo json_encode($result);
