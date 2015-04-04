<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_users_export = dirname(__FILE__);
include_once ($curdir_users_export."/../api.lib/api.base.php");
include_once ($curdir_users_export."/../api.lib/api.game.php");
include_once ($curdir_users_export."/../../config/config.php");

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1294, "This function allowed only for admin");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

// calculate count users
try {
	$stmt = $conn->prepare('
			SELECT
				COUNT(iduser) as cnt
			FROM
				user
	');
	$stmt->execute();
	if ($row = $stmt->fetch()) {
		$result['data']['count'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1295, $e->getMessage());
}

$zipname = $curdir_users_export.'/../../files/dumps/users_'.date('YmdHis').'.zip';

$zip = new ZipArchive();
if ($zip->open($zipname,  ZIPARCHIVE::CREATE) !== TRUE)
	APIHelpers::showerror(1292, 'Could not create '.$zipname.' (Please check access t folder files/dumps/)');

$zip->addEmptyDir('files');
$zip->addEmptyDir('files/users');
$zip->close();

if (!file_exists($zipname))
	APIHelpers::showerror(1293, 'Could not create '.$zipname.' (Please check access t folder files/dumps/)');

$zip->open($zipname,  ZIPARCHIVE::CREATE);

try {
	$stmt2 = $conn->prepare('
			SELECT
				*
			FROM
				user
			ORDER BY
				iduser ASC
	');
	$stmt2->execute();
	while ($row2 = $stmt2->fetch()) {
		$userid = $row2['iduser'];
		$uuid = $row2['uuid_user'];
		$oldlogoname = $curdir_users_export.'/../../'.$row2['logo'];
		if (file_exists($oldlogoname) && $uuid) {
			$newlogoname = 'files/users/'.$uuid.'.png';
			$zip->addFile($oldlogoname, $newlogoname);
		} else {
			$newlogoname = $row2['logo'];
		}
		
		$userarr = [
			'uuid' => $row2['uuid_user'],
			'email' => $row2['email'],
			'pass' => $row2['pass'],
			'role' => $row2['role'],
			'nick' => $row2['nick'],
			'logo' => $newlogoname,
			'last_ip' => $row2['last_ip'],
			'date_create' => $row2['date_create'],
			'date_last_signup' => $row2['date_last_signup'],
			'status' => $row2['status'],
		];
			
		$zip->addFromString($uuid.'.json',json_encode($userarr));
	}
} catch(PDOException $e) {
	APIHelpers::showerror(1296, $e->getMessage());
}

$result['data']['filename'] = $zipname;

$zip->close();
echo json_encode($result);