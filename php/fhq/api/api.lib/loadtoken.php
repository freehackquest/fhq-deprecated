<?php

$conn = null;
$token = null;
$issetToken = FHQHelpers::issetParam('token');

if ($issetToken) {
	$conn = FHQHelpers::createConnection($config);
	$token = FHQHelpers::getParam('token', '');
	APISecurity::loadByToken($conn, $token);
}
