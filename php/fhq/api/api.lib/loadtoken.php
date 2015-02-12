<?php

$conn = null;
$token = null;
$issetToken = APIHelpers::issetParam('token');

if ($issetToken) {
	$conn = APIHelpers::createConnection($config);
	$token = APIHelpers::getParam('token', '');
	APISecurity::loadByToken($conn, $token);
}
