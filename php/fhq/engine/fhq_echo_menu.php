<?
$curdir = dirname(__FILE__);

include_once "$curdir/fhq_class_security.php";

function echo_panel()
{
	$security = new fhq_security();
	$template = isset($_SESSION['user']['template']) ? $_SESSION['user']['template'] : 'base';

echo '
<script>
</script>
';
	/* echo '<table width=100%>
			<tr>
				<td >
					<p>
					Your name is <a id="btn_user_info" class="button3" href="javascript:void(0);" onclick="load_content_page(\'user_info\');" >'.$security->nick().'</a> ,
					your score is <font id="view_score" size=5>'.$security->score().'</font>
					and you can try <a class="button3" href="javascript:void(0);" onclick="recalculate_score();">recalculate score</a> . 
					also you can look your at quests: 
					<a class="button3" href="javascript:void(0);" onclick="load_content_page(\'quests_allow\');">Open</a> , 
					<a class="button3" href="javascript:void(0);" onclick="load_content_page(\'quests_process\');">Current</a> , 
					<a class="button3" href="javascript:void(0);" onclick="load_content_page(\'quests_completed\');">Completed</a> .
					You can write <a class="button3" href="javascript:void(0);" onclick="load_content_page(\'feedback_my\');">Feedback</a> .
					Also you can look at <a class="button3" href="javascript:void(0);" onclick="load_content_page(\'top100\');">"Top 100"</a> .
					And of course you can 
					<a class="button3" href="javascript:void(0);" onclick="exit();">logout</a> .
					<!-- And I almost forgot... You can look at <a class="button3" href="javascript:void(0);" onclick="load_content_page(\'dr_zoyberg\');">Dr. Zoyberg</a> .  -->
					Just do it!
					</p>
			</tr>
		</table>';
	*/
	
	$game_type = "";
	if (isset($_SESSION['game']))
		$game_type = $_SESSION['game']['type_game'];
	
	$arrmenu = array();
	
	$arrmenu[] = array(
		'name' => 'user_info',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="User profile"  onclick="load_content_page(\'user_info\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_default_logo_user.png"/> <div style="display: inline-block;" id="btn_user_info">'.$security->nick().'</div>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'scoreboard',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Scoreboard" onclick="load_content_page2(\'scoreboard\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_scoreboard.png"/>  <div style="display: inline-block;" id="view_score">'.$security->score().'</div>
			</div>
		',
		'show' => ($game_type == 'jeopardy' || $game_type == 'attack-defence'),
	);
	
	$arrmenu[] = array(
		'name' => 'recalculate_score',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Recalculate Score" onclick="recalculate_score();">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_recalculate.png"/>
			</div>
		',
		'show' => ($game_type == 'jeopardy' || $game_type == 'attack-defence'),
	);
	
	$arrmenu[] = array(
		'name' => 'advisers',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Advisers" onclick="load_content_page2(\'advisers\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_advisers.png"/>
			</div>
		',
		'show' => ($game_type == 'attack-defence'),
	);
	
	$arrmenu[] = array(
		'name' => 'send_flag',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Send Flag" onclick="window.open(\'send_flag.php\', \'_blank\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_send_flag.png"/>
			</div>
		',
		'show' => ($game_type == 'attack-defence'),
	);

	$arrmenu[] = array(
		'name' => 'quests_allow',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Tasks Open" onclick="load_content_page(\'quests_allow\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_tasks.png"/>
			</div>
		',
		'show' => ($game_type == 'jeopardy'),
	);
					
	$arrmenu[] = array(
		'name' => 'quests_process',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Tasks Current" onclick="load_content_page(\'quests_process\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_tasks.png"/>
			</div>
		',
		'show' => ($game_type == 'jeopardy'),
	);
	
	$arrmenu[] = array(
		'name' => 'quests_completed',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Tasks Completed" onclick="load_content_page(\'quests_completed\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_tasks.png"/>
			</div>
		',
		'show' => ($game_type == 'jeopardy'),
	);
	
	
	$arrmenu[] = array(
		'name' => 'games',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Games" onclick="load_content_page(\'games\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_games.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'teams',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Teams" onclick="load_content_page(\'teams\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_teams.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'news',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="News" onclick="load_content_page(\'news\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_news.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'feedback',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Feedback" onclick="load_content_page(\'feedback_my\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_feedback.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'hacker_girl',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Hacker girl here" onclick="load_content_page(\'hacker_girl\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_hacker_girl.png"/>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'rules',
		'html' => '
			<div class="fhq_btn_menu" data-hint="Rules" onclick="load_content_page(\'rules\');">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_rules.png"/><br>
			</div>
		',
		'show' => true,
	);
	
	$arrmenu[] = array(
		'name' => 'logout',
		'html' => '
			<div class="fhq_btn_menu hint--bottom" data-hint="Logout" onclick="exit();">
				<img width="50px" src="templates/'.$template.'/images/menu_btn_logout.png"/><br>
			</div>
		',
		'show' => true,
	);

	echo '<table width=100%>
			<tr>
				<td>';

	foreach ($arrmenu as $menu) {
		if ($menu['show']) {
			echo $menu['html'];
		}
	}

	echo '
		</tr>
	</table>';
	
	//add admins menu 
	if( $security->isAdmin())
	{
		echo '
		<table cellpadding="10px">
		<tr>
			<td>
				Admin:
					<!-- quest.php?action=add -->
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'feedbacks\');">Messages</a>
				    <a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'users\');">Users</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'answer_list\');">Answer List</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'answer_list\' , { backup : \'\' } );">Backup Answer List</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'statistics\');">Statistics</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'add_quest\');">Add new quest</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'add_news\');">Add News</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'add_user\');">Add user</a><br><br>
					System:	<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'update_db\');">Update DB</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page2(\'init_scoreboard\');">init scoreboard</a>
					
			</td>
		</tr>
		</table>';
	};
	
	//add admins menu 
	if( $security->isTester())
	{
		echo '
		<table cellpadding="10px" >
		<tr>
			<td>
				You is tester and you can:  
					<!-- quest.php?action=add -->
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'answer_list\' );">Answer List</a> 
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'answer_list\' , { backup : \'\' } );">Backup Answer List</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'add_news\');">Add News</a>
					<a class="button3 ad" href="javascript:void(0);" onclick="load_content_page(\'statistics\');">Statistics</a>
				     <!-- a href=""></a> , <a href="admin.php?action="></a -->
			</td>
		</tr>
		</table>';
	};
};
//---------------------------------------------------------------------	

?>
