<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Settings
 * API_DESCRIPTION: returned all config. passwords will be hidden.
 * API_ACCESS: only admin
 */
 
$curdir_settings_get = dirname(__FILE__);
include_once ($curdir_settings_get."/../api.lib/api.base.php");
include_once ($curdir_settings_get."/../api.lib/api.security.php");
include_once ($curdir_settings_get."/../api.lib/api.helpers.php");
include_once ($curdir_settings_get."/../../config/config.php");

include_once ($curdir_settings_get."/../api.lib/loadtoken.php");
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1280, 'This method only for admin');

$result = array(
	'result' => 'ok',
	'data' => array(),
);

$result['data'] = $config;

unset($result['data']['mail']['password']);
unset($result['data']['db']['userpass']);

echo json_encode($result);
