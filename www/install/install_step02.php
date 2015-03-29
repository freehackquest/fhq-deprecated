<?php
	if (file_exists("config/config.php")) {
		echo "If you want reinstall please rename config/config.php";
		exit;
	}

	$current_step = 2;
	include_once("install_base.php");
	
	$user = $config['db']['username'];
	$pass = $config['db']['userpass'];
	$dbname = $config['db']['dbname'];
	$dbhost = $config['db']['host'];

	$conn = new pdo('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', $user, $pass);

	if (isset($_GET['create_tables']))
	{
		$table_advisers = "CREATE TABLE IF NOT EXISTS `advisers` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) DEFAULT NULL,
			  `date_change` datetime DEFAULT NULL,
			  `owner` int(11) DEFAULT NULL,
			  `text` text,
			  `mark` int(11) DEFAULT '0',
			  `checked` int(11) DEFAULT '0',
			  `idgame` int(11) DEFAULT '0',
			  PRIMARY KEY (`id`),
			  KEY `title` (`title`),
			  KEY `owner` (`owner`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_advisers);
				
		$table_feedback = "CREATE TABLE IF NOT EXISTS `feedback` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `typeFB` text,
			  `full_text` text,
			  `author` int(11) DEFAULT NULL,
			  `dt` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_feedback);
		
		$table_feedback_msg = "CREATE TABLE IF NOT EXISTS `feedback_msg` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `feedback_id` int(11) DEFAULT NULL,
			  `msg` text,
			  `author` int(11) DEFAULT NULL,
			  `dt` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_feedback_msg);
		
		$table_flags = "CREATE TABLE IF NOT EXISTS `flags` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `idservice` int(10) unsigned DEFAULT NULL,
			  `flag` varchar(50) DEFAULT NULL,
			  `owner` varchar(300) DEFAULT NULL,
			  `date_start` datetime DEFAULT NULL,
			  `date_end` datetime DEFAULT NULL,
			  `user_passed` int(10) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_flags);
		
		$table_flags_live = "CREATE TABLE IF NOT EXISTS `flags_live` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `idservice` int(10) unsigned DEFAULT NULL,
			  `flag` varchar(50) DEFAULT NULL,
			  `owner` int(10) unsigned DEFAULT NULL,
			  `date_start` datetime DEFAULT NULL,
			  `date_end` datetime DEFAULT NULL,
			  `user_passed` int(10) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$conn->query($table_flags_live);
		
		$table_games = "CREATE TABLE IF NOT EXISTS `games` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `uuid_game` varchar(255) DEFAULT NULL,
			  `title` varchar(255) DEFAULT NULL,
			  `logo` varchar(255) DEFAULT NULL,
			  `type_game` varchar(255) DEFAULT NULL,
			  `date_create` datetime DEFAULT NULL,
			  `date_start` datetime DEFAULT NULL,
			  `date_stop` datetime DEFAULT NULL,
			  `date_change` datetime DEFAULT NULL,
			  `json_data` text,
			  `json_security_data` text,
			  `owner` int(11) DEFAULT NULL,
			  `rating` int(10) unsigned DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `uuid_game` (`uuid_game`),
			  KEY `date_create` (`date_create`),
			  KEY `date_start` (`date_start`),
			  KEY `date_stop` (`date_stop`),
			  KEY `date_change` (`date_change`),
			  KEY `owner` (`owner`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_games);
		
		
		$table_quest = "CREATE TABLE IF NOT EXISTS `quest` (
			  `idquest` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `subject` varchar(128) DEFAULT NULL,
			  `name` varchar(300) DEFAULT NULL,
			  `text` varchar(4048) DEFAULT NULL,
			  `answer` text,
			  `score` int(10) unsigned DEFAULT NULL,
			  `gameid` int(10) unsigned DEFAULT NULL,
			  `min_score` int(10) DEFAULT '0',
			  `for_person` bigint(20) DEFAULT '0',
			  `idauthor` bigint(20) DEFAULT '0',
			  `author` varchar(50) DEFAULT NULL,
			  PRIMARY KEY (`idquest`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_quest);

		$table_scoreboard = "CREATE TABLE IF NOT EXISTS `scoreboard` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `idgame` int(10) unsigned DEFAULT NULL,
			  `name` varchar(255) DEFAULT NULL,
			  `owner` varchar(300) DEFAULT NULL,
			  `score` int(10) unsigned DEFAULT NULL,
			  `date_change` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_scoreboard);
			
		$table_services = "CREATE TABLE IF NOT EXISTS `services` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `idgame` int(10) unsigned DEFAULT NULL,
			  `name` varchar(300) DEFAULT NULL,
			  `scriptpath` varchar(255) DEFAULT NULL,
			  `comment` varchar(4048) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_services);
		
		$table_tryanswer = "CREATE TABLE IF NOT EXISTS `tryanswer` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `iduser` int(11) DEFAULT NULL,
			  `idquest` int(11) DEFAULT NULL,
			  `answer_try` text,
			  `answer_real` text,
			  `passed` varchar(10) DEFAULT NULL,
			  `datetime_try` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_tryanswer);
		
		$table_tryanswer_backup = "CREATE TABLE IF NOT EXISTS `tryanswer_backup` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `iduser` int(11) DEFAULT NULL,
			  `idquest` int(11) DEFAULT NULL,
			  `answer_try` text,
			  `answer_real` text,
			  `passed` varchar(10) DEFAULT NULL,
			  `datetime_try` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_tryanswer_backup);
				
		$table_user = "CREATE TABLE IF NOT EXISTS `user` (
			  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `username` varchar(50) NOT NULL,
			  `password` text NOT NULL,
			  `score` int(10) unsigned NOT NULL DEFAULT '0',
			  `role` varchar(10) DEFAULT 'user',
			  `nick` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			  `uuid_user` varchar(255) DEFAULT NULL,
			  `rating` int(10) unsigned DEFAULT '0',
			  `activation_code` varchar(255) DEFAULT NULL,
			  `date_create` datetime DEFAULT NULL,
			  `date_activated` datetime DEFAULT NULL,
			  `date_last_signup` datetime DEFAULT NULL,
			  `last_ip` varchar(255) DEFAULT NULL,
			  `logo` text,
			  `ipserver` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`iduser`),
			  UNIQUE KEY `username` (`username`),
			  UNIQUE KEY `uuid_user` (`uuid_user`),
			  KEY `FK_user_1` (`score`) USING BTREE,
			  KEY `rating` (`rating`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$conn->query($table_user);
			
		$table_userquest = "CREATE TABLE IF NOT EXISTS `userquest` (
			  `iduser` int(10) NOT NULL,
			  `idquest` int(10) NOT NULL DEFAULT '0',
			  `stopdate` datetime DEFAULT NULL,
			  `startdate` datetime DEFAULT NULL,
			  PRIMARY KEY (`iduser`,`idquest`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$conn->query($table_userquest);

		
		include_once("install_gotonextstep.php");
	}
?>
<h1> Install (Step <?php echo $current_step; ?>) </h1>

Create tables: <br>
<form>
	<input type='submit' name='create_tables' value='Create tables & got to next step'/> <br>
</form>
