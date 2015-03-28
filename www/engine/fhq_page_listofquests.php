<?php
include_once "fhq_class_security.php";
include_once "fhq_class_database.php";

class fhq_page_listofquests
{
	var $typelist;	
	function fhq_page_listofquests($typelist)
	{
		$this->typelist = $typelist;
	}

	function echo_head()
	{
		echo '';
	}

	function title()
	{
		$t = $this->typelist;
		if($t == 'all')
			return 'All quests';
		else if($t == 'allow')
			return 'Allow quests';
		else if($t == 'completed')
			return 'Completed quests';
		else if($t == 'process')
			return 'Process quests';
		return 'Unknown list';
	}
	
	function echo_content()
	{
		if (!isset($_SESSION['game']))
		{
			echo 'Please choose the game<br><a class="button3 ad" href="javascript:void(0);" onclick="loadGames();">Games</a>';
			exit;
		}
		$security = new fhq_security();
		$errmsg = "";
		if (!checkGameDates($security, $errmsg)) {
			echo $errmsg;
			exit;
		}

		$query = "";
		$security = new fhq_security();
		
		$t = $this->typelist;
		if($t == 'all')
			$query = $this->getQuery_All($security);
		else if($t == 'allow')
			$query = $this->getQuery_Allow($security);
		else if($t == 'completed')
			$query = $this->getQuery_Completed($security);
		else if($t == 'process')
			$query = $this->getQuery_Process($security);


		if(strlen($query) == 0) return 'Not found list';	
		$db = new fhq_database();

		$color = "";
		$content = "";

		$mysql_result = $db->query( $query );
		
		$count = $db->count( $mysql_result );

		if( $count == 0) 
		{
			echo "No found quests.<br>
				Try change game.";
			echo '<a class="button3 ad" href="javascript:void(0);" onclick="loadGames();">Games</a>';
			return;
		};

		$type = $this->typelist;

		echo "$type($count):<br><br>
		<table cellspacing=2 cellpadding=10 class='alt' id='customers'>
					<tr class='alt'>
						<th width=100px>Subject</th>
						<th>Quests</th>
					</tr>
		";

		echo '<p>';
		function text_decode($text)
		{
				return htmlspecialchars_decode(base64_decode($text));
		}
		
		$tema = "";
		$bClass = false;
		for( $i = 0; $i < $count; $i++ )
		{
			$quest_name = text_decode(mysql_result( $mysql_result, $i, 'name'));
			$quest_score = mysql_result( $mysql_result, $i, 'score');
			$quest_id = mysql_result( $mysql_result, $i, 'idquest');
			$quest_subjects = text_decode(mysql_result( $mysql_result, $i, 'tema'));
			
			if( $tema != $quest_subjects)
			{
				$strclass = '';
				if ($bClass) 
					$strclass = " class='alt' ";
				$bClass = !$bClass;
			
				if ($tema != '') echo "</td></tr>";
				$tema = $quest_subjects;
				echo "<tr $strclass><td>$tema</td>
				<td>
				";
			}

			echo '
				<a class="btn btn-large btn-primary" href="javascript:void(0);" onclick="load_content_page(\'view_quest\', { id : '.$quest_id.'} );">
					<font size=1>'.$quest_id.' '.$quest_name.'</font><br>
						<font size=5>+'.$quest_score.'</font><br>
						<font size=1> sub: '.$quest_subjects.'</font>
						</a>
				';

		};
		echo "</td><tr> </table>";
	}
	
	function echo_onBodyEnd() {
		echo '';
	}
};
?>
