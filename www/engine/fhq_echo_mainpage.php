<?php
$curdir_echo_main_page = dirname(__FILE__);
include_once $curdir_echo_main_page.'/fhq_echo_menu.php';

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
	$onload = 'loadQuests();';
	
	/*if(isset($_GET['content_page']))
	{
		$json = json_encode($_GET, JSON_HEX_TAG);		
		$json = str_replace("'", "\'", $json);
		$json = str_replace("\"", "'", $json);

		$onload = 'load_content_page(\''.$_GET['content_page'].'\', '.$json.');';
	};*/
	
	echo '<html>';
	
	$template = isset($_SESSION['user']['profile']['template']) ? $_SESSION['user']['profile']['template'] : 'base';
	$template = htmlspecialchars($template);
	$versioncontent = '201504012309';

echo '
<head>
	<title> Free-Hack-Quests </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">

	<link rel="stylesheet" type="text/css" href="templates/base/styles/body.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/menu.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/site.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/button3.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/user_info.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/games.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/quest_info.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/overlay.css?ver='.$versioncontent.'" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/hint.css?ver='.$versioncontent.'">
	<link rel="stylesheet" type="text/css" href="templates/base/styles/timer.css?ver='.$versioncontent.'">
	<link rel="stylesheet" type="text/css" href="templates/base/styles/quests.css?ver='.$versioncontent.'">
	<link rel="stylesheet" type="text/css" href="templates/base/styles/users.css?ver='.$versioncontent.'">
	<link rel="stylesheet" type="text/css" href="templates/base/styles/events.css?ver='.$versioncontent.'">

	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/colors.css?ver='.$versioncontent.'" />

	<script type="text/javascript" src="js/fhq.frontend.lib.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_send_request.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_echo_head.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_modal_dialog.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_games.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_quests.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_users.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_menu.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_timer.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_updates.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_events.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_stats.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_feedback.js?ver='.$versioncontent.'"></script>';
	
?>
		<script type="text/javascript">
			var fhq = new FHQFrontEndLib();
			fhq.client = "web-fhq2014";
			fhq.baseUrl = fhq.getCurrentApiPath(); // or another path
			// fhq.token = fhq.getTokenFromCookie();		

			function logout() {
				fhq.security.logout();			
				window.location.href = "index.php";
			}

		</script>
<?php

echo '</head>
	';
	
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
						text
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
