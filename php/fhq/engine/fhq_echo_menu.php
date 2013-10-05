<?
include_once "fhq_class_security.php";

function echo_panel()
{
	$security = new fhq_security();

echo '
<script>
</script>
';

	echo '<table width=100%>
			<tr>
				<td >
					<p>
					Your name is <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'user_info\');" >'.$security->nick().'</a> ,
					your score is <font id="view_score" size=5>'.$security->score().'</font>
					and you can try <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="recalculate_score();">recalculate score</a> , 
					also you can look your quests:					
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_allow\');">Allow</a> , 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_process\');">Process</a> , 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'quests_completed\');">Completed</a> .
					You can to write message to <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'feedback_my\');">Feedback</a> .
					Also you can to look <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'top100\');">"Top 100"</a> .
					And of course you can 
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="exit();">logout</a> .
					And I almost forgot... You can to look at <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'dr_zoyberg\');">Dr. Zoyberg</a> . 
					Just do it!
					</p>
			</tr>
		</table>';
		
	//add admins menu 
	if( $security->isAdmin())
	{
		echo '
		<table cellpadding="10px" >
		<tr class="alert alert-info">
			<td>
				You is admin and you can:  
					<!-- quest.php?action=add -->
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'add_quest\');">Add new quest</a>  
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'feedbacks\');">Messages</a> 
				     <!-- a href=""></a> , <a href="admin.php?action="></a -->
				     <a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'users\');">users</a>
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
					<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="load_content_page(\'answer_list\');">Answer List</a> 
				     <!-- a href=""></a> , <a href="admin.php?action="></a -->
			</td>
		</tr>
		</table>';
	};
};
//---------------------------------------------------------------------	

?>
