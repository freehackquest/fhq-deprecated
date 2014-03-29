<?
	$curdir = dirname(__FILE__);
	include_once "$curdir/../config/config.php";
	include_once "$curdir/fhq_class_security.php";
	include_once "$curdir/fhq_class_database.php";
	
	class fhq_user_info
	{
		function echo_info()
		{
			$db = new fhq_database();
			$security = new fhq_security();

			echo '<pre>
				<a href="javascript:void(0);" id="reload_content" onclick="
					document.getElementById(\'btn_user_info\').innerHTML = \''.mysql_real_escape_string(htmlspecialchars($security->nick())).'\';
				"></a>
				Your name: '.$security->nick().'
				Your score: '.$security->score().'
				Role: '.$security->role().'
				Your place: '.$this->getPlace().' or look <a href=\'scoreboard.php\'>Scoreboard</a><br>
				<input id="edit_new_nick" type="text" value="'.$security->nick().'"/>';
				echo '<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
					load_content_page(\'user_set_new_my_nick\', 
						{
							nick : document.getElementById(\'edit_new_nick\').value
						}
					);
				">Set new nick</a><br></pre>';
		}
		
		function setNewMyNick($nick)
		{
			// pass: 672f88b
			$db = new fhq_database();
			$security = new fhq_security();
			$nick = htmlspecialchars($nick);
			$nick = substr($nick, 0, 40);
			$query = 'UPDATE user SET nick = \''.mysql_real_escape_string($nick).'\' WHERE iduser = '.$security->iduser();
			$security->setNick($nick);
			$result = $db->query( $query );
		}
		
		function getPlace()
		{
			$db = new fhq_database();
			$security = new fhq_security();

			$place = "";
			$query_w1 = "";
			$query_w2 = "";
			
			if($security->isUser())
			{
				$query_w1 = " WHERE role='user' ";
				$query_w2 = " role='user' AND ";
			}

			{
				$query = "SELECT count(iduser) as cnt FROM user WHERE $query_w2 score > (select score from user where iduser = ".$security->iduser().") ORDER BY score DESC";
				$result = $db->query( $query );
				$place = mysql_result($result, 0, 'cnt') + 1;
			}
			
			{
				$query = "SELECT count(iduser) as cnt FROM user $query_w1 ORDER BY score DESC";
				$result = $db->query( $query );
				$place .= " / ".mysql_result($result, 0, 'cnt');
			}
			return $place;
		}
		
		function echo_insert_form()
		{
			// $defs = new fhq_object();
			$content = '
				Add User: <br><br>
				Login: <input type="text" id="login" name="login" value="admin"/> <br>
				Pass: <input type="text" id="pass" name="pass" value="admin"/> <br>
				Nick: <input type="text" id="nick" name="nick" value="admin"/> <br>
				Role: <input type="text" id="role" name="role" value="admin"/>
				<h6>
					role is may be:
					<a href="javascript:void(0);" onclick="document.getElementById(\'role\').value = \'admin\'">admin</a>,
					<a href="javascript:void(0);" onclick="document.getElementById(\'role\').value = \'user\'">user</a>,
					<a href="javascript:void(0);" onclick="document.getElementById(\'role\').value = \'tester\'">tester</a>
				</h6>
				<br>
				
				Logo: <input type="text" id="logo" name="logo" value=""/> <br>
				<br>
				<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="
					var login = document.getElementById(\'login\').value;
					var pass = document.getElementById(\'pass\').value;
					var nick = document.getElementById(\'nick\').value;
					var role = document.getElementById(\'role\').value;
					var logo = document.getElementById(\'logo\').value;
					load_content_page(\'add_user\', { \'login\' : login, \'pass\' : pass, \'nick\' : nick, \'role\' : role, \'logo\' : logo} );
				">Add</a>';
			echo $content;
		}
		
		function add_user($email, $pass, $nickname, $role, $logo)
		{
			$db = new fhq_database();
			$security = new fhq_security();
			$registration = new fhq_registration();
			$nickname = $_GET['nick'];
			// $registration->removeEmail($email);
			$username = base64_encode(strtoupper($email));
			$query = "select count(*) as cnt from user where username='$username'";
			// echo "Query: ".$query."<br>";
			$result = $db->query($query);
			if ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{
					$cnt = $row['cnt'];
					// print_r($row);
					// echo "[cnt = $cnt]";
					if ($cnt == 0) {
						$password = $_GET['pass'];
						$role = $_GET['role'];
						$password_hash = $security->tokenByData( array($password, $username, strtoupper($email)));
						$query = "INSERT user( username, password, nick, role, score, logo) VALUES ('$username','$password_hash','$nickname','$role', 0, '$logo');";
						$result2 = $db->query($query);
						echo "complited<br>";
					}
					else
					{
						echo "user already exists<br>";
					}
			}
			mysql_free_result($result);
		}
	}
	//---------------------------------------------------------------------
?>
