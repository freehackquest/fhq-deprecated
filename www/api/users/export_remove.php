<?php
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

$result = APIHelpers::startpage();

APIHelpers::checkAuth();

$message = '';

if (!APISecurity::isAdmin())
	APIHelpers::error(403, 'This function allowed only for admin');

$result['result'] = 'ok';

if (!APIHelpers::issetParam('filename'))
	APIHelpers::error(404, 'Parameter filename did not found');

$filename = $curdir_users_export_remove.'/../../files/dumps/'.APIHelpers::getParam('filename', '');

if (!file_exists($filename))
	APIHelpers::error(404, 'File did not found');

unlink($filename);
$result['result'] = 'ok';
$result['data']['filename'] = $filename;

echo json_encode($result);
