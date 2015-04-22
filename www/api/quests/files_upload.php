<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Upload File
 * API_DESCRIPTION: Method for upload file to the quest
 * API_ACCESS: admin only
 * API_INPUT: questid - string, Identificator of the quest
 * API_INPUT: files - POST-FILES, files
 * API_INPUT: token - string, token
 */

$curdir_upload_logo = dirname(__FILE__);
include_once ($curdir_upload_logo."/../api.lib/api.base.php");
include_once ($curdir_upload_logo."/../../config/config.php");

APIHelpers::checkAuth();


if (!APISecurity::isAdmin())
	APIHelpers::showerror(1306, 'it can do only admin');
	
if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(1307, 'Parameter questid did not found');

$questid = APIHelpers::getParam('questid', 0);
if (!is_numeric($questid))
	APIHelpers::showerror(1308, 'userid must be numeric');

$questid = intval($questid);

if (count($_FILES) <= 0)
	APIHelpers::showerror(1309, 'Not found files '.count($_FILES));

$result = array(
	'result' => 'fail',
	'data' => array(),
);

function normalizefilename($filename) {
	$converter = array(
		'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '_',  'ы' => 'y',   'ъ' => '_',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '_',  'Ы' => 'Y',   'Ъ' => '_',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
	);

	$filename1 = strtr($filename, $converter);
	$result = '';
	$allowCharacters = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm_1234567890.';
	for ($i = 0; $i < strlen($filename1); $i++) {
		if (strpos($allowCharacters,$filename1[$i]) !== false) {
			$result .= $filename1[$i];
		}
	}
	return $result;
}

$conn = APIHelpers::createConnection($config);

$keys = array_keys($_FILES);

for($i = 0; $i < count($keys); $i++)
{
	$filename = $keys[$i];
	if ($_FILES[$filename]['error'] > 0)
	{
		APIHelpers::showerror(1310, "Error: " . $_FILES[$filename]["error"]);
	}
	else
	{
		$uuid = APIHelpers::gen_guid();
		// $filename2 = 'files/quests/quest'.$questid.'_'.normalizefilename($filename);
		$filepath = 'files/quests/'.$uuid.'_'.normalizefilename($_FILES[$filename]["name"]);
		$full_filename = $curdir_upload_logo.'/../../'.$filepath;

		// chmod($curdir_upload_logo.'/../../files/users/',0755);
		move_uploaded_file($_FILES[$filename]["tmp_name"],$full_filename);
		if(!file_exists($full_filename))
			APIHelpers::showerror(1311, 'File was not loaded');
		else {
			try {
				$query = 'INSERT INTO quests_files(uuid, questid, filepath, filename, size, dt) VALUES(?,?,?,?,?,NOW())';
				$stmt = $conn->prepare($query);
				$size = filesize($full_filename);
				
				if ($stmt->execute(array($uuid, $questid, $filepath, $_FILES[$filename]["name"], $size))) {
					$result['result'] = 'ok';
					$result['data']['filename'] = $filename;
					$result['data']['filepath'] = $filepath;
					$result['data']['size'] = $size;
					$result['data']['uuid'] = $uuid;
					$result['data']['questid'] = $questid;
				} else {
					APIHelpers::showerror(1312, 'Could not insert information about file');
				}
			} catch(PDOException $e) {
				APIHelpers::showerror(1313, $e->getMessage());
			}	
		}
	}
}

echo json_encode($result);
