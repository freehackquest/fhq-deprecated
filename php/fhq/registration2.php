<?php
include_once "engine/fhq.php";
// include dirname(__FILE__)."/config/config.php";

// ---------------------------------------------------------------------

if (!isset($config['registration']['allow']) || 
	(isset($config['registration']['allow']) && $config['registration']['allow'] != 'yes')) {
	echo "<h1>registration denied</h1>";
	exit;
}


/* email=" + encodeURIComponent(email) 
		+ "&nick=" + encodeURIComponent(nick)
		+ "&pass=" + encodeURIComponent(pass)
		+ "&pass_confirm=" + encodeURIComponent(pass_confirm)
		+ "&captcha=" + encodeURIComponent(captcha)
	*/	


if(isset($_GET['email']) && isset($_GET['nick']) && isset($_GET['pass']) && isset($_GET['pass_confirm']) && isset($_GET['captcha']))
{
		$email = $_GET['email'];
		$nick = $_GET['nick'];
		$pass = $_GET['pass'];
		$pass_confirm = $_GET['pass_confirm'];
		$captcha = $_GET['captcha'];
		
		$rem_captcha = $_SESSION['captcha_reg'];
		if( strtoupper($captcha) != strtoupper($rem_captcha) )
		{
			echo '<font color=#ff0000>Captcha is not correct,<br> please "Refresh captcha" and try again</font>';
			exit;
		};
		
		if (md5($pass) != md5($pass_confirm)) {
			echo '<font color=#ff0000>Password was not confirmed</font>';
			exit;
		}
		
		$role = 'user';
		$logo = '';
		$user_info = new fhq_user_info();
		$user_info->add_user($email, $pass, $nick, $role, $logo);
		exit;
};

// ---------------------------------------------------------------------

if(isset($_GET['foractivate']))
{
	echo_shortpage(new fhq_page_foractivate());
	exit;
};

// ---------------------------------------------------------------------
echo_shortpage(new fhq_page_registration2());

exit;
