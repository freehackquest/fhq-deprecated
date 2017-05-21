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
include_once ($curdir."/../../../api.lib/api.quest.php");

$response = APIHelpers::startpage();

APIHelpers::checkAuth();

if (!APIHelpers::issetParam('questid'))
	APIHelpers::error(400, 'Not found parameter "questid"');

$questid = APIHelpers::getParam('questid', 0);

if (!is_numeric($questid))
	APIHelpers::error(400, 'Parameter "questid" must be numeric');

if (!APIHelpers::issetParam('answer'))
	APIHelpers::error(400, 'Not found parameter "answer"');

$answer = APIHelpers::getParam('answer', '');

if ($answer == "")
  APIHelpers::error(400, 'Parameter "answer" must be not empty');

$conn = APIHelpers::createConnection();

$gameid = 0;
$stmt = $conn->prepare('SELECT gameid FROM quest WHERE idquest = ?');
$stmt->execute(array($questid));
if($row = $stmt->fetch()){
	$gameid = $row['gameid'];
}else{
	APIHelpers::error(404, 'Quest not found');
}

$message = '';
if (!APIHelpers::checkGameDates($conn, $gameid, $message))
	APIHelpers::error(400, $message);


$questid = intval($questid);

$response['result'] = 'ok';


$response['gameid'] = $gameid; 
$response['userid'] = APISecurity::userid();

$filter_by_state = APISecurity::isAdmin() ? '' : ' AND quest.state = "open" ';

$userid = APISecurity::userid();
$params[] = $userid;
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
				quest.idquest = ?
				'.$filter_by_state.'
		';


function addUsersQuestAnswers($conn, $questid, $user_answer, $real_answer, $levenshtein, $passed) {
	$answer_try = $user_answer;
	$answer_real = $real_answer;
	$query = 'INSERT INTO users_quests_answers(iduser, idquest, answer_try, answer_real, passed, levenshtein, datetime_try) VALUES (?, ?, ?, ?, ?, ?, NOW());';
	$params[] = APISecurity::userid();
	$params[] = intval($questid);
	$params[] = $answer_try;
	$params[] = $answer_real;
	$params[] = $passed;
	$params[] = $levenshtein;
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	return true;
}
	
try {
	$stmt = $conn->prepare($query);
	$stmt->execute($params);
	if($row = $stmt->fetch()){
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
				
				$new_user_score = APIHelpers::calculateScore();
				$response['new_user_score'] = intval($new_user_score);
				APIHelpers::updateUserRating();
				APIQuest::updateCountUserSolved($conn, $questid);
				addUsersQuestAnswers($conn, $questid, $answer, $real_answer, $levenshtein, 'Yes');

				// add to public events
				if (!APISecurity::isAdmin())
					APIEvents::addPublicEvents($conn, "users", 'User #'.APISecurity::userid().' {'.APISecurity::nick().'} passed quest #'.$questid.' {'.$questname.'} from game #'.$gameid.' (new user score: '.$new_user_score.')');
			} else {
				// check already try pass
				$stmt_check_users_quests_answers = $conn->prepare('select count(*) as cnt from users_quests_answers where answer_try = ? and iduser = ? and idquest = ?');
				$stmt_check_users_quests_answers->execute(array($answer, $userid, intval($questid)));
				if($row_check_users_quests_answers = $stmt_check_users_quests_answers->fetch()) {
					$count = intval($row_check_users_quests_answers['cnt']);
					$response['checkanswer'] = array($answer, $userid, intval($questid));
					if ($count > 0) {
						APIHelpers::error(400, 'Your already try this answer. Levenshtein distance: '.$levenshtein);
					}
				}
				addUsersQuestAnswers($conn, $questid, $answer, $real_answer, $levenshtein, 'No');
				APIHelpers::error(400, 'Answer incorrect. Levenshtein distance: '.$levenshtein);
			};
		} else if ($status == 'completed') {
			APIHelpers::error(400, 'Quest already passed');
		}

		/*if ($status == 'current' || $status == 'completed')
			$response['data']['text'] = $row['text'];*/
	}else{
		APIHelpers::error(404, 'Not found quest');
	}

} catch(PDOException $e) {
	APIHelpers::error(500, $e->getMessage());
}

APIHelpers::endpage($response);
