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
			include dirname(__FILE__)."/../config/config.php";

			echo '
				<div class="user_info_table">
					<div class="user_info_row">
						<div class="user_info_param">Your ID:</div>
						<div class="user_info_value">'.$security->userid().'</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Your name:</div>
						<div class="user_info_value">'.$security->nick().'</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Your name:</div>
						<div class="user_info_value">'.$security->nick().'</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Your role:</div>
						<div class="user_info_value">'.$security->role().'</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Your score:</div>
						<div class="user_info_value">'.$security->score().'</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Your place:</div>
						<div class="user_info_value">'.$this->getPlace().' or look <a class="button3 ad" href=\'scoreboard.php\'>Scoreboard</a></div>
					</div>
					<div class="user_info_row_skip">
					</div>
					';

					if (isset($config['profile']) && isset($config['profile']['change_nick']) && $config['profile']['change_nick'] == 'yes') {
						echo '
						<div class="user_info_row">
							<div class="user_info_param">Set your name to:</div>
							<div class="user_info_value">
								<input id="edit_new_nick" type="text" value="'.$security->nick().'"/>
							</div>
						</div>
						<div class="user_info_row">
							<div class="user_info_param"></div>
							<div class="user_info_value">
								<a class="button3 ad" href="javascript:void(0);" onclick="
								load_content_page(\'user_set_new_my_nick\', 
									{
										nick : document.getElementById(\'edit_new_nick\').value
									}
								);
							">Set new nick</a>
							</div>
						</div>
						<div class="user_info_row_skip">
						</div>
						';
					}
					
					echo '
					<div class="user_info_row">
						<div class="user_info_param">Set your country:</div>
						<div class="user_info_value">
							<input id="edit_user_country" type="text" value="'.htmlspecialchars($_SESSION['user']['profile']['country']).'"/>
						</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Set your city:</div>
						<div class="user_info_value">
							<input id="edit_user_city" type="text" value="'.htmlspecialchars($_SESSION['user']['profile']['city']).'"/>
						</div>
					</div>						
					<div class="user_info_row">
						<div class="user_info_param"></div>
						<div class="user_info_value">
							<div class="button3 ad" onclick="update_profile_location();">Update</div>
						</div>
					</div>
					<div class="user_info_row_skip">
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Style</div>
						<div class="user_info_value">
							current style: '.htmlspecialchars($_SESSION['user']['profile']['template']).'<br>
							<select id="edit_style">
								<option selected value="base">base</option>
								<option value="dark">dark</option>
							</select><br>
							<div class="button3 ad" onclick="update_profile_style()">Save</div>
						</div>
					</div>
					';
						
					echo '
					<div class="user_info_row_skip">
					</div>
					<div class="user_info_row">
						<div class="user_info_param">Old password:</div>
						<div class="user_info_value">
							<input id="old_password" type="password" value=""/>
						</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">New password:</div>
						<div class="user_info_value">
							<input id="new_password" type="password" value=""/>
						</div>
					</div>
					<div class="user_info_row">
						<div class="user_info_param">New password(confirm):</div>
						<div class="user_info_value">
							<input id="new_password_confirm" type="password" value=""/>
						</div>
					</div>					
					<div class="user_info_row">
						<div class="user_info_param"></div>
						<div class="user_info_value"><a class="button3 ad" href="javascript:void(0);" onclick="
							load_content_page(\'user_set_new_password\', 
								{
									old_password : document.getElementById(\'old_password\').value,
									new_password : document.getElementById(\'new_password\').value,
									new_password_confirm : document.getElementById(\'new_password_confirm\').value
								}
							);
						">Change password</a></div>
					</div>
					
					';
			echo'
				</div>
			';

			echo '
				<a href="javascript:void(0);" id="reload_content" onclick="
					document.getElementById(\'btn_user_info\').innerHTML = \''.mysql_real_escape_string(htmlspecialchars($security->nick())).'\';
				"></a>';								
		}
		
		function setNewMyNick($nick)
		{
			include dirname(__FILE__)."/../config/config.php";
			if (isset($config['profile']) && isset($config['profile']['change_nick']) && $config['profile']['change_nick'] == 'no') {
				return;
			}

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
		
		function setNewPassword($old_password, $new_password, $new_password_confirm) {
			$db = new fhq_database();
			$security = new fhq_security();
			
			if ($new_password != $new_password_confirm) {
				echo "new password is not confirmed";
				return;
			}
			
			if (strlen($new_password) < 6) {
				echo "new password could not be less then 6 simbols";
				return;
			}

			$email = $security->email();
			$username = base64_encode(strtoupper($email));
			
			$old_password_hash = $security->tokenByData( array($old_password, $username, strtoupper($email)));
			$new_password_hash = $security->tokenByData( array($new_password, $username, strtoupper($email)));

			$query = "select count(*) as cnt from user where username='$username' and password='$old_password_hash'";
			$result = $db->query($query);
			$row = mysql_fetch_row($result, MYSQL_ASSOC); // Data
			if ($row['cnt'] != "1") {
				echo "old password incorrect";
				mysql_free_result($result);
				return;
			}
			$query = "update user set password = '$new_password_hash' where username='$username' and password='$old_password_hash'";
			$db->query($query);
			echo "New password was set";
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
				<a class="button3" href="javascript:void(0);" onclick="
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
						$password_hash = $security->tokenByData( array($pass, $username, strtoupper($email)));
						$query = "INSERT user( username, password, nick, role, score, logo) VALUES ('$username','$password_hash','$nickname','$role', 0, '$logo');";
						$result2 = $db->query($query);
						echo "User registered<br>";
					}
					else
					{
						echo "<font color=#ff0000>user already exists</font><br>";
					}
			}
			mysql_free_result($result);
		}
	}
	//---------------------------------------------------------------------
?>
