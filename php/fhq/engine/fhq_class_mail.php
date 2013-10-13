<?
	include_once("config/config.php");
	
	
	
	//---------------------------------------------------------------------
	
	class fhq_mail
	{
		static function send($to_, $cc_, $subject, $body, &$errormsg)
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
			
			if(strlen($cc_) > 0)
				$headers['Cc'] = $cc_;

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
		
		function send_to_all($subject, $body)
		{
			include "config/config.php";
			$security = new fhq_security();
			$db = new fhq_database();
			$emails = "";
			$main_email = "";
			$result = $db->query('select username, password from user');
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{   
				$email = strtolower(base64_decode($row['username']));
				$password = $row['password'];
				$error = "";
				$notact = 'notactivated';
				if(substr($password, 0, strlen($notact)) != $notact)
				{
					if($emails != '') $emails .= ', ';
					$emails .= $email;
					if($main_email == '') $main_email = $email;
				}
			}
			
			$this->send($main_email, $emails, $subject, $body, $error);
		}
	}
	//---------------------------------------------------------------------
?>
