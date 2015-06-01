<?php
/*
 * API_NAME: Delete Quest
 * API_DESCRIPTION: Method for delete quest
 * API_ACCESS: admin only
 * API_INPUT: questid - string, Identificator of the quest
 * API_INPUT: token - string, token
 */

$curdir_quests_delete = dirname(__FILE__);
include_once ($curdir_quests_delete."/../api.lib/api.base.php");
include_once ($curdir_quests_delete."/../api.lib/api.game.php");
include_once ($curdir_quests_delete."/../api.lib/api.quest.php");
include_once ($curdir_quests_delete."/../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

$message = '';

if (!APIGame::checkGameDates($message))
	APIHelpers::showerror(1059, $message);

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror(1060, 'Not found parameter "questid"');

if (!APISecurity::isAdmin())
	APIHelpers::showerror(1061, 'Access denied. You are not admin.');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(1062, 'parameter "questid" must be numeric');

$conn = APIHelpers::createConnection($config);

$query = 'DELETE FROM quest WHERE idquest = ?';

// todo delete from userquest
// todo recalculate score for users
// todo delete from tryanswer
// todo delete from tryanswer_backup

try {
	$stmt = $conn->prepare($query);
	$stmt->execute(array(intval($questid)));
	$response['result'] = 'ok';
} catch(PDOException $e) {
	APIHelpers::showerror(1063, $e->getMessage());
}

APIQuest::updateMaxGameScore($conn, APIGame::id());

APIHelpers::endpage($response);
