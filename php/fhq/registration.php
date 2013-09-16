<?
include_once "engine/fhq.php";

// ---------------------------------------------------------------------

class fhq_registration
{
	function title()
	{
		return 'Registration<br><font size=2><a href="index.php">&larr; go to main page</a></font>';
	}

	function echo_content()
	{
		echo '
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
					</center>';
	}
};

// ---------------------------------------------------------------------

class fhq_foractivate
{
	function title()
	{
		return 'Activate account<br><font size=2><a href="index.php">&larr; go to main page</a></font>';
	}
	
	function echo_content()
	{
		 // registration.php
		// return ' blabla ';
		
		$foractivate = $_GET['foractivate'];
		
		$db = new fhq_database();
  	$security = new fhq_security();
		$query = "select * from user where password = 'notactivated$foractivate';";
		$result = $db->query($query);
		if( mysql_num_rows( $result ) == 1 )
		{
			$username = mysql_result($result, 0, 'username');
			$email = base64_decode($username);
			
			$nickname = "hacker-".substr(md5(rand().rand()), 0, 7);
			$password = substr(md5(rand().rand()), 0, 7);		
      $password_hash = $security->tokenByData( [$password, $username, strtoupper($email)]);

			$query2 = "update user set password = '$password_hash', nick = '$nickname', score = 0 where username = '$username';";
			$db->query($query2);


			$subject = "Your account was activated";
			
			$message = "
<html>
<head>
  <title>$subject</title>
</head>
<body>
Thank you for registration on Free-Hack-Quest!<br>
Your login: ".strtolower($email)."<br>
Your password: $password<br>
Your nickname: $nickname<br>
Now you could begin playing in this game, it here: ".$config['httpname']."</a>
</body>
</html>
";
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: noreply@fhq.keva.su'."\r\n";
			$headers .= 'Reply-To: noreply@fhq.keva.su'."\r\n";
			$headers .= 'X-Mailer: PHP/'.phpversion();
			
			mail($login, $subject, $message, $headers);
			
			echo 'Your account was activated.<br>Information for logon was sended to your email.';
		}
		else
		{
			echo 'It is not exists or already activated.';
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
			echo '<font color=#ff0000>Captcha is not correct,<br> please "Refresh captcha" and try again</font>';
			exit;
		};
		$email = $_GET['email'];

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "<font color=#ff0000>Invalid e-mail address.</font>";
			exit;
		}
		
		$username = base64_encode(strtoupper($email));	
		$db = new fhq_database();
		$query = "select * from user where username = '$username';";
		$result = $db->query($query);
		if( $db->count( $result ) >= 1 )
		{
			echo "<font color=#ff0000>This e-mail was already registered.</font>";
			exit;
		};
		
		$notactivated = md5(rand().rand());

		$query = "INSERT user( username, password, nick, role, score ) VALUES ('$username','notactivated$notactivated','$nickname','user', 0);";
		$result = $db->query($query);

		if($result == '1')
		{
			include "config/config.php";
			
			$subject = "Your account is activated.";
			
			$message = "
<html>
<head>
  <title>$subject</title>
</head>
<body>
If you was not tryed register on ".$config['httpname'].", just ignore this mail.<br>
For activate your account, please visit this page:<br>
".$config['httpname']."registration.php?foractivate=$notactivated
</body>
</html>
";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: noreply@fhq.keva.su'."\r\n";
			$headers .= 'Reply-To: noreply@fhq.keva.su'."\r\n";
			$headers .= 'X-Mailer: PHP/'.phpversion();

			mail($email, $subject, $message, $headers);

			echo "Information was sent on your e-mail.";
			exit;
		};
		echo "<font color=#ff0000>Registration is denied.</font>";
		exit;
};

// ---------------------------------------------------------------------

if(isset($_GET['foractivate']))
{
	echo_shortpage(new fhq_foractivate());
	exit;
};

// ---------------------------------------------------------------------

echo_shortpage(new fhq_registration());

exit;
