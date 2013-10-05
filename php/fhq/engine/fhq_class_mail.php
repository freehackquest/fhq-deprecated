<?
	include_once("config/config.php");
	
	
	
	//---------------------------------------------------------------------
	
	class fhq_mail
	{
		static function send($to_, $subject, $body, &$errormsg)
		{
			include "config/config.php";
			
			// Pear Mail Library
			require_once "Mail.php";
			
			$to = '<'.$to_.'>';
			
			$headers = array(
				'From' => '<'.$config['mail']['from'].'>',
				'To' => '<'.$to_.'>',
				'Subject' => $subject
			);
			
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
	//---------------------------------------------------------------------
?>
