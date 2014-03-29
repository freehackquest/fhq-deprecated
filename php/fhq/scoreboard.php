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
			echo '
			<script>
				var expo = setTimeout(function() {location.reload();}, 5000);
			</script>
			';
		}
		
		function echo_onBodyEnd() {
			echo '';
		}
		
		function echo_content()
		{
			$db = new fhq_database();
			$query = "SELECT iduser, logo, score, nick FROM user WHERE role='user' ORDER BY score DESC";
			$result = $db->query( $query );
			$i = 1;
			echo "<br><br>";
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{      
				$nick = $row["nick"];
				$score = $row["score"];
				$logo = $row["logo"];
				if ($logo != "") $logo = "<img src='$logo'>";
				echo ($i++)." $logo <font size=5> $nick </font>(score: $score);<br><br>\n";
			}
			mysql_free_result($result);
		}
	};

	echo_shortpage(new fhq_page_scoreboard());

	exit;
?>
