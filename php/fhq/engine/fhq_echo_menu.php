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
	
	echo '<table width=100%>
			<tr>
				<td >
					<div class="fhq_btn_menu hint--bottom" data-hint="User profile"  onclick="load_content_page(\'user_info\');">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_default_logo_user.png"/> <div style="display: inline-block;" id="btn_user_info">'.$security->nick().'</div>
					</div>
					
					<div class="fhq_btn_menu hint--bottom" data-hint="Games" onclick="load_content_page(\'games\');">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_games.png"/><br>
					</div>
					
					<!-- div class="fhq_btn_menu hint--bottom" data-hint="Teams" onclick="load_content_page(\'teams\');">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_default.png"/><br>
					</div -->
					
					<div class="fhq_btn_menu hint--bottom" data-hint="News" onclick="load_content_page(\'news\');">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_news.png"/><br>
					</div>
					
					<div class="fhq_btn_menu hint--bottom" data-hint="Feedback" onclick="load_content_page(\'feedback_my\');">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_feedback.png"/><br>
					</div>
					
					<div class="fhq_btn_menu hint--bottom" data-hint="Hacker girl here" onclick="load_content_page(\'hacker_girl\');">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_hacker_girl.png"/><br>
					</div>
					
					<div class="fhq_btn_menu" data-hint="Rules" onclick="load_content_page(\'rules\');">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_rules.png"/><br>
					</div>
					
					<div class="fhq_btn_menu hint--bottom" data-hint="Logout" onclick="exit();">
						<img width="50px" src="templates/'.$template.'/images/menu_btn_logout.png"/><br>
					</div>
					';

	if ($game_type == 'jeopardy') {
		echo '
				<p>
					<a class="button3 button3-menu" href="javascript:void(0);" onclick="load_content_page(\'quests_allow\');">Tasks Open</a>
					<a class="button3 button3-menu" href="javascript:void(0);" onclick="load_content_page(\'quests_process\');">Tasks Current</a> 
					<a class="button3 button3-menu" href="javascript:void(0);" onclick="load_content_page(\'quests_completed\');">Tasks Completed</a> 
					<a class="button3 button3-menu" href="javascript:void(0);" onclick="load_content_page2(\'scoreboard\');">Scoreboard</a>
					your score is <font id="view_score" size=5>'.$security->score().'</font> <a class="button3 button3-menu" href="javascript:void(0);" onclick="recalculate_score();">recalculate score</a>
				</p>
				<!-- And I almost forgot... You can look at <a class="button3" href="javascript:void(0);" onclick="load_content_page(\'dr_zoyberg\');">Dr. Zoyberg</a> .  -->
			';
	} else if ($game_type == 'attack-defence') {
			echo '<p> 
				<a class="button3 button3-menu" href="javascript:void(0);" onclick="load_content_page2(\'advisers\');">Advisers</a>
				<a class="button3 button3-menu" href="send_flag.php" target="_blank">Send Flag</a>
				<a class="button3 button3-menu" href="javascript:void(0);" onclick="load_content_page2(\'scoreboard\');">Scoreboard</a>
			</p>';
	} else {
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
