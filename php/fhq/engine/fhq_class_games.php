<?php 
	include_once "fhq_class_security.php";
	include_once "fhq_class_database.php";
	include_once "fhq_class_mail.php";
	
	//---------------------------------------------------------------------
	class fhq_games
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

		function add_game($text, $send_as_copies)
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
		
		function save_game($id_news, $text)
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

		function echo_games()
		{
			$security = new fhq_security();
			$db = new fhq_database();
			// $allow_edit = $security->isAdmin() || $security->isTester() || $security->isGod();
			
//			$query = 'SELECT * FROM games INNER JOIN user ON news.author = user.iduser ORDER BY datetime_ DESC LIMIT 0,5;';
  		$query = 'SELECT * FROM games INNER JOIN user ON news.author = user.iduser WHERE end_date < NOW() ORDER BY start_date DESC LIMIT 0,10;';
			$result = $db->query( $query );
			echo "<center>Games:</center><br>";
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{
				$id_news = $row['id'];
				$name = $row['name'];
				$start_date = $row['start_date'];
				$end_date = $row['end_date'];
				$nick = $row['nick'];
				echo "<pre><b>$name</b><br>[begin: $start_date,<br> end: $start_end]";
				
				/*if($iduser == $security->iduser())
				{
					echo '<hr><textarea id="news_text_'.$id_news.'">'.htmlspecialchars($text).'</textarea>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
					var news_text = document.getElementById(\'news_text_'.$id_news.'\').value;
					load_content_page(\'save_news\', { \'id\' : '.$id_news.', \'text\' : news_text });
				">Save</a>';
				};*/
				
				echo "<br></pre>";	
			}
		}
	}
?>
