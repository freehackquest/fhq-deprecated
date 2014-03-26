<?
	include_once "engine/fhq.php";
	
	
	class fhq_page_scoreboard
	{
		function title()
		{
			return 'Scoreboard';
		}

		function echo_head()
		{
			echo '';
		}
		
		function echo_onBodyEnd() {
			echo '';
		}
		
		function echo_content()
		{
			$db = new fhq_database();
			$query = "SELECT iduser, score, nick FROM user WHERE role='user' ORDER BY score DESC";
			$result = $db->query( $query );
			$i = 1;
			echo "<br><br>";
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{      
				$nick = $row["nick"];
				$score = $row["score"];
				echo ($i++)."<font size=5> $nick </font>(score: $score);<br><br>\n";
			}
			mysql_free_result($result);
		}
	};

	echo_shortpage(new fhq_page_scoreboard());

	exit;
?>
