<?
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
	
	if($content_page == "quests_all")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		$page = new fhq_page_listofquests('all');
		$page->echo_content($number_of_page);
		exit;
	}
	else if($content_page == "quests_allow")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		$page = new fhq_page_listofquests('allow');
		$page->echo_content($number_of_page);
		exit;
	}
	else if($content_page == "quests_process")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		$page = new fhq_page_listofquests('process');
		$page->echo_content($number_of_page);
		exit;
	}
	else if($content_page == "quests_completed")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		$page = new fhq_page_listofquests('completed');
		$page->echo_content($number_of_page);
		exit;
	}
	else if($content_page == "update_db" && $security->isAdmin()) {
		include dirname(__FILE__)."/db/update_database.php";
		exit;
	}
	else if($content_page == "feedback_my")
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
	else if($content_page == "dr_zoyberg")
	{
		echo '<img src="images/dr_zoyberg.gif"/>';
		exit;
	}
	else if($content_page == "user_info")
	{
		$user_info = new fhq_user_info();
		$user_info->echo_info();
		exit;
	}
	else if($content_page == "scoreboard")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		$score = new fhq_score();
		echo '<h6><a href="scoreboard.php" target="_blank">auto refresh scoreboard here</a></h6>';
		$score->echo_scoreboard();
		exit;
	}
	else if($content_page == "init_scoreboard")
	{
		$score = new fhq_score();
		$score->init_scoreboard();
		exit;
	}
	else if($content_page == "rules")
	{
		include_once(dirname(__FILE__)."/config/rules.html");
		exit;
	}
	else if ($content_page == "user_set_new_my_nick")
	{
		$user_info = new fhq_user_info();
		if(isset($_GET['nick']))
			$user_info->setNewMyNick($_GET['nick']);

		$user_info->echo_info();
		exit;
	}
	else if ($content_page == "user_set_new_password")
	{
		$user_info = new fhq_user_info();
		if(isset($_GET['old_password']) && isset($_GET['new_password']) && isset($_GET['new_password_confirm']))
			$user_info->setNewPassword($_GET['old_password'], $_GET['new_password'], $_GET['new_password_confirm']);
		$user_info->echo_info();
		exit;
	}
	else if ($content_page == "user_set_template")
	{
		$user_info = new fhq_user_info();
		if(isset($_GET['template']) && file_exists('templates/'.$_GET['template']))
			$_SESSION['user']['template'] = $_GET['template'];
		$user_info->echo_info();
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
	else if ($content_page == "news")
	{
		$news = new fhq_news();	
		$news->echo_news();
		exit;
	}
	else if ($content_page == "teams")
	{
		$news = new fhq_teams();
		$news->echo_teams();
		exit;
	}
	else if ($content_page == 'hacker_girl')
	{
		echo '<img src="files/orig/hacker_girl.png">';
		exit;
	}
	else if ($content_page == "add_news" && ($security->isAdmin() || $security->isTester()))
	{
		$news = new fhq_news();
		if(isset($_GET['text']) && isset($_GET['send_as_copies'])) {
			$send_as_copies = ($_GET['send_as_copies'] == 'true') || ($_GET['send_as_copies'] == '1');
			$news->add_news($_GET['text'], $send_as_copies);
			echo "sended";
		}

		$news->echo_insert_form();
		exit;
	}
	else if ($content_page == "save_news" && ($security->isAdmin() || $security->isTester()))
	{
		$news = new fhq_news();
		if(isset($_GET['text']) && isset($_GET['id']))
			$news->save_news($_GET['id'], $_GET['text']);
		echo "saved";
		exit;
	}
	else if ($content_page == "add_user" && $security->isAdmin())
	{
		$user = new fhq_user_info();
		if(isset($_GET['login']) && isset($_GET['pass']) && isset($_GET['nick']) && isset($_GET['role']) && isset($_GET['logo'])) {
			$user->add_user($_GET['login'], $_GET['pass'], $_GET['nick'], $_GET['role'], $_GET['logo']);
			// echo "added";
		}

		$user->echo_insert_form();
		exit;
	}
	else if($content_page == "users"  && $security->isAdmin())
	{
		include_once "engine/fhq_echo_users.php";
		echo_users();
		exit;
	}
	else if ($content_page == "user_set_new_role" && $security->isAdmin())
	{
		if(isset($_GET['iduser']) && isset($_GET['role']))
		{
			$iduser = $_GET['iduser'];
			$role = $_GET['role'];
			$query = 'UPDATE user SET role = \''.$role.'\' WHERE iduser = '.$iduser;
			$result = $db->query( $query );
		}

		include_once "engine/fhq_echo_users.php";
		echo_users();
		exit;
	}
	else if ($content_page == "user_set_new_nick" && $security->isAdmin())
	{
		if(isset($_GET['iduser']) && isset($_GET['nick']))
		{		
			$iduser = $_GET['iduser'];
			$nick = mysql_real_escape_string($_GET['nick']);
			$query = 'UPDATE user SET nick = \''.$nick.'\' WHERE iduser = '.$iduser;
			$result = $db->query( $query );
		}

		include_once "engine/fhq_echo_users.php";
		echo_users();
		exit;
	}
	else if ($content_page == "send_mail_again" && $security->isAdmin())
	{
		if(isset($_GET['iduser']) && isset($_GET['email']))
		{
			$email = $_GET['email'];
			$registration = new fhq_registration();
			$registration->removeEmail($email);
			$registration->addEmailAndSendMail($email);
		}

		include_once "engine/fhq_echo_users.php";
		echo_users();
		exit;
	}
	else if ($content_page == "remove_user" && $security->isAdmin())
	{
		if(isset($_GET['iduser']) && isset($_GET['email']))
		{
			$email = $_GET['email'];
			$registration = new fhq_registration();
			$registration->removeEmail($email);
		}

		include_once "engine/fhq_echo_users.php";
		echo_users();
		exit;
	}
	else if($content_page == "answer_list" && ($security->isAdmin() || $security->isTester())) 
	{
		include_once "engine/fhq_echo_answer_list.php";
		echo_answer_list();
		exit;
	}	
	else if($content_page == "view_quest")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		if(!isset($_GET['id']))
		{
			echo 'not found paramenter "id"';
			exit;	
		};
		
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
	else if($content_page == "edit_quest")
	{
		if(!$security->isAdmin())
		{
				echo "Forbidden";
				exit;
		};

		if(!isset($_GET['id']))
		{
			echo 'not found paramenter "id"';
			exit;	
		};
		
		$id = $_GET['id'];
		
		$quest =  new fhq_quest();
		
		if(!$quest->select($id))
		{
			echo '<font color="#ff0000">Not found quest with id = '.$id.'</font>';
			exit;
		}
		
		echo $quest->getForm();
		
	}
	else if($content_page == "take_quest")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		if(!isset($_GET['id']))
		{
			echo 'Not found paramenter "id"';
			exit;	
		};

		if(!is_numeric($_GET['id']))
		{
		  echo 'Not needed hack me';
				exit;	
		}

		$id = $_GET['id'];
		$quest = new fhq_quest();

		if(!$quest->take_quest($id))
		{
			echo '<font color="#ff0000">Not found quest with id = '.$id.'</font>';
			exit;
		};
		$quest->echo_view_quest();
		exit;
	}
	else if($content_page == "pass_quest")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		if(!isset($_GET['id']) && !isset($_GET['answer']))
		{
			echo 'Not found paramenter "id"';
			exit;
		};

		if(!is_numeric($_GET['id']))
		{
			echo 'don\'t needed hack me';
			exit;
		}

		$id = $_GET['id'];
		$answer = $_GET['answer'];
		$quest = new fhq_quest();

		if(!$quest->pass_quest($id, $answer))
		{
			echo '<font color="#ff0000">Not passed quest #'.$id.'</font><br><br>';
		};
		
		$quest->echo_view_quest();
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
	else if($content_page == "upload_files")
	{
		if(!$security->isAdmin())
		{
			echo "Forbidden";
			exit;
		};
		
		if(!isset($_GET['id']) && count($_FILES) <= 0)
		{
			echo 'Not found paramenter "id" or not files';
			exit;
		};

		if(!is_numeric($_GET['id']))
		{
			echo 'don\'t needed hack me';
			exit;
		}

		$id = $_GET['id'];
		$quest =  new fhq_quest();
		
		if(!$quest->select($id))
		{
			echo '<font color="#ff0000">Not found quest with id = '.$id.'</font>';
			exit;
		}

		$output_dir = 'files/';
		$keys = array_keys($_FILES);
		$prefix = 'quest'.$id.'_';
		for($i = 0; $i < count($keys); $i++)
		{
			$filename = $keys[$i];
			if ($_FILES[$filename]['error'] > 0)
			{
				echo "Error: " . $_FILES[$filename]["error"] . "<br>";
			}
			else
			{
				$full_filename = $output_dir.$prefix.$filename;
				move_uploaded_file($_FILES[$filename]["tmp_name"],$full_filename);
				// echo "Uploaded File: ".$full_filename."<br>";
				if(!file_exists($full_filename))
				  echo "$full_filename - File not exists!";
			}
		}
		
		$quest->echo_view_quest();
		exit;
	}
	else if($content_page == "add_quest")
	{
		if(!$security->isAdmin())
		{
			echo "Forbidden";
			exit;
		};
		
		$quest =  new fhq_quest();
		
		$quest->setEmptyAll();
		echo $quest->getForm();

		// if($check != "" ) $check = "<font color='#FE2E64'>Uncorrect(!):<br>$check</font><br>";		
		exit;
	}
	else if($content_page == "save_quest")
	{
		if(!$security->isAdmin())
		{
			echo "Forbidden";
			exit;
		};
		
		$quest =  new fhq_quest();
		
		$quest->fillQuestFromGet();
		
		$check = $quest->check();

		if( strlen($check) > 0 )
		{
			echo $quest->getForm();
			echo '<font color="#ff0000">'.$check.'</font>';
			exit;
		}
		
		// insert;
		
		if($quest->idquest() == 0)
			$id = $quest->insert();
		else 
			$id = $quest->update();
		
		if($id == 0)
		{
			echo $quest->getForm();
			echo '<font color="#ff0000">could not inserted</font>';
			exit;
		}
		
		if(!$quest->select($id))
		{
			echo '<font color="#ff0000">Not found quest with id = '.$id.'</font>';
			exit;
		}
		
		$quest->echo_view_quest();
		exit;
	}
	else if($content_page == "delete_quest")
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

		if(!is_numeric($_GET['id']))
		{
			echo 'don\'t needed hack me';
			exit;
		}

		$id = $_GET['id'];
		$quest = new fhq_quest();
		if( $quest->delete($id) )
		{
			echo 'Quest was deleted!';
			exit;
		}
		
		$quest->echo_view_quest();
		exit;
	}
	else if($content_page == "recalculate_score")
	{
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}
		
		$score = new fhq_score();
		echo $score->recalculate_score();
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
