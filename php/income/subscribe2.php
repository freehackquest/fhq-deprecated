<?

class SMTPClient
{
	function SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body)
	{
		$this->SmtpServer = $SmtpServer;
		$this->SmtpUser = base64_encode ($SmtpUser);
		$this->SmtpPass = base64_encode ($SmtpPass);
		$this->from = $from;
		$this->to = $to;
		$this->subject = $subject;
		$this->body = $body;
		if ($SmtpPort == "")
		{
			$this->PortSMTP = 25;
		}
		else
		{
			$this->PortSMTP = $SmtpPort;
		}
	}

	function SendMail ()
	{
		if ($SMTPIN = fsockopen ($this->SmtpServer, $this->PortSMTP))
		{
			fputs ($SMTPIN, "EHLO localhost\r\n");
			$talk["hello"] = fgets ( $SMTPIN, 1024 );
			fputs($SMTPIN, "auth login\r\n");
			$talk["res"]=fgets($SMTPIN,1024);
			fputs($SMTPIN, $this->SmtpUser."\r\n");
			$talk["user"]=fgets($SMTPIN,1024);
			fputs($MTPIN, $this->SmtpPass."\r\n");
			$talk["pass"]=fgets($SMTPIN,256);
			fputs ($SMTPIN, "MAIL FROM: <".$this->from.">\r\n");
			$talk["From"] = fgets ( $SMTPIN, 1024 );
			fputs ($SMTPIN, "RCPT TO: <".$this->to.">\r\n");
			$talk["To"] = fgets ($SMTPIN, 1024);
			fputs($SMTPIN, "DATA\r\n");
			$talk["data"]=fgets( $SMTPIN,1024 );
			fputs($SMTPIN, "To: <".$this->to.">\r\nFrom: <".$this->from.">\r\nSubject:".$this->subject."\r\n\r\n\r\n".$this->body."\r\n.\r\n");
			$talk["send"]=fgets($SMTPIN,256);
			// echo $talk["send"];
			//ЗАКРЫВАЕМ СОЕДИНЕНИЕ И ВЫХОДИМ ...
			fputs ($SMTPIN, "QUIT\r\n");
			fclose($SMTPIN);
		}
		else
		{
			return false;
		}
		return true; //$talk;
	}

}

// ------- func ends



require_once "config.php";

$error = '';
$info = '';

$email = $_GET['email'];

// echo ":::[".$email."] :: ";


// Проверка заполнения поля с адресом email
if ($email == '' || $email == 'your@email.com') {
	$error = $config['messages']['no_email'];
	echo $error;
}
// Проверка формата адреса email
else if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
{
	$error = $config['messages']['email_invalid'];
	echo $error;
}
// Соединенияе с базой
else if (!@mysql_connect($config['database']['host'], $config['database']['username'], $config['database']['password'])) {
	$info = $config['messages']['technical'];
	echo $info;
}
else if (!@mysql_select_db($config['database']['database']))
{
	$error = $config['messages']['technical'];
	echo $error;
}
else
{
	// Вставляем подписку
	$q = "INSERT INTO `keva_precour` (email, subscribed_at) VALUES ('".$email."', NOW())";
	@mysql_query($q);
        $good = "no";
	if (mysql_error())
	{
		// $error = $config['messages']['technical'];
		echo "Такой адрес уже есть в базе данных"; //$error;
	}
	else
	{
		// Готово.
		echo $config['messages']['thank_you'];
		$good = "yes";
	}

	if($good == "yes")
	{
		$username = base64_encode($email);
		$password = substr(md5(rand().rand()), 0, 7);
		$password_hash = md5($password);

		$nick = mysql_fetch_array(mysql_query("select name from nicknames where used = '0' order by rand() limit 0,1;"));
		mysql_query("update nicknames set used = 1 where name = '{$nick['name']}'");
		//print_r($nick);
		//error_reporting(E_ALL);
		mysql_select_db("freehackquest") or die(mysql_error());
		$sql = "SELECT * FROM user WHERE username='{$username}'";
		$result = mysql_query($sql);
		// $num_rows = mysql_num_rows($result);
		// echo $result;
		//$info .= $num_rows;
		if(mysql_num_rows($result) == 0)
 		{
			$nick = mysql_real_escape_string($nick['name']);
			$sql = "insert into user values (NULL, '{$username}', '{$password_hash}', 0, 'user', '{$nick}')";
			//mysql_select_db("freehackquest") or die(mysql_error());
			mysql_query($sql);


			$body = "Thank you for your subscription on our news!
			Before start our lessons we would to suggest to participate in mini-CTF game, which we already created account for you.
				login: {$email}
				password: {$password}
				random nick: {$nick['name']}
				link: http://free-hack-quest.keva.su/";
			$subj = "Keva CTF team";

			$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, "noreply@keva.su", $email, $subj, $body);
			$SMTPChat = $SMTPMail->SendMail();
			echo "<br /> Так же, вам было отправлено письмо с приглашением на мини игру.<br /><br /><font size='2'>
(внимание! письмо может оказаться в спаме <br />
или вообще не дойти если вы пользуетесь мало изместными почтовыми серверами <br /> или ввели не существующий почтовый сервер)</font>";
		}
	}
}
?>
