<?php
header("Access-Control-Allow-Origin: *");

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../../config/config.php");

APIHelpers::checkAuth();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$result['result'] = 'ok';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(900, 'Access only for admin');

// TODO: added pagginator
$conn = APIHelpers::createConnection($config);


if (!APIHelpers::issetParam('userid'))
  APIHelpers::showerror(901, 'Not found parameter userid');

$userid = APIHelpers::getParam('userid', 0);

if (!is_numeric($userid))
	APIHelpers::showerror(902, 'Error 902: incorrect userid');

include_once(dirname(__FILE__).'/../api.lib/SxGeo.php');
$SxGeo = new SxGeo(dirname(__FILE__).'/../api.lib/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);

$userid = intval($userid);
$query = 'SELECT id, ip, country, city, browser, date_sign_in FROM users_ips WHERE userid = ? ORDER BY id DESC LIMIT 0,25';
try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($userid)));
	while($row = $stmt->fetch())
	{
		$country = $row['country'];
		$city = $row['city'];
		$id = $row['id'];
		$ip = $row['ip'];
		$browser = $row['browser'];
		if (strlen($country) == 0 && strlen($city) == 0) {
			$o = $SxGeo->get($ip);
			if ($o) {
				$country = $o['country'];
				$city = $o['city'];
				$stmt2 = $conn->prepare('UPDATE users_ips SET country = ?, city = ? WHERE id = ?');
				$stmt2->execute(array($country, $city, $id));
			}
		}
				
		$result['data'][] = array(
			'id' => $id,
			'ip' => $ip,
			'country' => $country,
			'city' => $city,
			'date' => $row['date_sign_in'],
			'browser' => $browser,
		);
	}
	$result['result'] = 'ok';
} catch(PDOException $e) {
	showerror(822, 'Error 822: ' + $e->getMessage());
}
unset($SxGeo);
echo json_encode($result);
