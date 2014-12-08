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
		return 'show_index_element(\'indexcontent_sign_in\');';
		// return 'load_content_html(\'indexcontent\', \'pages/index/sign_in.html\');';
	}
	
	function echo_content()
	{
		$error_msg = "";
		if(isset($_SESSION['error_msg']))
		{
			$error_msg = "<br><br> <font color='#ff0000'>".$_SESSION['error_msg']."</font>";
			$_SESSION['error_msg'] = "";
		};

		?>
			<div class="index_menu">
				<div
					class="index_menu"
					onclick="show_index_element('indexcontent_sign_in');"
				>
					<img src="templates/base/images/index/signin.png"/>
				</div>
				<div
					class="index_menu"
					onclick="show_index_element('indexcontent_registration');"
				>
					<img src="templates/base/images/index/registration.png"/>
				</div>
				
				<div
					class="index_menu"
					onclick="show_index_element('indexcontent_about');"
				>
					<img src="templates/base/images/index/about.png"/>
				</div>
				<div
					class="index_menu"
					onclick="show_index_element('indexcontent_restore');"
				>
					<img src="templates/base/images/index/restore.png"/>
				</div>
			</div>
			<br>
			<!-- div class="indexcontent" id="indexcontent">
				Hi man!
			</div -->
			<br>
			
			<div class="indexcontent" id="indexcontent_sign_in">
				<center>
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
							<td colspan=2>
								<center>
									<a class="button3" href="javascript:void(0);" onclick="sign_in();">sign in</a>
								</center>
							</td>
						</tr>
					</table>
				</center>
			</div>

			<div class="indexcontent" id="indexcontent_registration">
				<!-- registration <a href="registration.php">here</a> -->
				<center>
				<b>create new account</b>
				<table cellspacing=10px cellpadding=10px>
					<tr>
						<td>E-mail</td>
						<td><input name="email" id="email_reg" value="" type="text" onkeydown="if (event.keyCode == 13) registration();"></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<img src="captcha.php" id="captcha_image_reg"/><br>
							<a href="javascript:void(0);" onclick="document.getElementById('captcha_image_reg').src = 'captcha.php?rid=' + Math.random();">Refresh Capcha</a>
							<br>
						</td>
					</tr>
					<tr>
						<td>Captcha</td>
						<td><input name="captcha" id="captcha_reg" value="" type="text" onkeydown="if (event.keyCode == 13) registration();"></td>
					</tr>
					<tr>
						<td colspan=2>
							<center>
								<a class="button3" href="javascript:void(0);" onclick="registration();">ok</a>
							</center>
						</td>
					</tr>
				</table>
				</center>
			</div>
			<div class="indexcontent" id="indexcontent_restore">
				<center>
				<b>restore password</b>
				<table cellspacing=10px cellpadding=10px>
					<tr>
						<td>E-mail</td>
						<td><input name="email" id="email_restore" value="" type="text" onkeydown="if (event.keyCode == 13) restore();"></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<img src="captcha.php" id="captcha_image_restore"/><br>
							<a href="javascript:void(0);" onclick="document.getElementById('captcha_image_restore').src = 'captcha.php?rid=' + Math.random();">Refresh Capcha</a>
							<br>
						</td>
					</tr>
					<tr>
						<td>Captcha</td>
						<td><input name="captcha" id="captcha_restore" value="" type="text" onkeydown="if (event.keyCode == 13) restore();"></td>
					</tr>
					<tr>
						<td colspan=2>
							<center>
								<a class="button3" href="javascript:void(0);" onclick="restore();">restore password</a>
							</center>
						</td>
					</tr>
				</table>
				</center>
			</div>
			<div class="indexcontent" id="indexcontent_about">
				<?
					include("about.php");
				?>
			</div>
			<br><br>
			<font id="info_message"></font>
			<font id="error_message" color='#ff0000'></font>
			<?

	}
};

if(isset($_SESSION['iduser']) && isset($_SESSION['email']))
{
	refreshTo("main.php");
};

$logon = new fhq_logon();
echo_shortpage($logon);

exit;	
?>
