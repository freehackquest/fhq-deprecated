<?php
$curdir = dirname(__FILE__);
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");

$security = new fhq_security();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (isset($_GET['email']) && isset($_GET['captcha'])) {
	$email = $_GET['email'];
	$captcha = $_GET['captcha'];

	$orig_captcha = $_SESSION['captcha_reg'];
	$_SESSION['captcha_reg'] = md5(rand().rand());

	if( strtoupper($captcha) == strtoupper($orig_captcha) ) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$username = base64_encode(strtoupper($email));
			$db = new fhq_database();
			$qresult = $db->query("select * from user where username = '$username';");
			if( $db->count( $qresult ) == 1 )
			{
				$password = substr(md5(rand().rand()), 0, 7);
				$password_hash = $security->tokenByData( array($password, $username, strtoupper($email)));

				$query = "UPDATE user SET password = '$password_hash' where username = '$username';";
				$qresult2 = $db->query($query);
				if($qresult2 == '1')
				{
					$subject = 'Restore password to your account on FreeHackQuest.';
					$message = '
	Restore:

	Somebody (may be you) reseted your password on '.$config['httpname'].'
	Your new password: '.$password.'
	'.$config['httpname'].'index.php
	';
					$mail = new fhq_mail();
					$error = "";
					if( $mail->send($email, '', '', $subject, $message, $error) ) {
						$result['result'] = 'ok';
						$result['data']['message'] = 'Check your your e-mail (also check spam).';
					} else {
						$result['error']['code'] = '107';
						$result['error']['message'] = 'Error 107: Problem with sending email. '.$error;
					}
					/*$result['result'] = 'ok';
					$result['data']['message'] = 'Check your your e-mail (also check spam). '.$password;*/
				} else {
					$result['error']['code'] = '106';
					$result['error']['message'] = 'Error 106: Registration is denied.';
				}
			} else {
				$result['error']['code'] = '105';
				$result['error']['message'] = 'Error 105: This e-mail was not registered.';
			}
			mysql_free_result($qresult);
		} else {
			$result['error']['code'] = '104';
			$result['error']['message'] = 'Error 104: Invalid e-mail address.';
		}		
	} else {
		$result['error']['code'] = '103';
		$result['error']['message'] = 'Error 103: Captcha is not correct,<br> please "Refresh captcha" and try again';
	}
} else {
	$result['error']['code'] = '108';
	$result['error']['message'] = 'Error 108: it was not found login';
}

echo json_encode($result);
