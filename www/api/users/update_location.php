<?php
/*
 * API_NAME: Update Location
 * API_DESCRIPTION: Method for update user profile
 * API_ACCESS: authorized users
 * API_INPUT: country - string, Country
 * API_INPUT: city - string, City
 * API_INPUT: university - string, Univercity
 * API_OKRESPONSE: { "result":"ok" }
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

$result = APIHelpers::startpage();

APIHelpers::checkAuth();

$result['result'] = 'ok';

$conn = APIHelpers::createConnection($config);

$country = '';
$city = '';

if (!APIHelpers::issetParam('country'))
  APIHelpers::error(400, 'Not found parameter "country"');

if (!APIHelpers::issetParam('city'))
  APIHelpers::error(400, 'Not found parameter "city"');
  
if (!APIHelpers::issetParam('university'))
  APIHelpers::error(400, 'Not found parameter "university"');

$country = APIHelpers::getParam('country', '');
$city = APIHelpers::getParam('city', '');
$university = APIHelpers::getParam('university', '');

$_SESSION['user']['profile']['country'] = $country;
$_SESSION['user']['profile']['city'] = $city;
$_SESSION['user']['profile']['university'] = $university;

$query = 'UPDATE users_profile SET value = ?, date_change = NOW() WHERE name = ? AND userid = ?';
$stmt = $conn->prepare($query);

$stmt->execute(array(htmlspecialchars($country), 'country', APISecurity::userid()));
$stmt->execute(array(htmlspecialchars($city), 'city', APISecurity::userid()));
$stmt->execute(array(htmlspecialchars($university), 'university', APISecurity::userid()));

$result['result'] = 'ok';


echo json_encode($result);
