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

$response = APIHelpers::startpage();
$conn = APIHelpers::createConnection();

APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::error(403, 'This method only for admin');

$response['result'] = 'ok';
$response['data'] = APIHelpers::$CONFIG;

unset($response['data']['mail']['password']);
unset($response['data']['db']['userpass']);

$response['data']['db']['version'] = APIUpdates::getVersion($conn);

APIHelpers::endpage($response);
