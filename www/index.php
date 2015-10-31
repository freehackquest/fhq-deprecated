<?php

if (!file_exists("config/config.php")) {
	echo "Please configure config/config.php";
	exit;
};

session_start();
if (isset($_SESSION['user']))
{
	header ("Location: main.php");
	exit;
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

		<link rel="stylesheet" type="text/css" href="templates/base/styles/fhq.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/body.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/menu.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/site.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/button3.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/games.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/quest_info.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/overlay.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/hint.css?ver=1">
		<link rel="stylesheet" type="text/css" href="templates/base/styles/timer.css?ver=1">
		<link rel="stylesheet" type="text/css" href="templates/base/styles/quests.css?ver=1">
		<link rel="stylesheet" type="text/css" href="templates/base/styles/users.css?ver=1">
		<link rel="stylesheet" type="text/css" href="templates/base/styles/events.css?ver=1">
		<link rel="stylesheet" type="text/css" href="styles/userpanel_by_nitive.css?ver=1"/>
		<link rel="stylesheet" type="text/css" href="templates/base/styles/jquery.datetimepicker.css?ver=1"/>

		<script type="text/javascript" src="js/fhq.frontend.lib.js?version=1"></script>
		<script type="text/javascript" src="js/index.js?version=1"></script>
		<script type="text/javascript">
			var fhq = new FHQFrontEndLib();
			fhq.client = "web-fhq2014";
			fhq.baseUrl = fhq.getCurrentApiPath(); // or another path
			// fhq.token = fhq.getTokenFromCookie();

			if (fhq.token && fhq.token != "")  // todo 
				fhq.security.logout();
			
			// new lib js
			function login() {
				var obj = fhq.security.login(this.email.value,this.password.value);
				if (obj.result == "fail") {
					this.error_message.innerHTML = "<b>" + obj.error.message + "</b>";
					this.info_message.innerHTML = "";
				} else {
					window.location.href = "main.php";
				}	
			}
		</script>
			
		<?php
			$anticolors = isset($_GET['dark']) ? 'base' : 'dark';
			$colors = isset($_GET['dark']) ? 'dark' : 'base';
			$hint = isset($_GET['dark']) ? 'You are on the dark side and you can not turning back.' : 'Join the dark side!';
			echo '<link rel="stylesheet" type="text/css" href="templates/'.$colors.'/styles/colors.css" />';
		?>

	</head>
	<body class="fhqbody" onload="show_index_element('indexcontent_sign_in'); loadCities();">

			<div id="rightpanel_unauth" class="fhqtopmenu_rightpanel">
				<div class="fhq_btn_menu hint--bottom" data-hint="Sign In"  onclick="show_index_element('indexcontent_sign_in');">
					<img class="fhq_btn_menu_img" src="images/menu/sign_in.svg"/>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Sign Up"  onclick="show_index_element('indexcontent_registration');">
					<img class="fhq_btn_menu_img" src="images/menu/sign_up.svg"/>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Restore Password"  onclick="show_index_element('indexcontent_restore');">
					<img class="fhq_btn_menu_img" src="images/menu/restore.svg"/>
				</div>
			</div>
			
			<div id="rightpanel_user" class="fhqtopmenu_rightpanel" style="display: none;">
				<div class="fhq_btn_menu hint--bottom" data-hint="User profile"  onclick="">
					<img class="fhq_btn_menu_img" src="images/menu/user.png"/>
					<div style="display: inline-block;" id="btn_user_info"></div>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Logout" onclick="logout();">
					<img class="fhq_btn_menu_img" src="images/menu/logout.png"/><br>
				</div>
			</div>

			<table width="100%" height="100%">
				<tr>
					<td width="5%" class="fhq_index_column" valign="top" align="center">
					</td>
					<td align="center" valign="middle">
						
							<div class="fhq_index_logo hint--bottom" data-hint="<?php echo $hint; ?>" onclick="window.location.href = '?<?php echo $anticolors; ?>';">
								<img src="templates/base/images/logo/fhq_2015.png" />
							</div><br><br>

							<div class="indexcontent" id="indexcontent_sign_in">
								<input placeholder="your@email.com" id="email" value="" type="text" onkeydown="if (event.keyCode == 13) login();"><br><br>
								<input placeholder="*****" id="password" value="" type="password"  onkeydown="if (event.keyCode == 13) login();"><br><br>
								<div class="fhqbtn" onclick="login();">sign in</div>
							</div>

							<div class="indexcontent" id="indexcontent_registration">
								<input placeholder="your@email.com" id="email_reg" value="" type="text" onkeydown="if (event.keyCode == 13) registration();"/><br><br>
								<img src="api/captcha.php" id="captcha_image_reg"/>
								<a href="javascript:void(0);" onclick="document.getElementById('captcha_image_reg').src ='api/captcha.php?rid=' + Math.random();">
									<img src="templates/base/images/index/refresh_captcha.png"/>
								</a>
								<br><br>
								<input placeholder="captcha" id="captcha_reg" value="" type="text" onkeydown="if (event.keyCode == 13) registration();"/><br><br>
								<div class="fhqbtn" onclick="registration();">Register</div>
							</div>
		
							<div class="indexcontent" id="indexcontent_restore">
								<input placeholder="your@email.com" id="email_restore" value="" type="text" onkeydown="if (event.keyCode == 13) restore();"><br><br>
								
								<img src="api/captcha.php" id="captcha_image_restore"/>
								<a href="javascript:void(0);" onclick="document.getElementById('captcha_image_restore').src = 'api/captcha.php?rid=' + Math.random();">
									<img src="templates/base/images/index/refresh_captcha.png"/>
								</a><br><br>

								<input placeholder="captcha" id="captcha_restore" value="" type="text" onkeydown="if (event.keyCode == 13) restore();">
								<br><br>
								<div class="fhqbtn" onclick="restore();">Restore password</div>
							</div>
		
							<br><br>

							<font id="info_message"></font>
							<font id="error_message" color='#ff0000'></font>

					</td>
					<td width="30%" class="fhq_index_column">
						<center>
							<div>
								<h1>free-hack-quest</h1>
								This is an open source platform for competitions in computer security.
							</div>
							<div id="cities">
							</div>
							<div id="about">
							</div>
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
	
