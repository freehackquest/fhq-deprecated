<?

if (!file_exists("config/config.php")) {
	header ("Location: install/install_step01.php");
	exit;
};

include_once "config/config.php";
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
		return "";
	}

	function echo_head()
	{
		echo '';
	}
	
	function echo_onBodyEnd() {
		echo '';
	}
	
	function get_onloadbody() {
		return 'load_content_html(\'indexcontent\', \'pages/index/sign_in.html\');';
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
			<div>
				<a class="indextabulation" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/sign_in.html\');">sign in</a>
				<a class="indextabulation" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/registration.html\');">registration</a>
				<a class="indextabulation" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/about.html\');">what is it?</a>
				<a class="indextabulation" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/contacts.html\');">contacts</a>
				<a class="indextabulation" href="javascript:void(0);" onclick="load_content_html(\'indexcontent\', \'pages/index/restore.html\');">restore</a>
			</div>
			<br>
			<div class="indexcontent" id="indexcontent">
				Hi man!
			</div>
			<br>
';
/*
		echo '
			<b>please, sign in to the system:</b><br>
<script>

</script>			
			<table cellspacing=10px cellpadding=10px>
					<tr>
						<td>E-mail</td>
						<td><input name="email" id="email" value="" type="text" onkeydown="if (event.keyCode == 13) sign_in();"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input name="password" id="password" value="" type="password"  onkeydown="if (event.keyCode == 13) sign_in();"></td>
					</tr>
					<tr>
						<td colspan = "2">
							<center>					
								<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="sign_in();">Sign in</a>
							</center>
						</td>
					</tr>
				</table>
			<center>';
			include dirname(__FILE__)."/config/config.php";
			if (isset($config['registration']['allow']) && $config['registration']['allow'] == 'yes') {
				$type = isset($config['registration']['allow']) ? $config['registration']['type'] : 'email';
				if ($type == 'email')
					echo ' or <a class="btn btn-small btn-info" href="registration.php">Create new account</a>';
				else if ($type == 'simple')
					echo ' or <a class="btn btn-small btn-info" href="registration2.php">Create new account</a>';
				else
					echo ' or unknown type of registration';
			}
			
			
		echo '</center>
			<br><br>
			<div id="result_auth"> </div>
';
* */
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
