<?

include_once "engine/fhq.php";


$security = new fhq_security();
		
if( isset( $_GET['exit']) )
{
	$security = new fhq_security();
	$security->logout();
	echo "OK";
	exit;
};

if($security->isLogged())
{
	refreshTo("main.php");
	return;
};
	
class fhq_logon
{
	function title()
	{
		return "</h2> quest game, the system prompts, <br>
						receipt and delivery of jobs in computer security.
					</h2><br><br>";
	}

	function echo_content()
	{
		$error_msg = "";
		if(isset($_SESSION['error_msg']))
		{
			$error_msg = "<br><br> <font color='#ff0000'>".$_SESSION['error_msg']."</font>";
			$_SESSION['error_msg'] = "";
		};

		echo '
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



if(isset($_SESSION['iduser']) && isset($_SESSION['email']))
{
	refreshTo("main.php");
};

if( isset( $_GET['email']) && isset($_GET['password']) )
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

	$security = new fhq_security();

	if( $security->login($_GET['email'], $_GET['password']) )
		echo "OK";
	else
		echo "<font color=#ff0000>Invalid: e-mail or/and password</font>";

	exit;
};

$logon = new fhq_logon();
echo_shortpage($logon);

exit;	
?>
