<?php 
	include_once("classDB.php");
	//---------------------------------------------------------------------
	class feedback
	{
		function selectType( $value, $name )
		{
			$arr;
			$arr['value'] = $value;
			$arr['display_name'] = $name;	
			return $arr;		
		}
		
		function getForm( $action, $method )
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
		
			$content = "
				<form action='$action' method='$method'>
				Send to admin:
				<br>
				<select width=80% name='type'>
					$options
				</select>
				<br><br>
				<textarea class='full_text' name='full_text'>".$this->full_text."</textarea>
				<br><br>
				<input type='submit' value='send'/>
				</form>
				";
			return $content;
		}
		
		var $full_text, $type, $username, $iduser;
		
		function check()
		{
			$errors = "";
			if(strlen($this->full_text) < 30) $errors .= "Length description must be > 30.<br>";
			return $errors;
		}
		
		function getSubMenu()
		{
		    $table = "";
		    $table .= "<table>";
		    $table .= "<tr><td width='30px'/>";
		    $table .= "<td><a href='?action=feedback_my'>My Feedbacks</a></td><td width='30px'/>";
		    $table .= "<td><a href='?action=feedback_add'>New Feedback</a></td><td width='30px'>";
		    $table .= "</tr>";
		    $table .= "</table><br><br>";
		    return $table;
		}
		function add( &$db )
		{
			$query = "INSERT INTO feedback( typeFB, full_text, author, dt ) 
				VALUES(\"".strtodb($this->type)."\",\"".strtodb($this->full_text)."\", ".$this->iduser.", now() )";
			//echo $query;
			$result = $db->query($query);
			return $result;
			//echo $result;
		}
		
		function create_token($var1, $var2, $var3, $var4, $var5)
		{
		    return md5("advgf_".$var1."%^789".$var2."_______".$var3."+++++++++".$var4."***===dkjfhksdf9088".$var5);
		}
		
		function getList( &$db , $admin, $userid)
		{
			$cl_F = "#1e1c16";
			$cl_S = "#0d0c09";
			$color1 = "";
			$color2 = "";
			$list = "\n\n<!-- list -->\n\n <center><table width=100% cellpadding='5px' border='0px' cellspacing='5px'>\n";
			$where = "";
			if( $admin != "yes" )
			{
			    $where = " WHERE feedback.author = $userid ";
			}
			$query = "SELECT id, typeFB, username, full_text, dt FROM feedback INNER JOIN user ON feedback.author = user.iduser $where ORDER BY id DESC;";
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
				$typeFB = mysql_result($result, $i, 'typeFB');
				$full_text = mysql_result($result, $i, 'full_text');
				$dt = mysql_result($result, $i, 'dt');
				$list .= 
				"\n\n<tr height='20px'><td width='50px'><td><center></center></td></tr>
				<tr bgcolor='$color1' cellpadding='6' >
					
					<td width='100%' colspan='2'>
					    [$author, $dt, $typeFB] 
					    <pre>$full_text</pre><br/>
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
				
				  $author_msg = base64_decode($author_msg);
				  $list .= "<tr> <td/> 
				  <td bgcolor='#000000'> 
				    [$author_msg,$dt_msg]:<br><pre>$msg</pre> </td> 
				  </tr>";
				};
				
				$token = $this->create_token( $id, $userid, "1", "2", "3" );
				
				$list .= "<tr>
				    <td/>
				    <td bgcolor='#000000'>
				    Answer:
				    <form method='POST'>
					<!-- textarea name='answer'></textarea -->
					<input name='answer_text' type='text'/>
					<input type='submit' value='Send'/>
					<input type='hidden' name='feedback_id' value='$id'/>
					<input type='hidden' name='feedback_id_token' value='$token'>
				    </form>
				    </td>
				</tr>
				\n";
			};
			$list .= "</table></center>";
			return $list;
		}
		
		function recvAnswer(&$db, &$check, $userid)
		{
		
		    if( !isset($_POST['answer_text']))
			return false;
		
		    $answer_text = getFromPost('answer_text');
		    $feedback_id = getFromPost('feedback_id');
		    $feedback_id_token = getFromPost('feedback_id_token');
		
		    //echo $answer_text;
		    if(strlen($answer_text) < 2 )
		    {
			$check .= "fuck!";
			return true;
		    }
		    
		    $token = $this->create_token($feedback_id, $userid, "1", "2", "3");
		    if( $feedback_id_token != $token )
		    {
			$check .= "fuck2!!! userid: $userid";
			return true;
		    }
		    
		    $query = "INSERT INTO feedback_msg(feedback_id, msg, author, dt) VALUES($feedback_id, \"$answer_text\", $userid, now())";
		    $result = $db->query($query);
		    if($result != '1')
		    {
			echo $query;
			return true;
		     };
		     
		     return true;
		
		}
	};
	//---------------------------------------------------------------------

?>
