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
			include_once "engine/fhq_page_income.php";
			echo_shortpage(new fhq_page_income());			
			exit;
	}
	
	$db = new fhq_database();

	$content = "";
	$title = "";
	$action = "";
	if(isset($_GET['action'])) $action = $_GET['action'];

	$msg_error = "404 quest not found ;)";
	$iduser = $security->iduser();
	//echo $iduser."<br>";

  if( $action == "quest" )
	{
		$title = "View Quest";

		$ft_quest = quest_error();

		if( isset($_GET['id']) && is_numeric($_GET['id']) )
                {
                   	$idquest = $_GET['id'];
			$title = 'Quest';
			
			$query = '
				SELECT * 
				FROM
					quest 
				WHERE 
					(quest.for_person = 0 OR quest.for_person = '.$security->iduser().')
					AND (quest.idquest = $idquest) 
					AND (min_score <= '.$security->score().' ) 
				LIMIT 0,1';
				
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
	else
	{
		// refreshTo("main.php?action=allow");
	};

	echo_mainpage( new simple_page($title, $content) );
	exit;
?>
