    <?
//	echo "hello!";
	//$ch = curl_init();

	include "basepage.php";	
	include "config.php";

	/*if( isset($_SESSION['iduser']) && isset($_SESSION['nickname']) )
	{
	    refreshTo("main.php");
	}*/

	$msg = "";

	//http://habrasorium.ru/php/655-avtorizaciya-s-facebook-i-twitter.html

	if( isset( $_POST['exit']) )
	{
		unset($_SESSION['nickname']);
		unset($_SESSION['iduser']);
		unset($_SESSION['lvl']);
		unset($_SESSION['maxexp']);
	};

	if(isset($_SESSION['iduser']) && isset($_SESSION['nickname']))
	{
		refreshTo("main.php");
	};

	//echo "1<br>";
	if( isset( $_POST['authtorization']) )
	{
		$nickname = getFromPost('nickname');
		$password = getFromPost('password');

		//echo $nickname."=[".base64_encode( $nickname )."]<br>";


		$db = mysql_connect( $db_host, $db_username, $db_userpass) or die("not connected to database");
		mysql_select_db( $db_namedb, $db);
		$query = "select * from user where username = '".base64_encode( $nickname )."' and password = '".md5($password)."';";
		$result = mysql_query($query);
//		echo mysql_affected_rows( $db );
		if( mysql_num_rows( $result ) == 1 )
		{
			$_SESSION['nickname'] = mysql_result($result, 0, 'nick');
			$_SESSION['iduser'] = mysql_result($result, 0, 'iduser');
			$_SESSION['score'] = mysql_result($result, 0, 'score');
			$_SESSION['role'] = mysql_result($result, 0, 'role');
			mysql_close();
			refreshTo("main.php");
		}
		else
		{
			$msg = $msg."Uncorrect pair: Nickname - Password<br>";
			mysql_close();
		};
	};

	if( strlen($msg) > 0 ) 
	{
		$msg = "<font color=#ff0000>".$msg."</font>";
	}
	//echo "2<br>";
?>


<html>
<head>
<title> free-hack-quest </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link rel='stylesheet' type='text/css' href='styles/style.css' />

</head>
<body class=main>
<center>


<table width='100%' height='100%'>
	<tr>
		<td align='center' valign='middle'>

			<table>
				
				<tr>
					<td> <img src='images/minilogo.jpg'> </td>

					<td >
					<h2>
					<?
						echo $site_name;
					?>
					</h2> quest game, the system prompts, <br>
						receipt and delivery of jobs in computer security.

					</h2>	
					<br><br>

					
					<!-- поля для авторизации -->					
					<b>please, authtorization in the system:</b><br>
					<form method='POST' action='index.php'>
					<table>
						<tr>
							<td> E-mail </td>
							<td><input name = "nickname" value="" type="text"></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input name = "password" value="" type="password"></td>
						</tr>
						<tr>
							<td colspan = '2'>
								<center>
									<? echo $msg;?>
									<input name="authtorization" value="Sign in" type="submit">
								</center>
							</td>
						</tr>
					</table>
					</form>
					<center>
						or <a href="http://income.keva.su">Registration</a>
					</center>

					</td>
				</tr>
				<tr>
					<td align = 'center'>
						
						
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
