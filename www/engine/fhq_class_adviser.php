<?php 
	$curdir = dirname(__FILE__);
	include_once "$curdir/fhq_class_security.php";
	include_once "$curdir/fhq_class_database.php";
	
	//---------------------------------------------------------------------
	class fhq_adviser
	{
		function echo_insert_form()
		{
			$content = '
				Add adviser<br><br>
				<table width=100%>
					<tr>
						<td align="right">Title:</td>
						<td><input type="text" id="adviser_title" value=""/></td>
					</tr>
					<tr>
						<td align="right">Text:</td>
						<td><textarea class="full_text" id="advisers_text"></textarea></td>
					</tr>
					<tr>
						<td align="right"></td>
						<td>
							<a class="button3" href="javascript:void(0);" onclick="
								var adviser_title = document.getElementById(\'adviser_title\').value;
								var adviser_text = document.getElementById(\'advisers_text\').value;
								load_content_page2(\'advisers\', { \'adviser_text\' : adviser_text, \'adviser_title\' : adviser_title } );
							">Add</a>		
						</td>
					</tr>
				</table>';
			echo $content;
		}

		function add_adviser($title, $text)
		{
			$security = new fhq_security();
			$db = new fhq_database();
			
			$idgame = 0;
			if (isset($_SESSION['game']))
				$idgame = $_SESSION['game']['id'];
			
			$query = 'insert into 
				advisers (title, text, owner, date_change, checked, mark, idgame)
				values(
					\''.mysql_real_escape_string($title).'\',
					\''.mysql_real_escape_string($text).'\',
					'.$security->iduser().',
					now(),
					0,
					0,
					'.$idgame.'
				)';
			$result = $db->query( $query );
		}
		
		function setNewMark($id_adviser, $mark, $iduser, $idgame)
		{
			$security = new fhq_security();
			$db = new fhq_database();
			if( !$security->isAdmin() && !$security->isTester() && !$security->isGod())
				return;

			$query = 'UPDATE advisers SET mark = '.$mark.', checked = 1, date_change = now() 
				WHERE 
					id = '.$id_adviser.' 
					and owner = '.$iduser.'
					and idgame = '.$idgame.'
				;
			';
			$db->query( $query );
			
			// recalculate score for advisers
			$query = 'select SUM(mark) as sm from advisers where idgame = '.$idgame.' and owner = '.$iduser.';';
			$result = $db->query($query);
			$newscore = mysql_result($result, 0, 'sm');
			mysql_free_result($result);
			echo $newscore;
			$score = new fhq_score();
			$score->update_score('Advisers', $idgame, $iduser, $newscore);
		}

		function echo_advisers($number_page)
		{
			$security = new fhq_security();
			$db = new fhq_database();
			$isAdmin = $security->isAdmin() || $security->isTester();
			
			$idgame = 0;
			if (isset($_SESSION['game']))
				$idgame = $_SESSION['game']['id'];
				
			$where = (!$isAdmin ? ' AND owner = '.$security->iduser().' ' : '');

			$records_on_page = 6;
			
			$query = 'FROM advisers INNER JOIN user ON advisers.owner = user.iduser WHERE idgame = '.$idgame.' '.$where.' ORDER BY checked, date_change DESC';
			
			$result = $db->query( 'SELECT count(id) as cnt '.$query );
			$alladvisers = mysql_result($result, 0, 'cnt');
			mysql_free_result($result);
			
			echo "<center>Advisers( $alladvisers ):</center><br>";

			if($alladvisers / $records_on_page > 0)
			{
				$count_pages = $alladvisers / $records_on_page;
				
				for($i = 0; $i < $count_pages; $i++)
				{
					if($i == $number_page)
						echo ' [ '.($i+1).' ] ';
					else
						echo '<a class="button3" href="javascript:void(0);" onclick="load_content_page2(\'advisers\', { number_of_page : \''.$i.'\'} );">'.($i+1).'</a>';
				}
			}
			echo '<br><br>';
			
			$start_record = $number_page*$records_on_page;
	
	
			// $query = 'SELECT * FROM games INNER JOIN user ON news.author = user.iduser ORDER BY datetime_ DESC LIMIT 0,5;';
			// WHERE end_date < NOW()
			
			$query = 'SELECT * '.$query.' LIMIT '.$start_record.','.$records_on_page;;
			$result = $db->query( $query );

			echo "
				<table cellspacing=2 cellpadding=10 class='alt' id='customers'>
					<tr class='alt'>
						<th width=100px>Owner</th>
						<th>Title/Text</th>
						<th>Mark</th>
						<th>Date changed/Checked</th>
					</tr>
			";

			$bClass = false;
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{
				$id_adviser = $row['id'];
				$title = $row['title'];
				$iduser = $row['iduser'];
				$text = $row['text'];
				$logo = $row['logo'];
				$owner = $row['nick'];
				$mark = $row['mark'];
				$date = $row['date_change'];
				$checked = $row['checked'];
				$checked = $checked == 1 ? " checked " : " not checked";
				
				$strclass = '';
				if ($bClass) 
					$strclass = " class='alt' ";
				$bClass = !$bClass;
				
				$form_change = "";
				
				if ($isAdmin) {
					$form_change = '<input type="text" id="adviser_mark_'.$id_adviser.'" value="'.$mark.'"/>
					<a class="button3" href="javascript:void(0);" onclick="
								var adviser_mark = document.getElementById(\'adviser_mark_'.$id_adviser.'\').value;
								load_content_page(\'adviser_set_mark\', { 
									\'adviser_mark\' : adviser_mark, 
									\'id_adviser\' : '.$id_adviser.',
									\'number_of_page\' : '.$number_page.',
									\'iduser\' : '.$iduser.',
									\'idgame\' : '.$idgame.' } 
								);
							">Set</a>
					';
				}

				echo "<tr $strclass>
					<td width=100px><img width=100px src='$logo'><br><center>".htmlspecialchars($owner)."</center></td>
					<td>Title: <pre>".htmlspecialchars($title)."</pre><br>Text:<pre>".htmlspecialchars($text)."</pre></td>
					<td>$mark <br> $form_change</td>
					<td>
						$date<br>
						<h1>$checked</h1>
					</td>
				</tr>";
			}
			echo "</table>";
			
			if ($security->isAdmin()) {

			}
		}
	}
?>
