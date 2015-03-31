<?php	
	include_once "engine/fhq.php";
		
	$security = new fhq_security();

	if(!$security->isLogged())
	{
		refreshTo("index.php");
		return;
	};

	$db = new fhq_database();

	echo_mainpage( new simple_page("", "") );
	exit;
?>
