<?
	include_once "basepage.php";

	if( !isset($_SESSION['iduser']) && !isset($_SESSION['nickname']))
	{
		refreshTo("index.php");
		return;
	};

	$exp = $_SESSION['score'];
	$role = $_SESSION['role'];
	$username = $_SESSION['nickname'];
	$userid = $_SESSION['iduser'];
	
	$db = new database;
	$db->connect();
	

	$content = "";
	$title = "";
	$action = "";
	if(isset($_GET['action'])) $action = $_GET['action'];

	$msg_error = "404 quest not found ;)";
	
	$score = $_SESSION['score'];
	
	if( !is_numeric($score) ) 
	{ 
		$score = 0; 
		$_SESSION['score'] = 0; 
	};
		
	$iduser = $_SESSION['iduser'];
	//echo $iduser."<br>";

	if( $action == "allow" )
	{
		$title = "Allow Quests";
		$query = "SELECT
				quest.idquest,
				quest.name,
				quest.score,
				quest.short_text,
				quest.tema
			FROM quest
			WHERE
			(idquest NOT IN (SELECT idquest FROM userquest WHERE userquest.iduser = $iduser)) AND (min_score <= $score )
			ORDER BY quest.score DESC
			";

		//$content .= print_list_quests( $db, $query, "Allow", "#032e03", "#011101" );
		$content .= print_list_quests( $db, $query, "Allow" );

	}
	else if( $action == "process" )
	{
		$title = "Process Quests";
		$score = $_SESSION['score'];

		$query = "
		SELECT
			quest.idquest, quest.name,
			quest.score, quest.short_text, quest.tema
		FROM userquest
		INNER JOIN quest ON quest.idquest = userquest.idquest
		WHERE (userquest.iduser = $iduser)
		AND (userquest.stopdate = '0000-00-00 00:00:00')
		LIMIT 0,100; ";

		$content .= print_list_quests( $db, $query, "Process" );
	}
	else if( $action == "completed" )
	{
		$title = "Completed Quests";

		$query = "SELECT
			quest.idquest, quest.name, 
			quest.score, quest.short_text, quest.tema
		FROM userquest
		INNER JOIN quest ON quest.idquest = userquest.idquest
		WHERE (userquest.iduser = $iduser)
		AND (userquest.stopdate <> '0000-00-00 00:00:00')
		LIMIT 0,100; ";

		$content .= print_list_quests( $db, $query, "Completed" );
	}
	else if( $action == "top100" )
	{
		$title = "Top100";

		$query = "SELECT score, username, nick FROM user ORDER BY score DESC LIMIT 0,100";
		$result = $db->query( $query );
		$count = $db->count( $result );
		for( $i = 0; $i < $count; $i++ )
		{
			$name = mysql_result( $result, $i, 'nick' );
			$score = mysql_result( $result, $i, 'score' );
                        $email = mysql_result( $result, $i, 'username' );
                        $email = base64_decode( $email );

			if(strlen($name) == 0)
			{
			    $name = "nonick";
			};
			//$name = base64_decode( $name );
			$content .= ($i+1)." $name (score: $score );";
			if( $role == 'admin' ) $content .= "<br><font size='2'>email: ".$email."</font><br>";

                        $content .= "<br>";
		};
	}
	else if( $action == "quest" )
	{
		$title = "View Quest";

		$ft_quest = quest_error();

		if( isset($_GET['id']) && is_numeric($_GET['id']) )
                {
                   	$idquest = $_GET['id'];
			$title = "Quest";
			if( !is_numeric($score) ) { $score = 0; $_SESSION['score'] = 0; };
			$query = "SELECT * FROM quest WHERE (idquest = $idquest) AND (min_score <= $score ) LIMIT 0,1";
			$result = $db->query( $query );
			$count = $db->count( $result );
			if( $count == 1)
			{
				$quest_name = mysql_result($result, 0, 'name');
				$quest_score = mysql_result($result, 0, 'score');
				$quest_id = mysql_result($result, 0, 'idquest');
				$quest_stext = mysql_result($result, 0, 'short_text');
				$quest_text = mysql_result($result, 0, 'text');
				$quest_subjects = mysql_result($result, 0, 'tema');
				$quest_min_score = mysql_result($result, 0, 'min_score');

				$content .=
				"
				 <font size=1>Name:</font> <br> #$quest_id $quest_name <br><br>
				 <font size=1>Score:</font> <br> +$quest_score <br><br>
				 <font size=1>Subject:</font> <br> $quest_subjects <br><br>
				 <font size=1>Short Text:</font> <br> $quest_stext <br><br>
				 <font size=1>Full Text:</font> <br> ".parse_bb_code($quest_text)." <br><br>";

				//что можно сделать с квестом:
				$query = "SELECT idquest, stopdate FROM userquest WHERE (idquest = $idquest) AND (iduser = $iduser) LIMIT 0,1";
				$result = $db->query( $query );
				$count = $db->count( $result );	 
				if($count == 1)
				{
					$stopdate = mysql_result($result, 0, 'stopdate');
					if( $stopdate == '0000-00-00 00:00:00')
					{
						$content .= "<br>
					<form method='POST' action='?action=pass_quest&id=$idquest'> 
						<input type='text' size='25' name='answer' value=''> <br>
						<input type='submit' name='take' value='Pass'> <br> <font size=1>try to pass the quest</font> 
					</form>";
					}
					else
					{
						$content .= "<br> Date: '$stopdate' <br> <font size=1>Quest completed</font>";
					};
				}
				else
				{
					
					$content .= "<br>
					<form method='POST' action='?action=take_quest&id=$idquest'> 
						<input type='submit' name='take' value='Take'> <br> <font size=1>Moves to the 'process'</font> 
					</form>";
				}
				//if admin
				if( $role == 'admin' )
				{
					$content .= "<br><br><br>Hello, admin!<br><br>
					<form method='POST'> 
						<input type='file' value='' name='upload_file'/> <input type='submit' value='Upload file'/>
					</form>
					
					<a href='quest.php?action=edit&id=$idquest'>edit quest</a><br><br>
					<a href='quest.php?action=delete&id=$idquest'>delete quest</a><br><br>
					
					
					";
					
				};
				
			}
			else
			{
				$content .= $msg_error;
			};
                };
		
		if( ! isset($_GET['id'])) $content .= $msg_error;

	}
	else if( $action == "take_quest" )
	{
		
		if( isset($_GET['id']) && is_numeric($_GET['id']) )
                {
                	$content .= "take it";	
                	$idquest = $_GET['id'];
                	
                	$query = "SELECT * FROM quest WHERE (idquest = $idquest) AND (min_score <= $score ) LIMIT 0,1";
			$result = $db->query( $query );
			$count = $db->count( $result );
                	if($count == 1 )
                	{
                		$nowdate = udate("Y-m-d H:i:s");
                		$query = "INSERT INTO userquest(idquest,iduser,startdate,stopdate) VALUES($idquest,$iduser,'$nowdate','0000-00-00 00:00:00');";
                		$result = $db->query( $query );
                		refreshTo("?action=quest&id=$idquest");
                	}
                	else
                	{
                		$content .= $msg_error;
                	};
                }
                else
                {
                	$content .= $msg_error;
                }
	}
	else if( $action == "pass_quest" )
	{
		if( isset($_GET['id']) && isset($_POST['answer']) && is_numeric($_GET['id']) )
                {
                	$idquest = $_GET['id'];
                	$answer = base64_encode(htmlspecialchars($_POST['answer']));
                	//echo base64_decode($_POST['answer']);
                	$query = "SELECT idquest FROM quest WHERE (idquest = $idquest) AND (min_score <= $score ) AND (answer = '$answer') LIMIT 0,1";
                	//echo $query;
			$result = $db->query( $query );
			$count = $db->count( $result );
                	if($count == 1 )
                	{
                		$nowdate = udate("Y-m-d H:i:s");
                		$query = "UPDATE userquest SET stopdate = '$nowdate' WHERE idquest = $idquest AND iduser = $iduser;";
                		$result = $db->query( $query );
                		recalculate_score($db, $iduser);
                	};
                };
                refreshTo("?action=quest&id=$idquest");	
	}
	else if( $action == "recalc_score" )
	{
		recalculate_score($db, $iduser);
		refreshTo("?action=");
	}
	else if( $action == "feedback_my")
	{
	    $title = "My Feedbacks";
	    $check = "";
	    $feedback = new feedback();
	    $content .= $feedback->getSubMenu();
	    //$content .= $feedback->getList($db, $userid);
	    
	    if($feedback->recvAnswer($db, $check, $userid))
	    {
	       if(strlen($check) > 0 )
		    $content .= $check;
	       else
	       {
		    refreshTo("?action=feedback_my");
	       };
	    }
	    else
	    {
		$content .= $feedback->getList($db, "no", $userid);
	    };
	}
	else if( $action == "feedback_add" )
	{
		$title = "New Feedback";
		$check = "";

		$feedback = new feedback();
		$content .= $feedback->getSubMenu();
		if( isset($_POST['full_text']) && isset($_POST['type']) )
		{
			$feedback->full_text = $_POST['full_text'];
			$feedback->type = $_POST['type'];
			$feedback->username = $username;
			$feedback->iduser = $iduser;
			
			$check = $feedback->check();
			if( strlen($check) == 0 )
			{
				$result = $feedback->add( $db );
				//echo "$result";
				if($result == '1') refreshTo('?action=feedback_my'); else echo "Please, stop break me!";
			};
		};
		
		$content .= $feedback->getForm("?action=feedback_add","POST");
		$content .= $check;
	}
	else
	{
		refreshTo("main.php?action=allow");
	};

	print_main_page("Free Hack Quest - ".$title, $content );
	exit;
