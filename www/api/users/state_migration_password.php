<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'ok',
	'data' => array(),
);


if (!APISecurity::isAdmin()) 
	APIHelpers::showerror(912, 'only for admin');
	
$conn = APIHelpers::createConnection($config);

try {
	$query = 'SELECT count(iduser) as cnt FROM user WHERE password = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array(''));
	if ($row = $stmt->fetch()) {
		$result['data']['new_passwords'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(7806, $e->getMessage());
}

try {
	$query = 'SELECT count(iduser) as cnt FROM user WHERE pass = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array(''));
	if ($row = $stmt->fetch()) {
		$result['data']['old_passwords'] = $row['cnt'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(7806, $e->getMessage());
}

try {
	$query = 'SELECT email, password FROM user WHERE pass = ?';
	$stmt = $conn->prepare($query);
	$stmt->execute(array(''));
	$result['data']['to_migrate'] = array();
	while($row = $stmt->fetch()) {
		$result['data']['to_migrate'][$row['email']] = $row['password'];
	}
} catch(PDOException $e) {
	APIHelpers::showerror(7806, $e->getMessage());
}

echo json_encode($result);
