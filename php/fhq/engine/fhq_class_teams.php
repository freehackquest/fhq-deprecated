<?php 
	include_once "fhq_class_security.php";
	include_once "fhq_class_database.php";
	include_once "fhq_class_mail.php";
	
	//---------------------------------------------------------------------
	class fhq_teams
	{
		function echo_insert_form()
		{
			/*$content = '
				Add News<br><br>
				<textarea class="full_text" id="news_text"></textarea>
				<br>
        <input type="checkbox" id="send_as_copies" />  Send as copies  <br>
				<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
					var news_text = document.getElementById(\'news_text\').value;
          var send_as_copies = document.getElementById(\'send_as_copies\').checked;          
					load_content_page(\'add_news\', { \'text\' : news_text, \'send_as_copies\' : send_as_copies } );
				">Add</a>
				';
      */
			echo $content;
		}

		function add_team($text, $send_as_copies)
		{
			$security = new fhq_security();
			$db = new fhq_database();
			if( !$security->isAdmin() && !$security->isTester() && !$security->isGod())
				exit;
			
			/*$query = 'insert into news (text, author, datetime_) values(\''.base64_encode($text).'\','.$security->iduser().', now())';
			$result = $db->query( $query );
			
			$mail = new fhq_mail();
			$mail->send_to_all('Free-Hack-Quest: News', $text, $send_as_copies);*/
		}
		
		function save_team($id_news, $text)
		{
			$security = new fhq_security();
			$db = new fhq_database();
			if( !$security->isAdmin() && !$security->isTester() && !$security->isGod())
				exit;

			/*$query = 'UPDATE news SET text = \''.base64_encode($text).'\', datetime_ = now() WHERE id = '.$id_news.';';
			$result = $db->query( $query );
			
			$mail = new fhq_mail();
			$mail->send_to_all('Free-Hack-Quest: News', $text, true);*/
		}

		function echo_teams()
		{
			$security = new fhq_security();
			$db = new fhq_database();
			// $allow_edit = $security->isAdmin() || $security->isTester() || $security->isGod();
			
//			$query = 'SELECT * FROM games INNER JOIN user ON news.author = user.iduser ORDER BY datetime_ DESC LIMIT 0,5;';
//  WHERE end_date < NOW()
  		$query = 'SELECT * FROM teams INNER JOIN user ON teams.author_id = user.iduser ORDER BY change_date DESC LIMIT 0,10;';
			$result = $db->query( $query );
			echo "<center>Teams:</center><br>
				<table cellspacing=2 cellpadding=10>
					<tr>
						<td>Logo</td>
						<td>Name</td>
						<td>User</td>
					</tr>
			";

			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{
				$id_news = $row['id'];
				$name = $row['team_name'];
				$logo = $row['team_logo'];
				$nick = $row['nick'];
				
				echo "<tr>
					<td><img width=100px src='$logo'></td>
					<td>$name</td>
					<td>$nick</td>
				";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
?>
