SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `advisers` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;


CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeFB` text NOT NULL,
  `full_text` text NOT NULL,
  `author` int(11) NOT NULL,
  `dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Структура таблицы `feedback_msg`
--

CREATE TABLE IF NOT EXISTS `feedback_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feedback_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `author` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Структура таблицы `flags`
--

CREATE TABLE IF NOT EXISTS `flags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idservice` int(10) unsigned DEFAULT NULL,
  `flag` varchar(50) DEFAULT NULL,
  `owner` varchar(300) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `user_passed` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=525 ;

-- --------------------------------------------------------

--
-- Структура таблицы `flags_live`
--

CREATE TABLE IF NOT EXISTS `flags_live` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idservice` int(10) unsigned DEFAULT NULL,
  `flag` varchar(50) DEFAULT NULL,
  `owner` int(10) unsigned DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `user_passed` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=584 ;

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `json_data` text NOT NULL,
  `uuid_game` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `type_game` varchar(255) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_stop` datetime DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  `json_security_data` text,
  `owner` int(11) DEFAULT NULL,
  `rating` int(10) unsigned DEFAULT NULL,
  `date_restart` datetime DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_game` (`uuid_game`),
  KEY `date_create` (`date_create`),
  KEY `date_start` (`date_start`),
  KEY `date_stop` (`date_stop`),
  KEY `date_change` (`date_change`),
  KEY `owner` (`owner`),
  KEY `date_restart` (`date_restart`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Структура таблицы `games_rules`
--

CREATE TABLE IF NOT EXISTS `games_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `ruleid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `datetime_` datetime NOT NULL,
  `author` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Структура таблицы `quest`
--

CREATE TABLE IF NOT EXISTS `quest` (
  `idquest` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tema` varchar(128) NOT NULL,
  `name` varchar(300) NOT NULL,
  `short_text` varchar(128) NOT NULL,
  `text` varchar(4048) NOT NULL,
  `answer` text NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `min_score` int(10) NOT NULL DEFAULT '0',
  `for_person` bigint(20) NOT NULL DEFAULT '0',
  `id_game` int(10) unsigned DEFAULT NULL,
  `idauthor` int(11) NOT NULL,
  `author` varchar(50) NOT NULL,
  `state` varchar(50) DEFAULT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `short_text_copy` varchar(128) DEFAULT NULL,
  `text_copy` varchar(4048) DEFAULT NULL,
  `answer_copy` text,
  `answer_upper_md5` text,
  `gameid` int(10) unsigned DEFAULT NULL,
  `quest_uuid` varchar(255) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `userid` bigint(20) DEFAULT '0',
  `description_state` varchar(4048) DEFAULT NULL,
  `count_user_solved` bigint(20) DEFAULT '0',
  PRIMARY KEY (`idquest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` varchar(4048) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `scoreboard`
--

CREATE TABLE IF NOT EXISTS `scoreboard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idgame` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `owner` varchar(300) DEFAULT NULL,
  `score` int(10) unsigned DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1041 ;

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idgame` int(10) unsigned DEFAULT NULL,
  `scriptpath` varchar(255) DEFAULT NULL,
  `comment` varchar(4048) DEFAULT NULL,
  `name` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid_team` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT '0',
  `logo` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `json_data` text,
  `json_security_data` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_team` (`uuid_team`),
  KEY `title` (`title`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tryanswer`
--

CREATE TABLE IF NOT EXISTS `tryanswer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `idquest` int(11) NOT NULL,
  `answer_try` text NOT NULL,
  `answer_real` text NOT NULL,
  `passed` varchar(10) NOT NULL,
  `datetime_try` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=165 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tryanswer_backup`
--

CREATE TABLE IF NOT EXISTS `tryanswer_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `idquest` int(11) NOT NULL,
  `answer_try` text NOT NULL,
  `answer_real` text NOT NULL,
  `passed` varchar(10) NOT NULL,
  `datetime_try` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Структура таблицы `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `result` varchar(255) DEFAULT NULL,
  `datetime_update` datetime DEFAULT NULL,
  `description` text,
  `userid` int(11) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `from_version` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` text NOT NULL,
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
  `email` varchar(128) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `team` int(11) DEFAULT '0',
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uuid_user` (`uuid_user`),
  UNIQUE KEY `email` (`email`),
  KEY `rating` (`rating`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=503 ;

-- --------------------------------------------------------

--
-- Структура таблицы `userquest`
--

CREATE TABLE IF NOT EXISTS `userquest` (
  `iduser` int(10) NOT NULL,
  `idquest` int(10) NOT NULL,
  `stopdate` datetime NOT NULL,
  `startdate` datetime NOT NULL,
  UNIQUE KEY `iduser` (`iduser`,`idquest`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0',
  `nick` varchar(30) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `activated` int(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(40) NOT NULL,
  `json_data` text,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_activated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `date_last_signin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `team` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  UNIQUE KEY `mail` (`mail`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=458 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_achievements`
--

CREATE TABLE IF NOT EXISTS `users_achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `text` varchar(1024) NOT NULL,
  `receipt_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_games`
--

CREATE TABLE IF NOT EXISTS `users_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `gameid` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_ips`
--

CREATE TABLE IF NOT EXISTS `users_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `date_sign_in` datetime DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `browser` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `country` (`country`),
  KEY `city` (`city`),
  KEY `client` (`client`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=164 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_profile`
--

CREATE TABLE IF NOT EXISTS `users_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_skills`
--

CREATE TABLE IF NOT EXISTS `users_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `skill` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_tokens`
--

CREATE TABLE IF NOT EXISTS `users_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data` varchar(4048) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Структура таблицы `userteams`
--

CREATE TABLE IF NOT EXISTS `userteams` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `date_begin` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `updates` (`id`, `name`, `result`, `datetime_update`, `description`, `userid`, `version`, `from_version`) VALUES
(27, 'test', 'updated', '2015-01-14 23:42:30', 'test', 46, 'u0001', 'u0000'),
(28, 'emails', 'updated', '2015-01-14 23:43:01', 'unpack mails from user.username to user.email', 46, 'u0002', 'u0001'),
(29, 'update table quest', 'updated', '2015-01-14 23:43:01', 'state and description_state to "" if null', 46, 'u0003', 'u0002'),
(30, 'drop user.score', 'updated', '2015-01-14 23:43:25', 'drop column score from user ', 46, 'u0004', 'u0003'),
(31, 'update table users_ips', 'updated', '2015-01-14 23:43:25', 'added column browser to users_ips', 46, 'u0005', 'u0004'),
(32, 'update table user', 'updated', '2015-01-14 23:43:25', 'added column status to user', 46, 'u0006', 'u0005'),
(33, 'update state for user', 'updated', '2015-01-14 23:43:25', 'update column status in user', 46, 'u0007', 'u0006'),
(34, 'added table users_skills', 'updated', '2015-01-14 23:43:25', 'update column status in user', 46, 'u0008', 'u0007'),
(35, 'added table users_achievements', 'updated', '2015-01-14 23:43:25', 'added table users_achievements', 46, 'u0009', 'u0008'),
(36, 'added column team', 'updated', '2015-01-14 23:43:26', 'added column team', 46, 'u0010', 'u0009'),
(37, 'added table users_tokens', 'updated', '2015-01-14 23:43:26', 'added table users_tokens', 46, 'u0011', 'u0010'),
(38, 'added table rules', 'updated', '2015-01-15 02:40:17', 'added table rules', 46, 'u0012', 'u0011'),
(39, 'added table games_rules', 'updated', '2015-01-15 02:40:17', 'added table games_rules', 46, 'u0013', 'u0012'),
(40, 'update logo for users', 'updated', '2015-01-16 01:19:20', 'update logo for users', 46, 'u0014', 'u0013');
