<?php
$curdir = dirname(__FILE__);
include ($curdir."/../../config/config.php");
include ($curdir."/../../engine/fhq.php");
header("Access-Control-Allow-Origin: *");

$security = new fhq_security();

$result = array(
	'result' => 'fail',
	'data' => array(),
);

if (isset($_GET['email']) && isset($_GET['captcha'])) {
	$email = $_GET['email'];
	$captcha = $_GET['captcha'];

	$orig_captcha = (string)$_SESSION['captcha_reg'];

	$result['error']['captcha_expected'] = strtoupper($captcha);
	$result['error']['captcha'] = strtoupper($orig_captcha);
	
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
						$result['error']['code'] = '1007';
						$result['error']['message'] = 'Error 1007[restore]: Problem with sending email. '.$error;
					}
				} else {
					$result['error']['code'] = '1006';
					$result['error']['message'] = 'Error 1006[restore]: Restore is denied.';
				}
			} else {
				$result['error']['code'] = '1005';
				$result['error']['message'] = 'Error 1005[restore]: This e-mail was not registered.';
			}
			mysql_free_result($qresult);
		} else {
			$result['error']['code'] = '1004';
			$result['error']['message'] = 'Error 1004[restore]: Invalid e-mail address.';
		}		
	} else {
		$result['error']['code'] = '1003';
		$result['error']['message'] = 'Error 1003[restore]: Captcha is not correct,<br> please "Refresh captcha" and try again';
	}
} else {
	$result['error']['code'] = '1008';
	$result['error']['message'] = 'Error 1008[restore]: Incorrect input parameters email or captcha';
}

echo json_encode($result);
