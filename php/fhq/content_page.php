<?
	include_once "engine/fhq.php";

	$security = new fhq_security();
		
	if(!$security->isLogged())
	{
		refreshTo("index.php");
		return;
	};
	
	$db = new fhq_database();
	
	$content_page = "";
	$number_of_page = 0;
	if(isset($_GET['content_page'])) $content_page = $_GET['content_page'];
	if(isset($_GET['number_of_page'])) $number_of_page = $_GET['number_of_page'];
	
	if($content_page == "quests_all")
	{
		$page = new fhq_page_listofquests('all');
		$page->echo_content($number_of_page);
		exit;
	}
	else if($content_page == "quests_allow")
	{	
		$page = new fhq_page_listofquests('allow');
		$page->echo_content($number_of_page);
		exit;
	}
	else if($content_page == "quests_process")
	{
		$page = new fhq_page_listofquests('process');
		$page->echo_content($number_of_page);
		exit;
	}
	else if($content_page == "quests_completed")
	{
		$page = new fhq_page_listofquests('completed');
		$page->echo_content($number_of_page);
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
	else if($content_page == "top100")
	{
		$query = "";
		if($security->isUser())
			$query = "SELECT iduser, score, nick FROM user WHERE role='user' ORDER BY score DESC LIMIT 0,100";
		else
			$query = "SELECT iduser, score, nick, role FROM user ORDER BY score DESC LIMIT 0,100";

		$result = $db->query( $query );
		$i = 1;
		echo "TOP 100<br><br>";
		while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
		{      
			$iduser = $row["iduser"];
			$nick = $row["nick"];
			$score = $row["score"];
			$role = isset($row['role']) ? $row['role'].'<br>' : "";

			$bCurrentUser = $iduser == $security->iduser();

			echo ($i++);
			echo ($bCurrentUser ? "<font size=3 color=#ff0000>" : "<font size=3>");
			echo " $nick (score: $score);</font><br><font size=1>$role</font><br>\n";
		}
		mysql_free_result($result);
		exit;
	}
	else if($content_page == "user_info")
	{
		echo '<pre>
Your name: '.$security->nick().'
Your score: '.$security->score().'
Role: '.$security->role().'
Your place: place / all
</pre>';

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
	else if($content_page == "answer_list" && ($security->isAdmin() || $security->isTester())) 
	{
		echo '<pre>Not work yet</pre>';
		exit;
	}	
	else if($content_page == "view_quest")
	{
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
		
	}
	else if($content_page == "edit_quest")
	{
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
		$score = new fhq_score();
		echo $score->recalculate_score();
		exit;
	}
	else 
	{
		// echo "404 page not found ;)";
		// exit;
	}
	
?>
