<?
	include "basepage.php";

	$msg = "";
	$nickname = "";

	if( isset( $_POST['registration']) )
	{
		$nickname = getFromPost('nickname');
		$password = getFromPost('password');
		$retry_password = getFromPost('retrypassword');
		$captcha = getFromPost('captcha');

		if( strlen( $nickname) <= 3 )
		{
			$msg = $msg."Nickname - must be more 3 characters<br>";
		}
		else
		{
			include_once("config.php");
			//check nickname
			$db = new database();
			$db->connect();
			$query = "select * from user where username = '".base64_encode($nickname)."';";
			$result = $db->query($query);
			if( mysql_affected_rows( $db ) >= 1 )
			{
				$msg = $msg."this Nickname already exists<br>";
			};
		};

		if( strlen( $password ) <= 3 )
		{
			$msg = $msg . "Password - must be more 3 characters<br>";
		}
		else
		{
			if( $password != $retry_password ) $msg = $msg."Password и Retry Password - не совпадают<br>";
		};

		$rem_captcha = $_SESSION['captcha_reg'];


		if( strtoupper($captcha) != strtoupper($rem_captcha) )
		{
			$msg = $msg."Captcha - not correct<br>";
		};

		if( strlen($msg) > 0 )
		{
			$msg = "<font color=#ff0000>".$msg."</font>";
		}
		else
		{
			$db = new database();
			$db->connect();

			$query = "INSERT user( username, password ) VALUES ('".base64_encode($nickname)."','".md5($password)."');";

			$result = $db->query($query);
			// if( $result != 1 ) echo "record was insert<br>";

			if( $result == 1 )
			{
				refreshTo("index.php");
				return;
			}
			else
			{
				$msg .= $result."Sorry, We have same problem with registration now\n."
					."Try again later.";
			}
		};
	};
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> free-hack-quest - Registration </title>
<link rel='stylesheet' type='text/css' href='styles/style.css' />
</head>
<body class="main">
<center>

<table width='100%' height='100%'>
	<tr>
		<td align='center' valign='middle'>

			<table>				
				<tr>
					<td> <img src='images/minilogo.jpg'> </td>
					
					
					<td > <h2>free-hack-quest - Registration</h2>
					
					<form method='POST' action='registration.php'>
					<table>
						<tr>
							<td>Nickname ( > 3 simbols ) </td>
							<td><input name="nickname" value="<? echo $nickname; ?>" type="text"></td>
						</tr>
						<tr>
							<td>Password ( > 3 simbols ) </td>
							<td><input name="password" value="" type="password"></td>
						</tr>
						<tr>
							<td>Retry Password</td>
							<td><input name="retrypassword" value="" type="password"></td>
						</tr>
						<tr>
							<td></td>
							<td><img src='captcha.php' id='captcha-image'/><br>
							<a href="javascript:void(0);" onclick="document.getElementById('captcha-image').src = 'captcha.php?rid=' + Math.random();">Refresh Capcha</a></td>
						</tr>
						<tr>
							<td>Captcha</td>
							<td><input name="captcha" value="" type="text"></td>
						</tr>
						<tr>
							<td colspan = '2'>
								
								<center>
									<? echo $msg;?>
									<input name="registration" value="Registration" type="submit">
								</center>
							</td>
						</tr>
					</table>
					</form>
					<center>
						<a href="index.php">go to main page</a>
					</center>
					</td>
				</tr>
				<tr>
					<td align = 'center'>
						<?
                                                        //gd_info();	
							//phpinfo();

						?>
						<!-- поля для авторизации -->					
						
					</td>
					<td></td>
				</tr>
			</table>
			
			
			
		</td>
	</tr>
</table>

</center>

</body>
</html>
