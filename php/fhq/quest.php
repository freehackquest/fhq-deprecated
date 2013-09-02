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

	echo_mainpage( new simple_page($title, $content) );
	exit;
?>
