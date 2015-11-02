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

		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
			(function (d, w, c) {
				(w[c] = w[c] || []).push(function() {
					try {
						w.yaCounter32831012 = new Ya.Metrika({
							id:32831012,
							clickmap:true,
							trackLinks:true,
							accurateTrackBounce:true
						});
					} catch(e) { }
				});

				var n = d.getElementsByTagName("script")[0],
					s = d.createElement("script"),
					f = function () { n.parentNode.insertBefore(s, n); };
				s.type = "text/javascript";
				s.async = true;
				s.src = "https://mc.yandex.ru/metrika/watch.js";

				if (w.opera == "[object Opera]") {
					d.addEventListener("DOMContentLoaded", f, false);
				} else { f(); }
			})(document, window, "yandex_metrika_callbacks");
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/32831012" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->

	</head>
	<body class="fhqbody fhqearth" onload="fhqgui.loadCities();">

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
							<div class="fhqmodaldialog_iconclose" onclick="fhqgui.closeFHQModalDialog();"></div>
							<div class="fhqmodaldialog_iconfhq"></div>
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

			<!-- Donate -->

			<div id="donate-form" style="display: none;">
				<center>
					<br>
					<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/donate.xml?account=41001311490795&quickpay=donate&payment-type-choice=on&default-sum=&targets=%D0%BD%D0%B0+%D0%B4%D0%BE%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D1%83+%D0%BF%D1%80%D0%BE%D0%B5%D1%82%D0%B0+Free-Hack-Quest&target-visibility=on&project-name=Free-Hack-Quest&project-site=http%3A%2F%2Ffhq.sea-kg.com&button-text=01&comment=on&hint=%D0%BE%D1%82+%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D1%82%D0%B5%D0%BB%D1%8F+%3F+%D0%B6%D0%B5%D1%80%D1%82%D0%B2%D1%83%D1%8E+%D0%B4%D0%BB%D1%8F+%D0%B4%D0%BE%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BA%D0%B8%2F%D1%81%D0%BE%D0%B7%D0%B4%D0%B0%D0%BD%D0%B8%D0%B5+%D0%BA%D0%B2%D0%B5%D1%81%D1%82%D0%BE%D0%B2&mail=on&successURL=fhq.sea-kg.com%2Fdonate-thanks.html" width="526" height="203"></iframe>
					<br>
					<div style="padding: 0.6em; background-color: border-radius: 7px; -moz-border-radius: 7px;">
						<a href="https://money.yandex.ru/embed/?from=sbal" title="Виджеты Яндекс.Денег" style="width: 200px; height: 100px; display: block; margin-bottom: 0.6em; background: url('https://money.yandex.ru/share-balance.xml?id=17819619&key=6DE09B07E089E0AA') 0 0 no-repeat; -background: none; -filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='https://money.yandex.ru/share-balance.xml?id=17819619&key=6DE09B07E089E0AA', sizingMethod = 'crop');"></a>
						<form action="https://money.yandex.ru/direct-payment.xml" method="post">
							<input type="hidden" name="receiver" value="41001311490795"/>
							<input type="hidden" name="sum" value="0"/>
							<input type="hidden" name="destination" value="Яндекс.Деньги &#8212; на хорошее дело не жалко!"/>
							<input type="hidden" name="FormComment" value="Пожертвование через виджет &#171Мой баланс&#187;"/>
						</form>
					</div>
				</center>
			</div>

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
					<img class="fhq_btn_menu_img" src="images/menu/sign_in_50x50.png"/>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Sign Up"  onclick="fhqgui.showSignUpForm();">
					<img class="fhq_btn_menu_img" src="images/menu/sign_up_50x50.png"/>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Restore Password"  onclick="fhqgui.showResetPasswordForm();">
					<img class="fhq_btn_menu_img" src="images/menu/resetpass_50x50.png"/>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Donate" onclick="fhqgui.showDonateForm();">
					<img class="fhq_btn_menu_img" src="images/menu/donate_50x50.png"/>
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
				<div class="fhq_btn_menu hint--bottom" data-hint="Donate">
					<a target="_blank" href="http://fhq.sea-kg.com/donate.html"><img class="fhq_btn_menu_img" src="images/menu/donate.svg"/></a>
				</div>
			</div>

			<!-- Vertical Left Panel -->
			
			


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
								<img width="100px" src="images/menu/sign_in_100x100.png"/>
							</div>

							<div class="index_menu hint--bottom" data-hint="Sign Up"  onclick="fhqgui.showSignUpForm();">
								<img width="100px" src="images/menu/sign_up_100x100.png"/>
							</div>

							<div class="index_menu hint--bottom" data-hint="Reset Password" onclick="fhqgui.showResetPasswordForm();">
								<img width="100px" src="images/menu/resetpass_100x100.png"/>
							</div>
							
							<div class="index_menu hint--bottom" data-hint="Donate" onclick="fhqgui.showDonateForm();">
								<img width="100px" src="images/menu/donate_100x100.png"/>
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
	
