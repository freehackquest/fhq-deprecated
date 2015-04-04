<?php

if (!file_exists("config/config.php")) {
	header ("Location: install/install_step01.php");
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

		<!-- todo -->
		<?php
			$template = isset($_SESSION['user']['profile']['template']) ? $_SESSION['user']['profile']['template'] : 'base';
			$template = htmlspecialchars($template);
			echo '
			<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/colors.css?ver=1" />	
			';
		?>

		<script type="text/javascript" src="js/fhq.frontend.lib.js?ver=1"></script>
		<script type="text/javascript" src="js/fhq.gui.lib.js?ver=1"></script>
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
		<script type="text/javascript" src="js/fhq_settings.js?ver=1"></script>

		<script type="text/javascript">
			var fhq = new FHQFrontEndLib();
			var fhqgui = new FHQGuiLib();

			fhq.client = "web-fhq2014";
			fhq.baseUrl = fhq.getCurrentApiPath(); // or another path
			// fhq.token = fhq.getTokenFromCookie();		

			function logout() {
				fhq.security.logout();			
				window.location.href = "index.php";
			}

			function loadAbout() {
				send_request_post_html('about.php', '', function(html) {
					document.getElementById('content_page').innerHTML = html;
				});
			}

		</script>
		
	</head>
	<body onload="loadQuests();" class="main">
		<div id="modal_dialog" class="overlay">
			<div class="overlay_table">
				<div class="overlay_cell">
					<div class="overlay_content">
						<div id="modal_dialog_content">
							text
						</div>
						<div class="overlay_close">
							<a class="button3 ad" href="javascript:void(0);" onclick="closeModalDialog();">
								Close
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<center>
			<table cellspacing=10px cellpadding=10px width="100%" height="100%">
				<tr class="fhq_menucolor">
					<td align="left" valign = "top" width="82%">
<?php
	$role = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : 'user';
	$score = isset($_SESSION['user']['score']) ? $_SESSION['user']['score'] : 0;
	$userid = isset($_SESSION['user']['iduser']) ? $_SESSION['user']['iduser'] : 0;
	$nick = isset($_SESSION['user']['nick']) ? $_SESSION['user']['nick'] : '';
	$nick = htmlspecialchars($nick);
	$template = 'base';

	$game_type = "";
	$game_title = "";
	$gameid = 0;
	if (isset($_SESSION['game']))
	{
		$game_type = $_SESSION['game']['type_game'];
		$game_title = $_SESSION['game']['title'];
		$gameid = $_SESSION['game']['id'];
	}
	
	$arrmenu = array();

	/*<a href="?"><img src="templates/base/images/minilogo.png"/></a><br><br>
				<center>'.$game_info.'<br>
				<div class="button3 ad" onclick="">change game</div>
				</center>
	*/
	
	$arrmenu[] = array(
		'name' => 'logo',
		'html' => '
			<div class="fhq_btn_menu fhq_btn_menu_color_none hint--bottom" data-hint="Free-Hack-Quest" onclick="window.location.href = \'?\';">
				<img class="fhq_btn_menu_img" src="templates/base/images/logo/fhq_2015_small.png"/>
			</div>
		',
		'show' => true,
	);
	
	/*$arrmenu[] = array(
		'name' => 'splitter',
		'html' => '
			<div class="fhq_btn_menu_splitter">
			</div>
		',
		'show' => true,
	);*/
	
	$arrmenu[] = array(
		'name' => 'game_info',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Please click to change a curent game."  onclick="changeGame();">
				<div class="fhq_btn_menu_img">
					'.($game_type == '' ? 'Unknown<br>Game' : '<b>'.$game_title.'<br>'.$game_type.'</b>').'
				</div>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'scoreboard',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Scoreboard" onclick="loadScoreboard('.$gameid.');">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_scoreboard.png"/>  <div style="display: inline-block;" id="view_score">'.$score.'</div>
			</div>
		',
		'show' => ($game_type == 'jeopardy' || $game_type == 'attack-defence'),
	);

	$arrmenu[] = array(
		'name' => 'rules',
		'html' => '
			<div class="fhq_btn_menu" data-hint="Rules" onclick="loadGameRules('.$gameid.');">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/rules.png"/><br>
			</div>
		',
		'show' => ($game_type == 'jeopardy' || $game_type == 'attack-defence'),
	);
	
	/*$arrmenu[] = array(
		'name' => 'advisers',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Advisers" onclick="load_content_page2(\'advisers\');">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_advisers.png"/>
			</div>
		',
		'show' => ($game_type == 'attack-defence'),
	);
	
	$arrmenu[] = array(
		'name' => 'send_flag',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Send Flag" onclick="window.open(\'send_flag.php\', \'_blank\');">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_send_flag.png"/>
			</div>
		',
		'show' => ($game_type == 'attack-defence'),
	);*/


	$arrmenu[] = array(
		'name' => 'quests',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Quests" onclick="loadQuests();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/quests.png"/>
			</div>
		',
		'show' => ($game_type == 'jeopardy'),
	);

	$arrmenu[] = array(
		'name' => 'stats',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Statistics" onclick="createPageStatistics('.$gameid.'); updateStatistics();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/stats.png"/><br>
			</div>
		',
		'show' => ($game_type == 'jeopardy'),
	);	

	$arrmenu[] = array(
		'name' => 'splitter',
		'html' => '
			<div class="fhq_btn_menu_splitter">
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'system_menu',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Settings" onclick="loadSettings();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_system.png"/>
			</div>
		',
		'show' => $role == 'admin',
	);
	
	$arrmenu[] = array(
		'name' => 'users',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Users" onclick="createPageUsers(); updateUsers();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_users.png"/>
			</div>
		',
		'show' => $role == 'admin',
	);
	
	$arrmenu[] = array(
		'name' => 'answerlist',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Answer List" onclick="createPageAnswerList(); updateAnswerList();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/answerlist.png"/>
			</div>
		',
		'show' => $role == 'admin',
	);

	$arrmenu[] = array(
		'name' => 'install_updates',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Install Updates" onclick="installUpdates();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/updates.png"/>
			</div>
		',
		'show' => $role == 'admin',
	);
		
	$arrmenu[] = array(
		'name' => 'splitter',
		'html' => '
			<div class="fhq_btn_menu_splitter">
			</div>
		',
		'show' => $role == 'admin',
	);
	
	$arrmenu[] = array(
		'name' => 'games',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Games" onclick="loadGames();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/games.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'news',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="News" onclick="loadEvents();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/news.png"/>
			</div>
		',
		'show' => true,
	);

	$arrmenu[] = array(
		'name' => 'feedback',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Feedback" onclick="loadFeedback();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_feedback.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'user_info',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="User profile"  onclick="loadUserProfile('.$userid.');">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/user.png"/> <div style="display: inline-block;" id="btn_user_info">'.$nick.'</div>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'about',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="About" onclick="loadAbout();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/about.png"/><br>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'logout',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Logout" onclick="logout();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/logout.png"/><br>
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
?>
					</td>
				</tr>
				<tr>
					<td height="100%" valign="top">
						<center>
							<div id="content_page">
							</div>
						</center>
					</td>
				</tr>
				<tr>
					<td colspan="2">
			<?php 
				include('copyright.php');
			?>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>
