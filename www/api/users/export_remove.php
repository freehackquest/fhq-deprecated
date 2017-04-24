<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

/*
 * API_NAME: Remove dump of users
 * API_DESCRIPTION: Method will be remove zip-archive
 * API_ACCESS: admin only
 * API_INPUT: filename - string, filename for removing
 * API_OKRESPONSE: { "result":"ok", "data" : { "filename" : "files/dumps/users_XXXX.zip" } }
 */

$curdir_users_export_remove = dirname(__FILE__);
include_once ($curdir_users_export_remove."/../api.lib/api.base.php");
include_once ($curdir_users_export_remove."/../api.lib/api.game.php");
include_once ($curdir_users_export_remove."/../../config/config.php");

$result = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1297, 'This function allowed only for admin');

$result['result'] = 'ok';

if (!APIHelpers::issetParam('filename'))
	APIHelpers::showerror(1298, 'Parameter filename did not found');

$filename = $curdir_users_export_remove.'/../../files/dumps/'.APIHelpers::getParam('filename', '');

if (!file_exists($filename))
	APIHelpers::showerror(1299, 'File did not found');

unlink($filename);
$result['result'] = 'ok';
$result['data']['filename'] = $filename;

echo json_encode($result);
