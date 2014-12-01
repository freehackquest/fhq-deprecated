<?

function echo_head($page)
{
	$template = isset($_SESSION['user']['template']) ? $_SESSION['user']['template'] : 'base';
	
	echo '
<head>
	<title> Free-Hack-Quests </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">

	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/body.css" />
	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/site.css" />
	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/button3.css" />
	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/user_info.css" />
	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/quest_info.css" />
	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/overlay.css" />
	<link rel="stylesheet" type="text/css" href="templates/'.$template.'/styles/hint.css" />

	<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script -->
	<!-- script src="http://malsup.github.com/jquery.form.js"></script -->	
	<script type="text/javascript" src="js/fhq_send_request.js"></script>
	<script type="text/javascript" src="js/fhq_echo_head.js"></script>
	<script type="text/javascript" src="js/fhq_modal_dialog.js"></script>
	<script type="text/javascript" src="js/fhq_games.js"></script>
	<script type="text/javascript" src="js/fhq_users.js"></script>
	<script type="text/javascript" src="js/fhq_menu.js"></script>
	
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
