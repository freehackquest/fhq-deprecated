<?php
	include_once "engine/fhq.php";

	$security = new fhq_security();
		
	if(!$security->isLogged())
	{
		refreshTo("index.php");
		return;
	};
	
	$income = new fhq_income();
	if ( 
		!$income->isStarted() 
		&& !$security->isAdmin()
		&& !$security->isTester()
		&& !$security->isGod()
	)
	{
		echo "free-hack-quest not started yet";	
		exit;
	}

  if($income->isFinished())
  {
    refreshTo("scoreboard.php");
	 	exit;  
  };
	
	$db = new fhq_database();
	
	$content_page = "";
	$number_of_page = 0;
	if(isset($_GET['content_page'])) $content_page = $_GET['content_page'];
	if(isset($_POST['content_page'])) $content_page = $_POST['content_page'];
	if(isset($_GET['number_of_page'])) $number_of_page = $_GET['number_of_page'];
	if(isset($_POST['number_of_page'])) $number_of_page = $_POST['number_of_page'];


	if($content_page == "feedback_my")
	{
	    $feedback = new fhq_feedback();
	    $feedback->echo_menu();
	    $feedback->echo_list();
		exit;
	}
	else if($content_page == "feedbacks")
	{
	    $feedback = new fhq_feedback();
	    $feedback->echo_list();
		exit;
	}
	else if($content_page == "about")
	{
	    include("about.php");
		exit;
	}
	else if($content_page == "feedback_add")
	{
		$feedback = new fhq_feedback();
		$feedback->echo_menu();

	    if( isset($_GET['full_text']) && isset($_GET['feedback_type']) )
		{
			$feedback->full_text = $_GET['full_text'];
			$feedback->type = $_GET['feedback_type'];

			$check = $feedback->check();
			if( strlen($check) == 0 )
			{
				$result = $feedback->add();
				//echo "$result";
				if($result == '1') 
				{
					$feedback->echo_list();
					exit;
				};
			};
			echo 'error: '.$check.'<br>';
		};
		
		if( isset($_GET['answer_text']) 
			&& isset($_GET['feedback_id']) 
			&& isset($_GET['feedback_id_token']) 
		)
		{
			echo $feedback->recvAnswer();
			$feedback->echo_list();
			exit;
	    };
		
	    $feedback->echo_insert_form("?action=feedback_add","POST");
		exit;
	}
	else if($content_page == "statistics"  && ($security->isAdmin() || $security->isTester()))
	{
		include_once "engine/fhq_echo_statistics.php";
		echo_statistics();
		echo "not yet work";
		exit;
	}
	else if ($content_page == "advisers")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		$adviser = new fhq_adviser();
		if (isset($_POST['adviser_text']) && isset($_POST['adviser_title'])) {
			$adviser->add_adviser($_POST['adviser_title'], $_POST['adviser_text']);
		}
		$adviser->echo_insert_form();
		$adviser->echo_advisers($number_of_page);
		exit;
	}
	else if ($content_page == "adviser_set_mark" && ($security->isAdmin() || $security->isTester())) {
		$adviser = new fhq_adviser();
		if (isset($_GET['adviser_mark']) && isset($_GET['id_adviser']) && isset($_GET['iduser']) && isset($_GET['idgame'])) {
			$adviser->setNewMark($_GET['id_adviser'], $_GET['adviser_mark'], $_GET['iduser'], $_GET['idgame']);
		}
		$adviser->echo_insert_form();
		$adviser->echo_advisers($number_of_page);
		exit;
	}
	/*else if ($content_page == "send_mail_again" && $security->isAdmin())
	{
		// todo redesign it
		if(isset($_GET['iduser']) && isset($_GET['email']))
		{
			$email = $_GET['email'];
			$registration = new fhq_registration();
			$registration->removeEmail($email);
			$registration->addEmailAndSendMail($email);
		}

		exit;
	}*/
	else if($content_page == "answer_list" && ($security->isAdmin() || $security->isTester())) 
	{
		include_once "engine/fhq_echo_answer_list.php";
		echo_answer_list();
		exit;
	}
	else if($content_page == "export_quest")
	{
		if(!$security->isAdmin())
		{
				echo "Forbidden";
				exit;
		};

		if(!isset($_GET['id']))
		{
			echo 'Not found paramenter "id"';
			exit;	
		};
		
		$id = $_GET['id'];
		
		$quest =  new fhq_quest();
		
		if(!$quest->select($id))
		{
			echo '<font color="#ff0000">Not found quest with id = '.$id.'</font>';
			exit;
		}
		
		// $quest->echo_view_quest();
		
		$quest->export();
		exit;
	}
	else if($content_page == "remove_file")
	{
		if(!$security->isAdmin())
		{
			echo "Forbidden";
			exit;
		};
		
		if(!isset($_GET['id']) && !isset($_GET['file']))
		{
			echo 'Not found paramenter "id"';
			exit;
		};

		if(!is_numeric($_GET['id']))
		{
			echo 'don\'t needed hack me';
			exit;
		}

		if(file_exists($_GET['file']))
			unlink($_GET['file']);
		else
			echo "File not found: '".$_GET['file']."'<br>";
			
		$id = $_GET['id'];
		
		$quest =  new fhq_quest();
		
		if(!$quest->select($id))
		{
			echo '<font color="#ff0000">Not found quest with id = '.$id.'</font>';
			exit;
		}
		
		$quest->echo_view_quest();
		exit;
	}
	else if ($content_page == "profile")
	{
		if (!isset($_GET['user_id']))
		{
			echo "User doesn't found";
			exit;
		}
		else
		{
			$user_id = intval($_GET['user_id']);
			$profile = new fhq_profile();
			echo $profile->get_user_profile($user_id);
			exit;
		}
	}
	else 
	{
		// echo "404 page not found ;)";
		// exit;
	}
	
?>
