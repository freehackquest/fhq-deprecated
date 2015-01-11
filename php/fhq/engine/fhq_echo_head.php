<?php

function echo_head($page)
{
	$template = isset($_SESSION['user']['profile']['template']) ? $_SESSION['user']['profile']['template'] : 'base';
	$template = htmlspecialchars($template);
	$versioncontent = '201501102124';
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

	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/colors.css?ver='.$versioncontent.'" />

	<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js?ver='.$versioncontent.'"></script -->
	<!-- script src="http://malsup.github.com/jquery.form.js?ver='.$versioncontent.'"></script -->	
	<!-- script type="text/javascript" src="js/encoder.js?ver='.$versioncontent.'"></script -->
	<script type="text/javascript" src="js/fhq_send_request.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_echo_head.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_modal_dialog.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_games.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_quests.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_users.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_menu.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_timer.js?ver='.$versioncontent.'"></script>
	<script type="text/javascript" src="js/fhq_updates.js?ver='.$versioncontent.'"></script>

	<style>
		textarea.full_text
		{	
			margin: 0pt; 
			width: 80%;
			height: 200px;
		}

	</style>
</head>
	';
};
?>
