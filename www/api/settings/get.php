<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir_settings_get = dirname(__FILE__);
include_once ($curdir_settings_get."/../api.lib/api.base.php");
include_once ($curdir_settings_get."/../api.lib/api.security.php");
include_once ($curdir_settings_get."/../api.lib/api.helpers.php");
include_once ($curdir_settings_get."/../../config/config.php");

include_once ($curdir_settings_get."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1280, 'access denie. you must be admin.');

$result = array(
	'result' => 'ok',
	'data' => array(),
);

$result['data'] = $config;

unset($result['data']['mail']['password']);
unset($result['data']['db']['userpass']);

echo json_encode($result);
