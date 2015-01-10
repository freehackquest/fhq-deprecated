<?php
	$rootdir = dirname(__FILE__);
	include_once("$rootdir/../config/config.php");

	//---------------------------------------------------------------------
	
	class fhq_mail
	{
		static function send($to_, $cc_, $bcc_, $subject, $body, &$errormsg)
		{
			include dirname(__FILE__)."/../config/config.php";
			
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
		
    function send_to_admin($subject, $body, &$errormsg)
    {
      include dirname(__FILE__)."/../config/config.php";
			$security = new fhq_security();
			$db = new fhq_database();
      $emails = "";
      $result = $db->query('select username, password from user where role=\'admin\'');
      while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{   
				$email = strtolower(base64_decode($row['username']));
				if($emails != '') $emails .= ', ';
				$emails .= '<'.$email.'>';
			}
      $emails = substr($emails, 1, strlen($emails) - 2);
      $error = "";
      @$this->send($emails, '', '', $subject, $body, $error);
    }

		function send_to_all($subject, $body, $send_as_copies)
		{
			include dirname(__FILE__)."/../config/config.php";
			
			if (isset($config['mail']['allow']) && $config['mail']['allow'] != 'yes' )
				return false;
				
			$security = new fhq_security();
			$db = new fhq_database();
			$emails = "";
			$main_email = "";
			$result = $db->query('select username, password from user');
      $count = 0;
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) // Data
			{   
				$email = strtolower(base64_decode($row['username']));
				$password = $row['password'];				
				$notact = 'notactivated';
				$first = true;
				if(substr($password, 0, strlen($notact)) != $notact)
				{	          
					if($emails != '') $emails .= ', ';
					$emails .= '<'.$email.'>';
          if(strlen($main_email) == 0) $main_email = $email;
          $count++;

          /*if($count > 15)
          {
            $count = 0;
            $emails = substr($emails, 1, strlen($emails) - 2);
            $error = "";
            if($send_as_copies)
              $this->send($main_email, $emails, '', $subject, $body, $error);
            else
              $this->send($main_email, '', $emails, $subject, $body, $error);
            $emails = "";
            sleep(45);
          }*/
				}
			}
      
			$emails = substr($emails, 1, strlen($emails) - 2);
			// echo htmlspecialchars($emails);
      $error = "";
      if($send_as_copies)
        $this->send($main_email, $emails, '', $subject, $body, $error);
      else
        $this->send($main_email, '', $emails, $subject, $body, $error);
		}
	}
	//---------------------------------------------------------------------
?>
