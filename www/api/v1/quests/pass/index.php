<?php
/*
 * API_NAME: Try Pass Quest
 * API_DESCRIPTION: Method for check user answer for the quest
 * API_ACCESS: authorized users
 * API_INPUT: questid - integer, Identificator of the quest
 * API_INPUT: answer - string, answer
 * API_INPUT: token - string, token
 */

$curdir = dirname(__FILE__);
include_once ($curdir."/../../../api.lib/api.base.php");
include_once ($curdir."/../../../api.lib/api.answerlist.php");
include_once ($curdir."/../../../api.lib/api.quest.php");
include_once ($curdir."/../../../../config/config.php");

$response = APIHelpers::startpage($config);

APIHelpers::checkAuth();

if (!APIHelpers::issetParam('questid'))
	APIHelpers::showerror2(1212, 400, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::showerror(1215, 'Parameter "questid" must be numeric');

if (!APIHelpers::issetParam('answer'))
	APIHelpers::showerror2(1213, 400, 'Not found parameter "answer"');

$answer = APIHelpers::getParam('answer', '');

if ($answer == "")
  APIHelpers::showerror(1214, 'Parameter "answer" must be not empty');

$conn = APIHelpers::createConnection($config);

$gameid = 0;
$stmt = $conn->prepare('SELECT gameid FROM quest WHERE idquest = ?');
$stmt->execute(array($questid));
if($row = $stmt->fetch()){
	$gameid = $row['gameid'];
}else{
	APIHelpers::showerror2(2213, 404, 'Quest not found');
}

$message = '';
if (!APIHelpers::checkGameDates($conn, $gameid, $message))
	APIHelpers::showerror2(1211, 400, $message);


$questid = intval($questid);

$response['result'] = 'ok';


$response['gameid'] = $gameid; 
$response['userid'] = APISecurity::userid();

$filter_by_state = APISecurity::isAdmin() ? '' : ' AND quest.state = "open" ';
$filter_by_score = APISecurity::isAdmin() ? '' : ' AND quest.min_score <= '.APISecurity::score().' ';

$userid = APISecurity::userid();
$params[] = $userid;
$params[] = $gameid;
$params[] = intval($questid);

$questname = '';

$query = '
			SELECT 
				quest.idquest,
				quest.name,
				quest.answer,
				users_quests.dt_passed
			FROM 
				quest
			LEFT JOIN 
				users_quests ON users_quests.questid = quest.idquest AND users_quests.userid = ?
			WHERE
				quest.gameid = ?
				AND quest.idquest = ?
				'.$filter_by_state.'
				'.$filter_by_score.'
		';

try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	if($row = $stmt->fetch())
	{
		$questname = $row['name'];
		$status = '';
		if ($row['dt_passed'] == null)
			$status = 'open';
		else
			$status = 'completed';

		$response['data'] = array(
			'questid' => $row['idquest'],
			'dt_passed' => $row['dt_passed'],
		);
		$response['quest'] = $row['idquest'];
		$real_answer = $row['answer'];
		$levenshtein = levenshtein(strtoupper($real_answer), strtoupper($answer));		
		
		if ($status == 'open') {

			// check answer
			if (md5(strtoupper($real_answer)) == md5(strtoupper($answer))) {
				$response['result'] = 'ok';

				// insert record
				{
					$stmt_users_quests = $conn->prepare("INSERT INTO users_quests(userid, questid, dt_passed) VALUES(?,?,NOW())");
					$stmt_users_quests->execute(array(APISecurity::userid(), $questid));
				}
				
				$new_user_score = APIHelpers::calculateScore($conn, $gameid);			
				$response['new_user_score'] = intval($new_user_score);
				if (APISecurity::score() != $response['new_user_score'])
				{
					APISecurity::setUserScore($response['new_user_score']);
					$query2 = 'UPDATE users_games SET date_change = NOW(), score = ? WHERE userid = ? AND gameid = ?;';
					$stmt2 = $conn->prepare($query2);
					$stmt2->execute(array(intval($new_user_score), APISecurity::userid(), $gameid));
				}
				APIQuest::updateCountUserSolved($conn, $questid);
				APIAnswerList::addTryAnswer($conn, $questid, $answer, $real_answer, $levenshtein, 'Yes');
				APIAnswerList::movedToBackup($conn, $questid);

				// add to public events
				if (!APISecurity::isAdmin())
					APIEvents::addPublicEvents($conn, "users", 'User #'.APISecurity::userid().' {'.APISecurity::nick().'} passed quest #'.$questid.' {'.$questname.'} from game #'.$gameid.' (new user score: '.$new_user_score.')');
			} else {
				// check already try pass
				$stmt_check_tryanswer = $conn->prepare('select count(*) as cnt from tryanswer where answer_try = ? and iduser = ? and idquest = ?');
				$stmt_check_tryanswer->execute(array($answer, $userid, intval($questid)));
				if($row_check_tryanswer = $stmt_check_tryanswer->fetch()) {
					$count = intval($row_check_tryanswer['cnt']);
					$response['checkanswer'] = array($answer, $userid, intval($questid));
					if ($count > 0) {
						APIHelpers::showerror(1318, 'Your already try this answer. Levenshtein distance: '.$levenshtein);
					}
				}
				APIAnswerList::addTryAnswer($conn, $questid, $answer, $real_answer, $levenshtein, 'No');
				APIHelpers::showerror(1216, 'Answer incorrect. Levenshtein distance: '.$levenshtein);
			};
		} else if ($status == 'completed') {
			APIHelpers::showerror(1217, 'Quest already passed');
		}

		/*if ($status == 'current' || $status == 'completed')
			$response['data']['text'] = $row['text'];*/
	}
	else
	{
		APIHelpers::showerror(1218, 'Not found quest');
	}

} catch(PDOException $e) {
	APIHelpers::showerror(1219, $e->getMessage());
}

APIHelpers::endpage($response);
