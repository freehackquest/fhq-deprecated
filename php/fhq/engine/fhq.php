<?
session_start();

$curdir = dirname(__FILE__);

function refreshTo($new_page)
{
	header ("Location: $new_page");
	exit;
};

function checkGameDates($security, &$message) {
	
	if (!isset($_SESSION['game'])) {
		$message = 'Select game please';
		return false;
	}

	if ($security->isAdmin() || $security->isTester())
		return true;

	$date_start = new DateTime();
	date_timestamp_set($date_start, strtotime($_SESSION['game']['date_start']));
	$date_stop = new DateTime();
	date_timestamp_set($date_stop, strtotime($_SESSION['game']['date_stop']));
	$date_current = new DateTime();
	date_timestamp_set($date_current, time());
	$di_start = $date_current->diff($date_start);
	$di_stop = $date_stop->diff($date_current);

	// echo date_diff($date_current, $date_start)."<br>";
	if ($di_start->invert == 0) {
		echo 'Game will be started after: <br>'.$di_start->d.' day(s) '.$di_start->h.' hour(s) '.$di_start->m.' minute(s) '.$di_start->s.' second(s). <br>';
		return false;
	}

	if ($di_stop->invert == 0) {
		echo 'Game ended: <br>'.$di_stop->d.' day(s) '.$di_stop->h.' hour(s) '.$di_stop->m.' minute(s) '.$di_stop->s.' second(s) ago. <br>';
		return false;
	}

	return true;
}


include_once "$curdir/fhq_base.php";
include_once "$curdir/fhq_class_income.php";
include_once "$curdir/fhq_class_security.php";
include_once "$curdir/fhq_class_registration.php";
include_once "$curdir/fhq_class_objects.php";
include_once "$curdir/fhq_class_feedback.php";
include_once "$curdir/fhq_class_score.php";
include_once "$curdir/fhq_class_answer_list.php";
include_once "$curdir/fhq_class_quest.php";
include_once "$curdir/fhq_class_teams.php";
include_once "$curdir/fhq_class_adviser.php";
include_once "$curdir/fhq_class_mail.php";
include_once "$curdir/fhq_class_user_info.php";
include_once "$curdir/fhq_class_news.php";
include_once "$curdir/fhq_page_listofquests.php";
include_once "$curdir/fhq_page_registration2.php";
include_once "$curdir/fhq_page_foractivate.php";
include_once "$curdir/fhq_echo_shortpage.php";
include_once "$curdir/fhq_echo_mainpage.php";
include_once "$curdir/fhq_class_profile.php";
?>
