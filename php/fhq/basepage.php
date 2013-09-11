<?

session_start();
		
function getFromPost($name)
{
	$m = "";
	if( isset( $_POST[$name] ) )
	{
		$m = htmlspecialchars( $_POST[$name] );
	};
	return $m;
};

//---------------------------------------------------------------------

	function refreshTo($new_page)
	{
		//header ("Location: $new_page");

		echo "
		<html>
<head>
<script type='text/javascript'>
<!--
function delayer(){
    window.location = '$new_page'
}
//-->
</script>
</head>
<body onLoad=\"setTimeout('delayer()', 0)\">
<h2>Prepare to be redirected!</h2>
<p>This page is a time delay redirect, please update your bookmarks to our new 
location!</p>

</body>
</html>
";
		exit;
	};

	include_once("classDB.php");
	include_once("classFeedback.php");

	//---------------------------------------------------------------------

	function parse_bb_code($text)	
	{
		$text = preg_replace('/\[(\/?)(b|i|u|s)\s*\]/', "<$1$2>", $text);

		$text = preg_replace('/\[br\]/', '<br>', $text);

		$text = preg_replace('/\[code\]/', '<pre><code>', $text);
		$text = preg_replace('/\[\/code\]/', '</code></pre>', $text);

		$text = preg_replace('/\[(\/?)quote\]/', "<$1blockquote>", $text);
		$text = preg_replace('/\[(\/?)quote(\s*=\s*([\'"]?)([^\'"]+)\3\s*)?\]/', "<$1blockquote>Цитата $4:<br>", $text);
		//$text = preg_replace('/\[url\](?:http:\/\/)?([a-z0-9-.\/]+\.\w{2,4})\[\/url\]/', "<a href=\"http://$1\">$1</a>", $text);
               //  date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
		$text = preg_replace('/\[url\](\s+)\[\/url\]/', "<a href='$1'>$1</a>", $text);

		//$text = preg_replace('/\[url\s?=\s?([\'"]?)(?:http:\/\/)?([a-z0-9-.]+\.\w{2,4})\1\](.*?)\[\/url\]/', "<a href=\"http://$2\">$3</a>", $text);


		$text = preg_replace('/\[img\s*\]([^\]\[]+)\[\/img\]/', "<img src='$1'/>", $text);
		$text = preg_replace('/\[img\s*=\s*([\'"]?)([^\'"\]]+)\1\]/', "<img src='$2'/>", $text);

		return $text;
	}
	//---------------------------------------------------------------------

	function print_list_quests( &$db, $query, $type )
	{
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
	};
	//---------------------------------------------------------------------
	function print_info_quest( &$db, $query, $type )
	{

	};
	//---------------------------------------------------------------------
	//хитрая функция
	function udate($format, $utimestamp = null)
	{
		if (is_null($utimestamp))
			$utimestamp = microtime(true);

		$timestamp = floor($utimestamp);
		$milliseconds = round(($utimestamp - $timestamp) * 1000000);

		return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
	}
	//---------------------------------------------------------------------
	
	function quest_error()
	{
		return "404 quest not found ;)";
	};
	//---------------------------------------------------------------------
	
	class typefield_base
	{
	    function cl_typefield()
	    {
	    
	    }
	    
	    function getEditField( $name, $value )
	    {
		return "<input type='text' size='30' name='$name' value='$value'/>";
	    }
	    function getFieldValuePost( $name )
	    {
		
	    }
	};
	
	class typefield_text extends typefield_base
	{
	    
	}
	
	class field
	{
	    private $name, $db_name, $value, $typefield;
	
	    function cl_field( $name, $db_name, $typefield  )
	    {
		$this->typefield = $typefield;
	    }
	    
	    function getType()
	    {
		return $typefield;
	    }
	    
	    function getEditField()
	    {
		return $this->typefield->getEditField($this->name, $this->value);
	    }
	}
	class cl_quest
	{
		private $quest_name, $short_text, $full_text, $score, $min_score, $subject, $answer, $reply_answer, $idquest;
		private $fields;
		
		function cl_quest()
		{
		    $field['idquest'] = new field('idquest', 'idquest', new typefield_int() );
		    $field['quest_name'] = new field('quest_name','quest_name', new typefield_text() );
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
			$this->idquest = "";
			$this->quest_name = "";
 			$this->short_text = "";
 			$this->full_text = "";
			$this->score = "";
			$this->min_score = "";
			$this->subject = "";
			$this->answer = "";
			$this->reply_answer = "";
		}

		function setQuestName( $text ) { $this->quest_name = strtodb($text); }
		function setShortText( $text ) { $this->short_text = strtodb($text); }
		function setFullText( $text ) { $this->full_text = strtodb($text); }
		function setScore( $number ) { $this->score = strtodb($number); }
		function setMinScore( $number ) { $this->min_score = strtodb($number); }
		function setSubject( $text ) { $this->subject = strtodb($text); }
		function setAnswer( $text ) { $this->answer = base64_encode(htmlspecialchars($text)); }

		function getQuestName() { return $this->quest_name; }


		function check()
		{
			$check = "";

			if( strlen($this->quest_name) < 3 ) $check .= "length of 'Name' must be > 3 <br>";
			if( strlen($this->short_text) < 10 ) $check .= "length of 'Short text' must be > 10 <br>";
			if( strlen($this->full_text) < 20 ) $check .= "length of 'Full Text' must be > 20 <br>";
			if( strlen($this->score) == 0 ) $check .= " 'Score' is empty <br>";
			if( !is_numeric($this->score) ) $check .= " 'Score' is not numeric <br>";
			if( strlen($this->min_score) == 0 ) $check .= " 'Min Score' is empty <br>";
			if( !is_numeric($this->min_score) ) $check .= " 'Min Score' is not numeric <br>";
			if( strlen($this->subject) < 4 ) $check .= "length of 'Subject' must be > 4 <br>";
			if( strlen(base64_decode($this->answer)) < 16 ) $check .= "length of 'Answer' must be > 16 <br>";	
			return $check;
		}

		function insert( &$db )
		{
			if(strlen($this->check()) != 0) return 0;
			$query = "INSERT INTO quest( name, short_text, text, score, min_score, tema, answer )
				VALUES('".$this->quest_name."',
				 	'".$this->short_text."',
				 	'".$this->full_text."',
				 	".$this->score.",
				 	".$this->min_score.",
				 	'".$this->subject."',
				 	'".$this->answer."') ";
				// echo $query;
				$result = $db->query( $query );
				if( $result == 1 ) 
				{
					$this->idquest = mysql_insert_id();
			  		return $id;
				};
			return 0;
		}

		function update( &$db, $idquest )
		{
			if( !is_numeric($idquest) ) return false;

			if(strlen($this->check()) != 0) return false;

			$query = "UPDATE quest SET
				name = '".$this->quest_name."',
				short_text = '".$this->short_text."',
				text = '".$this->full_text."',
				score = ".$this->score.",
				min_score = ".$this->min_score.",
				tema = '".$this->subject."',
				answer = '".$this->answer."'
				WHERE idquest = $idquest ;";

				//echo $query;

			$result = $db->query( $query );
			return ( $result == 1);
			//	if( $result == 1 ) return true;
			// return false;
		}

		function select( &$db, $id )
		{
			// echo "id = $id<br>";
			if( !is_numeric($id) ) return false;

			$query = "SELECT * FROM quest WHERE idquest = $id LIMIT 0,1;";
			$result = $db->query( $query );
			// echo $query."<br>";
			if( !$db->count($result) == 1 ) return false;

			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$this->quest_name = $row['name'];
			// echo "quest_name: // ".$this->quest_name."<br>";
			
			
			$this->short_text = $row['short_text'];
			$this->full_text = $row['text'];
			$this->score = $row['score'];
			$this->min_score = $row['min_score'];
			$this->subject = $row['tema'];
			$this->answer = $row['answer'];
			return true;
		}
		
		function delete_quest( &$db, $id )
		{
			$query = "DELETE FROM quest WHERE idquest=$id";
			$result = $db->query($query);
			return ($result == 1);
		}
		
		function getForm( $url, $method )
		{
			return "
			<form action='$url' method='$method'>
			<table>
			<tr>
				<td>Name:</td>
				<td><input type='text' size=30 name='quest_name' value='".$this->quest_name."'/></td>
			</tr>
			<tr>
				<td>Short Text:</td>
				<td><input type='text' size=30 name='short_text' value='".$this->short_text."'/></td>
			</tr>
			<tr>
				<td>Full Text:</td>
				<td><textarea class='full_text' name='full_text'>".$this->full_text."</textarea></td>
			</tr>

			<tr>
				<td>Score(+):</td>
				<td><input type='text' size=30 name='score' value='".$this->score."'/></td>
			</tr>
			<tr>
				<td>Min Score(>):</td>
				<td><input type='text' size=30 name='min_score' value='".$this->min_score."'/></td>
			</tr>
			<tr>
				<td>Subject:</td>
				<td><input type='text' size=30 name='subject' value='".$this->subject."'/></td>
			</tr>
			<tr>
				<td>Answer:</td>
				<td><input type='text' size=30 name='answer' value='".base64_decode($this->answer)."'/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' name='save' value='Save'/></td>
			</tr>
			</table>
		</form>";

		}
	};

	function init_arr_quest()
	{
		$arr;
		$arr['quest_name'] = "";
		$arr['short_text'] = "";
		$arr['full_text'] = "";
		$arr['score'] = "";
		$arr['min_score'] = "";
		$arr['subject'] = "";
		$arr['answer'] = "";
		$arr['reply_answer'] = "";

	};
	//---------------------------------------------------------------------
	function print_form_quest( $url, $method, &$arr )//$quest_name, $short_text, $full_text, $score, $min_score, $subject, $answer, $reply_answer )
	{
		$text = "
		<form action='$url' method='$method'>
			<table>
			<tr>
				<td>Name:</td>
				<td><input type='text' size=30 name='quest_name' value='".$arr['quest_name']."'/></td>
			</tr>
			<tr>
				<td>Short Text:</td>
				<td><input type='text' size=30 name='short_text' value='".$arr['quest_name']."short_text'/></td>
			</tr>
			<tr>
				<td>Full Text:</td>
				<td><textarea class='full_text' name='full_text'>".$arr['quest_name']."$full_text</textarea></td>
			</tr>

			<tr>
				<td>Score(+):</td>
				<td><input type='text' size=30 name='score' value='".$arr['score']."$'/></td>
			</tr>
			<tr>
				<td>Min Score(>):</td>
				<td><input type='text' size=30 name='min_score' value='".$arr['min_score']."'/></td>
			</tr>
			<tr>
				<td>Subject:</td>
				<td><input type='text' size=30 name='subject' value='".$arr['subject']."'/></td>
			</tr>
			<tr>
				<td>Answer:</td>
				<td><input type='text' size=30 name='answer' value='".$arr['reply_answer']."'/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' name='add' value='Save'/></td>
			</tr>
			</table>
		</form>";
		return $text;
	};
?>
