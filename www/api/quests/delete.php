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

$name = '';
$subject = '';

// check quest
try {
	$stmt = $conn->prepare('SELECT * FROM quest WHERE idquest = ?');
	$stmt->execute(array(intval($questid)));
	if ($row = $stmt->fetch()) {
		$name = $row['name'];
		$subject = $row['subject'];
	} else {
		APIHelpers::showerror(1190, 'Quest #'.$gameid.' does not exists.');
	}
} catch(PDOException $e) {
 	APIHelpers::showerror(1152, $e->getMessage());
}

// todo delete from userquest
// todo recalculate score for users
// todo delete from tryanswer
// todo delete from tryanswer_backup

try {
	$stmt_quest = $conn->prepare('DELETE FROM quest WHERE idquest = ?');
	$stmt_quest->execute(array(intval($questid)));
	
	// remove from tryanswer
	$stmt_tryanswer = $conn->prepare('DELETE FROM tryanswer WHERE idquest = ?');
 	$stmt_tryanswer->execute(array(intval($questid)));
 	
	// remove from tryanswer_backup
	$stmt_tryanswer_backup = $conn->prepare('DELETE FROM tryanswer_backup WHERE idquest = ?');
 	$stmt_tryanswer_backup->execute(array(intval($questid)));

	// remove from userquest
	$stmt_userquest = $conn->prepare('DELETE FROM userquest WHERE idquest = ?');
 	$stmt_userquest->execute(array(intval($questid)));
 	
 	// remove from users_quests
	$stmt_users_quests = $conn->prepare('DELETE FROM users_quests WHERE questid = ?');
 	$stmt_users_quests->execute(array(intval($questid)));
	
	$response['result'] = 'ok';
	APIEvents::addPublicEvents($conn, "quests", "Removed quest #".$questid.' '.htmlspecialchars($name).' (subject: '.htmlspecialchars($subject).') ');
} catch(PDOException $e) {
	APIHelpers::showerror(1063, $e->getMessage());
}

APIQuest::updateMaxGameScore($conn, APIGame::id());

APIHelpers::endpage($response);
