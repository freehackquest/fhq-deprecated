<?
$curdir = dirname(__FILE__);

include_once "$curdir/fhq_class_security.php";

function echo_panel()
{
	$security = new fhq_security();

echo '
<script>
</script>
';

	/* echo '<table width=100%>
			<tr>
				<td >
					<p>
					Your name is <a id="btn_user_info" class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'user_info\');" >'.$security->nick().'</a> ,
					your score is <font id="view_score" size=5>'.$security->score().'</font>
					and you can try <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="recalculate_score();">recalculate score</a> . 
					also you can look your at quests: 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_allow\');">Open</a> , 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_process\');">Current</a> , 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_completed\');">Completed</a> .
					You can write <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'feedback_my\');">Feedback</a> .
					Also you can look at <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'top100\');">"Top 100"</a> .
					And of course you can 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="exit();">logout</a> .
					<!-- And I almost forgot... You can look at <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'dr_zoyberg\');">Dr. Zoyberg</a> .  -->
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
					<p>
					<a id="btn_user_info" class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'user_info\');" >'.$security->nick().'</a> 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'games\');">Games</a>
					<!-- a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'teams\');">Teams</a -->
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'news\');">News</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'feedback_my\');">Feedback</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'hacker_girl\');">Hacker girl here</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="exit();">logout</a>
					</p>';

	if ($game_type == 'jeopardy') {
		echo '
				<p>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_allow\');">Tasks Open</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_process\');">Tasks Current</a> 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_completed\');">Tasks Completed</a> 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'top100\');">"Top 100"</a> <br>
					your score is <font id="view_score" size=5>'.$security->score().'</font> <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="recalculate_score();">recalculate score</a>
				</p>
				<!-- And I almost forgot... You can look at <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'dr_zoyberg\');">Dr. Zoyberg</a> .  -->
			';
	} else if ($game_type == 'attack-defence') {
			echo '<p> 
			hzzz
			</p>';
	} else {
		echo '<p>
			Please choose the game in menu \'games\'.
		</p>';
	}

	echo '
		</tr>
	</table>';
	
	
	//add admins menu 
	if( $security->isAdmin())
	{
		echo '
		<table cellpadding="10px">
		<tr class="alert alert-info">
			<td>
				Admin:
					<!-- quest.php?action=add -->
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'add_quest\');">Add new quest</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'feedbacks\');">Messages</a>
				    <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'users\');">users</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'answer_list\');">Answer List</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'answer_list\' , { backup : \'\' } );">Backup Answer List</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'add_news\');">Add News</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'statistics\');">Statistics</a>
			</td>
		</tr>
		</table>';
	};
	
	//add admins menu 
	if( $security->isTester())
	{
		echo '
		<table cellpadding="10px" >
		<tr class="alert alert-info">
			<td>
				You is tester and you can:  
					<!-- quest.php?action=add -->
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'answer_list\' );">Answer List</a> 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'answer_list\' , { backup : \'\' } );">Backup Answer List</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'add_news\');">Add News</a>
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'statistics\');">Statistics</a>
				     <!-- a href=""></a> , <a href="admin.php?action="></a -->
			</td>
		</tr>
		</table>';
	};
};
//---------------------------------------------------------------------	

?>
