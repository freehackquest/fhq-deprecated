<?php

if (!file_exists("config/config.php")) {
	header ("Location: install/install_step01.php");
	exit;
};

include_once "config/config.php";
include_once "engine/fhq.php";

$security = new fhq_security();

if (isset( $_GET['exit']) )
{
	$security = new fhq_security();
	$security->logout();
	echo "OK";
	exit;
};

if (isset($_SESSION['user']))
{
	refreshTo("main.php");
};

?>

<html>
	<head>
		<title>Free Hack Quest</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="sea-kg" />
		<meta name="copyright" lang="ru" content="sea-kg" />
		<meta name="description" content="competition information security" />
		<meta name="keywords" content="security, fhq, fhq 2012, fhq 2013, fhq 2014, free, hack, quest, competition, information security, ctf, joepardy" />		
		<link rel="stylesheet" type="text/css" href="templates/base/styles/site.css" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/body.css" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/button3.css" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/hint.css">
		<script type="text/javascript" src="js/index.js"></script>

		<?php
			$anticolors = isset($_GET['dark']) ? 'base' : 'dark';
			$colors = isset($_GET['dark']) ? 'dark' : 'base';
			$hint = isset($_GET['dark']) ? 'You are on the dark side and you can not turning back.' : 'Join the dark side!';
			echo '<link rel="stylesheet" type="text/css" href="templates/'.$colors.'/styles/colors.css" />';
		?>
		
	</head>
	<body class="main" onload="show_index_element('indexcontent_sign_in');">

			<table width="100%" height="100%">
				<tr>
					<td width="5%" class="fhq_index_column" valign="top">
					</td>
					<td align="center" valign="middle">
						
							<div class="fhq_index_logo hint--bottom" data-hint="<?php echo $hint; ?>" onclick="window.location.href = '?<?php echo $anticolors; ?>';">
								<img src="templates/base/images/logo/fhq_2015.png" />
							</div><br><br>
							<div class="index_menu">
								<div class="index_menu hint--bottom" data-hint="Sign in" onclick="show_index_element('indexcontent_sign_in');">
									<img width="100px" src="templates/base/images/index/sign_in.png"/>
								</div>
								<div class="index_menu hint--bottom" data-hint="Registration" onclick="show_index_element('indexcontent_registration');">
									<img width="100px" src="templates/base/images/index/registration.png"/>
								</div>
								<div class="index_menu hint--bottom" data-hint="Restore password" onclick="show_index_element('indexcontent_restore');" >
									<img width="100px" src="templates/base/images/index/restore.png"/>
								</div>
							</div>

							<div class="indexcontent" id="indexcontent_sign_in">
								<input placeholder="your@email.com" id="email" value="" type="text" onkeydown="if (event.keyCode == 13) sign_in();"><br><br>
								<input placeholder="*****" id="password" value="" type="password"  onkeydown="if (event.keyCode == 13) sign_in();"><br><br>
								<div class="button3 ad" onclick="sign_in();">sign in</div>
							</div>

							<div class="indexcontent" id="indexcontent_registration">
								<input placeholder="your@email.com" id="email_reg" value="" type="text" onkeydown="if (event.keyCode == 13) registration();"/><br><br>
								<img src="captcha.php" id="captcha_image_reg"/>
								<a href="javascript:void(0);" onclick="document.getElementById('captcha_image_reg').src ='captcha.php?rid=' + Math.random();">
									<img src="templates/base/images/index/refresh_captcha.png"/>
								</a>
								<br><br>
								<input placeholder="captcha" id="captcha_reg" value="" type="text" onkeydown="if (event.keyCode == 13) registration();"/><br><br>
								<div class="button3 ad" onclick="registration();">Register</div>
							</div>
		
							<div class="indexcontent" id="indexcontent_restore">
								<input placeholder="your@email.com" id="email_restore" value="" type="text" onkeydown="if (event.keyCode == 13) restore();"><br><br>
								
								<img src="captcha.php" id="captcha_image_restore"/>
								<a href="javascript:void(0);" onclick="document.getElementById('captcha_image_restore').src = 'captcha.php?rid=' + Math.random();">
									<img src="templates/base/images/index/refresh_captcha.png"/>
								</a><br><br>

								<input placeholder="captcha" id="captcha_restore" value="" type="text" onkeydown="if (event.keyCode == 13) restore();">
								<br><br>
								<div class="button3 ad" onclick="restore();">Restore password</div>
							</div>
		
							<br><br>

							<font id="info_message"></font>
							<font id="error_message" color='#ff0000'></font>

					</td>
					<td width="30%" class="fhq_index_column">
						<center>
							<?php
				include("about.php");
			?>					
					
					</td>
				</tr>
				<tr>
					<td class="fhq_index_column"></td>
					<td><?php include('copyright.php'); ?></td>
					<td class="fhq_index_column"></td>
				</tr>
			</table>
		</center>
	</body>
</html>
	