?>



<html>

	<? print_head(  ); ?>

<body class="main">
<center>

<table width='100%' height='100%'>
	<tr>
		<td> 
			<img src='images/minilogo.jpg'/>
		</td>
		<td align='left' valign = 'top' width='100%'>
			<hr>
				<? print_score_name() ?>
			<hr>
				<? print_menu(); ?>
			<br>
		</td>
	</tr>
	<tr>
		<td height='100%' colspan='2' valign='top'>
		<center>
		<?
			//вывод задания
			if( isset( $_GET['idquest'] ) )
			{
				$idquest = $_GET['idquest'];

				$db = mysql_connect( $db_host, $db_username, $db_userpass);
				mysql_select_db( $db_namedb, $db);

				$query = "select * from quests where idquest = $idquest";
				$result = mysql_query( $query );
				
				if( mysql_num_rows( $result ) == 1 )
				{
					$quest_name = mysql_result($result, 0, 'name');
					$quest_score = mysql_result($result, 0, 'score');
					$quest_id = mysql_result($result, 0, 'idquest');
					$quest_stext = mysql_result($result, 0, 'short_text');
					$quest_text = mysql_result($result, 0, 'text');
					$quest_subjects = mysql_result($result, 0, 'tema');
				};
								
				echo "
	<table width=100%>
		<tr>
			<td width=15%></td>
			<td><hr></td>
			<td width=15%></td>
		</tr>

		<tr>
			<td width=15%></td>
			<td>
	Name: $quest_name<br>
	Short Text: $quest_stext<br>
	Score: + $quest_score<br>
	Subjects: $quest_subjects<br>

	</td>
		<td width=15%></td>
	</tr>



	<tr>
		<td width=15%></td>
		<td><hr></td>
		<td width=15%></td>
	</tr>

	<tr>
		<td width=15%></td>
		<td>Text: $quest_text</td>
		<td width=15%></td>
	</tr>

	<tr>
		<td width=15%></td>
		<td><hr></td>
		<td width=15%></td>
	</tr>

	<tr>
		<td width=15%></td>
		<td>
		Статистика:<br>
		number of people have completed the task: ???<br>
		Рейтинг квеста (для тех кто выполнил): ??? ( <a href='#'> + like</a>/ <a href='#'> - sucks</a> ) тут список кому понравилось <br>
		</td>
		<td width=15%></td>
	</tr>

	<tr>
		<td width=15%></td>
		<td><hr></td>
		<td width=15%></td>
	</tr>

	<tr>
		<td width=15%></td>
		<td><form method='GET' action='main.php'> 
				<input type='submit' name='take' value='Take'>
				<input type='hidden' name='idquest' value='$idquest'>
			</form></td>
		<td width=15%></td>
	</tr>
<!-- 
	<tr>
		<td width=15%></td>
		<td>Answer: <form> <input type='text' size=50 name='answer' value=''> <input type='submit' name='ok' value='send'></form></td>
		<td width=15%></td>
	</tr>
-->
</table>
";


$idquest;
			};


			
			
			if( $quests == "allow" )
			{
				

				//$query = "select * from quests where min_score <= $score LIMIT 0,50;";
				
				
			}
			else if( $quests == "process" )
			{
				$score = $_SESSION['score'];
				$iduser = $_SESSION['iduser'];

				$query = "SELECT
						quests.idquest, 
						quests.name, 
						quests.score, 
						quests.short_text, 
						quests.tema
					FROM userquest
					INNER JOIN quests ON quests.idquest = userquest.idquest
					WHERE (userquest.iduser = $iduser)
					AND (userquest.stopdate = '0000-00-00 00:00:00') 
					LIMIT 0,100; ";
				
				$result = $db->query( $query );
				

				$count = mysql_num_rows( $result );
				
				//<font color='#FF0000' >Вы не сможете ознакомитсья с текстом задания, пока не возьмете квест .</font><br><br>				

				echo "
				Process (".$count."):<br><br>
				<table cellspacing=0 cellpadding=10 width=100%>
				";

				print_list_quests( $result , "#032e03", "#011101" );

				echo "</table>";				
			}
			else if( $quests == "completed" )
			{
				$iduser = $_SESSION['iduser'];

				$query = "SELECT
						quests.idquest, 
						quests.name, 
						quests.score, 
						quests.short_text, 
						quests.tema
					FROM userquest
					INNER JOIN quests ON quests.idquest = userquest.idquest
					WHERE (userquest.iduser = 1)
					AND (userquest.stopdate <> '0000-00-00 00:00:00') 
					LIMIT 0,100; ";
				
				$result = $db->query( $query );
				
				$count = mysql_num_rows( $result );
				
				//<font color='#FF0000' >Вы не сможете ознакомитсья с текстом задания, пока не возьмете квест .</font><br><br>				

				echo "
				Completed (".$count."):<br><br>
				<table cellspacing=0 cellpadding=10 width=100%>
				";

				print_list_quests( $result , "#032e03", "#011101" );

				echo "</table>";	
			}
			else if( $quests == "top100" )
			{
				$query = "SELECT score, nick FROM usersy ORDER BY score LIMIT 0,100";
				$result = $db->query( $query );
				$count = $db->count( $result );
				for( $i = 0; $i < $count; $i++ )
				{
					$name = mysql_result( $result, $i, 'nick' );
					$score = mysql_result( $result, $i, 'score' );
					//$name = base64_decode( $name );
					echo  ($i+1)." $name (score: $score ); <br>";
				};
			}
			else if( $quests == "feedback" )
			{
				echo "Just no";
			}
			else
			{
				echo "123";
				refreshTo("main.php?quests=allow");
			}

		?>
		</center>

		</td>
	</tr>
</table>

</center>

</body>
</html>
