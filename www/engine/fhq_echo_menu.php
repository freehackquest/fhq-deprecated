<?php
$curdir = dirname(__FILE__);

include_once "$curdir/fhq_class_security.php";

function echo_panel()
{
	$security = new fhq_security();
	$template = isset($_SESSION['user']['profile']['template']) ? $_SESSION['user']['profile']['template'] : 'base';
	// TODO : image must be loaded from another folder
	$template = 'base';
echo '
<script>
</script>
';
	
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
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_scoreboard.png"/>  <div style="display: inline-block;" id="view_score">'.$security->score().'</div>
			</div>
		',
		'show' => ($game_type == 'jeopardy' || $game_type == 'attack-defence'),
	);

	$arrmenu[] = array(
		'name' => 'rules',
		'html' => '
			<div class="fhq_btn_menu" data-hint="Rules" onclick="loadGameRules('.$gameid.');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_rules.png"/><br>
			</div>
		',
		'show' => ($game_type == 'jeopardy' || $game_type == 'attack-defence'),
	);
	
	$arrmenu[] = array(
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
	);


	$arrmenu[] = array(
		'name' => 'quests',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Quests" onclick="loadQuests();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_quests.png"/>
			</div>
		',
		'show' => ($game_type == 'jeopardy'),
	);

	$arrmenu[] = array(
		'name' => 'stats',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Statistics" onclick="loadStatistics('.$gameid.');">
				<img width="50px" src="templates/'.$template.'/images/menu/stats.png"/><br>
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
			<div class="fhq_btn_menu hint--bottom" data-hint="System Menu" onclick="showSystemMenu();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_system.png"/>
			</div>
		',
		'show' => $security->isAdmin(),
	);
	
	$arrmenu[] = array(
		'name' => 'users',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Users" onclick="createPageUsers(); updateUsers();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_users.png"/>
			</div>
		',
		'show' => $security->isAdmin(),
	);
	
	$arrmenu[] = array(
		'name' => 'answerlist',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Answer List" onclick="loadAnswerList();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu/answerlist.png"/>
			</div>
		',
		'show' => $security->isAdmin(),
	);
	
	$arrmenu[] = array(
		'name' => 'splitter',
		'html' => '
			<div class="fhq_btn_menu_splitter">
			</div>
		',
		'show' => $security->isAdmin(),
	);
	
	$arrmenu[] = array(
		'name' => 'games',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Games" onclick="loadGames();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_games.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'news',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="News" onclick="loadEvents();">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_news.png"/>
			</div>
		',
		'show' => true,
	);

	$arrmenu[] = array(
		'name' => 'feedback',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Feedback" onclick="load_content_page(\'feedback_my\');">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_feedback.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'user_info',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="User profile"  onclick="loadUserProfile('.$security->userid().');">
				<img class="fhq_btn_menu_img" src="templates/'.$template.'/images/menu_btn_default_logo_user.png"/> <div style="display: inline-block;" id="btn_user_info">'.$security->nick().'</div>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'about',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="About" onclick="load_content_page(\'about\');">
				<img width="50px" src="templates/'.$template.'/images/menu/about.png"/><br>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'logout',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Logout" onclick="logout();">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_logout.png"/><br>
			</div>
		',
		'show' => true,
	);
	
	// system menu
	
	$arrsystemmenu = array();
	
	// todo
	$arrsystemmenu[] = array(
		'name' => 'feedbacks',
		'html' => '
			<div class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'feedbacks\');">Messages</div>
		',
		'show' => $security->isAdmin(),
	);
	
	// todo
	$arrsystemmenu[] = array(
		'name' => 'install_updates',
		'html' => '
			<div class="button3 ad" href="javascript:void(0);" onclick="installUpdates();">Install Updates</div>
		',
		'show' => $security->isAdmin(),
	);

	// todo
	/*$arrsystemmenu[] = array(
		'name' => 'init_scoreboard',
		'html' => '
			<div class="button3 ad" onclick="load_content_page2(\'init_scoreboard\');">init scoreboard</div>
		',
		'show' => $security->isAdmin(),
	);*/
	
	// echo menu

	foreach ($arrmenu as $menu) {
		if ($menu['show']) {
			echo $menu['html'];
		}
	}
	
	echo '<div id="system_menu" style="display: none;">';
	foreach ($arrsystemmenu as $menu) {
		if ($menu['show']) {
			echo $menu['html'];
		}
	}
	echo '</div>';
};
//---------------------------------------------------------------------	
