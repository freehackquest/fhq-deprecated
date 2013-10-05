<?
include_once "engine/fhq.php";

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
		
		$registration = new fhq_registration();

		if(!$registration->addEmailAndSendMail($email))
		{
			echo "<font color=#ff0000>Registration is denied.</font>";
		};
		exit;
		
		/*			
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
			echo '<font color=#ff0000>This e-mail was already registered.</font>';
			mysql_free_result($result);
			exit;
		};
		
		$notactivated = md5(rand().rand());
		$nickname = "hacker-".substr(md5(rand().rand()), 0, 7);
		$query = "INSERT user( username, password, nick, role, score ) VALUES ('$username','notactivated$notactivated','$nickname','user', 0);";
		$result = $db->query($query);

		if($result == '1')
		{
			
			$subject = "Activation your account on FreeHackQuest.";
			
			$message = "
Registration:

If you was not tryed register on ".$config['httpname'].", just ignore this mail.

For activate your account, please visit this page:
".$config['httpname']."registration.php?foractivate=$notactivated
";
			$mail = new fhq_mail();
			$error = "";
			if( $mail->send($email, $subject, $message, $error) )
				echo "Check your your e-mail (also check spam).";
			else
				echo '<font color=#ff0000>Problem with sending email. '.$error.'</font>';
			exit;
		};
		echo "<font color=#ff0000>Registration is denied.</font>";
		exit;
		* */
};

// ---------------------------------------------------------------------

if(isset($_GET['foractivate']))
{
	echo_shortpage(new fhq_page_foractivate());
	exit;
};

// ---------------------------------------------------------------------

echo_shortpage(new fhq_page_registration());

exit;
