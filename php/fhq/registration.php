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


			$subject = "Your account was activated";
			
			$message = "
<html>
<head>
  <title>$subject</title>
</head>
<body>
Thank you!<br>
Your login: $login<br>
Your password: $password<br>
Your nickname: $nickname<br>
Now you could begin playing in this game, it here: <a href='$httpname'>$httpname</a>
</body>
</html>
";
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: noreply@fhq.keva.su'."\r\n";
			$headers .= 'Reply-To: noreply@fhq.keva.su'."\r\n";
			$headers .= 'X-Mailer: PHP/'.phpversion();
			
			mail($email, 'Your account was activated', $message, $headers);
			
			return 'Your account was activated.<br>Information for logon was sended to your email.';
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
		
		$notactivated = md5(rand().rand());

		$query = "INSERT user( username, password, nick, role, score ) VALUES ('$username','notactivated$notactivated','$nickname','user', 0);";
		$result = $db->query($query);

		if($result == '1')
		{
			include "config/config.php";
			
			$subject = "Activate your account";
			
			$message = "
<html>
<head>
  <title>$subject</title>
</head>
<body>
If you was not tryed register on $httpname, just ignore this mail.<br>
For activate your account, please visit this page:<br>
".$httpname."registration.php?foractivate=$notactivated'
</body>
</html>
";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: noreply@fhq.keva.su'."\r\n";
			$headers .= 'Reply-To: noreply@fhq.keva.su'."\r\n";
			$headers .= 'X-Mailer: PHP/'.phpversion();

			mail($email, $subject, $message, $headers);

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
