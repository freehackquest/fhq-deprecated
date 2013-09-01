<?
include "basepage.php";
include "echo_shortpage.php";
	
	
class fhq_logon
{

	function getTitle()
	{
		return "</h2> quest game, the system prompts, <br>
						receipt and delivery of jobs in computer security.
					</h2><br><br>";
	}
	
	function getContent()
	{
		$error_msg = "";
		if(isset($_SESSION['error_msg']))
		{
			$error_msg = "<br><br> <font color='#ff0000'>".$_SESSION['error_msg']."</font>";
			$_SESSION['error_msg'] = "";
		};

		return '
			<b>please, authtorization in the system:</b><br>
<script>
function sign_in()
{
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
  };  
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		if(xmlhttp.responseText == "OK")
			window.location.href = "main.php";
		else
			document.getElementById("result_auth").innerHTML=xmlhttp.responseText;
	}
  }
  var email = document.getElementById(\'email\').value;
  var password = document.getElementById(\'password\').value;
  xmlhttp.open("GET","index.php?email="+email + "&password=" + password,true);
  xmlhttp.send();
}
</script>			
			<form method="POST" action="">
				<table>
					<tr>
						<td> E-mail: </td>
						<td><input name="email" id="email" value="" type="text" onkeydown="if (event.keyCode == 13) sign_in();"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input name="password" id="password" value="" type="password"  onkeydown="if (event.keyCode == 13) sign_in();"></td>
					</tr>
					<tr>
						<td colspan = "2">
							<center>					
								<a href="javascript:void(0);" onclick="sign_in();">Sign in</a>
							</center>
						</td>
					</tr>
				</table>
			</form>
			<center>
					or <a href="registration.php">Registration</a>
			</center>
			<br><br>
			<div id="result_auth"> </div>
';
	}
};

if( isset( $_POST['exit']) )
{
	unset($_SESSION['iduser']);
	unset($_SESSION['email']);
	unset($_SESSION['nick']);
	unset($_SESSION['score']);
	unset($_SESSION['role']);
};

if(isset($_SESSION['iduser']) && isset($_SESSION['email']))
{
	refreshTo("main.php");
};

if( isset( $_GET['email']) && isset($_GET['password'])
)
{
	if(strlen($_GET['email']) == 0)
	{
			echo "<font color=#ff0000>E-mail must be is not empty</font>";
			exit;
	}
	
	if(strlen($_GET['password']) == 0)
	{
			echo "<font color=#ff0000>Password must be is not empty</font>";
			exit;
	}
	
	$username = base64_encode($_GET['email']);
	$password = md5($_GET['password']);
	
	$db = new database();
	$db->connect();
	$query = "select * from user where username = '$username' and password = '$password';";
	$result = $db->query($query);
		
	if( mysql_num_rows( $result ) == 1 )
	{
		$_SESSION['iduser'] = mysql_result($result, 0, 'iduser');
		$_SESSION['email'] = mysql_result($result, 0, 'username');
		$_SESSION['nick'] = mysql_result($result, 0, 'nick');
		$_SESSION['score'] = mysql_result($result, 0, 'score');
		$_SESSION['role'] = mysql_result($result, 0, 'role');
		echo "OK";
		exit;
	}
	else
	{
		echo "<font color=#ff0000>Invalid: e-mail or/and password</font>";
	};
	
	exit;
};

$logon = new fhq_logon();
echo_shortpage($logon);

exit;	
?>
