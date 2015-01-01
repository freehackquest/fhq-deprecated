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
			$registration = new fhq_registration();
			if($registration->addEmailAndSendMail($email))
			{
				$result['result'] = 'ok';
				$result['data']['message'] = 'Check your your e-mail (also please check spam).';
			} 
			else 
			{
				$result['error']['code'] = '110';
				$result['error']['message'] = 'Error 110[registration]: Problem with registration. '.$error;
			};
		}
		else
		{
			$result['error']['code'] = '111';
			$result['error']['message'] = 'Error 111[registration]: Invalid e-mail address.';
		}		
	} else {
		$result['error']['code'] = '112';
		$result['error']['message'] = 'Error 112[registration]: Captcha is not correct,<br> please "Refresh captcha" and try again';
	}
} else {
	$result['error']['code'] = '113';
	$result['error']['message'] = 'Error 113[registration]: Incorrect input parameters email or captcha';
}

echo json_encode($result);
