<?php	
	include_once "engine/fhq.php";
		
	$security = new fhq_security();

	if(!$security->isLogged())
	{
		refreshTo("index.php");
		return;
	};

/*	$income = new fhq_income();
	if ( 
		!$income->isStarted() 
		&& !$security->isAdmin()
		&& !$security->isTester()
		&& !$security->isGod()
	)
	{
			include_once "engine/fhq_page_income.php";
			echo_shortpage(new fhq_page_income());
			exit;
	}

  if($income->isFinished())
  {
    refreshTo("scoreboard.php");
	 	exit;  
  };
*/
	$db = new fhq_database();

	echo_mainpage( new simple_page("", "") );
	exit;
?>
