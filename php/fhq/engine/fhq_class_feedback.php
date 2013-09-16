<?php 
	include_once "fhq_class_security.php";
	include_once "fhq_class_database.php";
	
	//---------------------------------------------------------------------
	class fhq_feedback
	{
		var $full;
		function fhq_feedback($full)
		{
			$security = new fhq_security();
			if($full && $security->isAdmin())
				$this->full = true;
		}
		function selectType( $value, $name )
		{
			$arr;
			$arr['value'] = $value;
			$arr['display_name'] = $name;	
			return $arr;		
		}
		
		function echo_insert_form( $action, $method )
		{
			$arr;
			
			$arr[] = $this->selectType("Complaint", "Complaint (Жалоба)" );
			$arr[] = $this->selectType("Defect", "Defect (Недочет)" );
			$arr[] = $this->selectType("Error", "Error (Ошибка)" );
			$arr[] = $this->selectType("Approval", "Approval (Одобрение)" );
			$arr[] = $this->selectType("Proposal", "Proposal (Предложение)" );
			
			$options = "";
			
			for( $i = 0; $i < count($arr); $i++)
			{
				$checked = "";
				if( $arr[$i]['value'] == $this->type) 
					$checked = "selected";
					
				$options .= "\t <option value='".$arr[$i]['value']."' $checked> ".$arr[$i]['display_name']." </option> \n";
			};

			//if( $type == "Complaint" ) $complaint = 
		
			$content = '
				Send to admin:
				<br>
				<select width=80% id="feedback_type">
					'.$options.'
				</select>
				<br><br>
				<textarea class="full_text" id="full_text">'.$this->full_text.'</textarea>
				<br><br>
				<a href="javascript:void(0);" onclick="
					var e = document.getElementById(\'feedback_type\');
					var feedback_type = e.options[e.selectedIndex].value;
					var full_text = document.getElementById(\'full_text\').value;
					load_content_page(\'feedback_add\', {\'feedback_type\' : feedback_type, \'full_text\' : full_text });
				">send</a>
				';
			echo $content;
		}
		
		var $full_text, $type, $username, $iduser;
		
		function check()
		{
			$errors = "";
			if(strlen($this->full_text) < 10) $errors .= "Length description must be >= 10.<br>";
			return $errors;
		}
		
		function echo_menu()
		{
			echo '
	<table>
		<tr><td width="30px"/>
		<td><a href="javascript:void(0);" onclick="load_content_page(\'feedback_my\');">My Feedbacks</a></td><td width="30px"/>
		<td><a href="javascript:void(0);" onclick="load_content_page(\'feedback_add\');">New Feedback</a></td><td width="30px">
		</tr>
		</table><br><br>
';
		}
		function add()
		{
			$security = new fhq_security();
			$db = new fhq_database();
			
			$query = "INSERT INTO feedback( typeFB, full_text, author, dt ) 
				VALUES(\"".strtodb($this->type)."\",\"".strtodb($this->full_text)."\", ".$security->iduser().", now() )";
			//echo $query;
			$result = $db->query($query);
			return $result;
			//echo $result;
		}
		
		function create_token($var1, $var2, $var3, $var4, $var5)
		{
		    return md5("advgf_".$var1."%^789".$var2."_______".$var3."+++++++++".$var4."***===dkjfhksdf9088".$var5);
		}
		
		function echo_list()
		{
			$security = new fhq_security();
			$db = new fhq_database();
			$admin = ($security->isAdmin()) ? true : false;

			$cl_F = "#1e1c16";
			$cl_S = "#0d0c09";
			$color1 = "";
			$color2 = "";
			$list = "\n\n<!-- list -->\n\n <center><table width=100% cellpadding='5px' border='0px' cellspacing='5px'>\n";
			$where = "";

			if( !$admin )
			    $where = ' WHERE feedback.author = '.$security->iduser();
			
			$query = "SELECT id, typeFB, nick, username, full_text, dt FROM feedback INNER JOIN user ON feedback.author = user.iduser $where ORDER BY id DESC;";
			//echo $query;
			$result = $db->query($query);
			$count = $db->count($result);
			
			for( $i = 0; $i < $count; $i++)
			{
				if( $i % 2 == 0 )
				{
					$color1 = $cl_F;
					$color2 = "#380000";
				}
				else
				{
					$color1 = $cl_S;
					$color2 = "#380000";
				}
				$id = mysql_result($result, $i, 'id');
				$author = base64_decode(mysql_result($result, $i, 'username'));
				$nick = mysql_result($result, $i, 'nick');
				$typeFB = mysql_result($result, $i, 'typeFB');
				$full_text = mysql_result($result, $i, 'full_text');
				$dt = mysql_result($result, $i, 'dt');
				
				if($admin) $nick .= ', '.$author;
				
				$list .= 
				"\n\n<tr height='20px'><td width='50px'><td><center></center></td></tr>
				<tr bgcolor='$color1' cellpadding='6' >
					
					<td width='100%' colspan='2'>
					    <pre>[$nick, $dt, $typeFB]<br><br>$full_text</pre><br/>
					</td>
					
				</tr>";
				
				$list .= "<!-- msg -->";
				
				$query_msg = "SELECT * FROM feedback_msg LEFT JOIN user ON feedback_msg.author = user.iduser WHERE feedback_id = $id ORDER BY id DESC";
				$result_msg = $db->query($query_msg);
				$count_msg = $db->count($result_msg);
				for($i_m = 0; $i_m < $count_msg; $i_m++)
				{
				  $msg = mysql_result($result_msg, $i_m, 'msg');
				  $author_msg = mysql_result($result_msg, $i_m, 'username');
				  $dt_msg = mysql_result($result_msg, $i_m, 'dt');
				  $nick_msg = mysql_result($result_msg, $i_m, 'nick');
				  $author_msg = base64_decode($author_msg);
				  
				  if($admin) $nick_msg .= ','.$author_msg;

				  $list .= "<tr> <td/> 
				  <td bgcolor='#000000'> 
				   <pre>[$nick_msg,$dt_msg]:<br>$msg</pre> </td> 
				  </tr>";
				};
				
				$token = $this->create_token( $id, $security->iduser(), "1", "2", "3" );
				
				
				$list .= '
				<tr>
				    <td/>
				    <td bgcolor="#000000">
						Answer:
						<!-- onkeydown="if (event.keyCode == 13) send_answer'.$id.'();" -->
						<input id="answer_text_'.$id.'" type="text"/>
						<a href="javascript:void(0);" onclick="							
							var answer_text = document.getElementById(\'answer_text_'.$id.'\').value;
							load_content_page(\'feedback_add\', { \'feedback_id_token\': \''.$token.'\', \'answer_text\' : answer_text, \'feedback_id\' : \''.$id.'\'});
						">Send</a>
				    </td>
				</tr>';
			};
			$list .= "</table></center>";
			echo $list;
		}
		
		function recvAnswer()
		{
		    if( !isset($_GET['answer_text']))
				return "error feedback_2";

		    $answer_text = htmlspecialchars($_GET['answer_text']);
		    $feedback_id = $_GET['feedback_id'];
		    $feedback_id_token = $_GET['feedback_id_token'];

		    //echo $answer_text;
		    if(strlen($answer_text) < 2 )
				return "It's very short answer... ";
		    
		    if(!is_numeric($feedback_id))
				return "error(feedback:3) feedback_id must be int";
		    
		    $security = new fhq_security();
			$db = new fhq_database();
			
		    $token = $this->create_token($feedback_id, $security->iduser(), "1", "2", "3");
		    if( $feedback_id_token != $token )
				return "error(feedback:4) It is not you!!! you have id: ". $security->iduser();
		    
		    $query = "INSERT INTO feedback_msg(feedback_id, msg, author, dt) VALUES($feedback_id, \"$answer_text\", ".$security->iduser().", now())";
		    $result = $db->query($query);
		    if($result != '1') return "error(feedback:5) query is not right: ".$query;
		}
	};
	//---------------------------------------------------------------------

?>
