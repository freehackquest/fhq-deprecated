<?
include_once "fhq_security.php";

function echo_panel()
{
	$security = new fhq_security();
	
	//add admins menu 
	/*if( $_SESSION['role'] == 'admin' )
	{
		echo "
		<tr bgcolor='#760505'>
			<td>Admin's Panel:</td>
			<td width=30px> </td>
			<td><a href='quest.php?action=add'> Add New Quest</a></td>
			<td width=30px> </td>
			<!-- td><a href='main.php?action=completed'> Users </a></td -->
			<!-- td width=30px> </td -->
			<td><a href='admin.php?action=feedback'>Messages</a></td>
			<td width=30px> </td>
			<td><a href='main.php?action=feedback'></a></td>
			<td></td>
			<td></td>
		</tr>";
	};*/

echo '
<script>
function exit()
{
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
  };  
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		if(xmlhttp.responseText == "OK")
			window.location.href = "index.php";
	}
  }
  xmlhttp.open("GET","index.php?exit",true);
  xmlhttp.send();
}

function dr_zoyberg()
{
  document.getElementById("content_page").innerHTML="<img width=100% src=\"http://fc03.deviantart.net/fs70/f/2012/119/b/7/zoidberg_trace_by_deepfry3-d4y0wlc.png\"/>";
	/*
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
  };  
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		if(xmlhttp.responseText == "OK")
			window.location.href = "index.php";
	}
	content_page
	
  }
  xmlhttp.open("GET","index.php?exit",true);
  xmlhttp.send();
  */
};

</script>
';


	echo '<table width=100%>
			<tr>
				<td >
					Your name are <a href="" >'.$security->nick().'</a>,
					your score is <font size=5>'.$security->score().'</font> 
					and you can try <a href="" >recalculate score</a>, 
					also you can look your quests: 
					<a href="" >All</a>, 
					<a href="main.php?action=allow" >Allow</a>,
					<a href="main.php?action=process" >Process</a>,
					<a href="main.php?action=completed" >Completed</a>. 
					You can to write message to <a href="main.php?action=feedback_my" >Feedback</a>.
					Also you can to look <a href="main.php?action=top100" >"Top 100"</a>.
					And of course you can 
					<a href="javascript:void(0);" onclick="exit();">logout</a>.
					And I almost forgot... You can to look at <a href="javascript:void(0);" onclick="dr_zoyberg();">Dr. Zoyberg</a>. Do it.
			</tr>
		</table>';
};
//---------------------------------------------------------------------	

?>
