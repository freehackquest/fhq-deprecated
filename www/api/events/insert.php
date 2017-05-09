<?php
/*
 * API_NAME: Insert event
 * API_DESCRIPTION: Method for insert event
 * API_ACCESS: admin
 * API_INPUT: token - string, token
 * API_INPUT: type - string, type of event
 * API_INPUT: message - string, message of event
 */

$curdir_events_insert = dirname(__FILE__);
include_once ($curdir_events_insert."/../api.lib/api.helpers.php");
include_once ($curdir_events_insert."/../../config/config.php");
include_once ($curdir_events_insert."/../api.lib/api.base.php");

$response = APIHelpers::startpage($config);
APIHelpers::checkAuth();

if(!APISecurity::isAdmin())
  APIHelpers::error(403, 'access denie. you must be admin.');

if (!APIHelpers::issetParam('type'))
  APIHelpers::error(400, 'not found parameter type');

if (!APIHelpers::issetParam('message'))
  APIHelpers::error(400, 'not found parameter message');

$type = APIHelpers::getParam('type', 'info');
$message = APIHelpers::getParam('message', '???');

if (strlen($message) <= 3)
  APIHelpers::error(400, 'message must be informative! (more than 3 character)');

$conn = APIHelpers::createConnection($config);
APIEvents::addPublicEvents($conn, $type, $message);
$response['result'] = 'ok';

APIHelpers::endpage($response);
