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
		<link rel="stylesheet" type="text/css" href="css/fhq.css?ver=1"/>
		<!-- link rel="stylesheet" type="text/css" href="css/fhq.min.css?ver=1"/-->
		<link rel="stylesheet" type="text/css" href="templates/base/styles/jquery.datetimepicker.css?ver=1"/>

		<script type="text/javascript" src="js/fhq.frontend.lib.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq.gui.lib.js?ver=1"></script>
		<script src="//code.jquery.com/jquery-2.0.0b1.js"></script>
		<script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
		<script type="text/javascript" src="js/libs/progressbar-0.8.1.min.js"></script>
		<script type="text/javascript" src="js/libs/Chart-1.0.2.js"></script>
		<script type="text/javascript" src="js/fhq_send_request.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_echo_head.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_modal_dialog.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_games.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_quests.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_users.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_timer.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_updates.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_events.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_stats.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_feedback.js?ver=1"></script>
		<script type="text/javascript">
			var fhq = new FHQFrontEndLib();
			var fhqgui = new FHQGuiLib(fhq);
			fhq.client = "web-fhq2015";
			fhq.baseUrl = fhq.getCurrentApiPath(); // or another path
			// fhq.token = fhq.getTokenFromCookie();

			if (fhq.token && fhq.token != "")  // todo 
				fhq.security.logout();

		</script>
			
		<?php
			$anticolors = isset($_GET['dark']) ? 'base' : 'dark';
			$colors = isset($_GET['dark']) ? 'dark' : 'base';
			$hint = isset($_GET['dark']) ? 'You are on the dark side and you can not turning back.' : 'Join the dark side!';
			echo '<link rel="stylesheet" type="text/css" href="templates/'.$colors.'/styles/colors.css" />';
		?>

	</head>
	<body class="fhqbody" onload="fhqgui.loadCities();">

			<div id="modal_dialog" class="overlay">
				<div class="overlay_table">
					<div class="overlay_cell">
						<div class="overlay_content">
							<div id="modal_dialog_content">
								text
							</div>
							<div class="overlay_buttons">
								<div class="fhqbtn" onclick="closeModalDialog();">Close</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- FHQModalDialog -->
			<div id="fhqmodaldialog" class="fhqmodaldialog" onclick="fhqgui.clickFHQModalDialog_dialog();">
				<div class="fhqmodaldialog_table">
					<div class="fhqmodaldialog_cell">
						<div class="fhqmodaldialog_content" onclick="fhqgui.clickFHQModalDialog_content();">
							<div id="fhqmodaldialog_header" class="fhqmodaldialog_header"></div>
							<div id="fhqmodaldialog_content" class="fhqmodaldialog_content2"></div>
							<div id="fhqmodaldialog_buttons" class="fhqmodaldialog_buttons"></div>
							<div id="fhqmodaldialog_btncancel" class="fhqmodaldialog_btncancel">
								<div class="fhqbtn" onclick="fhqgui.closeFHQModalDialog();">Cancel</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Todo Content Place -->


			<!-- Sign In -->

			<div id="signin-form" style="display: none;">
				<!-- img src="images/logo_middle.png" /><br><br -->
				<!-- todo replace type="text" to type="email" (html5) -->
				<input placeholder="your@email.com" id="signin-email" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.signin(); else fhqgui.cleanupSignInMessages();">
				<br><br>
				<input placeholder="*****" id="signin-password" value="" type="password"  onkeydown="if (event.keyCode == 13) fhqgui.signin(); else fhqgui.cleanupSignInMessages();">
				<br><br>
				<font id="signin-error-message" color='#ff0000'></font>
			</div>

			<div id="signin-form-buttons" style="display: none;">
				<div class="fhqbtn" onclick="fhqgui.signin();">Sign In</div>
			</div>

			<!-- Sign Up -->

			<div id="signup-form" style="display: none;">
				<!-- todo replace type="text" to type="password" -->
				<input placeholder="your@email.com" id="signup-email" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.signup(); else fhqgui.cleanupSignUpMessages();"/>
				<br><br>
				<img src="api/captcha.php?rid=1" id="signup-captcha-image"/>
				<div class="fhqbtn" onclick="fhqgui.refreshSignUpCaptcha();"><img src="images/refresh.svg"/></div>
				<br><br>
				<input placeholder="captcha" id="signup-captcha" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.signup(); else fhqgui.cleanupSignUpMessages();"/>
				<br><br>
				<font id="signup-info-message"></font>
				<font id="signup-error-message" color='#ff0000'></font>
			</div>

			<div id="signup-form-buttons" style="display: none;">
				<div class="fhqbtn" onclick="fhqgui.signup();">Sign Up</div>			
			</div>

			<!-- Reset Password -->

			<div id="reset-password-form" style="display: none;">
				<input placeholder="your@email.com" id="reset-password-email" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.resetPassword(); else fhqgui.cleanupResetPasswordMessages();">
				<br><br>
				<img src="api/captcha.php" id="reset-password-captcha-image"/>
				<div class="fhqbtn" onclick="fhqgui.refreshResetPasswordCaptcha();"><img src="images/refresh.svg"/></div>
				<br><br>
				<input placeholder="captcha" id="reset-password-captcha" value="" type="text" onkeydown="if (event.keyCode == 13) fhqgui.resetPassword(); else fhqgui.cleanupResetPasswordMessages();">
				<br><br>
				<font id="reset-password-info-message"></font>
				<font id="reset-password-error-message" color='#ff0000'></font>
			</div>
			
			<div id="reset-password-form-buttons" style="display: none;">
				<div class="fhqbtn" onclick="fhqgui.resetPassword();">Reset</div>			
			</div>
			
			<!-- Right Menu Panel for Unathorized Users -->

			<div id="rightpanel_unauth" class="fhqtopmenu_rightpanel">
				<div class="fhq_btn_menu hint--bottom" data-hint="Sign In"  onclick="fhqgui.showSignInForm();">
					<img class="fhq_btn_menu_img" src="images/menu/sign_in.svg"/>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Sign Up"  onclick="fhqgui.showSignUpForm();">
					<img class="fhq_btn_menu_img" src="images/menu/sign_up.svg"/>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Restore Password"  onclick="fhqgui.showResetPasswordForm();">
					<img class="fhq_btn_menu_img" src="images/menu/resetpass.svg"/>
				</div>
			</div>
			
			<!-- Right Menu Panel for Athorized Users -->
			
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
							</div>
							<br><br>
							<div class="index_menu hint--bottom" data-hint="Sign In"  onclick="fhqgui.showSignInForm();">
								<img width="100px" src="images/menu/sign_in.svg"/>
							</div>

							<div class="index_menu hint--bottom" data-hint="Sign Up"  onclick="fhqgui.showSignUpForm();">
								<img width="100px" src="images/menu/sign_up.svg"/>
							</div>

							<div class="index_menu hint--bottom" data-hint="Reset Password"  onclick="fhqgui.showResetPasswordForm();">
								<img width="100px" src="images/menu/resetpass.svg"/>
							</div>
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
	
