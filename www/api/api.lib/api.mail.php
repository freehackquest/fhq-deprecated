<?php
$curdir_apilib_mail = dirname(__FILE__);
include_once ($curdir_apilib_mail."/../../config/config.php");

class APIMail {
	static function send($config, $to_, $cc_, $bcc_, $subject, $body, &$errormsg)
	{	
		/*if (isset($config['mail']['allow']) && $config['mail']['allow'] != 'yes' )
			return false;*/
		
		// Pear Mail Library
		require_once "Mail.php";
		
		$to = '<'.$to_.'>';
		
		$headers = array(
			'From' => '<'.$config['mail']['from'].'>',
			'To' => '<'.$to_.'>',
			'Subject' => $subject
		);
		
		if(strlen($cc_) > 0)
			$headers['Cc'] = '<'.$cc_.'>';

	if(strlen($bcc_) > 0)
			$headers['Bcc'] = '<'.$bcc_.'>';

		// @ - hide warnings
		$smtp = @Mail::factory('smtp', array(
			'host' => $config['mail']['host'],
			'port' => $config['mail']['port'],
			'auth' => $config['mail']['auth'],
			'username' => $config['mail']['username'],
			'password' => $config['mail']['password']
		));

		$mail = @$smtp->send($to, $headers, $body);
		// $errormsg = $mail->getMessage();
		return true; // PEAR::isError($mail);
	}
}
