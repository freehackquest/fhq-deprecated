<?
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
	
	function getQuery_Process($security)
	{
		return '
		SELECT
			quest.idquest, quest.name,
			quest.score, quest.short_text, quest.tema
		FROM userquest
		INNER JOIN quest ON quest.idquest = userquest.idquest
		WHERE 
		(quest.for_person = 0 or quest.for_person = '.$security->iduser().')
		AND (userquest.iduser = '.$security->iduser().')
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
			(quest.for_person = 0 or quest.for_person = '.$security->iduser().')
			AND (idquest NOT IN (SELECT idquest FROM userquest WHERE userquest.iduser = '.$security->iduser().')) AND (min_score <= '.$security->score().' )
			ORDER BY quest.score DESC';
	}
	
	function getQuery_Completed($security)
	{
		return 'SELECT
			quest.idquest, quest.name, 
			quest.score, quest.short_text, quest.tema
		FROM userquest
		INNER JOIN quest ON quest.idquest = userquest.idquest
		WHERE 
		(quest.for_person = 0 or quest.for_person = '.$security->iduser().') AND
		(userquest.iduser = '.$security->iduser().')
		AND (userquest.stopdate <> "0000-00-00 00:00:00")
		LIMIT 0,100; ';
	}
	
	function getQuery_All($security)
	{
		return 'SELECT
				quest.idquest,
				quest.name,
				quest.score,
				quest.short_text,
				quest.tema
			FROM quest
			WHERE
				
			ORDER BY quest.score DESC';
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
			echo "No found quests";
			return;
		};

		$type = $this->typelist;

		echo "$type($count):<br><br>
			<!-- <table width=100%>

		<tr class='alt'>
			<td width=15%> </td>
			<td>#id name</td>
			<td>Score</td>
			<td>Subject</td>
			<td>Short Text</td>
  		<td width=15%> </td>
 		</tr> -->";

		echo '<p>';
		function text_decode($text)
		{
				return htmlspecialchars_decode(base64_decode($text));
		}
		
		for( $i = 0; $i < $count; $i++ )
		{
			$quest_name = text_decode(mysql_result( $mysql_result, $i, 'name'));
			$quest_score = mysql_result( $mysql_result, $i, 'score');
			$quest_id = mysql_result( $mysql_result, $i, 'idquest');
			$quest_stext = text_decode(mysql_result( $mysql_result, $i, 'short_text'));
			$quest_subjects = text_decode(mysql_result( $mysql_result, $i, 'tema'));

			echo '
				<!-- <tr >
					<td width=15%> </td>
					<td>
						<a class="btn btn-large btn-primary" href="javascript:void(0);" onclick="load_content_page(\'view_quest\', { id : '.$quest_id.'} );"><b>#'.$quest_id.'</b> '.$quest_name.'</a>
						
						
						
					</td>
					<td>+'.$quest_score.'</td>
					<td>'.$quest_subjects.'</td>
					<td>'.$quest_stext.'</td>
          <td width=15% class="beginend"> </td>
				</tr>
				 -->
				<!-- div class="btn btn-large btn-primary" -->
				<a class="btn btn-large btn-primary" href="javascript:void(0);" onclick="load_content_page(\'view_quest\', { id : '.$quest_id.'} );">
					<font size=1>'.$quest_id.' '.$quest_name.'</font><br>
						<font size=5>+'.$quest_score.'</font><br>
						<font size=1> sub: '.$quest_subjects.'</font>
						</a>
				<!-- /div -->
				<!-- </p> -->
				';

		};
		echo "<!-- </table> -->";
	}
	
	function echo_onBodyEnd() {
		echo '';
	}
};
?>
