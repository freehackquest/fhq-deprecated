<?php
/*
 * API_NAME: Feedback Message Update
 * API_DESCRIPTION: Method for change feedback text
 * API_ACCESS: admin only
 * API_INPUT: id - integer, feedback message id
 * API_INPUT: text - string, text message
 * API_INPUT: token - string, token
 */

$curdir_events_insert = dirname(__FILE__);
include_once ($curdir_events_insert."/../api.lib/api.helpers.php");
include_once ($curdir_events_insert."/../../config/config.php");
include_once ($curdir_events_insert."/../api.lib/api.base.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::showerror(1275, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('id'))
  APIHelpers::showerror(1276, 'not found parameter id');
  
if (!APIHelpers::issetParam('text'))
  APIHelpers::showerror(1278, 'not found parameter text');

$id = APIHelpers::getParam('id', 0);
$text = APIHelpers::getParam('text', '');

if (!is_numeric($id))
  APIHelpers::showerror(1279, 'Parameter id must be integer');

$id = intval($id);

$conn = APIHelpers::createConnection($config);

try {
 	$stmt = $conn->prepare('UPDATE feedback_msg SET text = ? WHERE id = ?');
 	$stmt->execute(array($text, intval($id)));
 	$response['result'] = 'ok';
} catch(PDOException $e) {
 	APIHelpers::showerror(1277, $e->getMessage());
}

APIHelpers::endpage($response);
