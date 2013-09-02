<?
include_once "fhq_security.php";
include_once "fhq_database.php";

class fhq_page_listofquests
{
	var $typelist;
	function fhq_page_listofquests($typelist)
	{
		$this->typelist = $typelist;
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
	
	function getQuery_Process($security)
	{
		return '
		SELECT
			quest.idquest, quest.name,
			quest.score, quest.short_text, quest.tema
		FROM userquest
		INNER JOIN quest ON quest.idquest = userquest.idquest
		WHERE (userquest.iduser = '.$security->iduser().')
		AND (userquest.stopdate = "0000-00-00 00:00:00")
		LIMIT 0,100; ';
	}
	
	function getQuery_Allow($security)
	{
		return 'SELECT
				quest.idquest,
				quest.name,
				quest.score,
				quest.short_text,
				quest.tema
			FROM quest
			WHERE
			(idquest NOT IN (SELECT idquest FROM userquest WHERE userquest.iduser = '.$security->iduser().')) AND (min_score <= '.$security->score().' )
			ORDER BY quest.score DESC';
	}
	
	function getQuery_Completed($security)
	{
		return 'SELECT
			quest.idquest, quest.name, 
			quest.score, quest.short_text, quest.tema
		FROM userquest
		INNER JOIN quest ON quest.idquest = userquest.idquest
		WHERE (userquest.iduser = '.$security->iduser().')
		AND (userquest.stopdate <> "0000-00-00 00:00:00")
		LIMIT 0,100; ';
	}
	
	function getQuery_All($security)
	{
		return ' 
		 ';
	}
	
	function echo_content()
	{
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
			return "no quest";
		};

		$content .= "$type($count):<br>
			<table cellspacing=0 cellpadding=10 width=100%>

		<tr>
			<td width=15%> </td>
			<td>#id name</td>
			<td>Score</td>
			<td>Subject</td>
			<td>Short Text</td>
 		</tr>";


		$color = "#000000";
		$color1 = "#003130";
		$color2 = $color1;
		for( $i = 0; $i < $count; $i++ )
		{
			$quest_name = mysql_result( $mysql_result, $i, 'name');
			$quest_score = mysql_result( $mysql_result, $i, 'score');
			$quest_id = mysql_result( $mysql_result, $i, 'idquest');
			$quest_stext = mysql_result( $mysql_result, $i, 'short_text');
			$quest_subjects = mysql_result( $mysql_result, $i, 'tema');

			if( $i % 2 == 0 ) $color = $color1; else $color = $color2;

			$content .= "
				<tr bgcolor = ".$color.">
					<td width=15%> </td>
					<td><a href='main.php?action=quest&id=".$quest_id."'><b>#$quest_id</b> ".$quest_name."</a></td>
					<td>+$quest_score</td>
					<td>$quest_subjects</td>
					<td>$quest_stext</td>
					<td width=15%> </td>
				</tr>

				<tr> <td></td> <td> </td> <td></td> </tr>
				";

		};
		$content .= "</table>";
		return $content;
	}
};
?>
