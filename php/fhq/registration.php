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
