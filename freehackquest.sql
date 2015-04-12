SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `email_delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` varchar(255) NOT NULL,
  `dt` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT '',
  `text` text,
  `userid` int(11) DEFAULT '0',
  `feedbackid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

CREATE TABLE IF NOT EXISTS `feedback_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` datetime NOT NULL,
  `text` text,
  `feedbackid` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid_game` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `type_game` varchar(255) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_stop` datetime DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `date_restart` datetime DEFAULT NULL,
  `description` text,
  `organizators` varchar(255) DEFAULT '',
  `state` varchar(255) DEFAULT 'copy',
  `form` varchar(255) DEFAULT 'online',
  `rules` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_game` (`uuid_game`),
  UNIQUE KEY `uuid_game_2` (`uuid_game`),
  UNIQUE KEY `uuid_game_3` (`uuid_game`),
  KEY `date_create` (`date_create`),
  KEY `date_start` (`date_start`),
  KEY `date_stop` (`date_stop`),
  KEY `date_change` (`date_change`),
  KEY `owner` (`owner`),
  KEY `date_restart` (`date_restart`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `public_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=287 ;

CREATE TABLE IF NOT EXISTS `quest` (
  `idquest` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `text` varchar(4048) NOT NULL,
  `answer` text NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `min_score` int(10) NOT NULL DEFAULT '0',
  `for_person` bigint(20) NOT NULL DEFAULT '0',
  `idauthor` int(11) NOT NULL,
  `state` varchar(50) DEFAULT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `answer_upper_md5` text,
  `gameid` int(10) unsigned DEFAULT NULL,
  `quest_uuid` varchar(255) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `userid` bigint(20) DEFAULT '0',
  `description_state` varchar(4048) DEFAULT NULL,
  `count_user_solved` bigint(20) DEFAULT '0',
  `author` varchar(255) NOT NULL,
  PRIMARY KEY (`idquest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

CREATE TABLE IF NOT EXISTS `quests_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `questid` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `filepath` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

CREATE TABLE IF NOT EXISTS `tryanswer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `idquest` int(11) NOT NULL,
  `answer_try` text NOT NULL,
  `answer_real` text NOT NULL,
  `passed` varchar(10) NOT NULL,
  `datetime_try` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=500 ;

CREATE TABLE IF NOT EXISTS `tryanswer_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `idquest` int(11) NOT NULL,
  `answer_try` text NOT NULL,
  `answer_real` text NOT NULL,
  `passed` varchar(10) NOT NULL,
  `datetime_try` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=524 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3014 ;

CREATE TABLE IF NOT EXISTS `userquest` (
  `iduser` int(10) NOT NULL,
  `idquest` int(10) NOT NULL,
  `stopdate` datetime NOT NULL,
  `startdate` datetime NOT NULL,
  UNIQUE KEY `iduser` (`iduser`,`idquest`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `nick` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `dt_create` datetime NOT NULL,
  `dt_last_login` datetime NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=533 ;

CREATE TABLE IF NOT EXISTS `users_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `gameid` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=408 ;

CREATE TABLE IF NOT EXISTS `users_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

CREATE TABLE IF NOT EXISTS `users_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data` varchar(4048) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=245 ;

CREATE TABLE IF NOT EXISTS `user_old` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(10) DEFAULT 'user',
  `nick` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uuid_user` varchar(255) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_last_signup` datetime DEFAULT NULL,
  `last_ip` varchar(255) DEFAULT NULL,
  `logo` text,
  `email` varchar(128) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `pass` varchar(255) DEFAULT '',
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `uuid_user` (`uuid_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=530 ;

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
(40, 'update logo for users', 'updated', '2015-01-16 01:19:20', 'update logo for users', 46, 'u0014', 'u0013'),
(41, 'added columns', 'updated', '2015-01-19 22:21:58', 'added column organizators, state and form to games', 46, 'u0015', 'u0014'),
(42, 'drop table teams and userteams', 'updated', '2015-03-25 21:54:54', 'drop table teams and userteams', 46, 'u0016', 'u0015'),
(43, 'updated table games', 'updated', '2015-03-25 22:06:13', 'updated table games', 46, 'u0017', 'u0016'),
(44, 'drop table games_rules', 'updated', '2015-03-25 22:08:10', 'drop table games_rules', 46, 'u0018', 'u0017'),
(45, 'updated games', 'updated', '2015-03-25 22:50:33', 'updated games', 46, 'u0019', 'u0018'),
(46, 'removed users_achievements, users_skills', 'updated', '2015-03-25 23:07:54', 'removed users_achievements, users_skills', 46, 'u0020', 'u0019'),
(47, 'generate user uuids', 'updated', '2015-03-25 23:15:21', 'generate user uuids', 46, 'u0021', 'u0020'),
(49, 'new table public_events', 'updated', '2015-03-27 14:28:41', 'new table public_events', 46, 'u0022', 'u0021'),
(1062, 'moved news to public events', 'updated', '2015-03-27 14:54:45', 'moved news to public events', 46, 'u0023', 'u0022'),
(1063, 'remove table news', 'updated', '2015-03-27 22:42:37', 'remove table news', 46, 'u0024', 'u0023'),
(1064, 'new column pass', 'updated', '2015-03-27 22:42:45', 'new column pass', 46, 'u0025', 'u0024'),
(1065, 'new table email_delivery', 'updated', '2015-03-28 07:38:55', 'new table email_delivery', 46, 'u0026', 'u0025'),
(1066, 'drop columns', 'updated', '2015-03-28 08:28:35', 'drop columns user.activation_code, date_activated, rating', 46, 'u0027', 'u0026'),
(1067, 'new table emails', 'updated', '2015-03-28 09:15:20', 'new table emails', 46, 'u0028', 'u0027'),
(1068, 'updated table games', 'updated', '2015-03-28 13:21:42', 'updated table games', 46, 'u0029', 'u0028'),
(1069, 'migrate data and removed id_game, tema', 'updated', '2015-03-29 23:34:22', 'migrate data and removed id_game, tema', 46, 'u0030', 'u0029'),
(1070, 'migrate data and removed name', 'updated', '2015-03-29 23:45:26', 'migrate data and removed name', 46, 'u0031', 'u0030'),
(1071, 'migrate data and removed text_copy', 'updated', '2015-03-29 23:50:09', 'migrate data and removed text_copy', 46, 'u0032', 'u0031'),
(1072, 'migrate data and removed answer_copy', 'updated', '2015-03-29 23:57:21', 'migrate data and removed answer_copy', 46, 'u0033', 'u0032'),
(1074, 'migrate data in tryanswer, tryanswer_backup', 'updated', '2015-03-30 00:11:51', 'migrate data in tryanswer, tryanswer_backup', 46, 'u0034', 'u0033'),
(1075, 'removed user.password', 'updated', '2015-03-31 01:23:37', 'removed user.password', 46, 'u0035', 'u0034'),
(1077, 'added table quests_files', 'updated', '2015-03-31 01:29:51', 'added table quests_files', 46, 'u0036', 'u0035'),
(1081, 'redesign table feedback', 'updated', '2015-04-01 01:03:02', 'redesign table feedback', 46, 'u0037', 'u0036'),
(1083, 'redesign table feedback_msg', 'updated', '2015-04-01 01:11:20', 'redesign table feedback_msg', 46, 'u0038', 'u0037'),
(1084, 'removed user.ipserver and user.username', 'updated', '2015-04-03 20:55:56', 'removed user.ipserver and user.username', 46, 'u0039', 'u0038'),
(1085, 'drop table advisers', 'updated', '2015-04-03 21:15:49', 'drop table advisers', 46, 'u0040', 'u0039'),
(1087, 'drop table flags and flags_live', 'updated', '2015-04-03 21:17:20', 'drop table flags and flags_live', 46, 'u0041', 'u0040'),
(1088, 'drop table scoreboard', 'updated', '2015-04-03 21:21:29', 'drop table scoreboard', 46, 'u0042', 'u0041'),
(1089, 'drop table services', 'updated', '2015-04-03 21:22:17', 'drop table services', 46, 'u0043', 'u0042'),
(3010, 'add quests_files.filepath', 'updated', '2015-04-04 00:49:12', 'add quests_files.filepath', 46, 'u0044', 'u0043'),
(3011, 'added table users', 'updated', '2015-04-08 00:52:16', 'added table users', 46, 'u0045', 'u0044'),
(3012, 'rename table user => user_old', 'updated', '2015-04-08 00:55:52', 'rename table user => user_old', 46, 'u0046', 'u0045'),
(3013, 'added table users', 'updated', '2015-04-08 01:07:39', 'added table users', 46, 'u0047', 'u0046');

INSERT INTO `users` (`id`, `uuid`, `email`, `pass`, `role`, `nick`, `logo`, `dt_create`, `dt_last_login`, `last_ip`, `status`) VALUES
(46, '39A551F4-3BF0-A1C8-8686-06A5C510DDA3', 'admin', '00fe92df464389f2da26c14475ad81e2632904fa', 'admin', 'sea-kg', 'files/users/46.png', '0000-00-00 00:00:00', '2015-04-12 23:49:58', '127.0.0.1', 'activated');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
