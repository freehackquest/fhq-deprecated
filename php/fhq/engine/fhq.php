<?
session_start();

function refreshTo($new_page)
{
	header ("Location: $new_page");
	exit;
};

include_once "fhq_base.php";
include_once "fhq_class_security.php";
include_once "fhq_echo_shortpage.php";
include_once "fhq_echo_mainpage.php";
include_once "fhq_page_listofquests.php";

?>