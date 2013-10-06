<?
class fhq_quest
{
	private $quest_name, $short_text, $full_text, $score, $min_score, $subject, $answer, $reply_answer, $idquest, $for_person;
	private $fields;
	
	function fhq_quest()
	{
		$this->for_person = 0;
    $this->idquest = 0;
	   // $field['idquest'] = new field('idquest', 'idquest', new typefield_int() );
	   // $field['quest_name'] = new field('quest_name','quest_name', new typefield_text() );
	   //    $field['short_text'] = new field('short_text','short_text',);
	   //   $field['full_text'] = new field();
	   //   $field['score'] = new field();
	   //   $field['min_score'] = new field();
	   //   $field['subject'] = new field();
	   //   $field['answer'] = new field();
	   //   $field['reply_answer'] = new field();
	}
	
	//очищаем все переменные
	function setEmptyAll()
	{
		$this->idquest = 0;
		$this->quest_name = "";
		$this->short_text = "";
		$this->full_text = "";
		$this->score = "";
		$this->min_score = "";
		$this->subject = "";
		$this->answer = "";
		$this->reply_answer = "";
		$this->reply_answer = "";
		$this->for_person = 0;
	}

	function setQuestName( $text ) { $this->quest_name = $text; }
	function setShortText( $text ) { $this->short_text = $text; }
	function setFullText( $text ) { $this->full_text = $text; }
	function setScore( $number ) { $this->score = $number; }
	function setMinScore( $number ) { $this->min_score = $number; }
	function setSubject( $text ) { $this->subject = $text; }
	function setAnswer( $text ) { $this->answer = $text; }
	function setForPerson( $number ) { $this->for_person = $number; }
	
	function getQuestName() { return $this->quest_name; }
	function idquest() { return $this->idquest; }


	function check()
	{
		$check = "";

		if( strlen($this->quest_name) < 3 ) $check .= "length of 'Name' must be more than 3 <br>";
		if( strlen($this->short_text) < 10 ) $check .= "length of 'Short text' must be more than 10 <br>";
		if( strlen($this->full_text) < 20 ) $check .= "length of 'Full Text' must be more than 20 <br>";
		if( strlen($this->score) == 0 ) $check .= " 'Score' is empty <br>";
		if( !is_numeric($this->score) ) $check .= " 'Score' is not numeric <br>";
		if( strlen($this->min_score) == 0 ) $check .= " 'Min Score' is empty <br>";
		if( !is_numeric($this->min_score) ) $check .= " 'Min Score' is not numeric <br>";
		if( strlen($this->subject) < 4 ) $check .= "length of 'Subject' must be more than 4 <br>";
		if( strlen($this->answer) < 8 ) $check .= "length of 'Answer' must be more than 8 <br>";	
		return $check;
	}

	function insert()
	{
		$security = new fhq_security();
		$db = new fhq_database();
		
		if(strlen($this->check()) != 0) return 0;
		$query = "INSERT INTO quest( name, short_text, text, score, min_score, tema, answer, for_person )
			VALUES('".base64_encode($this->quest_name)."',
				'".base64_encode($this->short_text)."',
				'".base64_encode($this->full_text)."',
				".$this->score.",
				".$this->min_score.",
				'".base64_encode($this->subject)."',
				'".base64_encode($this->answer)."',
				".$this->for_person."
				) ";
		// echo $query;
		$result = $db->query( $query );
		if( $result == 1 ) 
		{
			$this->idquest = mysql_insert_id();
			return $this->idquest;
		};
		return 0;
	}
	
	function update()
	{
		$security = new fhq_security();
		$db = new fhq_database();
		if($this->idquest == 0)
			return 0;
		
		if(strlen($this->check()) != 0) return 0;
		$query = "UPDATE quest SET 
					name = '".base64_encode($this->quest_name)."', 
					short_text = '".base64_encode($this->short_text)."', 
					text = '".base64_encode($this->full_text)."', 
					score = ".$this->score.", 
					min_score = ".$this->min_score.", 
					tema = '".base64_encode($this->subject)."', 
					answer = '".base64_encode($this->answer)."', 
					for_person = ".$this->for_person."
				WHERE 
					idquest = ".$this->idquest;
			
		// echo $query;
		$result = $db->query( $query );
		if( $result == 1 ) 
			return $this->idquest;
		return 0;
	}

	function select( $id )
	{
		$security = new fhq_security();
		$db = new fhq_database();
		
		// echo "id = $id<br>";
		if( !is_numeric($id) ) return false;

		$query = '
			SELECT * 
			FROM 
				quest 
			WHERE 
				(quest.for_person = 0 OR quest.for_person = '.$security->iduser().')
				AND (idquest = '.$id.')
				AND (min_score <= '.$security->score().' ) 
			LIMIT 0,1;
		';
		$result = $db->query( $query );
		// echo $query."<br>";
		if( !$db->count($result) == 1 ) return false;

		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->idquest = $row['idquest'];
		$this->quest_name = base64_decode($row['name']);
		// echo "quest_name: // ".$this->quest_name."<br>";
		$this->short_text = base64_decode($row['short_text']);
		$this->full_text = base64_decode($row['text']);
		$this->score = $row['score'];
		$this->min_score = $row['min_score'];
		$this->subject = base64_decode($row['tema']);
		$this->answer = base64_decode($row['answer']);
		return true;
	}
	
