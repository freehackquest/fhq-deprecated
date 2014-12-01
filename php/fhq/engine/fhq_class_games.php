<?php 
	$curdir = dirname(__FILE__);
	include_once "$curdir/fhq_class_security.php";
	include_once "$curdir/fhq_class_database.php";
	include_once "$curdir/fhq_class_mail.php";
	
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
				<a class="button3" href="javascript:void(0);" onclick="
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
//  WHERE end_date < NOW()
			$query = 'SELECT 
				games.id,
				games.title,
				games.date_start,
				games.date_stop,
				games.logo,
				games.owner,
				user.nick
			FROM games INNER JOIN user ON games.owner = user.iduser ORDER BY date_start DESC LIMIT 0,10;';
			$result = $db->query( $query );

			echo "<center>Games:</center><br>
				<table cellspacing=2 cellpadding=10 class='alt' id='customers'>
					<tr class='alt'>
						<th width='100'>Logo</td>
						<th>Name</td>
						<th>Start Date</td>
						<th>End Date</td>
						<th>Owner</td>
					</tr>
			";

			$bClass = false;
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{
				$id_game = $row['id'];
				$title = $row['title'];
				$date_start = $row['date_start'];
				$date_stop = $row['date_stop'];
				$logo = $row['logo'];
				$nick = $row['nick'];
				$owner = $row['owner'];

				$strclass = '';
				if ($bClass) 
					$strclass = " class='alt' ";
				$bClass = !$bClass;
								
				echo "<tr $strclass>
					<td><img width=100px src='$logo'></td>
					<td><div class='button3 ad' onclick='chooseGame($id_game);'>$title</div></td>
					<td>$date_start</td>
					<td>$date_stop</td>					
					<td>
						<div class='button3 ad' onclick='showUserProfile($owner);'>$nick</div>
					</td>
				";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
?>
