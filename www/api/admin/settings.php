<?php
/*
 * API_NAME: Settings
 * API_DESCRIPTION: returned all config. Passwords will be removed.
 * API_ACCESS: only admin
 * API_INPUT: token - guid, secret token
 */
 
$curdir_admin_settings = dirname(__FILE__);
include_once ($curdir_admin_settings."/../api.lib/api.base.php");
include_once ($curdir_admin_settings."/../api.lib/api.security.php");
include_once ($curdir_admin_settings."/../api.lib/api.helpers.php");
include_once ($curdir_admin_settings."/../api.lib/api.updates.php");
include_once ($curdir_admin_settings."/../../config/config.php");

$response = APIHelpers::startpage($config);
$conn = APIHelpers::createConnection($config);

APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1280, 'This method only for admin');

$response['result'] = 'ok';
$response['data'] = $config;

unset($response['data']['mail']['password']);
unset($response['data']['db']['userpass']);

$response['data']['db']['version'] = APIUpdates::getVersion($conn);

APIHelpers::endpage($response);
