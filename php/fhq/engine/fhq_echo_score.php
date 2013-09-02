<?
include_once "fhq_security.php";

function echo_score()
{
	$security = new fhq_security();
	echo '<table width=100%>
			<tr>
				<td  >
					<form method="POST" action="?action=recalc_score"> 
						Your Score: <font size="3" color="#999999"> '.$security->score().' </font>
						<input name = "refresh_reit" value="recalculate score" type="submit">
					</form>	
				</td>

				<td align="right">
					
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
</script>

					You: <font size="3" >'.$security->nick().'
					<a href="javascript:void(0);" onclick="exit();">Exit</a>
				</td>

			</tr>
		</table>';
};
//---------------------------------------------------------------------		

?>
