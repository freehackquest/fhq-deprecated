<?
	include_once "basepage.php";
	
	if( !isset($_SESSION['iduser']) && !isset($_SESSION['nickname']))
	{
		refreshTo("index.php");
		return;
	};
	
	$exp = $_SESSION['score'];

	$db = new database;
	$db->connect();

	$content = "";
	$title = "";
	$action = "";
	if(isset($_GET['action'])) $action = $_GET['action'];

	$score = $_SESSION['score'];
	$iduser = $_SESSION['iduser'];

	if( $action == "quest" )
	{

	}
	else if( $action == "add" )
	{
		$title = "Add New Quest";
		
		$quest =  new cl_quest();
		
		$quest->setEmptyAll();
		$check = "";

		if( isset($_POST['save']) )
		{
			$quest->setQuestName($_POST['quest_name']);
			$quest->setShortText($_POST['short_text']);
			$quest->setFullText($_POST['full_text']);
			$quest->setScore($_POST['score']);
			$quest->setMinScore($_POST['min_score']);
			$quest->setSubject($_POST['subject']);
			$quest->setAnswer($_POST['answer']);
			
			
			$check = $quest->check();

			//good
			if( strlen($check) == 0 )
			{
				$id = $quest->insert($db);
				if( $id != 0 ) refreshTo("main.php?action=quest&id=$id");
			}
		};	

		if($check != "" ) $check = "<font color='#FE2E64'>Uncorrect(!):<br>$check</font><br>";
		
		$content .= 
		"$check 
		Add New Quest:
		".$quest->getForm("quest.php?action=add", "POST")." ";
		
	}
	else if( $action == "delete" )
	{
		if( isset($_GET['id']) )
		{
			$idquest = $_GET['id'];
			if( is_numeric($idquest) )
			{
			    $quest = new cl_quest();
			    $quest->delete_quest($db, $idquest);
			    $content .= "deleted";
			}
			else
			{
			    $content .= "not hack me";
			}
		}
		else
		{
		    $content .= "What are you want delete?";
		};
	}
	else if( $action == "edit" )
	{
		if( isset($_GET['id']) )
		{
			$idquest = $_GET['id'];
			if( is_numeric($idquest) )
			{
				$title = "Edit Quest";
			
				$quest =  new cl_quest();
		
				$quest->setEmptyAll();
				$check = "";
			
				if( isset($_POST['save']) )
				{
					$quest->setQuestName($_POST['quest_name']);
					$quest->setShortText($_POST['short_text']);
					$quest->setFullText($_POST['full_text']);
					$quest->setScore($_POST['score']);
					$quest->setMinScore($_POST['min_score']);
					$quest->setSubject($_POST['subject']);
					$quest->setAnswer($_POST['answer']);
			
			
					$check = $quest->check();

					//good
					if( strlen($check) == 0 )
					{
						if( $quest->update( $db, $idquest ) ) refreshTo("main.php?action=quest&id=$idquest");
					};
				}
				else
				{
					if( $quest->select($db, $_GET['id'] ) )
					{
						
					}
					else
					{
						$content .= quest_error();
					}
				};
				
				
				if($check != "" ) $check = "<font color='#FE2E64'>Uncorrect(!):<br>$check</font><br>";
					
				$content .= 
				"$check 
				Edit Quest:
				".$quest->getForm("quest.php?action=edit&id=$idquest", "POST")." ";
			}
			else
			{
				$content .= quest_error();
			};		
		}
		else
		{
			$content .= quest_error();
		};
	}
	else if( $action == "process" )
	{
		$title = "Process Quests";
		$score = $_SESSION['score'];
				

	}
	else if( $action == "completed" )
	{
		$title = "Completed Quests";

		$query = "SELECT
			quests.idquest, quests.name, 
			quests.score, quests.short_text, quests.tema
		FROM userquest
		INNER JOIN quests ON quests.idquest = userquest.idquest
		WHERE (userquest.iduser = $iduser)
		AND (userquest.stopdate <> '0000-00-00 00:00:00') 
		LIMIT 0,100; ";
	
		$content .= print_list_quests( $db, $query, "Completed" );
	}
	else if( $action == "top100" )
	{
		$query = "SELECT score, username FROM usersy ORDER BY score DESC LIMIT 0,100";
		$result = $db->query( $query );
		$count = $db->count( $result );
		for( $i = 0; $i < $count; $i++ )
		{
			$name = mysql_result( $result, $i, 'username' );
			$score = mysql_result( $result, $i, 'score' );
			$name = base64_decode( $name );
			$content .= ($i+1)." $name (score: $score ); <br>";
		};	
	}
	else if( $action == "feedback" )
	{
		$content .= "
		Письмо админам:
		<br>
		<select width=80%>
			<option>Жалоба</option>
			<option>Недочет</option>
			<option>Ошибка</option>
			<option>Одобрение</option>
			<option>Предложение</option>
		</select>
		<br>
		<textarea></textarea>
		<br>
		<input type='submit' value='send'/>
		";
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
				$query = "SELECT score, username FROM usersy ORDER BY score LIMIT 0,100";
				$result = $db->query( $query );
				$count = $db->count( $result );
				for( $i = 0; $i < $count; $i++ )
				{
					$name = mysql_result( $result, $i, 'username' );
					$score = mysql_result( $result, $i, 'score' );
					$name = base64_decode( $name );
					echo  ($i+1)." $name (score: $score ); <br>";
				};
			}
			else if( $quests == "feedback" )
			{
				echo "Just no";
			}
			else
			{
				// echo "123";
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