	function delete( $id )
	{
		$security = new fhq_security();
		$db = new fhq_database();
		
		$query = "DELETE FROM userquest WHERE idquest=$id";
		$result = $db->query($query);
		$query = "DELETE FROM quest WHERE idquest=$id";
		$result = $db->query($query);
		return ($result == 1);
	}

  function take_quest( $idquest )
	{
    $security = new fhq_security();
		$db = new fhq_database();

    if($this->idquest == 0)
      if(!$this->select($idquest))
        return false;

    $where = ' AND (min_score <= '.$security->score().' )';
    if($security->isAdmin()) $where = "";

    $query = 'SELECT * 
      FROM 
        quest 
      WHERE 
        (idquest = '.$idquest.') '.$where.'
        AND (for_person = 0 OR for_person = '.$security->iduser().' ) LIMIT 0,1
     ';
		$result = $db->query( $query );
    $count = $db->count( $result );
    if($count != 1 ) return false;

      
    $nowdate = date('Y-m-d H:i:s');
    $query = 'INSERT INTO userquest(idquest,iduser,startdate,stopdate) 
        VALUES('.$idquest.','.$security->iduser().',\''.$nowdate.'\',\'0000-00-00 00:00:00\');';
    $result = $db->query( $query );

    if($result != '1') return false;      
    return true;
	}

	function pass_quest( $idquest, $answer )
	{
		$security = new fhq_security();
		$db = new fhq_database();

		if($this->idquest == 0)
			if(!$this->select($idquest))
				return false;

		$answer_list = new fhq_answer_list();
		if(md5($answer) != md5($this->answer))
		{
			$answer_list->addTryAnswer( $security->iduser(), $this->idquest, $answer, $this->answer, 'No');
			return false;
		}
		$answer_list->addTryAnswer( $security->iduser(), $this->idquest, $answer, $this->answer, 'Yes');
		
		$nowdate = date('Y-m-d H:i:s');
		$query = 'UPDATE userquest SET stopdate = \''.$nowdate.'\' WHERE idquest = '.$idquest.' AND iduser = '.$security->iduser().';';
		$result = $db->query( $query );
		if($result != '1') return false;
		$score = new fhq_score();
		$score->recalculate_score(false);
		return true;
	}
	
	function fillQuestFromGet()
	{
		if(isset($_GET['idquest']))
			$this->idquest = htmlspecialchars($_GET['idquest']);
		else 
			$this->idquest = 0;
		
		$this->quest_name = htmlspecialchars($_GET['quest_name']);
		$this->short_text = htmlspecialchars($_GET['quest_short_text']);
		$this->full_text = htmlspecialchars($_GET['quest_full_text']);
		$this->score = $_GET['quest_score'];
		$this->min_score = $_GET['quest_min_score'];
		$this->subject = htmlspecialchars($_GET['quest_subject']);
		$this->answer = htmlspecialchars($_GET['quest_answer']);
	}
	
	function getForm()
	{
		$edit_quest = '';
		$js = ''; 
		
		if($this->idquest > 0)
		{
			$edit_quest = '
			<tr>
				<td>id:</td>
				<td>
					'.$this->idquest.'
					<input id="idquest" type="hidden" value="'.$this->idquest.'"/>
				</td>
			</tr>';
			$js = '\'idquest\' : document.getElementById(\'idquest\').value,';
		};
		
		return '
		<table>
		'.$edit_quest.'
		<tr>
			<td>Name:</td>
			<td><input type="text" id="quest_name" size=30 value="'.$this->quest_name.'"/></td>
		</tr>
		<tr>
			<td>Short Text:</td>
			<td><input type="text" size=30 id="quest_short_text" value="'.$this->short_text.'"/></td>
		</tr>
		<tr>
			<td>Full Text:</td>
			<td><textarea class="full_text" id="quest_full_text">'.$this->full_text.'</textarea></td>
		</tr>

		<tr>
			<td>Score(+):</td>
			<td><input type="text" size=30 id="quest_score" value="'.$this->score.'"/></td>
		</tr>
		<tr>
			<td>Min Score(>):</td>
			<td><input type="text" size=30 id="quest_min_score" value="'.$this->min_score.'"/></td>
		</tr>
		<tr>
			<td>Subject:</td>
			<td><input type="text" size=30 id="quest_subject" value="'.$this->subject.'"/></td>
		</tr>
		<tr>
			<td>Answer:</td>
			<td><input type="text" size=30 id="quest_answer" value="'.$this->answer.'"/></td>
		</tr>
		<tr>
			<td></td>
			<td><br>
			<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				var quest_name = document.getElementById(\'quest_name\').value;
				var quest_short_text = document.getElementById(\'quest_short_text\').value;
				var quest_full_text = document.getElementById(\'quest_full_text\').value;
				var quest_score = document.getElementById(\'quest_score\').value;
				var quest_min_score = document.getElementById(\'quest_min_score\').value;
				var quest_subject = document.getElementById(\'quest_subject\').value;
				var quest_answer = document.getElementById(\'quest_answer\').value;

				load_content_page(\'save_quest\', {
						'.$js.'
						\'quest_name\' : quest_name, 
						\'quest_short_text\' : quest_short_text, 
						\'quest_full_text\' : quest_full_text, 
						\'quest_score\' : quest_score, 
						\'quest_min_score\' : quest_min_score, 
						\'quest_subject\' : quest_subject, 
						\'quest_answer\' : quest_answer, 
						\'quest_name\' : quest_name
					});
			">
			Save quest
			</a>
				
			</td>
		</tr>
		<tr>
			<td></td>
			<td><div id="quest_error"></div></td>
		</tr>
		</table>';
	}
	
	function export()
	{
	   $security = new fhq_security();
	   if( !$security->isAdmin() )
		{
		   echo "Not found page";
		   exit;
		}
		echo "Not yet work";
   }
	
	function echo_view_quest()
	{
		$security = new fhq_security();
		echo ' 
		    <a href="javascript:void(0);" id="reload_content" onclick="
				document.getElementById(\'view_score\').innerHTML = \''.$security->score().'\';"></a>

			<font size=1>Name:</font> <br> #'.$this->idquest.' '.htmlspecialchars_decode($this->quest_name).' <br><br>
			<font size=1>Score:</font> <br> +'.$this->score.' <br><br>
			<font size=1>Subject:</font> <br> '.htmlspecialchars_decode($this->subject).' <br><br>
			<font size=1>Short Text:</font> <br> '.htmlspecialchars_decode($this->short_text).' <br><br>
		';
		
		$db = new fhq_database();
		$idquest = $this->idquest;
		$iduser = $security->iduser();

		$query = 'SELECT idquest, stopdate FROM userquest WHERE (idquest = '.$idquest.') AND (iduser = '.$iduser.') LIMIT 0,1';
		$result = $db->query( $query );
		$count = $db->count( $result );	 
		if($count == 1)
		{
			echo '<font size=1>Full Text:</font> <br><pre>'.htmlspecialchars_decode($this->full_text).'</pre><br><br>';

			$stopdate = mysql_result($result, 0, 'stopdate');
			if( $stopdate == '0000-00-00 00:00:00')
			{
				echo '
				<input id="answer_for_quest" type="text"/>
				<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				var answer_for_quest = document.getElementById(\'answer_for_quest\').value;
				load_content_page(\'pass_quest\', { id : '.$idquest.', \'answer\' : answer_for_quest } );
				">Pass Quest</a>
				';
			}
			else
			{
				echo '<br> Date: "'.$stopdate.'" <br> <font size=1>Quest completed</font>';
			};
		}
		else
		{    		
			echo '<br>
				<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'take_quest\', { id : '.$idquest.'} );">Take Quest</a>
				<br> <font size=1>It will be move to the \'process\'</font>';
		}
		
		// todo: if admin
		if( $security->isAdmin() )
		{
			echo '<br><br><br>Hello, admin!<br><br><p>';
				
			if ($handle = opendir('files')) {
				$filter = "quest".$idquest;
				// echo "<br>Directory handle: $handle\n<br>";
				// echo "Entries:\n<br>";
				// echo $filter."<br>";
				/* This is the correct way to loop over the directory. */
				while (false !== ($entry = readdir($handle))) 
				{
					if(substr($entry, 0, strlen($filter)) == $filter)
					{
						$file = 'files/'.$entry;
						echo '<div class="alert alert-info">'.$file.'
							<a class="btn btn-small btn-info" target="_blank" href="'.$file.'">Open</a>
							<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
								if(delete_file())
								{
									load_content_page(\'remove_file\', { id : '.$idquest.', file : \''.$file.'\' } );
								};
							">Remove</a>
							</div>
						';
					}
				}
				closedir($handle);
			}

			echo '</p>
						<input name="file" id="file" size="27" type="file" required multiple />
						
						<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
							
							var files = document.getElementById(\'file\').files;
							// upload_file(files,'.$idquest.');
							load_content_page_files(files, \'upload_files\', { id : '.$idquest.' } );
						">Upload</a><br><br>
					';
			
			echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
				load_content_page(\'edit_quest\', { id : '.$idquest.' } );
				">Edit Quest</a>

            <a class="btn btn-small btn-info" href="content_page.php?content_page=export_quest&id='.$idquest.'">Export</a>

				<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
					if(delete_quest())
					{
						load_content_page(\'delete_quest\', { id : '.$idquest.'} );
					}
				">Delete Quest</a><br><br>
			';
			


		};
	}
};
?>
