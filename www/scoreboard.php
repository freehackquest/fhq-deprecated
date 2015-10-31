<?php
		$versioncontent = '201503281358';
?>

<head>
	<title> Free-Hack-Quests Scoreboard </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
	<meta name="author" content="sea-kg" />
    <meta name="copyright" lang="ru" content="sea-kg" />
    <meta name="description" content="competition information security" />
    <meta name="keywords" content="security, fhq, fhq 2012, fhq 2013, fhq 2014, free, hack, quest, competition, information security, ctf, joepardy" />
	
	<link rel="stylesheet" type="text/css" href="templates/base/styles/body.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/menu.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/site.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/button3.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/user_info.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/games.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/quest_info.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/overlay.css?ver=<?php echo $versioncontent; ?>" />
	<link rel="stylesheet" type="text/css" href="templates/base/styles/hint.css?ver=<?php echo $versioncontent; ?>"/>
	<link rel="stylesheet" type="text/css" href="templates/base/styles/timer.css?ver=<?php echo $versioncontent; ?>"/>
	<link rel="stylesheet" type="text/css" href="templates/base/styles/quests.css?ver=<?php echo $versioncontent; ?>"/>
	<link rel="stylesheet" type="text/css" href="templates/base/styles/users.css?ver=<?php echo $versioncontent; ?>"/>
	<link rel="stylesheet" type="text/css" href="templates/base/styles/events.css?ver=<?php echo $versioncontent; ?>"/>
	<link rel="stylesheet" type="text/css" href="templates/base/styles/fhq.css?ver=<?php echo $versioncontent; ?>"/>

<?php
	$get_gameid = '';
	$gameid = 1;
	if (isset($_GET['gameid'])) {
		if (is_numeric($_GET['gameid'])) {
			$gameid = intval($_GET['gameid']);
			// todo show select game somewhere
		}
		$get_gameid = '&gameid='.$gameid;
	}

	$anticolors = isset($_GET['dark']) ? 'base' : 'dark';
	$colors = isset($_GET['dark']) ? 'dark' : 'base';
	$hint = isset($_GET['dark']) ? 'You are on the dark side and you can not turning back.' : 'Join the dark side!';
	echo '<link rel="stylesheet" type="text/css" href="templates/'.$colors.'/styles/colors.css?ver='.$versioncontent.'" />';
?>

	<script type="text/javascript" src="js/fhq.frontend.lib.js?ver=1"></script>
	<script type="text/javascript" src="js/fhq.gui.lib.js?ver=1"></script>
	<script type="text/javascript" src="js/fhq_send_request.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_echo_head.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_modal_dialog.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_games.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_quests.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_users.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_menu.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_timer.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_updates.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/fhq_events.js?ver=<?php echo $versioncontent; ?>"></script>
	<script type="text/javascript" src="js/scoreboard.js?ver=<?php echo $versioncontent; ?>"></script>
	
	<script type="text/javascript">
		var fhq = new FHQFrontEndLib();
		var fhqgui = new FHQGuiLib();

		fhq.client = "web-fhq2014";
		fhq.baseUrl = fhq.getCurrentApiPath(); // or another path
		// fhq.token = fhq.getTokenFromCookie();
		//javascript:
/*function playSound( url ){   
  document.getElementById("sound").innerHTML="<embed src='"+url+"' hidden=true autostart=true loop=false>";
}*/
	</script>
	
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
			(function (d, w, c) {
				(w[c] = w[c] || []).push(function() {
					try {
						w.yaCounter32831012 = new Ya.Metrika({
							id:32831012,
							clickmap:true,
							trackLinks:true,
							accurateTrackBounce:true
						});
					} catch(e) { }
				});

				var n = d.getElementsByTagName("script")[0],
					s = d.createElement("script"),
					f = function () { n.parentNode.insertBefore(s, n); };
				s.type = "text/javascript";
				s.async = true;
				s.src = "https://mc.yandex.ru/metrika/watch.js";

				if (w.opera == "[object Opera]") {
					d.addEventListener("DOMContentLoaded", f, false);
				} else { f(); }
			})(document, window, "yandex_metrika_callbacks");
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/32831012" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
</head>
	<body class="fhqbody" onload="updateInfo(<?php echo $gameid;?>);">
			<table width="100%" height="100%">
				<tr>
					<td align="center" valign="top" class="fhq_index_column">
							<div class="fhq_index_logo hint--bottom" data-hint="<?php echo $hint; ?>" onclick="window.location.href='?<?php echo $anticolors.$get_gameid; ?>';">
								<img width=100px src="templates/base/images/logo/fhq_2015.png" />
							</div>
							<h1>News</h1>
							<div id="events_panel">Loading...</div>
					</td>
					<td width="50%" valign="top" >
						<center>
							<img width=85px src="images/menu/scoreboard.png" />
							<div id="game_info_panel">Loading...</div>
							<h1>Scoreboard</h1>
							<div id="scoreboard_table" class="fhq_scoreboard_table"></div>
							
							<?php
								// http://localhost/fhq/templates/base/images/menu_btn_news.png
							?>			
					</td>
				</tr>
				<tr>
					<td class="fhq_index_column"><?php include('copyright.php'); ?></td>
					<td><?php include('copyright.php'); ?></td>
				</tr>
			</table>
		</center>
	</body>
</html>
