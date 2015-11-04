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
		<script src="https://code.jquery.com/jquery-2.0.0b1.js"></script>
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

			if (fhq.token && fhq.token != "")  // todo 
				fhq.security.logout();

			$(document).ready(function() {
				// loading cool dark style
				if(fhqgui.containsPageParam("dark")){
					$('#jointothedarkside').attr('data-hint', 'You are on the dark side and you can not turning back.');
					$('#jointothedarkside').attr('onclick', 'window.location.href = "?base";');
				}else{
					$('#jointothedarkside').attr('data-hint', 'Join the dark side!');
					$('#jointothedarkside').attr('onclick', 'window.location.href = "?dark";');
				}
				
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
				
				if(fhqgui.containsPageParam("page")){
					var page=fhqgui.pageParams['page'];
					if(page == "scoreboard"){
						loadScoreboard(0);
					}else if(page == "about"){
						fhqgui.loadAbout();
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
	<body class="fhqbody fhqearth">

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

			<!-- Horizontal Left Panel -->

			<div class="fhqtopmenu_leftpanel">
				<a id="btnmenu_main_page" class="fhq_btn_menu hint--right" data-hint="Main Page" href="?page=main_page">
					<img class="fhq_btn_menu_img" src="templates/base/images/logo/fhq_2015_small.png"/>
				</a>

				<a id="btnmenu_about" class="fhq_btn_menu hint--bottom" data-hint="About" href="?page=about">
					<img class="fhq_btn_menu_img" src="images/menu/unknown.png"/>
				</a>

				<div id="btnmenu_game" class="fhq_btn_menu hint--bottom" data-hint="Please click to change a curent game"  onclick="changeGame();">
					<div class="fhq_btn_menu_img">
						<img class="fhq_btn_menu_img" src="images/menu/unknown.png"/>
					</div>
				</div>
				<a id="btnmenu_scoreboard" class="fhq_btn_menu hint--bottom" data-hint="Scoreboard" href="?page=scoreboard">
					<img class="fhq_btn_menu_img" src="images/menu/scoreboard.png"/>
					<div class="fhqredcircle hide" id="view_score"></div>
				</a>
				<div id="btnmenu_rules" class="fhq_btn_menu" data-hint="Rules" onclick="fhqgui.loadRules(0);">
					<img class="fhq_btn_menu_img" src="images/menu/rules.png"/><br>
				</div>
				<div id="btnmenu_quests" class="fhq_btn_menu hint--bottom" data-hint="Quests" onclick="loadQuests();">
					<img class="fhq_btn_menu_img" src="images/menu/quests.png"/>
				</div>
				<a id="btnmenu_stats" class="fhq_btn_menu hint--bottom" data-hint="Statistics" href="?page=stats">
					<img class="fhq_btn_menu_img" src="images/menu/stats.png"/>
				</a>
					
				<div class="fhq_btn_menu hint--bottom" data-hint="Filter" id="btnfilter" onclick="fhqgui.showFilter();">
					<img class="fhq_btn_menu_img" src="images/menu/filter.png"/><br>
				</div>
			</div>

			<!-- Vertical Left Panel -->
			<div class="fhqleftmenu">
				
				<a id="btnmenu_games" class="fhq_btn_menu hint--right" data-hint="Games" href="?page=games">
					<img class="fhq_btn_menu_img" src="images/menu/games.png"/>
					<div class="fhqredcircle hide" id="plus_events">0</div>
				</a>
				
				<a id="btnmenu_skills" class="fhq_btn_menu hint--right" data-hint="Skills" href="?page=skills">
					<img class="fhq_btn_menu_img" src="images/menu/skills.png"/>
				</a>
				
				<a id="btnmenu_news" class="fhq_btn_menu hint--right" data-hint="News" href="?page=news">
					<img class="fhq_btn_menu_img" src="images/menu/news.png"/>
					<div class="fhqredcircle hide" id="plus_events">0</div>
				</a>

				<div id="btnmenu_feedback" class="fhq_btn_menu hint--right" data-hint="Feedback" onclick="loadFeedback();">
					<img class="fhq_btn_menu_img" src="images/menu/feedback.png"/>
					<!-- div class="fhqredcircle" id="plus_feedback">0</div -->
				</div>
				<div id="btnmenu_settings" class="fhq_btn_menu hint--right" data-hint="Settings" onclick="fhqgui.loadSettings('content_page');">
					<img class="fhq_btn_menu_img" src="images/menu/settings.png"/>
				</div>
				<div id="btnmenu_users" class="fhq_btn_menu hint--right" data-hint="Users" onclick="createPageUsers(); updateUsers();">
					<img class="fhq_btn_menu_img" src="images/menu/users.png"/>
				</div>
				<div id="btnmenu_answer_list" class="fhq_btn_menu hint--right" data-hint="Answer List" onclick="createPageAnswerList(); updateAnswerList();">
					<img class="fhq_btn_menu_img" src="images/menu/answerlist.png"/>
				</div>
				<div id="btnmenu_updates" class="fhq_btn_menu hint--right" data-hint="Install Updates" onclick="installUpdates();">
					<img class="fhq_btn_menu_img" src="images/menu/updates.png"/>
				</div>
			</div>

			<table cellspacing=10px cellpadding=10px width="100%" height="100%">
				<tr>
					<td colspan=2 align=left valign=top height=85px></td>
				</tr>
				<tr>
					<td width=70px id="submenu" valign="top" align=right></td>
					<td height="100%" valign="top">
						<center>
						<div id="content_page">
							
						</div>
						</center>
					</td>
				</tr>
				<tr>
					<td id="copyright" colspan=2>
<center>
	<font face="Arial" size=2>Copyright © 2011-2015 sea-kg. Source code: <a href="https://github.com/freehackquest/fhq">github.com</a> API: <a href="api/?html">html</a> or <a href="api/?json">json</a> VM: <a href="http://files.sea-kg.com/fhq-ova/" target="_ablank">ova</a> Team: <a href="https://ctftime.org/team/16804">ctftime</a> Donate: <a href="http://fhq.sea-kg.com/donate.html">donate</a><br></font>
</center>
					</td>
				</tr>
			</table>


			<div id="mainpage" style="display: none;">
				<table>
					<tr>
						<td valign="top">
							<div id="jointothedarkside" class="fhq_index_logo hint--bottom leftimg">
								<img class="leftimg" src="templates/base/images/logo/fhq_2015.png"/>
							</div>
						</td>
						<td valign="top">
							<h1>free-hack-quest</h1>
								This is an open source platform for competitions in computer security.
							<div id="cities">
							</div>
						</td>
					</tr>
				</table>
			</div>
		</center>
	</body>
</html>
	
