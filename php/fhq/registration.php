<?
include "basepage.php";
include "echo_shortpage.php";

// ---------------------------------------------------------------------

class fhq_registration
{
		function getTitle()
		{
			return "Registration<br><font size=2><a href='index.php'>&larr; go to main page</a></font>";
		}

		function getContent()
		{
			 // registration.php
			return '
<form method="POST" action="">
					<table>
						<tr>
							<td>Write your e-mail:</td>
							<td><input name="email" id="user_email" value="" type="text"></td>
						</tr>
						<tr>
							<td></td>
							<td><img src="captcha.php" id="captcha-image"/><br>
							<a href="javascript:void(0);" onclick="document.getElementById(\'captcha-image\').src = \'captcha.php?rid=\' + Math.random();">Refresh Capcha</a></td>
						</tr>
						<tr>
							<td>Captcha</td>
							<td><input name="captcha" id="user_captcha" value="" type="text"></td>
						</tr>
						<tr>
							<td colspan = "2">
								
								<center>
									<br>
<script>
function sendQuery(str)
{
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
  };  
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
	  document.getElementById("answer").innerHTML=xmlhttp.responseText;
	}
  }
  var email = document.getElementById(\'user_email\').value;
  var captcha = document.getElementById(\'user_captcha\').value;
  xmlhttp.open("GET","registration.php?email="+email + "&captcha=" + captcha,true);
  xmlhttp.send();
}
</script>
									<!-- <input name="registration" value="Registration" type="submit"> -->
									<a href="javascript:void(0);" onclick="sendQuery();">Send query</a>
									<br><br>
									
								</center>
							</td>
						</tr>
					</table>
					</form>
					<center>
						<br>
						<div id="answer"></div>
					</center>			
			';
		}
};

// ---------------------------------------------------------------------

class fhq_foractivate
{
	function getTitle()
	{
		return "Activate account<br><font size=2><a href='index.php'>&larr; go to main page</a></font>";
	}
	
	function getContent()
	{
		 // registration.php
		// return ' blabla ';
		
		$foractivate = $_GET['foractivate'];
		
		$db = new database();
		$db->connect();
		$query = "select * from user where password = 'notactivated$foractivate';";
		$result = $db->query($query);
		if( mysql_num_rows( $result ) == 1 )
		{
			$username = mysql_result($result, 0, 'username');
			$login = base64_decode($username);
			
			$nickname = "hacker-".substr(md5(rand().rand()), 0, 7);
			$password = substr(md5(rand().rand()), 0, 7);
			$password_hash =  md5($password);
			$query2 = "update user set password = '$password_hash', nick = '$nickname', score = 0 where username = '$username';";

			$db->query($query2);

			$message = "
Thanks!
\r\n
Your account was activated.\r\n
	login: $login\r\n
	password: $password\r\n
	nickname: $nickname\r\n
Now you could begin playing in this game, it here: http://fhq.keva.su/ ";
			
			$message = wordwrap($message, 70);

			$headers = 'From: noreply@fhq.keva.su'."\r\n".
			'Reply-To: noreply@fhq.keva.su'."\r\n".
			'X-Mailer: PHP/'.phpversion();

			mail($email, 'Activated your account', $message, $headers);

			return 'Your account was activated.';
		}
		else
		{
			return 'It is not exists or already activated.';
		}
	}
};

// ---------------------------------------------------------------------

if(isset($_GET['email']) && isset($_GET['captcha']))
{
		$captcha = $_GET['captcha'];
		
		$rem_captcha = $_SESSION['captcha_reg'];
		if( strtoupper($captcha) != strtoupper($rem_captcha) )
		{
			echo "Captcha is not correct,<br> please 'Refresh captcha' and try again";
			exit;
		};
		
		$email = $_GET['email'];
		
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "Invalid e-mail address.";
			exit;
		}
		
		$username = base64_encode(strtolower($email));
		
		$db = new database();
		$db->connect();
		$query = "select * from user where username = '$username';";
		$result = $db->query($query);
		if( mysql_num_rows( $result ) >= 1 )
		{
			echo "This e-mail was already registered.";
			exit;
		};
		
		
		/* $nickname = "hacker-".substr(md5(rand().rand()), 0, 7);
		 $password = substr(md5(rand().rand()), 0, 7);
		 $password_hash =  md5($username.$password);
		*/
		
		$notactivated = md5(rand().rand());

		$query = "INSERT user( username, password, nick, role, score ) VALUES ('$username','notactivated$notactivated','$nickname','user', 0);";
		$result = $db->query($query);

		include "config/config.php";

		if($result == '1')
		{
			$message = "
If you was not tryed register on $httpname, just ignore this mail.\r\n
For activate your account, please visit this page:\r\n
".$httpname."registration.php?foractivate=$notactivated";

			$message = wordwrap($message, 70);

			$headers = 'From: noreply@fhq.keva.su'."\r\n".
			'Reply-To: noreply@fhq.keva.su'."\r\n".
			'X-Mailer: PHP/'.phpversion();

			mail($email, 'Activate your account', $message, $headers);

			echo "Next instruction was sent letter on your e-mail.";
			exit;
		};
		echo "Registration is denied.";		
		exit;
};

// ---------------------------------------------------------------------

if(isset($_GET['foractivate']))
{
	$page = new fhq_foractivate();
	echo_shortpage($page);
	exit;
};

// ---------------------------------------------------------------------

$page = new fhq_registration();
echo_shortpage($page);

exit;
