<?

class fhq_page_registration2
{
	function title()
	{
		return 'Create new account<br><font size=2><a class="btn btn-small btn-info" href="index.php">&larr; go to main page</a></font>';
	}

	function echo_head()
	{
		echo '';
	}
	
	function echo_onBodyEnd() {
		echo '';
	}
	
	function echo_content()
	{
		echo '
			<form method="POST" action="">
				<table cellspacing=10px cellpadding=10px>
					<tr>
						<td align="right">E-mail:</td>
						<td><input name="email" id="user_email" value="" type="text"></td>
					</tr>
					<tr>
						<td align="right">Nick:</td>
						<td><input name="nick" id="user_nick" value="" type="text"></td>
					</tr>
					<tr>
						<td align="right">Password:</td>
						<td><input name="pass" id="user_pass" value="" type="password"></td>
					</tr>
					<tr>
						<td>Password (confirm):</td>
						<td><input name="pass_confirm" id="user_pass_confirm" value="" type="password"></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<img src="captcha.php" id="captcha-image"/><br>
							<a class="btn" href="javascript:void(0);" onclick="document.getElementById(\'captcha-image\').src = \'captcha.php?rid=\' + Math.random();">Refresh Capcha</a>
							<br>
						</td>
					</tr>
					<tr>
						<td align="right">Captcha:</td>
						<td><input name="captcha" id="user_captcha" value="" type="text"></td>
					</tr>
					<tr>
						<td colspan = "2">
							
							<center>
								<br>
<script>
function sendQuery(str)
{
  document.getElementById("answer").innerHTML="<img width=100px src=\'images/sending.gif\'>";

  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
     xmlhttp=new XMLHttpRequest();
  };  
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
	  document.getElementById("answer").innerHTML=xmlhttp.responseText;
	}
  }
  var email = document.getElementById(\'user_email\').value;
  var nick = document.getElementById(\'user_nick\').value;
  var pass = document.getElementById(\'user_pass\').value;
  var pass_confirm = document.getElementById(\'user_pass_confirm\').value;
  var captcha = document.getElementById(\'user_captcha\').value;
  
  xmlhttp.open("GET","registration2.php?email=" + encodeURIComponent(email) 
		+ "&nick=" + encodeURIComponent(nick)
		+ "&pass=" + encodeURIComponent(pass)
		+ "&pass_confirm=" + encodeURIComponent(pass_confirm)
		+ "&captcha=" + encodeURIComponent(captcha)
	,true)

  xmlhttp.send();
}
</script>
									<a class="btn btn-small btn-info" href="javascript:void(0);" onclick="sendQuery();">Send query</a>
									<br><br>
									
								</center>
							</td>
						</tr>
					</table>
					</form>
					<center>
						<br>
						<div id="answer"></div>
					</center>';
	}
};

?>
