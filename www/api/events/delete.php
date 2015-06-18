<?php
/*
 * API_NAME: Delete event
 * API_DESCRIPTION: Method for remove event
 * API_ACCESS: admin
 * API_INPUT: token - string, access token
 * API_INPUT: id - integer, id of event
 */

$curdir_events_delete = dirname(__FILE__);
include_once ($curdir_events_delete."/../api.lib/api.helpers.php");
include_once ($curdir_events_delete."/../../config/config.php");
include_once ($curdir_events_delete."/../api.lib/api.base.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1003, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1004, 'not found parameter id');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(1005, 'incorrect id');

$conn = APIHelpers::createConnection($config);

try {
 	$stmt = $conn->prepare('DELETE FROM public_events WHERE id = ?');
 	$stmt->execute(array(intval($id)));
 	$response['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1006, $e->getMessage());
}

APIHelpers::endpage($response);
