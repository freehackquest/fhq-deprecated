<?php
/*
 * API_NAME: Delete Feedback
 * API_DESCRIPTION: remove feedback and all submessages for it
 * API_ACCESS: admin only
 * API_INPUT: id - string, Identificator of the feedback
 * API_INPUT: token - string, token
 */

$curdir_events_insert = dirname(__FILE__);
include_once ($curdir_events_insert."/../api.lib/api.helpers.php");
include_once ($curdir_events_insert."/../../config/config.php");
include_once ($curdir_events_insert."/../api.lib/api.base.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1243, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1244, 'not found parameter id');

$id = APIHelpers::getParam('id', 0);

if (!is_numeric($id))
  APIHelpers::showerror(1245, 'incorrect id');

$conn = APIHelpers::createConnection($config);

try {
 	$conn->prepare('DELETE FROM feedback WHERE id = ?')->execute(array(intval($id)));
 	$conn->prepare('DELETE FROM feedback_msg WHERE feedback_id = ?')->execute(array(intval($id)));
 	
 	$response['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1246, $e->getMessage());
}

APIHelpers::endpage($response);
