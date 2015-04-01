<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_settings_get = dirname(__FILE__);
include_once ($curdir_settings_get."/../api.lib/api.base.php");
include_once ($curdir_settings_get."/../api.lib/api.security.php");
include_once ($curdir_settings_get."/../api.lib/api.helpers.php");
include_once ($curdir_settings_get."/../../config/config.php");


$result = array(
	'result' => 'ok',
	'data' => array(),
);

$conn = APIHelpers::createConnection($config);

try {
 	$stmt = $conn->prepare('SELECT DISTINCT city FROM users_ips');
 	$stmt->execute();

 	while ($row = $stmt->fetch()) {
		$result['result'] = 'ok';
		if ($row['city'] != "" && $row['city'] != "localhost")
			$result['data'][] = htmlspecialchars($row['city']);
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}
echo json_encode($result);
