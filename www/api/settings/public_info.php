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

// cities
try {
 	$stmt = $conn->prepare('SELECT city, count(userid) cnt FROM users_ips GROUP BY city ORDER BY cnt DESC');
 	$stmt->execute();
	
	$result['data']['cities'] = array();
 	while ($row = $stmt->fetch()) {
		$result['result'] = 'ok';
		if ($row['city'] != "" && $row['city'] != "localhost") {
			$result['data']['cities'][] = array(
				"city" => htmlspecialchars($row['city']),
				"cnt" => htmlspecialchars($row['cnt']),
			);
		}
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

// quests
$result['data']['quests'] = array();

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM quest WHERE for_person = 0');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['count'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

// tryanswers and succsessuful
try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['attempts'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer_backup WHERE passed="No"');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['attempts'] += intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

try {
 	$stmt = $conn->prepare('SELECT count(*) cnt FROM tryanswer_backup WHERE passed="Yes"');
 	$stmt->execute();

 	if ($row = $stmt->fetch()) {
		$result['data']['quests']['solved'] = intval($row['cnt']);
	}

} catch(PDOException $e) {
 	APIHelpers::showerror(1283, $e->getMessage());
}

echo json_encode($result);
