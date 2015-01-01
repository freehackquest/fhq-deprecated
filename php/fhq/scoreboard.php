<?php
	include_once "engine/fhq.php";
	
	
	class fhq_page_scoreboard
	{
		function title()
		{
			return '';
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
			$score = new fhq_score();
			if (isset($_SESSION['game'])) {
				echo '<br><font size=6>'.$_SESSION['game']['title'].'</font><br>
				'.$_SESSION['game']['type_game'].'';
			}
			$score->echo_scoreboard(true);
		}
	};

	echo_shortpage(new fhq_page_scoreboard());

	exit;
?>
