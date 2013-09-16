<?
include_once "fhq_class_security.php";

function echo_panel()
{
	$security = new fhq_security();

echo '
<script>


function dr_zoyberg()
{
  document.getElementById("content_page").innerHTML="<img width=100% src=\"http://fc03.deviantart.net/fs70/f/2012/119/b/7/zoidberg_trace_by_deepfry3-d4y0wlc.png\"/>";	
};

var myTimer;

function recalculate_score()
{
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	};
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			var text = xmlhttp.responseText;
			
			document.getElementById("view_score").innerHTML=text;
			
			var value = parseInt(text,10);
			if(isNaN(value))
			{
				if(!myTimer) myTimer = setInterval(recalculate_score,1000);
			}
			else
			{
				clearInterval(myTimer);
				myTimer = undefined;
			}
		}
	}
	
	xmlhttp.open("GET", "content_page.php?content_page=recalculate_score",true);
	xmlhttp.send();
};

</script>
';

	echo '<table width=100%>
			<tr>
				<td >
					Your name are <a href="" >'.$security->nick().'</a>,
					your score is <font id="view_score" size=5>'.$security->score().'</font>
					and you can try <a href="javascript:void(0);" onclick="recalculate_score();">recalculate score</a>, 
					also you can look your quests: 
					<a href="javascript:void(0);" onclick="load_content_page(\'quests_all\');">All</a>, 
					<a href="javascript:void(0);" onclick="load_content_page(\'quests_allow\');">Allow</a>,
					<a href="javascript:void(0);" onclick="load_content_page(\'quests_process\');">Process</a>,
					<a href="javascript:void(0);" onclick="load_content_page(\'quests_completed\');">Completed</a>. 
					You can to write message to <a href="javascript:void(0);" onclick="load_content_page(\'feedback_my\');">Feedback</a>.
					Also you can to look <a href="javascript:void(0);" onclick="load_content_page(\'top100\');">"Top 100"</a>.
					And of course you can 
					<a href="javascript:void(0);" onclick="exit();">logout</a>.
					And I almost forgot... You can to look at <a href="javascript:void(0);" onclick="load_content_page(\'dr_zoyberg\');">Dr. Zoyberg</a>. Do it.
			</tr>
		</table>';
		
	//add admins menu 
	if( $security->isAdmin())
	{
		echo '
		<table cellpadding="10px" >
		<tr bgcolor="#007700">
			<td>
				You is admin and you can:  
					<!-- quest.php?action=add -->
					<a href="javascript:void(0);" onclick="load_content_page(\'add_quest\');">Add new quest</a>, 
					<a href="javascript:void(0);" onclick="load_content_page(\'feedbacks\');">Messages</a>, 
				     <!-- a href=""></a> , <a href="admin.php?action="></a -->
			</td>
		</tr>
		</table>';
	};
};
//---------------------------------------------------------------------	

?>
