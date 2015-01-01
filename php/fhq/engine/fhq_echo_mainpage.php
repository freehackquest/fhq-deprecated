<?php
$curdir = dirname(__FILE__);

include_once "$curdir/fhq_echo_head.php";
include_once "$curdir/fhq_echo_menu.php";

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
	// $onload = 'load_content_page(\'quests_allow\');';
	$onload = 'loadTasks();';
	
	if(isset($_GET['content_page']))
	{
		$json = json_encode($_GET, JSON_HEX_TAG);		
		$json = str_replace("'", "\'", $json);
		$json = str_replace("\"", "'", $json);

		$onload = 'load_content_page(\''.$_GET['content_page'].'\', '.$json.');';
	};
	
	echo '<html>';
	echo_head( $page );
    // reload_news();
    $game_info = "";
	if (isset($_SESSION['game'])) {
		$game_info .= "<b><font size=5>".$_SESSION['game']['title']."</font></b><br>";
		$game_info .= $_SESSION['game']['type_game'];
	}
	// $game_info .= "<img width=150 src='".$_SESSION['game']['logo']."'/>";
		
	echo '<body onload="'.$onload.'" class="main">

	<div id="modal_dialog" class="overlay">
		<div class="overlay_table">
			<div class="overlay_cell">
				<div class="overlay_content">
					<div id="modal_dialog_content">
						Текст посередине DIV
					</div>
					<div class="overlay_close">
						<a class="button3 ad" href="javascript:void(0);" onclick="closeModalDialog();">
							Close
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<center>
	<table cellspacing=10px cellpadding=10px width="100%" height="100%">
		<tr class="fhq_menucolor">
			<td align="left" valign = "top" width="82%">
					';
	// echo_score();
	echo_panel();
	
	echo '
			</td>
		</tr>
		<tr>
			<td height="100%" valign="top">
				<center>
					<div id="content_page">
					</div>
				</center>
			</td>
		</tr>
		<tr>
			<td colspan="2">';
			
	include('copyright.php');
		
	echo '		
			</td>
		</tr>
	</table>
	</center>
	</body>
	</html> ';
}
?>
