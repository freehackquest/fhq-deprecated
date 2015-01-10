<?php

include_once "fhq_class_security.php";
include_once "fhq_class_database.php";
include_once "fhq_class_registration.php";

class fhq_page_foractivate
{
	function title()
	{
		return 'Activate account<br><font size=2><a href="index.php">&larr; go to main page</a></font>';
	}
	
	function echo_head()
	{
		echo '';
	}
	
	function echo_onBodyEnd() {
		echo '';
	}
	
	function echo_content()
	{
		// registration.php
		// return ' blabla ';
		
		$foractivate = $_GET['foractivate'];
		$registration = new fhq_registration();
		$registration->activationAccount($foractivate);
	}
};

?>
