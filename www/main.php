<?php

if (!file_exists("config/config.php")) {
	echo "Please configure config/config.php";
	exit;
};

session_start();
if (!isset($_SESSION['user']))
{
	header ("Location: index.php");
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
		<script src="js/libs/jquery-3.1.0.min.js"></script>

		<link rel="stylesheet" type="text/css" href="templates/base/styles/fhq.css?ver=1" />
		<link rel="stylesheet" type="text/css" href="templates/base/styles/body.css?ver=1" />
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
		<link rel="stylesheet" type="text/css" href="templates/base/styles/jquery.datetimepicker.css?ver=1"/>
		<link rel="stylesheet" type="text/css" href="css/fhq.css?ver=1"/>
		<link rel="stylesheet" type="text/css" href="css/fhq_fix.css?ver=1" />
		<!-- link rel="stylesheet" type="text/css" href="css/fhq.min.css?ver=1"/-->

		<!-- todo -->
		<?php
			$template = isset($_SESSION['user']['profile']['template']) ? $_SESSION['user']['profile']['template'] : 'base';
			$template = htmlspecialchars($template);
		?>

		<script type="text/javascript" src="js/fhq.localization.lib.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq.frontend.lib.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq.gui.lib.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq.plugins/plugins.js?ver=1"></script>
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
			fhq.client = "web-fhq2015";
			fhq.baseUrl = fhq.getCurrentApiPath(); // or another path
			// fhq.token = fhq.getTokenFromCookie();
			var fhqgui = new FHQGuiLib(fhq);

			function logout() {
				fhq.security.logout();			
				window.location.href = "index.php";
			}
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
	<body onload="loadQuests(); updateCountOfEvents(); fhqgui.processParams();" class="fhqbody fhqearth <?php echo $template; ?>">
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
			
		<center>

			<div class="fhqtopmenu_leftpanel">
				<?php

			$role = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : 'user';
			$score = isset($_SESSION['user']['score']) ? $_SESSION['user']['score'] : 0;
			$userid = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
			$nick = isset($_SESSION['user']['nick']) ? $_SESSION['user']['nick'] : '';
			$nick = htmlspecialchars($nick);
			$template = 'base';

			$gameid = 0;
			$hint_on_game = 'Please click to change a curent game';
			if (isset($_SESSION['game'])) {
				$game_title = $_SESSION['game']['title'];
				$game_logo = $_SESSION['game']['logo'];
				$gameid = $_SESSION['game']['id'];
				$hint_on_game = $game_title.' ('.$hint_on_game.') ';
			} else {
				$game_logo = 'images/menu/unknown.png';
			}
			
			$arrmenu = array();
			
			$arrmenu[] = array(
				'name' => 'logo',
				'html' => '
					<div class="fhq_btn_menu fhq_btn_menu_color_none hint--right" data-hint="About" onclick="fhqgui.loadMainPage();">
						<img class="fhq_btn_menu_img" src="templates/base/images/logo/fhq_2015_small.png"/>
					</div>
				',
				'show' => true,
			);

			$arrmenu[] = array(
				'name' => 'game_info',
				'html' => '
					<div class="fhq_btn_menu hint--bottom" data-hint="'.$hint_on_game.'"  onclick="changeGame();">
						<div class="fhq_btn_menu_img">
							<img class="fhq_btn_menu_img" src="'.$game_logo.'"/>
						</div>
					</div>
				',
				'show' => true,
			);

			$arrmenu[] = array(
				'name' => 'scoreboard',
				'html' => '
					<div class="fhq_btn_menu hint--bottom" data-hint="Scoreboard" onclick="loadScoreboard('.$gameid.');">
						<img class="fhq_btn_menu_img" src="images/menu/scoreboard.png"/>
						<div class="fhqredcircle" id="view_score">'.$score.'</div>
					</div>
				',
				'show' => ($gameid != 0),
			);

			$arrmenu[] = array(
				'name' => 'rules',
				'html' => '
					<div class="fhq_btn_menu" data-hint="Rules" onclick="fhqgui.loadRules('.$gameid.');">
						<img class="fhq_btn_menu_img" src="images/menu/rules.png"/><br>
					</div>
				',
				'show' => ($gameid != 0),
			);

			$arrmenu[] = array(
				'name' => 'quests',
				'html' => '
					<div class="fhq_btn_menu hint--bottom" data-hint="Quests" onclick="loadQuests();">
						<img class="fhq_btn_menu_img" src="images/menu/quests.png"/>
					</div>
				',
				'show' => ($gameid != 0),
			);

			$arrmenu[] = array(
				'name' => 'stats',
				'html' => '
					<div class="fhq_btn_menu hint--bottom" data-hint="Statistics" onclick="createPageStatistics('.$gameid.'); updateStatistics('.$gameid.');">
						<img class="fhq_btn_menu_img" src="images/menu/stats.png"/><br>
					</div>
				',
				'show' => ($gameid != 0),
			);
			
			
			
			$arrmenu[] = array(
				'name' => 'filter',
				'html' => '
					<div class="fhq_btn_menu hint--bottom" data-hint="Filter" id="btnfilter" onclick="fhqgui.showFilter();">
						<img class="fhq_btn_menu_img" src="images/menu/filter.png"/><br>
					</div>
				',
				'show' => true,
			);	

			// echo menu
			foreach ($arrmenu as $menu) {
				if ($menu['show']) {
					echo $menu['html'];
				}
			}
	?>		</div>
		
			<div class="fhqtopmenu_rightpanel">
				<div class="fhq_btn_menu hint--bottom" data-hint="User profile"  onclick="loadUserProfile(<?php echo $userid; ?>);">
					<img class="fhq_btn_menu_img" src="images/menu/user.png"/>
					<div style="display: inline-block;" id="btn_user_info"><?php echo $nick; ?></div>
				</div>
				<div class="fhq_btn_menu hint--bottom" data-hint="Logout" onclick="logout();">
					<img class="fhq_btn_menu_img" src="images/menu/logout.png"/><br>
				</div>
			</div>

			<div class="fhqleftmenu">
				
<?php
						$arrmenu2 = array();

		$arrmenu2[] = array(
			'name' => 'games',
			'html' => '
				<div class="fhq_btn_menu hint--right" data-hint="Games" onclick="fhqgui.loadGames();">
					<img class="fhq_btn_menu_img" src="images/menu/games.png"/>
				</div>
			',
			'show' => true,
		);
		
		$arrmenu2[] = array(
			'name' => 'skills',
			'html' => '
				<div class="fhq_btn_menu hint--bottom" data-hint="Skills" onclick="fhqgui.createPageSkills(); fhqgui.updatePageSkills();">
					<img class="fhq_btn_menu_img" src="images/menu/skills.png"/><br>
				</div>
			',
			'show' => true,
		);

		$arrmenu2[] = array(
			'name' => 'news',
			'html' => '
				<div class="fhq_btn_menu hint--right" data-hint="News" onclick="createPageEvents(); updateEvents();">
					<img class="fhq_btn_menu_img" src="images/menu/news.png"/>
					<div class="fhqredcircle" id="plus_events">0</div>
				</div>
			',
			'show' => true,
		);

		$arrmenu2[] = array(
			'name' => 'feedback',
			'html' => '
				<div class="fhq_btn_menu hint--right" data-hint="Feedback" onclick="loadFeedback();">
					<img class="fhq_btn_menu_img" src="images/menu/feedback.png"/>
					<!-- div class="fhqredcircle" id="plus_feedback">0</div -->
				</div>
			',
			'show' => true,
		);
			
		$arrmenu2[] = array(
			'name' => 'users',
			'html' => '
				<div class="fhq_btn_menu hint--right" data-hint="Settings" onclick="fhqgui.loadSettings(\'content_page\');">
					<img class="fhq_btn_menu_img" src="images/menu/settings.png"/>
				</div>
			',
			'show' => $role == 'admin',
		);
		
		$arrmenu2[] = array(
			'name' => 'users',
			'html' => '
				<div class="fhq_btn_menu hint--right" data-hint="Users" onclick="createPageUsers(); updateUsers();">
					<img class="fhq_btn_menu_img" src="images/menu/users.png"/>
				</div>
			',
			'show' => $role == 'admin',
		);
		
		$arrmenu2[] = array(
			'name' => 'answerlist',
			'html' => '
				<div class="fhq_btn_menu hint--right" data-hint="Answer List" onclick="createPageAnswerList(); updateAnswerList();">
					<img class="fhq_btn_menu_img" src="images/menu/answerlist.png"/>
				</div>
			',
			'show' => $role == 'admin',
		);

		$arrmenu2[] = array(
			'name' => 'install_updates',
			'html' => '
				<div class="fhq_btn_menu hint--right" data-hint="Install Updates" onclick="installUpdates();">
					<img class="fhq_btn_menu_img" src="images/menu/updates.png"/>
				</div>
			',
			'show' => $role == 'admin',
		);

		// echo menu
		foreach ($arrmenu2 as $menu) {
			if ($menu['show']) {
				echo $menu['html'];
			}
		}	
						
						?>
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
					<td colspan=2>
			<?php 
				include('copyright.php');
			?>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>
