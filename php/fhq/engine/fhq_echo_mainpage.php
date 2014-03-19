<?
include_once "fhq_echo_head.php";
include_once "fhq_echo_menu.php";

class simple_page
{
	var $title;
	var $content;
	function simple_page($title, $content)
	{
		$this->title = $title;
		$this->content = $content;
	}

	function title()
	{
		return $this->title;
	}
	
	function echo_content()
	{
		echo $this->content;
	}
};
		

function echo_mainpage($page)
{	
	// if() view_quest
	$onload = 'load_content_page(\'quests_allow\');';
	
	if(isset($_GET['content_page']))
	{
		$json = json_encode($_GET, JSON_HEX_TAG);		
		$json = str_replace("'", "\'", $json);
		$json = str_replace("\"", "'", $json);

		$onload = 'load_content_page(\''.$_GET['content_page'].'\', '.$json.');';
	};
	
	echo '<html>';
	echo_head( $page );
  $games = new fhq_games();
    
    // reload_news();
    
	echo '<body onload="'.$onload.'" class="main">
	<center>
	<table cellspacing=10px cellpadding=10px width="100%" height="100%">
		<tr bgcolor="#FFFFF0">
			<td width=2% valign="top">
				<a href="?"><img src="images/minilogo.png"/></a>
			</td>
			<td align="left" valign = "top" width="82%">
					';
	// echo_score();
	echo_panel();
	
	echo '
				<br>
			</td>
		</tr>
		<tr>
			<td colspan="2" height="100%" valign="top">
				<center>
					<div id="content_page">
					</div>
				</center>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<center>
					<font size=1>© 2011-2014 sea-kg</font>
					<!-- pre><div id="debug_info"/></pre -->
				</center>
			</td>
		</tr>
	</table>
	</center>
	</body>
	</html> ';
}
?>
