<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$country = '';
$city = '';

if (!APIHelpers::issetParam('country'))
  APIHelpers::showerror(1103, 'Not found parameter "country"');

if (!APIHelpers::issetParam('city'))
  APIHelpers::showerror(1104, 'Not found parameter "city"');
  
if (!APIHelpers::issetParam('university'))
  APIHelpers::showerror(1105, 'Not found parameter "university"');

$country = APIHelpers::getParam('country', '');
$city = APIHelpers::getParam('city', '');
$university = APIHelpers::getParam('university', '');


try {
	$_SESSION['user']['profile']['country'] = $country;
	$_SESSION['user']['profile']['city'] = $city;
	$_SESSION['user']['profile']['university'] = $university;
	
	$query = 'UPDATE users_profile SET value = ?, date_change = NOW() WHERE name = ? AND userid = ?';
	$stmt = $conn->prepare($query);

	$stmt->execute(array(htmlspecialchars($country), 'country', APISecurity::userid()));
	$stmt->execute(array(htmlspecialchars($city), 'city', APISecurity::userid()));
	$stmt->execute(array(htmlspecialchars($university), 'university', APISecurity::userid()));

	$result['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1106, $e->getMessage());
}

echo json_encode($result);
