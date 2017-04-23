-- phpMyAdmin SQL Dump
-- version 4.6.4deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Апр 23 2017 г., 15:22
-- Server version: 5.7.17-0ubuntu0.16.10.1
-- PHP Version: 7.0.15-0ubuntu0.16.10.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `freehackquest`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatmessages`
--

CREATE TABLE `chatmessages` (
  `id` int(11) NOT NULL,
  `user` varchar(128) NOT NULL,
  `message` varchar(255) NOT NULL,
  `dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `classbook`
--

CREATE TABLE `classbook` (
  `id` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `uuid` varchar(128) NOT NULL,
  `parentuuid` varchar(128) NOT NULL,
  `name_ru` varchar(128) NOT NULL,
  `name_en` varchar(128) NOT NULL,
  `dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_delivery`
--

CREATE TABLE `email_delivery` (
  `id` int(11) NOT NULL,
  `to_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `dt` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT '',
  `text` text,
  `userid` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_msg`
--

CREATE TABLE `feedback_msg` (
  `id` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `text` text,
  `feedbackid` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
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
  `maxscore` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `public_events`
--

CREATE TABLE `public_events` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `dt` datetime NOT NULL,
  `message` varchar(2048) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quest`
--

CREATE TABLE `quest` (
  `idquest` int(10) UNSIGNED NOT NULL,
  `name` varchar(300) NOT NULL,
  `text` varchar(4048) NOT NULL,
  `answer` text NOT NULL,
  `score` int(10) UNSIGNED NOT NULL,
  `min_score` int(10) NOT NULL DEFAULT '0',
  `idauthor` bigint(20) DEFAULT '0',
  `author` varchar(50) DEFAULT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `answer_upper_md5` varchar(255) DEFAULT NULL,
  `gameid` int(10) UNSIGNED DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `description_state` varchar(4048) DEFAULT NULL,
  `quest_uuid` varchar(255) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `userid` bigint(20) DEFAULT '0',
  `count_user_solved` bigint(20) DEFAULT '0',
  `copyright` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quests_files`
--

CREATE TABLE `quests_files` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `questid` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  `filepath` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `quests_hints`
--

CREATE TABLE `quests_hints` (
  `id` int(11) NOT NULL,
  `questid` int(11) NOT NULL,
  `text` varchar(4048) NOT NULL,
  `dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tryanswer`
--

CREATE TABLE `tryanswer` (
  `id` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idquest` int(11) NOT NULL,
  `answer_try` text NOT NULL,
  `answer_real` text NOT NULL,
  `passed` varchar(10) NOT NULL,
  `datetime_try` datetime NOT NULL,
  `levenshtein` int(11) DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tryanswer_backup`
--

CREATE TABLE `tryanswer_backup` (
  `id` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idquest` int(11) NOT NULL,
  `answer_try` text NOT NULL,
  `answer_real` text NOT NULL,
  `passed` varchar(10) NOT NULL,
  `datetime_try` datetime NOT NULL,
  `levenshtein` int(11) DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int(11) NOT NULL,
  `from_version` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `result` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `datetime_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `updates`
--

INSERT INTO `updates` (`id`, `from_version`, `version`, `name`, `description`, `result`, `userid`, `datetime_update`) VALUES
(1, 'u0000', 'u0001', 'test', 'test', 'updated', 220, '2015-01-16 01:55:57'),
(2, 'u0001', 'u0002', 'emails', 'unpack mails from user.username to user.email', 'updated', 220, '2015-01-16 01:56:01'),
(3, 'u0002', 'u0003', 'update table quest', 'state and description_state to "" if null', 'updated', 220, '2015-01-16 01:56:01'),
(4, 'u0003', 'u0004', 'drop user.score', 'drop column score from user ', 'updated', 220, '2015-01-16 01:56:01'),
(5, 'u0004', 'u0005', 'update table users_ips', 'added column browser to users_ips', 'updated', 220, '2015-01-16 01:56:01'),
(6, 'u0005', 'u0006', 'update table user', 'added column status to user', 'updated', 220, '2015-01-16 01:56:01'),
(7, 'u0006', 'u0007', 'update state for user', 'update column status in user', 'updated', 220, '2015-01-16 01:56:01'),
(8, 'u0007', 'u0008', 'added table users_skills', 'update column status in user', 'updated', 220, '2015-01-16 01:56:01'),
(9, 'u0008', 'u0009', 'added table users_achievements', 'added table users_achievements', 'updated', 220, '2015-01-16 01:56:01'),
(10, 'u0009', 'u0010', 'added column team', 'added column team', 'updated', 220, '2015-01-16 01:56:01'),
(11, 'u0010', 'u0011', 'added table users_tokens', 'added table users_tokens', 'updated', 220, '2015-01-16 01:56:01'),
(12, 'u0011', 'u0012', 'added table rules', 'added table rules', 'updated', 220, '2015-01-16 01:56:02'),
(13, 'u0012', 'u0013', 'added table games_rules', 'added table games_rules', 'updated', 220, '2015-01-16 01:56:02'),
(14, 'u0013', 'u0014', 'update logo for users', 'update logo for users', 'updated', 220, '2015-01-16 01:56:02'),
(15, 'u0014', 'u0015', 'added columns', 'added column organizators, state and form to games', 'updated', 220, '2015-01-19 22:29:54'),
(16, 'u0015', 'u0016', 'drop table teams and userteams', 'drop table teams and userteams', 'updated', 220, '2015-03-25 22:56:01'),
(17, 'u0016', 'u0017', 'updated table games', 'updated table games', 'updated', 220, '2015-03-25 22:56:02'),
(18, 'u0017', 'u0018', 'drop table games_rules', 'drop table games_rules', 'updated', 220, '2015-03-25 22:56:02'),
(19, 'u0018', 'u0019', 'updated games', 'updated games', 'updated', 220, '2015-03-25 22:56:02'),
(20, 'u0019', 'u0020', 'removed users_achievements, users_skills', 'removed users_achievements, users_skills', 'updated', 220, '2015-03-27 18:29:36'),
(21, 'u0020', 'u0021', 'generate user uuids', 'generate user uuids', 'updated', 220, '2015-03-27 18:29:40'),
(22, 'u0021', 'u0022', 'new table public_events', 'new table public_events', 'updated', 220, '2015-03-27 18:29:40'),
(23, 'u0022', 'u0023', 'moved news to public events', 'moved news to public events', 'updated', 220, '2015-03-27 18:29:40'),
(24, 'u0023', 'u0024', 'remove table news', 'remove table news', 'updated', 220, '2015-03-27 18:29:47'),
(25, 'u0024', 'u0025', 'new column pass', 'new column pass', 'updated', 220, '2015-03-27 22:59:38'),
(26, 'u0025', 'u0026', 'new table emails', 'new table emails', 'updated', 220, '2015-03-28 09:22:31'),
(27, 'u0026', 'u0027', 'new table emails', 'new table emails', 'updated', 220, '2015-03-28 09:22:31'),
(28, 'u0027', 'u0028', 'new table emails', 'new table emails', 'updated', 220, '2015-03-28 09:22:32'),
(29, 'u0028', 'u0029', 'updated table games', 'updated table games', 'updated', 220, '2015-03-28 14:01:05'),
(30, 'u0029', 'u0030', 'migrate data and removed id_game, tema', 'migrate data and removed id_game, tema', 'updated', 220, '2015-03-30 01:00:58'),
(31, 'u0030', 'u0031', 'migrate data and removed name', 'migrate data and removed name', 'updated', 220, '2015-03-30 01:01:06'),
(32, 'u0031', 'u0032', 'migrate data and removed text_copy', 'migrate data and removed text_copy', 'updated', 220, '2015-03-30 01:01:14'),
(33, 'u0032', 'u0033', 'migrate data and removed answer_copy', 'migrate data and removed answer_copy', 'updated', 220, '2015-03-30 01:01:23'),
(34, 'u0033', 'u0034', 'migrate data in tryanswer, tryanswer_backup', 'migrate data in tryanswer, tryanswer_backup', 'updated', 220, '2015-03-30 01:03:43'),
(35, 'u0034', 'u0035', 'removed user.password', 'removed user.password', 'updated', 220, '2015-03-31 02:02:21'),
(36, 'u0035', 'u0036', 'added table quests_files', 'added table quests_files', 'updated', 220, '2015-03-31 02:02:21'),
(37, 'u0036', 'u0037', 'redesign table feedback', 'redesign table feedback', 'updated', 220, '2015-04-01 01:48:56'),
(38, 'u0037', 'u0038', 'redesign table feedback_msg', 'redesign table feedback_msg', 'updated', 220, '2015-04-01 01:48:56'),
(39, 'u0038', 'u0039', 'removed user.ipserver and user.username', 'removed user.ipserver and user.username', 'updated', 220, '2015-04-04 01:25:02'),
(40, 'u0039', 'u0040', 'drop table advisers', 'drop table advisers', 'updated', 220, '2015-04-04 01:25:02'),
(41, 'u0040', 'u0041', 'drop table flags and flags_live', 'drop table flags and flags_live', 'updated', 220, '2015-04-04 01:25:02'),
(42, 'u0041', 'u0042', 'drop table scoreboard', 'drop table scoreboard', 'updated', 220, '2015-04-04 01:25:02'),
(43, 'u0042', 'u0043', 'drop table services', 'drop table services', 'updated', 220, '2015-04-04 01:25:02'),
(44, 'u0043', 'u0044', 'add quests_files.filepath', 'add quests_files.filepath', 'updated', 220, '2015-04-04 01:25:02'),
(45, 'u0044', 'u0045', 'added table users', 'added table users', 'updated', 0, '2015-04-09 00:13:25'),
(46, 'u0045', 'u0046', 'rename table user => user_old', 'rename table user => user_old', 'updated', 0, '2015-04-09 00:13:25'),
(47, 'u0046', 'u0047', 'added table users', 'added table users', 'updated', 0, '2015-04-09 00:13:30'),
(48, 'u0047', 'u0048', 'change message in events (for indexing and search)', 'change message in events (for indexing and search)', 'updated', 220, '2015-04-22 19:29:15'),
(49, 'u0048', 'u0049', 'add column maxscore', 'add column maxscore', 'updated', 220, '2015-05-16 00:32:28'),
(50, 'u0049', 'u0050', 'add column maxscore', 'add column maxscore', 'updated', 220, '2015-05-16 00:32:28'),
(51, 'u0050', 'u0051', 'add column levenshtein', 'add column levenshtein', 'updated', 220, '2015-05-16 00:32:28'),
(52, 'u0051', 'u0052', 'calculate levenshtein distance', 'calculate levenshtein distance', 'updated', 220, '2015-05-16 00:33:33'),
(53, 'u0052', 'u0053', 'calculate levenshtein distance (backup)', 'calculate levenshtein distance (backup)', 'updated', 220, '2015-05-16 00:35:18'),
(54, 'u0053', 'u0054', 'recalculate user scoreboards', 'recalculate user scoreboards', 'updated', 220, '2015-06-02 01:02:14'),
(55, 'u0054', 'u0055', 'added new table users_quests', 'added new table users_quests', 'updated', 220, '2015-06-07 18:53:23'),
(56, 'u0055', 'u0056', 'added cleanup users_games', 'added cleanup users_games', 'updated', 220, '2015-06-07 18:53:23'),
(57, 'u0056', 'u0057', 'added table users_token_invalid', 'added table users_token_invalid', 'updated', 220, '2015-06-07 20:59:09'),
(58, 'u0057', 'u0058', 'rename games.uuid_game games.uuid', 'rename games.uuid_game games.uuid', 'updated', 220, '2015-06-07 20:59:09'),
(59, 'u0058', 'u0059', 'change userid and questid in users_quests to INT', 'change userid and questid in users_quests to INT', 'updated', 220, '2015-06-07 20:59:09'),
(60, 'u0059', 'u0060', 'moved data from userquest to users_quests', 'moved data from userquest to users_quests', 'updated', 220, '2015-06-07 20:59:46'),
(61, 'u0060', 'u0061', 'remove personal quests which not passed', 'remove personal quests which not passed', 'updated', 47, '2015-07-23 11:11:43'),
(62, 'u0061', 'u0062', 'remove table user_old', 'remove table user_old', 'updated', 47, '2015-07-23 11:11:43'),
(63, 'u0062', 'u0063', 'remove table userquest', 'remove table userquest', 'updated', 47, '2015-07-23 11:11:43'),
(64, 'u0063', 'u0064', 'remove personal quests which passed', 'remove personal quests which passed', 'updated', 47, '2015-07-23 11:11:43'),
(65, 'u0064', 'u0065', 'update quests uuid', 'update quests uuid', 'updated', 47, '2015-07-23 11:11:43'),
(66, 'u0065', 'u0066', 'update quests uuid', 'update quests uuid', 'updated', 47, '2015-07-23 11:11:43'),
(67, 'u0066', 'u0067', 'Added column copyright to quest', 'Added column copyright to quest', 'updated', 0, '2017-01-07 06:24:22'),
(68, 'u0067', 'u0068', 'Added table hints', 'Added table hints', 'updated', 0, '2017-01-07 06:24:22'),
(71, 'u0068', 'u0069', 'Add columns to users', 'Add columns to users', 'updated', 0, '2017-03-03 20:21:44'),
(72, 'u0069', 'u0070', 'Removed table users_ips', 'Removed table users_ips', 'updated', 0, '2017-03-04 13:58:02'),
(73, 'u0070', 'u0071', 'Added classbook table', 'Added classbook table', 'updated', 0, '2017-03-14 00:22:40'),
(75, 'u0071', 'u0072', 'Added chatmessages table', 'Added chatmessages table', 'updated', 0, '2017-04-01 12:52:57'),
(77, 'u0072', 'u0073', 'Added captcha table', 'Added captcha table', 'updated', 0, '2017-04-23 13:11:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
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
  `country` varchar(255) DEFAULT '',
  `region` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  `latitude` double DEFAULT '0',
  `longitude` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_captcha`
--

CREATE TABLE `users_captcha` (
  `id` int(11) NOT NULL,
  `captcha_uuid` varchar(127) NOT NULL,
  `captcha_val` varchar(127) NOT NULL,
  `dt_expired` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_games`
--

CREATE TABLE `users_games` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `gameid` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_profile`
--

CREATE TABLE `users_profile` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `date_change` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_quests`
--

CREATE TABLE `users_quests` (
  `userid` int(11) DEFAULT NULL,
  `questid` int(11) DEFAULT NULL,
  `dt_passed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_tokens`
--

CREATE TABLE `users_tokens` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data` varchar(4048) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_tokens_invalid`
--

CREATE TABLE `users_tokens_invalid` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data` varchar(4048) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `chatmessages`
--
ALTER TABLE `chatmessages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `classbook`
--
ALTER TABLE `classbook`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `email_delivery`
--
ALTER TABLE `email_delivery`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `feedback_msg`
--
ALTER TABLE `feedback_msg`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_game` (`uuid`),
  ADD UNIQUE KEY `uuid_game_2` (`uuid`),
  ADD KEY `date_create` (`date_create`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `date_stop` (`date_stop`),
  ADD KEY `date_change` (`date_change`),
  ADD KEY `owner` (`owner`),
  ADD KEY `date_restart` (`date_restart`);

--
-- Индексы таблицы `public_events`
--
ALTER TABLE `public_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message` (`message`(255));

--
-- Индексы таблицы `quest`
--
ALTER TABLE `quest`
  ADD PRIMARY KEY (`idquest`);

--
-- Индексы таблицы `quests_files`
--
ALTER TABLE `quests_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `quests_hints`
--
ALTER TABLE `quests_hints`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tryanswer`
--
ALTER TABLE `tryanswer`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tryanswer_backup`
--
ALTER TABLE `tryanswer_backup`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_captcha`
--
ALTER TABLE `users_captcha`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_games`
--
ALTER TABLE `users_games`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_profile`
--
ALTER TABLE `users_profile`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_quests`
--
ALTER TABLE `users_quests`
  ADD UNIQUE KEY `userid` (`userid`,`questid`);

--
-- Индексы таблицы `users_tokens`
--
ALTER TABLE `users_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_tokens_invalid`
--
ALTER TABLE `users_tokens_invalid`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `chatmessages`
--
ALTER TABLE `chatmessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `classbook`
--
ALTER TABLE `classbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `email_delivery`
--
ALTER TABLE `email_delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=594;
--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `feedback_msg`
--
ALTER TABLE `feedback_msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT для таблицы `public_events`
--
ALTER TABLE `public_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3993;
--
-- AUTO_INCREMENT для таблицы `quest`
--
ALTER TABLE `quest`
  MODIFY `idquest` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
--
-- AUTO_INCREMENT для таблицы `quests_files`
--
ALTER TABLE `quests_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;
--
-- AUTO_INCREMENT для таблицы `quests_hints`
--
ALTER TABLE `quests_hints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT для таблицы `tryanswer`
--
ALTER TABLE `tryanswer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28565;
--
-- AUTO_INCREMENT для таблицы `tryanswer_backup`
--
ALTER TABLE `tryanswer_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28747;
--
-- AUTO_INCREMENT для таблицы `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=532;
--
-- AUTO_INCREMENT для таблицы `users_captcha`
--
ALTER TABLE `users_captcha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT для таблицы `users_games`
--
ALTER TABLE `users_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1562;
--
-- AUTO_INCREMENT для таблицы `users_profile`
--
ALTER TABLE `users_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2905;
--
-- AUTO_INCREMENT для таблицы `users_tokens`
--
ALTER TABLE `users_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2271;
--
-- AUTO_INCREMENT для таблицы `users_tokens_invalid`
--
ALTER TABLE `users_tokens_invalid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
