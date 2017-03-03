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

		<link rel="shortcut icon" href="favicon.ico">
		<script src="js/libs/jquery-3.1.0.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css?ver=1"/>
		<link rel="stylesheet" type="text/css" href="css/fhq.css?ver=1"/>
		<!-- link rel="stylesheet" type="text/css" href="css/fhq.min.css?ver=1"/-->

		<script type="text/javascript" src="js/fhq.base.js"></script>
		<script type="text/javascript" src="js/fhq.localization.js"></script>
		<script type="text/javascript" src="js/fhq.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq.ws.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq.gui.lib.js?ver=1"></script>
		
		<script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
		<script type="text/javascript" src="js/libs/progressbar-0.8.1.min.js"></script>
		<script type="text/javascript" src="js/libs/Chart-1.0.2.js"></script>
		<script type="text/javascript" src="js/fhq_send_request.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_echo_head.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_modal_dialog.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_games.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_users.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_timer.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_events.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_stats.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq_feedback.js?ver=1"></script>
		<script type="text/javascript">
			fhq.client = "web-fhq2015";
			fhq.baseUrl = fhq.getCurrentApiPath(); // or another path
			var fhqgui = new FHQGuiLib(fhq);
			

			if (fhq.token && fhq.token != "")  // todo 
				fhq.security.logout();

			$(document).ready(function() {
			
				fhqgui.applyColorScheme();
				fhq.ws.setWSState(fhq.ws.getWSState()); // Update state of WS
				fhqgui.loadTopPanel();
				fhq.ui.initChatForm();

				$("#btnfilter").hide();
				$("#btnmenu_game").hide();

				$("#btnmenu_rules").hide();
				$("#btnmenu_quests").hide();
				$("#btnmenu_stats").hide();
				
				// $("#btnmenu_games").hide();
				// $("#btnmenu_skills").hide();

				$("#btnmenu_feedback").hide();
				$("#btnmenu_settings").hide();
				$("#btnmenu_users").hide();
				$("#btnmenu_answer_list").hide();
				$("#btnmenu_updates").hide();
				
				if(fhq.containsPageParam("news")){
					createPageEvents();
					updateEvents();
				}else if(fhq.containsPageParam("quests")){
					fhqgui.loadQuests();
				}else if(fhq.containsPageParam("about")){
					fhqgui.loadMainPage();
				}else if(fhq.containsPageParam("page")){
					var page=fhq.pageParams['page'];
					if(page == "scoreboard"){
						loadScoreboard(0);
					}else if(page == "news"){
						createPageEvents();
						updateEvents();
					}else if(page == "main_page"){
						fhqgui.loadMainPage();
					}else if(page == "games"){
						fhqgui.loadGames();
					}else if(page == "skills"){
						fhqgui.createPageSkills();
						fhqgui.updatePageSkills();
					}else if(page == "stats"){
						// todo
						createPageStatistics('.$gameid.');
						updateStatistics('.$gameid.');
					}else{
						$("#content_page").html('unknown page');
					}
				}else{
					fhqgui.loadMainPage();
				}
			});
		</script>

		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
			if(window.location.host == "freehackquest.com"){
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
			}
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/32831012" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->

	</head>
	<body>

			<!-- Modal dialog -->
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
			
			
			<div class="fhqtopmenu_toppanel"></div>


			<div id="copyright">
				<center>
					<font face="Arial" size=2>Copyright © 2011-2016 sea-kg.
					Source code: <a href="https://github.com/freehackquest/fhq">github.com</a>
					API: <a href="api/?html">html</a> or <a href="api/?json">json</a>
					VM: <a href="http://dist.freehackquest.com/" target="_ablank">ova</a>
					Team: <a href="https://ctftime.org/team/16804">ctftime</a>
					Donate: <a href="http://fhq.sea-kg.com/donate.html">donate</a>
					WS State: <font id="websocket_state">?</font><br>
					</font>
				</center>
			</div>

			<div class="sendchatmessage-form">
				<input id="sendchatmessage_text" type="text">
				<div id="sendchatmessage_submit" class="sendchatmessage-submit"></div>
			</div>
			
			<table cellspacing=10px cellpadding=10px width="100%" height="100%">
				<tr>
					<td colspan=2 align=left valign=top height=85px></td>
				</tr>
				<tr>
					<td height="100%" valign="top">
						<center>
						<div id="content_page">
							
						</div>
						</center>
					</td>
				</tr>
			</table>
		</center>
		
	</body>
</html>
	
