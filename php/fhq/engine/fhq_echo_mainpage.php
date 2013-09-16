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
	
	echo "<html>";
	echo_head( $page );

	echo '<body onload="load_content_page(\'user_info\');"class="main">
	<center>
	<table width="100%" height="100%">
		<tr>
			<td>
				<img src="images/minilogo.jpg"/>
			</td>
			<td align="left" valign = "top" width="100%">
				<hr>
					';
	// echo_score();
	'<hr>';
	echo_panel();
	
	echo '
				<br>
			</td>
		</tr>
		<tr>
			<td height="100%" colspan="2" valign="top">
				<center id="content_page">
					<div id="content_page"/>
				</center>
				
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<center>
					<font size=1>© 2011-2013 sea-kg</font>
					<pre><div id="debug_info"/></pre>
				</center>
			</td>
		</tr>
	</table>
	</center>
	</body>
	</html> ';
}
?>
