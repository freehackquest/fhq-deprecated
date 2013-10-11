<?
session_start();

function refreshTo($new_page)
{
	header ("Location: $new_page");
	exit;
};

include_once "fhq_base.php";
include_once "fhq_class_income.php";
include_once "fhq_class_security.php";
include_once "fhq_class_registration.php";
include_once "fhq_class_feedback.php";
include_once "fhq_class_score.php";
include_once "fhq_class_answer_list.php";
include_once "fhq_class_quest.php";
include_once "fhq_class_mail.php";
include_once "fhq_class_user_info.php";
include_once "fhq_page_listofquests.php";
include_once "fhq_page_registration.php";
include_once "fhq_page_foractivate.php";
include_once "fhq_echo_shortpage.php";
include_once "fhq_echo_mainpage.php";

?>
