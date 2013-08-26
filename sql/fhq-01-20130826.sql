-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 23 2013 г., 20:14
-- Версия сервера: 5.5.24-0ubuntu0.12.04.1
-- Версия PHP: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `freehackquest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeFB` text CHARACTER SET utf8 NOT NULL,
  `full_text` text CHARACTER SET utf8 NOT NULL,
  `author` int(11) NOT NULL,
  `dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Дамп данных таблицы `feedback`
--

INSERT INTO `feedback` (`id`, `typeFB`, `full_text`, `author`, `dt`) VALUES
(5, 'Complaint', 'Â«61 70 70 6C 65Â» - Ð¯Ð±Ð»Ð¾ÐºÐ¾ apple\r\nÐ¿Ð¾Ð¿Ñ‹Ñ‚ÐºÐ° Ð½ÐµÑÐºÐ¾Ð»ÑŒÐºÐ¸Ð¼Ð¸ Ð´ÐµÐºÐ¾Ð´ÐµÑ€Ð°Ð¼Ð¸, Ñ€ÐµÐ·ÑƒÐ»ÑŒÑ‚Ð°Ñ‚ Ð¾Ð´Ð¸Ð½ Ð¸ Ñ‚Ð¾Ñ‚ Ð¶Ðµ ÑÐ¸ÑÑ‚ÐµÐ¼Ð° Ð½Ðµ Ð¿Ñ€Ð¸Ð½Ð¸Ð¼Ð°ÐµÑ‚, ÐºÐ¾Ð½ÐµÑ‡Ð½Ð¾ Ð²Ð¾Ð·Ð¼Ð¾Ð¶Ð½Ð¾ Ñ Ð¾ÑˆÐ¸Ð±Ð°ÑŽÑÑŒ, Ð° Ð¼Ð¾Ð¶ÐµÑ‚ Ð±Ð°Ð³Ð°, ÐµÑÐ»Ð¸ Ð¼Ð¾Ð¶Ð½Ð¾ Ð¿Ñ€Ð¾Ð²ÐµÑ€ÑŒÑ‚Ðµ :3', 32, NULL),
(6, 'Complaint', '#1 ÑƒÑ‚Ð¾Ñ‡Ð½Ð¸Ñ‚ÑŒ\n61:70:70:6C:65\napple  (Ð¯Ð±Ð»Ð¾ÐºÐ¾, Ð¯Ð±Ð»Ð¾Ð½Ñ, ÑÐ±Ð»Ð¾Ñ‡Ð½Ñ‹Ð¹ - Ñ€ÑƒÑ).\nÐ¿Ñ€Ð¾Ð±Ð¾Ð²Ð°Ð» Ð½ÐµÑÐºÐ¾Ð»ÑŒÐºÐ¾ Ð²Ð°Ñ€Ð¸Ð°Ñ†Ð¸Ð¹.', 32, NULL),
(7, 'Defect', '#1 ÑƒÑ‚Ð¾Ñ‡Ð½Ð¸Ñ‚ÑŒ Ð¸ Ð²Ð¾Ð·Ð¼Ð¾Ð¶Ð½Ð¾ Ð»Ð¸?\r\n61:70:70:6C:65\r\napple  (Ð¯Ð±Ð»Ð¾ÐºÐ¾, Ð¯Ð±Ð»Ð¾Ð½Ñ, ÑÐ±Ð»Ð¾Ñ‡Ð½Ñ‹Ð¹ - Ñ€ÑƒÑ).\r\nÐ¿Ñ€Ð¾Ð±Ð¾Ð²Ð°Ð» Ð½ÐµÑÐºÐ¾Ð»ÑŒÐºÐ¾ Ð²Ð°Ñ€Ð¸Ð°Ñ†Ð¸Ð¹.\r\n', 32, NULL),
(8, 'Proposal', '#11\r\nÐ²Ñ€Ð¾Ð´Ðµ Ð²ÑÐµ md5, Ñ‡ÐµÑ€ÐµÐ· Ð±Ð°Ð·Ñ‹ Ð¿Ñ€Ð¾Ð±Ð¸Ð» Ð²ÑÐµ Ð¿Ñ€Ð¾Ñ‚ÐµÑÑ‚Ð¸Ð» Ð¸ Ð¿Ñ€Ð¾Ð´ÑƒÐ±Ð»Ð¸Ñ€Ð¾Ð²Ð°Ð», Ð²Ð°Ñ€Ð¸Ð°Ð½Ñ‚ Ð¾Ñ‚Ð²ÐµÑ‚Ð¾Ð² ÑÐ¾Ð²Ð¿Ð°Ð´Ð°ÐµÑ‚.\r\nÐ½Ð¾ ÐºÐ°Ðº Ð¿Ñ€Ð°Ð²Ð¸Ð»ÑŒÐ½Ð¾ Ð²Ð²ÐµÑÑ‚Ð¸ Ð´Ð°Ð½Ð½Ñ‹Ðµ Ð½ÐµÐ¸Ð·Ð²ÐµÑÑ‚Ð½Ð¾ :(\r\nnfox\r\nqui\r\nThe\r\nrow\r\nckb \r\n', 32, NULL),
(9, 'Proposal', 'Ð²Ð¾Ð·Ð²Ñ€Ð°Ñ‰Ð°ÑÑÑŒ Ðº #11\r\nÐ²ÑÐµ Ð²Ñ‹ÑˆÐµ Ð¿ÐµÑ€ÐµÑ‡Ð¸ÑÐ»ÐµÐ½Ð½Ñ‹Ðµ Ð´Ð°Ð½Ð½Ñ‹Ðµ ÑƒÐ¶ Ð¾Ñ‡ÐµÐ½ÑŒ Ð¿Ð¾Ð´Ñ…Ð¾Ð´ÑÑ‚ Ðº Ð´Ð°Ð½Ð½Ñ‹Ð¼ Ð¸Ð· ÑÐ¼ÑƒÐ»ÑÑ‚Ð¾Ñ€Ð° &quot;Ð­Ð½Ð¸Ð³Ð¼Ñ‹&quot; :)\r\nÐ¿Ñ€ÑƒÑ„ : http://enigma.louisedade.co.uk/help.html \r\n^_^', 32, NULL),
(10, 'Proposal', '#11\r\nThe quick brown fox jumps over the lazy dog \r\nÐ—Ð°ÑˆÐ¸Ñ„Ñ€Ð¾Ð²Ð°Ð½ Ð¾Ñ‚Ñ€Ñ‹Ð²Ð¾Ðº Ð¸Ð· Ð´Ð°Ð½Ð½Ð¾Ð¹ Ð¿Ð°Ð½Ð°Ð³Ñ€Ð°Ð¼Ð¼Ñ‹, Ð²ÑÐµ Ð² md5.\r\nÐµÑÐ»Ð¸ Ð¿Ð¾ Ð¿Ð¾Ñ€ÑÐ´ÐºÑƒ Ñ‚Ð¾ : nfox,qui,The,row,ckb .\r\nÐÐ¾ Ð² ÐºÐ°ÐºÐ¾Ð¼ Ð¸Ð¼ÐµÐ½Ð½Ð¾ Ñ„Ð¾Ñ€Ð¼Ð°Ñ‚Ðµ Ð²Ð²Ð¾Ð´Ð¸Ñ‚ÑŒ Ð´Ð°Ð½Ð½Ñ‹Ðµ Ñ‚Ð°Ðº Ð¸ Ð½Ðµ Ð¿Ð¾Ð½ÑÑ‚Ð½Ð¾:) ÐœÐ¾Ð¶Ð½Ð¾ ÐºÐ°ÐºÐ¸Ðµ-Ñ‚Ð¾ Ð¿Ð¾ÑÑÐ½ÐµÐ½Ð¸Ñ Ñ‡Ñ‚Ð¾-Ð»Ð¸ ?:)', 32, NULL),
(11, 'Proposal', 'Ð½Ñƒ Ð¾Ñ‚Ð²ÐµÑ‚ÑŒÑ‚Ðµ Ñ…Ð¾Ñ‚ÑŒ ÐºÑ‚Ð¾-Ð½Ð¸Ð±ÑƒÐ´ÑŒ :)\r\n#11\r\n#1\r\n ', 32, NULL),
(12, 'Proposal', 'Ð‘Ð¾Ð»ÑŒÑˆÐµ ÐºÐ²ÐµÑÑ‚Ð¾Ð²! ÐœÐ¾Ð»Ð¾Ð´Ñ†Ñ‹ )', 53, NULL),
(13, 'Proposal', 'Ð‘Ð¾Ð»ÑŒÑˆÐµ ÐºÐ²ÐµÑÑ‚Ð¾Ð²! ÐœÐ¾Ð»Ð¾Ð´Ñ†Ñ‹ )', 53, NULL),
(14, 'Proposal', 'Ð‘Ð¾Ð»ÑŒÑˆÐµ ÐºÐ²ÐµÑÑ‚Ð¾Ð²! ÐœÐ¾Ð»Ð¾Ð´Ñ†Ñ‹ )', 53, NULL),
(15, 'Proposal', 'Ð‘Ð¾Ð»ÑŒÑˆÐµ ÐºÐ²ÐµÑÑ‚Ð¾Ð²! ÐœÐ¾Ð»Ð¾Ð´Ñ†Ñ‹ )', 53, NULL),
(21, 'Proposal', 'Ð½Ð° ÑÐµÑ€Ð²ÐµÑ€Ðµ Ð½Ð°ÑˆÐµÐ» Ð·Ð°Ð´Ð°Ñ‡ÐºÑƒ Ð¿Ð¾ ÑÑ‚ÐµÐ³Ð°Ð½Ð¾Ð³Ñ€Ð°Ñ„Ð¸Ð¸ :) Ð²Ñ€Ð¾Ð´Ðµ Ð´Ð»Ñ Ð´ÐµÑˆÐ¸Ñ„Ñ€Ð¾Ð²ÐºÐ¸ ÐºÐ»ÑŽÑ‡, Ð° Ñ‚Ð¾Ñ‡Ð½ÐµÐµ passphrase :) Ð° Ð¾Ð½ Ð±ÑƒÐ´ÐµÑ‚ Ð² ÑƒÑÐ»Ð¾Ð²Ð¸Ð¸? Ð¸Ð»Ð¸ Ð½ÑƒÐ¶Ð½Ð¾ Ð±ÑƒÐ´ÐµÑ‚ Ñ‚Ð¾Ð¶Ðµ Ð³Ð´Ðµ-Ñ‚Ð¾ Ð´ÐµÑˆÐ¸Ñ„Ñ€Ð¾Ð²Ð°Ñ‚ÑŒ Ð¸ Ð±Ñ€ÑƒÑ‚Ð¸Ñ‚ÑŒÑ‚?:)\r\nÐ²Ñ‹Ð»Ð¾Ð¶Ð¸Ñ‚Ðµ Ð·Ð°Ð´Ð½Ð¸Ð°Ðµ Ð¿Ð»Ð¸Ð· :)', 32, '2012-09-11 02:17:31'),
(20, 'Complaint', 'Ð‘Ð¾Ð»ÑŒÑˆÐµ ÐºÐ²ÐµÑÑ‚Ð¾Ð² ÐœÐ¾Ð»Ð¾Ð´Ñ†Ñ‹!', 23, '2012-09-11 01:39:12'),
(22, 'Proposal', 'Ð½Ð° ÑÐµÑ€Ð²ÐµÑ€Ðµ Ð½Ð°ÑˆÐµÐ» Ð·Ð°Ð´Ð°Ñ‡ÐºÑƒ Ð¿Ð¾ ÑÑ‚ÐµÐ³Ð°Ð½Ð¾Ð³Ñ€Ð°Ñ„Ð¸Ð¸ :) Ð²Ñ€Ð¾Ð´Ðµ Ð´Ð»Ñ Ð´ÐµÑˆÐ¸Ñ„Ñ€Ð¾Ð²ÐºÐ¸ ÐºÐ»ÑŽÑ‡, Ð° Ñ‚Ð¾Ñ‡Ð½ÐµÐµ passphrase :) Ð° Ð¾Ð½ Ð±ÑƒÐ´ÐµÑ‚ Ð² ÑƒÑÐ»Ð¾Ð²Ð¸Ð¸? Ð¸Ð»Ð¸ Ð½ÑƒÐ¶Ð½Ð¾ Ð±ÑƒÐ´ÐµÑ‚ Ñ‚Ð¾Ð¶Ðµ Ð³Ð´Ðµ-Ñ‚Ð¾ Ð´ÐµÑˆÐ¸Ñ„Ñ€Ð¾Ð²Ð°Ñ‚ÑŒ Ð¸ Ð±Ñ€ÑƒÑ‚Ð¸Ñ‚ÑŒÑ‚?:)\r\nÐ²Ñ‹Ð»Ð¾Ð¶Ð¸Ñ‚Ðµ Ð·Ð°Ð´Ð½Ð¸Ð°Ðµ Ð¿Ð»Ð¸Ð· :)', 32, '2012-09-11 02:17:44'),
(23, 'Proposal', 'Ð° Ð¼Ð¾Ð¶Ð½Ð¾ Ð±Ð¾Ð»ÐµÐµ live-Ð²ÐµÑ€ÑÐ¸ÑŽ Ñ„Ð¸Ð¸Ð´Ð±Ð°ÐºÐ° Ð´Ð¾Ð±Ð°Ð²Ð¸Ñ‚ÑŒ ?:)', 32, '2012-09-11 02:18:27'),
(24, 'Complaint', 'try pass quest -&gt; try to pass the quest', 85, '2012-09-11 08:15:40'),
(26, 'Complaint', 'Ñ„Ð°Ð·Ð·Ð¸Ð½Ð³\r\nÐ² ÑƒÐ±ÑƒÐ½Ñ‚Ðµ Ð¾ÐºÐ°Ð·Ð°Ð»Ð¾ÑÑŒ Ð²ÑÐµ Ð² Ñ€Ð°Ð·Ñ‹ Ð¿Ñ€Ð¾Ñ‰Ðµ\r\nsudo gedit /media/D/fuzz_50\r\nÐ¾Ñ‚ÐºÑ€Ñ‹Ð²Ð°ÐµÑ‚ÑÑ Ð¸ Ð²ÑÐµ Ñ‡Ñ‚Ð¾ Ð² Ð»ÐµÐ²Ð¾Ð¹ ÐºÐ¾Ð´Ð¸Ñ€Ð¾Ð²ÐºÐµ, Ð¿Ð¾Ñ‡ÐµÐ¼Ñƒ-Ñ‚Ð¾ Ð²Ñ‹Ð´ÐµÐ»Ð¸Ð»Ð¾ÑÑŒ ÐºÑ€Ð°ÑÐ½Ñ‹Ð¼ Ñ†Ð²ÐµÑ‚Ð¾Ð¼, Ð° Ð²ÑÐµ Ñ‡Ñ‚Ð¾ Ð² asii Ñ‚Ð¾ Ð½Ðµ Ð¿Ð¾Ð´ÑÐ²ÐµÑ‡Ð¸Ð²Ð°Ð»Ð¾ÑÑŒ, Ð¸ Ñ„Ð»Ð°Ð³ Ð½Ð°ÑˆÐµÐ»ÑÑ Ð² Ð¼Ð¾Ð¼ÐµÐ½Ñ‚ :)\r\nÐ½Ð¸ÐºÐ°ÐºÐ¾Ð³Ð¾ Ð´Ð¸Ð·Ð°ÑÑÐ¼Ð±Ð»Ð¸Ð½Ð³Ð° Ð¸ Ð¿Ð¾Ð´Ð¾Ð±Ð½Ð¾Ð³Ð¾ :)\r\nÐ½Ð¾ Ñ‡ÐµÑ€ÐµÐ· ÐºÐ¾Ð¼Ð°Ð½Ð´Ñƒ Ð±Ñ‹Ð»Ð¾ ÐµÑÑ‚ÐµÑÑ‚Ð²ÐµÐ½Ð½Ð¾ ÐºÑ€ÑƒÑ‡Ðµ :)', 32, '2012-09-16 13:03:35'),
(27, 'Complaint', 'ÐŸÐ°ÑÐ¾Ð½Ñ‹ Ð¿Ð¾Ñ‡Ð¸Ð½Ð¸Ñ‚Ðµ ÑÐ°Ð¹Ñ‚Ñ‹!!!', 63, '2012-09-19 13:04:44'),
(28, 'Proposal', 'Ð¼Ð¾Ð¶ÐµÑ‚ ÑÐ´ÐµÐ»Ð°ÐµÑ‚Ðµ ÑƒÐ¶Ðµ ctf.keva.su?', 133, '2012-09-21 13:55:45'),
(29, '', '                                    ', 133, '2012-09-21 14:06:57'),
(30, '&lt;?&gt;', '                                           ', 133, '2012-09-21 14:15:43'),
(31, '&lt;script&gt; alert(&quot;REallY&quot;); &lt;/script&gt;', '                                                        ', 133, '2012-09-21 14:16:23');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Дамп данных таблицы `feedback_msg`
--

INSERT INTO `feedback_msg` (`id`, `feedback_id`, `msg`, `author`, `dt`) VALUES
(1, 25, 'hello', 23, '2012-09-12 00:48:37'),
(2, 25, 'hello2', 23, '2012-09-12 00:56:08'),
(3, 25, '', 23, '2012-09-12 01:26:16'),
(4, 25, '', 23, '2012-09-12 01:26:21'),
(5, 25, 'dl;skf;df', 23, '2012-09-12 01:27:33'),
(6, 25, 'ÐŸÑ€Ð¸Ð²ÐµÑ‚!', 23, '2012-09-12 01:30:55'),
(7, 25, 'ddd', 23, '2012-09-12 01:40:30'),
(8, 25, 'dsfd', 23, '2012-09-12 01:41:22'),
(9, 24, 'Ð˜ÑÐ¿Ñ€Ð°Ð²Ð¸Ð»! ÑÐ¿Ð°ÑÐ¸Ð±Ð¾!', 23, '2012-09-12 01:41:51'),
(10, 23, 'Ð½Ñƒ Ð¼Ð¾Ð¶Ð½Ð¾ Ð½Ð°Ð·Ð²Ð°Ñ‚ÑŒ ÑÑ‚Ð¾ live ?', 23, '2012-09-12 01:42:19'),
(11, 22, 'Ð·Ð°Ð´Ð°Ð½Ð¸Ðµ Ð¿Ð¾ÑÐ²Ð¸Ñ‚ÑŒÑÑ Ð¿Ð¾ÑÐ»Ðµ Ð½Ð°Ð±Ð¾Ñ€Ð° Ð¾ÐºÐ¾Ð»Ð¾ 200 Ð¾Ñ‡ÐºÐ¾Ð²', 23, '2012-09-12 01:42:44'),
(12, 20, 'ÑÐ¿Ð°ÑÐ¸Ð±Ð¾! ÑÑ‚Ð°Ñ€Ð°ÐµÐ¼ÑÑ!', 23, '2012-09-12 01:43:02'),
(13, 15, 'ÑÐ¿Ð°ÑÐ¸Ð±Ð¾! ÑÑ‚Ð°Ñ€Ð°ÐµÐ¼ÑÑ!', 23, '2012-09-12 01:43:18'),
(14, 11, 'Ð¿Ñ€Ð¾ÑÑ‚Ð¸Ñ‚Ðµ Ð¿Ð»Ð¾Ñ…Ð¾ Ñ€Ð°Ð±Ð¾Ñ‚Ð°Ð» Ñ„Ð¸Ð´Ð±ÐµÐº', 23, '2012-09-12 01:43:49'),
(15, 24, 'ÐœÐ¾Ð¶ÐµÑˆÑŒ ÐµÑ‰Ðµ ÑÐºÐ¸Ð´Ñ‹Ð²Ð°Ñ‚ÑŒ!', 23, '2012-09-12 01:53:30'),
(16, 26, 'Ð½Ð¾Ñ€Ð¼Ð°Ð»ÑŒÐ½Ð¾Ðµ Ñ€ÐµÑˆÐµÐ½Ð¸Ðµ! Ð¿Ñ€Ð°Ð²Ð´Ð° Ð¼Ñ‹ÑÐ»ÑŒ Ð±Ñ‹Ð»Ð¾ Ð¾ Ð¿ÐµÑ€ÐµÐ¿Ð¾Ð»Ð½ÐµÐ½Ð¸Ð¸ Ð²Ñ…Ð¾Ð´Ð½Ñ‹Ñ… Ð´Ð°Ð½Ð½Ñ‹Ñ…', 23, '2012-09-19 20:37:06'),
(17, 27, 'Ð° Ñ‡Ñ‚Ð¾ Ð½Ðµ Ñ‚Ð°Ðº? Ð²Ñ‹Ð¹Ñ‚Ð¸ Ð½Ðµ ÑƒÐ´Ð°ÐµÑ‚ÑÑ? ))) Ñ‚Ð°Ðº ÑÑ‚Ð¾ Ñ‚Ð°Ðº Ð·Ð°Ð´ÑƒÐ¼Ð°Ð½Ð¾! )))', 23, '2012-09-19 20:37:40'),
(18, 28, 'ÑÐ´ÐµÐ»Ð°ÐµÐ¼ ! Ð±Ð»Ð¸Ð½ (((', 23, '2012-09-23 15:38:36'),
(19, 29, 'Ð·Ð°ÑÐµÐ¼ Ð¿Ñ‹Ñ‚Ð°ÐµÐ¼ÑÑ Ð»Ð¾Ð¼Ð°Ñ‚ÑŒ?', 23, '2012-09-23 15:38:59'),
(20, 30, 'Ð·Ð°ÑÐµÐ¼ Ð¿Ñ‹Ñ‚Ð°ÐµÐ¼ÑÑ Ð»Ð¾Ð¼Ð°Ñ‚ÑŒ?', 23, '2012-09-23 15:39:02'),
(21, 31, 'Ð·Ð°ÑÐµÐ¼ Ð¿Ñ‹Ñ‚Ð°ÐµÐ¼ÑÑ Ð»Ð¾Ð¼Ð°Ñ‚ÑŒ?', 23, '2012-09-23 15:39:09'),
(22, 28, 'Ð³Ð¾Ñ‚Ð¾Ð²Ð¾', 23, '2012-09-23 22:18:29'),
(23, 31, 'Ð¿Ñ€Ð¾ÑÑ‚Ð¾ Ð±Ñ‹Ð»Ð¾ Ð¸Ð½Ñ‚ÐµÑ€ÐµÑÑ‚Ð½Ð¾:))', 133, '2012-09-24 01:05:45'),
(24, 31, 'Ð½Ðµ Ð½Ñƒ Ð²Ñ‹ Ð±Ð»Ð¸Ð½ Ð¿Ð¾Ñ‡Ð¸Ð½Ð¸Ð»Ð° ctf.keva.su, Ñ‚Ð¾Ñ‚ Ð¶Ðµ income Ñ‚Ð¾Ð»ÑŒÐºÐ¾ Ñ Ð´Ñ€ÑƒÐ³Ð¸Ð¼ Ñ„Ð¾Ð½Ð¾Ð¼:)', 133, '2012-09-24 01:07:11'),
(25, 31, 'Ð¸ Ð´Ñ€ÑƒÐ³Ð¾Ð¹ ÑÐ¿Ð¸ÑÐ¾Ðº )))', 23, '2012-09-24 04:13:15'),
(26, 21, 'ÐºÐ°ÐºÐ¾Ð¹ Ð½Ð¾Ð¼ÐµÑ€ Ð·Ð°Ð´Ð°Ð½Ð¸Ñ ?', 23, '2012-10-14 01:06:56');

-- --------------------------------------------------------

--
-- Структура таблицы `lvl`
--

CREATE TABLE IF NOT EXISTS `lvl` (
  `idlvl` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `minexp` int(10) unsigned NOT NULL,
  `maxexp` int(10) unsigned NOT NULL,
  `namelvl` varchar(45) NOT NULL,
  PRIMARY KEY (`idlvl`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `lvl`
--

INSERT INTO `lvl` (`idlvl`, `minexp`, `maxexp`, `namelvl`) VALUES
(1, 0, 100, 'новичок'),
(2, 100, 200, 'все еще новичок');

-- --------------------------------------------------------

--
-- Структура таблицы `quest`
--

CREATE TABLE IF NOT EXISTS `quest` (
  `idquest` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tema` varchar(128) CHARACTER SET utf8 NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `short_text` varchar(128) CHARACTER SET utf8 NOT NULL,
  `text` varchar(4048) CHARACTER SET utf8 NOT NULL,
  `answer` text CHARACTER SET utf8 NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `min_score` int(10) NOT NULL DEFAULT '0',
  `lvl` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idquest`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `quest`
--

INSERT INTO `quest` (`idquest`, `tema`, `name`, `short_text`, `text`, `answer`, `score`, `min_score`, `lvl`) VALUES
(1, 'ÐºÑ€Ð¸Ð¿Ñ‚Ð¾', 'Ð’ÑÐµ Ð¿Ñ€Ð¾ÑÑ‚Ð¾!', 'ÐŸÑ€Ð¾ÑÑ‚Ð¾Ðµ Ð¿Ñ€ÐµÐ¾Ð±Ñ€Ð°Ð·Ð¾Ð²Ð°Ð½Ð¸Ðµ Ð´Ð°Ð½Ð½Ñ‹Ñ… (Ð´Ð²Ð¾Ð¹Ð½Ð¾Ðµ)', 'Ð’Ð°Ð¼ Ð½ÐµÐ¾Ð±Ñ…Ð¾Ð´Ð¸Ð¼Ð¾ Ð¿ÐµÑ€ÐµÐ²ÐµÑÑ‚Ð¸ Ð½Ð° Ñ€ÑƒÑÑÐºÐ¸Ð¹ ÑÐ·Ñ‹Ðº Ñ‡Ñ‚Ð¾ Ñ‚ÑƒÑ‚ Ð½Ð°Ð¿Ð¸ÑÐ°Ð½Ð¾: Â«61 70 70 6C 65Â»\r\n[br][br]\r\nÐŸÐ¾Ð¶Ð°Ð»ÑƒÐ¹ÑÑ‚Ð° Ð¿Ð¾Ð»ÑƒÑ‡ÐµÐ½Ð½Ð¾Ðµ ÑÐ»Ð¾Ð²Ð¾ Ð½ÐµÐ¾Ð±Ñ…Ð¾Ð´Ð¸Ð¼Ð¾: \r\n[br][br]1. Ð¿Ð¾Ð»ÑƒÑ‡ÐµÐ½Ð½Ð¾Ðµ ÑÐ»Ð¾Ð²Ð¾ Ð´Ð¾Ð»Ð¶Ð½Ð¾ Ð±Ñ‹Ñ‚ÑŒ Ð² Ð½Ð¸Ð¶Ð½ÐµÐ¼ Ñ€ÐµÐ³Ð¸ÑÑ‚Ñ€Ðµ[br][br]\r\n2.Ð´Ð¾Ð±Ð°Ð²Ð¸Ñ‚ÑŒ Ñ‚Ð¾Ñ‡ÐµÐº ÑÑ‚Ð¾Ð»ÑŒÐºÐ¾ Ñ‡Ñ‚Ð¾ Ð±Ñ‹ Ð¾Ð±Ñ‰Ð°Ñ Ð´Ð»Ð¸Ð½Ð° ÑÐ¾ÑÑ‚Ð°Ð²Ð¸Ð»Ð° 16 ÑÐ¸Ð¼Ð²Ð¾Ð»Ð¾Ð².[br] ÐÐ°Ð¿Ñ€Ð¸Ð¼ÐµÑ€: &quot;Ð°Ñ€Ð±ÑƒÐ·..........&quot; [br]', '0Y/QsdC70L7QutC+Li4uLi4uLi4uLg==', 10, 0, 1),
(10, 'Crypro', 'Crypro-50', 'Crypro-50 try it', 'Was ist das?\r\n[code]\r\n---------------------------\r\nU2ltcGxlQ29kZUNvbnZlcnNpb24\r\n---------------------------\r\n[/code]\r\n', 'U2ltcGxlQ29kZUNvbnZlcnNpb24=', 50, 0, 0),
(11, 'Crypto', 'Crypto-100', 'Crypto-100 try next', '2d2ac19e453238aeb762088cb14ae395[br]\r\n9d24420082d450cb81caf59e2b173279[br]\r\na4704fd35f0308287f2937ba3eccf5fe[br]\r\nf1965a857bc285d26fe22023aa5ab50d[br]\r\nfa075a987059a1e8a1f6b8bba8e177b0[br]\r\n1. not space![br]\r\n2. First character must be in upper case![br]', 'VGhlcXVpY2ticm93bmZveA==', 100, 50, 0),
(12, 'Fuzzing', 'Fuzzing-50', 'Fuzzing-50', 'for linux:[br]\r\n[code]\r\nhttp://free-hack-quest.keva.suquests/fuzzing-50/fuzz_50\r\n[/code]\r\n[br]\r\nfor windows:[br]\r\n[code]\r\nhttp://free-hack-quest.keva.su/quests/fuzzing-50/fuzz_50.exe\r\n[/code]', 'NTllNGI2YjlmZjhmZTAyZGExOGE2MDI1ZmIzYzA1Yjc=', 50, 0, 0),
(13, 'Web-50', 'Web-50', 'it is very simple!', 'free-hack-quest.keva.su/quests/web-50', 'NjM5OTQwYWIwZTA0MjMxMzYxOTY5ZmFkNzE2ODUwYzg=', 50, 0, 0),
(14, 'Crypto', 'What do you think about this?', 'What do you think about this?', '[img]http://free-hack-quest.keva.su/quests/crypto-500/crypto500.jpg[/img]\r\n[br]\r\nÑ…Ð¸Ð½Ñ‚: Ð¾Ñ‚Ð²ÐµÑ‚ Ð½Ð° Ð°Ð½Ð³Ð»Ð¸ÑÐºÐ¾Ð¼(Ð½Ð°Ð·Ð²Ð°Ð½Ð¸Ðµ Ñ„Ð¸Ð»ÑŒÐ¼Ð°), Ñ Ð¿Ñ€Ð¾Ð±ÐµÐ»Ð°Ð¼Ð¸ Ð¸ Ð¿Ð¾ Ð¿Ñ€Ð°Ð²Ð¸Ð»Ð°Ð¼ Ð½Ð°Ð¿Ð¸ÑÐ°Ð½Ð¸Ñ Ð»ÑŽÐ±Ñ‹Ñ… Ð½Ð°Ð·Ð²Ð°Ð½Ð¸Ð¹', 'VGhlIEhpdGNoaGlrZXLigJlzIEd1aWRlIHRvIHRoZSBHYWxheHk=', 500, 200, 0),
(15, 'Flag?', 'Bank-500', 'Try to hack a bank!', 'http://free-hack-quest.keva.su/quests/bank-500/\r\n\r\nCheckout $1 000 000 to hackers!', 'OGE0YjNjZTViNTVkNTA4NDU5M2VjYzUxZjgyYTUyMTM=', 500, 100, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(767) NOT NULL,
  `password` text NOT NULL,
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `role` varchar(10) CHARACTER SET utf8 DEFAULT 'user',
  `nick` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `username` (`username`),
  KEY `FK_user_1` (`score`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=201 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`iduser`, `username`, `password`, `score`, `role`, `nick`) VALUES
(23, 'c2VhLWtnQHlhLnJ1', '7ccfb2501540fd79e453309991104278', 1260, 'admin', 'Blight Aberration'),
(24, 'Z29hbGtlZXBlcl9pQG1haWwucnU=', 'd175e2e5a06a904e1d97c643967a9902', 0, 'user', 'Mimiron'),
(25, 'Z29hbGtlZXBlcl9pMkBtYWlsLnJ1', 'dc9da6355b4928b0d8e11974dc457c26', 0, 'user', 'The Crone'),
(31, 'cC5hLnNvbG92ZXlAZ21haWwuY29t', '9855d4087550aaeb5a489a838c83aa22', 0, 'user', 'Highlord Bolvar Fordragon'),
(32, 'eWFyb3NsYXYubnVyQGdtYWlsLmNvbQ==', '0f921754629da7fcde99b11836c7017b', 210, 'user', 'Tyrande'),
(33, 'NmFwYTZhc2hAZ21haWwuY29t', 'fe8aebe3a2b8a1a87d9bf859a1b87172', 0, 'user', 'High Astromancer Solarian'),
(34, 'dmVyd29sZjAwN0BtYWlsLnJ1', '13957414f185f3b4ea3cd2e6faf599ef', 10, 'user', 'Felmyst'),
(35, 'bW9zc2thbGV2QGdtYWlsLmNvbQ==', 'b822534c7d6f3fbc10cf254d137295a5', 0, 'user', 'Magmadar'),
(36, 'c29zaWVibGFuQGdtYWlsLmNvbQ==', 'f919d7f12e4dfa69f1ed119276f39172', 10, 'user', 'Narrhok Steelbreaker'),
(37, 'c2VyZ2V5YmVsb3ZlQGdtYWlsLmNvbQ==', '2b05c9cd20b8e28d02b1b0d264e943f9', 660, 'admin', 'King Llane'),
(38, 'cW1hdHZleUBnbWFpbC5jb20=', '8f60f1205cd5d37dec8aef1b15fe1f4b', 10, 'user', 'Olm the Summoner'),
(39, 'a29uZG9yYnJuQGdtYWlsLmNvbQ==', 'a879736276a0db0fd905b36ba0aea0df', 760, 'admin', 'Thurg'),
(40, 'YWhyZWo3QG1haWwucnU=', '0c8b6a7285ec37d9af1f3afbd87fbb2a', 0, 'user', 'Shadow Trap'),
(41, 'QWxleGFuZGVydHNveTE5OTRAbGl2ZS5jb20=', '621b8f005ac119987c39875f978a9383', 60, 'user', 'Image of Thane Korth''azz'),
(42, 'QXBrOTNAbWFpbC5ydQ==', 'a5e6132e29336406d5133e6b0534e2d7', 0, 'user', 'Fire Bomb'),
(43, 'Y29uc3QxM0B2dG9tc2tlLnJ1', '5c0faa66270dbfa7bcf2266fa3356e80', 0, 'user', 'Madrigosa'),
(44, 'ZGVtQG1zLnR1c3VyLnJ1', '37227b615ab35ad15bbbefacbb8fbda3', 0, 'user', 'Cairne Bloodhoof'),
(45, 'ZGltYS1tdWtvdmtpbkBtYWlsLnJ1', '2df23e5980ba84001e84d2dd96e60180', 0, 'user', 'Hydross the Unstable'),
(46, 'ZGltb240aWs5Mzg5QG1haWwucnU=', '1350ea14c028460423827fe124751894', 160, 'user', 'Highlord Tirion Fordring'),
(47, 'ZGlzZWw5MmtrQGdtYWlsLmNvbQ==', '34a9e11473c5d1e5ff42740148ee1793', 0, 'user', 'Amani Lynx Spirit'),
(48, 'ZGlzZWw5MmtrQG1haWwucnU=', '4876444eff299b66dc7300d7495b7bec', 0, 'user', 'Olm the Summoner'),
(49, 'ZGl2ZV9uMGxsQG1haWwucnU=', '69bdd2860c60540fe160bebbf71ba3d2', 760, 'user', 'Moroes'),
(50, 'ZGl2ZV93b3JrQG1haWwucnU=', '92a3e6eecb4eba245815684bff5cc94a', 0, 'user', 'Kaz''rogal'),
(51, 'ZGxhdHZvaWhwaXNlbUBnbWFpbC5jb20=', '7e5da28088960fba30c3fd6c15962ba5', 0, 'user', 'Mimiron'),
(52, 'ZHlnYXJhQGdtYWlsLmNvbQ==', 'a00aa694da898e26e9339a35aa5ed560', 0, 'user', 'High Tinker Mekkatorque'),
(53, 'ZWF0aW5ncGVvcGxlaXNmdW4uZGFAZ21haWwuY29t', 'e4bc7533ea19f9eb13cb5248fb367053', 260, 'user', 'Kavina Grovesong'),
(54, 'ZXZnZXQwckBtYWlsLnJ1', 'a1f036a75e02a01d5dc2aa980deb0d71', 0, 'user', 'The Lich King'),
(55, 'ZXZpbGdhbWVyQHQtc2sucnU=', 'd044fa89f31f16a88f39217671df9b10', 0, 'user', 'Grand Widow Faerlina'),
(56, 'ZXhpbS5raXJpbGxAZ21haWwuY29t', 'cc910bb305d10a80cddea29bfa4dc145', 50, 'user', 'Balistoides'),
(57, 'Zm94LnVzZXIuM0BnbWFpbC5jb20=', '7863e9dba93197566b7133abd1713129', 260, 'user', 'Felmyst Visual'),
(58, 'Zm94XzNAbWFpbC5ydQ==', '14eb31fddef764a8c1f8519eb13638d0', 110, 'user', 'Gehennas'),
(60, 'aGl0czk0QG1haWwucnU=', '485462e176997282814ce91f9c836866', 0, 'user', 'The Lich King'),
(61, 'SUlBSEtAbWFpbDIwMDAucnU=', 'a48a9d92314fd987ac7f3be4a629366f', 0, 'user', 'High King Maulgar'),
(62, 'amNkM250MG5AZ21haWwuY29t', '3b3c98dec89fd34c4391834f4009521c', 0, 'user', 'Essence of Desire'),
(63, 'a2FybWlja29hbGE5MDRAZ21haWwuY29t', '16404e0edcd92334be8d872d2564f5e6', 160, 'user', 'Alexstrasza the Life-Binder'),
(64, 'a21hdG9yaW5AbWFpbC5ydQ==', '8188019c2ffaf9b12f00f91e18a201fe', 0, 'user', 'Professor Putricide'),
(65, 'a29sdDAyMTlAbWFpbC5ydQ==', 'a343f6eb0301bc71aff349dced47dfbd', 0, 'user', 'Lord Valthalak'),
(66, 'a29uZG9yYnJuQHlhbmRleC5ydQ==', 'a3c95f9d27bd8788fb3b9adc5f18af6e', 0, 'user', 'Warchief Blackhand UNUSED'),
(67, 'a290YW5kdmxhQGdtYWlsLmNvbQ==', '03c1912d6e3d1578cfb6314e88faf07c', 110, 'user', 'Colossus of Regal'),
(68, 'a290b2Zvc0BnbWFpbC5jb20=', '41198024297f8a22949f01820f85cad7', 10, 'user', 'Sara'),
(69, 'bGF5c25za29AZ21haWwuY29t', '41180f67650fc465ff1f2bb7e7f3d6ac', 0, 'user', 'Owen Test Creature'),
(70, 'bWFpbHRvX2Frb3N0QG1haWwucnU=', '0403579c7ce04876de268c00809a1d64', 10, 'user', 'Icy Blast'),
(71, 'bWFsbWFrczIzQHJhbWJsZXIucnU=', 'f3ec712bec524994b1cbeb4d7de4c0d6', 0, 'user', 'Maexxna'),
(72, 'bWlyZXhzaWx2ZXJAZ21haWwuY29t', '00f377d9f6187e355b358441f18995ce', 0, 'user', 'The Ebon Watcher'),
(73, 'TXIudmlwLnBydXNha292QG1haWwucnU=', '6004a56b797f95e406240493c5a5ad1b', 10, 'user', 'Shocuul'),
(74, 'bXJiNGVsQGhvdG1haWwuY29t', '9122e60e311846fd079146fc85a2a4f9', 0, 'user', 'Fandral Staghelm'),
(75, 'b2duZWxsaXMyQG1haWwucnU=', 'af9ce89264a52d5220a12f8100b17c24', 10, 'user', 'Gehennas'),
(76, 'b2xnYUB2ZXJzaGluaW5hLnJ1', 'cd730ab2ae83f4d1efad7d97ed052c3f', 0, 'user', 'Attumen the Huntsman'),
(77, 'T3ZlcmV0Y2hAaG90bWFpbC5jb20=', '2808de842dc03213ea065d76a78c47bf', 0, 'user', '[NOT USED] Neltharion'),
(78, 'cGVyc2VpZHNzdGFyZmFsbEBnbWFpbC5jb20=', '95761818f6160ea7a0e52f928ee55c3a', 60, 'user', 'The Lich King'),
(79, 'cHIwcmFpZGVyQG1haWwucnU=', 'ee98c4e675a9eb0d6187a1ef5d56a85e', 760, 'user', 'Thorim'),
(80, 'cHJpbXVzX2hhcmRAbWFpbC5ydQ==', '0ebe87e1cc58f3ed43aecb1228b67ec2', 0, 'user', 'Thorim'),
(81, 'cm9tYW4uZ29yb2tob3Zza3lAbGl2ZS5jb20=', 'c037e78e8094c404c6976c4664030c68', 0, 'user', 'Mother Shahraz'),
(82, 'c2hpbmthcmVua28ua0BnbWFpbC5jb20=', '546d036364eacafb3137bf39a500c641', 260, 'user', 'Halazzi'),
(83, 'c29iZXJOVEB5YW5kZXgucnU=', '1d7575d41682093e509120c81ea3e9c9', 10, 'user', 'Cairne Bloodhoof'),
(84, 'c3QuZGVtb25fNjY2QG1haWwucnU=', '169ac12b30a35a40a9eb2805a4b8b91d', 0, 'user', 'Leviathan Mk II'),
(85, 'dGFpbHMwN0BsaXZlLnJ1', '0273824ea88368aa1909e6e43b935f80', 210, 'user', 'General Zarithrian'),
(86, 'dGhlLmNyZWF0ZXlAZ21haWwuY29t', 'f97caf2eeb95c33f40b1d58f15035976', 0, 'user', 'Highlord Tirion Fordring'),
(87, 'dHcxdEBtYWlsLnJ1', '2fbacec86b836fc1953b373e8081000f', 0, 'user', 'Highlord Mograine Transform'),
(88, 'dmFsaWRjb205NEBtYWlsLnJ1', '698ee0ba7eea5eb23feff01a25c74895', 0, 'user', 'Thrall'),
(89, 'dml0YWxpai1lcmJha2hhZXZAeWFuZGV4LnJ1', 'a129abc2b92bb30c44cc5d597dc36342', 0, 'user', 'The Lich King'),
(90, 'dml0YWx5LnN1bWluQGdtYWlsLmNvbQ==', '4064edb5a559d304760a79c796298ead', 60, 'user', 'Doom Lord Kazzak'),
(91, 'd2FsZGVtYXIueWFuQGdtYWlsLmNvbQ==', '79755ef638571b88e52f83536c2d6c4d', 60, 'user', 'Lady Vashj'),
(92, 'eG11cm54QGdtYWlsLmNvbQ==', 'd96b94dcabcea3b377d5a301b33ab7e8', 210, 'user', 'Kael''thas Sunstrider'),
(93, 'WXJpa2tvd2FsZW5rb0BnbWFpbC5jb20=', '412dd962d26323a48bf8ce1e5f5c0295', 0, 'user', 'Qiraji Lieutenant General'),
(94, 'WXJpa2tvd0FsZW5rb0BtYWlsLnJ1', 'cde26fbce578e017af02fe3e0973e4c9', 0, 'user', 'Fel Reaver Netherstorm'),
(95, 'WmVtZWxpYTIxM0B5YW5kZXgucnU=', '37e6a2392baa919404b75489e520660e', 210, 'user', 'M''uru'),
(96, 'enVldi5wLnZAZ21haWwuY29t', '47fc3fb169b9cb67768d7a4619869553', 0, 'user', 'Baron Kazum'),
(97, 'dmVyc2hpbmluYS5vbHlhQGdtYWlsLmNvbQ==', 'dcd9bad865aa0021d41bb6d6b66b34b9', 0, 'user', '[UNUSED] The Lich King'),
(98, 'amNkM250MG5Ac2libWFpbC5jb20=', '2317d69ba17bdaffcb42524cff08960b', 260, 'user', 'Vol''jin'),
(99, 'YWxleC1tZWdpb25AbWFpbC5ydQ==', 'b74b2c0c219b7a6c61479103aa44a24a', 0, 'user', 'Runemaster Molgeim'),
(100, 'a2F0ZWNhdDM5QHJhbWJsZXIucnU=', '7ab3d0a1769d0202ec83324fa11e34e2', 0, 'user', 'Azgalor'),
(101, 'THl0dWsxQGdtYWlsLmNvbQ==', 'b2cb0a8650341d5888f927a57c56a814', 160, 'user', 'Thrall'),
(102, 'bW9vbmJpc2hvcEBob3RtYWlsLmNvbQ==', '742aaf0a705c448e7c39ba4397a64930', 10, 'user', 'Merithra of the Dream'),
(103, 'a3VsaXNoMTcuODlAbWFpbC5ydQ==', 'fcb93dd1162a211295457c032b274454', 10, 'user', 'Essence of Desire'),
(104, 'YWxleEB1a2hvdi5ydQ==', 'a7346eab2d5012a344daa67c7f582ee7', 0, 'user', 'Jan''alai'),
(105, 'd2hhdGZvckBzaWJtYWlsLmNvbQ==', 'b241725c2bd528de1f752d662a0f0ec8', 160, 'user', 'Sartharion'),
(106, 'emJvcmlzQHJhbWJsZXIucnU=', 'e22ff149e716dd15ae3a08dcd287bd43', 760, 'user', 'King Tor Visual'),
(107, 'b3htYXBAbWFpbC5ydQ==', 'd59ae7c0371e3654828d59c0c0f0a4f2', 0, 'user', 'Thorim'),
(108, 'dGFnZXI4N0Biay5ydQ==', 'c89cd4704c218bcebd7f7df3dcdf3ba0', 0, 'user', 'Madrigosa'),
(110, 'b3Nlbm55eUB5YW5kZXgucnU=', '75c5d65788126f3031059e98b0344733', 0, 'user', 'Spirit of Rhunok'),
(111, 'YW5nZWxpbmFyYUBtYWlsLnJ1', 'e849fc41e7c88f2636640f25e65b77a4', 0, 'user', 'Highlord Tirion Fordring'),
(112, 'YW5nZWxpbmFyYUB5YW5kZXgucnU=', '48237a1ac18ebfd2cdafb89a32545f80', 0, 'user', 'Prince Keleseth'),
(113, 'dW5yZWFsbnZAZ21haWwuY29t', '106ed373a674d731354b3d07a85a77a2', 0, 'user', 'Spirit of Blaumeux'),
(114, 'c2tpcHBlcmV4QGdtYWlsLmNvbQ==', '81f1f120cc4e166a3b315c87e895d807', 0, 'user', 'Lady Malande'),
(115, 'emxveV9keWFka2FAbWFpbC5ydQ==', '04e1efb17510acd7553643e4c55f7dc4', 0, 'user', 'Gathios the Shatterer'),
(116, 'ZS5hLnBla2Fyc2tpaEBnbWFpbC5jb20=', '50f668904b1d8d6d80f31735c6095c61', 0, 'user', 'Kalecgos'),
(117, 'YWxleGFuZHJiZXJrdXRAc2libWFpbC5jb20=', '3a6c817dc15f363c0ce4ecbd7c367e53', 210, 'user', 'Anthar Forgemender'),
(118, 'bnVkZWxhMjJAeWFob28uY29t', '1ff3c6bfaaa3bf2e0b76c32e28c5cf14', 210, 'user', 'Acanthurus'),
(119, 'b250aGVzZWxmQHlhbmRleC5ydQ==', 'a849a0d1b47bd07fa43949a7b3b4d97f', 60, 'user', 'Malygos'),
(120, 'YS5uLmJhcmtoYXRvdkBnbWFpbC5jb20=', 'c9583c3e24de122a0ba08fe3d0c48f11', 210, 'user', 'Lord Kri'),
(121, 'ZnJheGVyQG1haWwucnU=', 'f91b1c84af187699a79fb0a49c08b0b5', 60, 'user', 'King Varian Wrynn'),
(122, 'cmlvc2xlYmVkQGdtYWlsLmNvbQ==', '0c65ce5709a4a8eab2aef774fa5ad3f5', 110, 'user', 'Unkillable Test Dummy 73 Warrior'),
(123, 'c296aW5vdmFfaXJpbmFAbWFpbC5ydQ==', 'c975ff2532950ee1cf95e15be3b20be2', 0, 'user', 'Lord Tirion Fordring'),
(124, 'amFtZXNtYXJpYXJ0aUBtYWlsLnJ1', 'f37e9a9a234d92dd80ff56fa37496e6f', 50, 'user', 'The Skybreaker'),
(125, 'Z3JlZW5zdW5Ac2libWFpbC5jb20=', '4ac7d8d5551f8b0bd493a2fd3fecfb29', 0, 'user', 'Anachronos Dragon Form'),
(127, 'b2xkcHJvZ2VyQGdtYWlsLmNvbQ==', '931322b08414a9d5f013d928cd05426f', 0, 'user', 'Qiraji Lieutenant General'),
(128, 'bWFtQGFzZGFzZC5ydQ==', 'a097b99bf0f3834acb8f8d47365b9976', 0, 'user', 'Dream Fog'),
(130, 'bW1hbW1AYXNkYXNkLnJ1', 'a6b1de7a286ebe6e78b0dd83e5840e6d', 0, 'user', 'Baine Bloodhoof'),
(131, 'bWF4MTMwNDE5OTNAbWFpbC5ydQ==', 'a40c40f044f4497c079480a2f9f05fd3', 160, 'user', 'Veras Darkshadow'),
(132, 'cGFrMjQxQG1haWwucnU=', '0185c8c9d79fad0e0cef2d16d25306a1', 10, 'user', 'Princess Huhuran'),
(133, 'eW93d2kwMEBnbWFpbC5jb20=', '38bc8d43d437ae43488d6f517b0a2dbc', 260, 'user', 'Unkillable Test Dummy 73 Paladin'),
(135, 'ZG92emhlbmtvLm8uaUBtYWlsLnJ1', '4c6e1db933852c5e00d69345dcee11ae', 0, 'user', 'Morgan Test'),
(138, 'RmVvbWF0YXJAbWFpbC5ydQ==', '7e307c02de73ec2016b4f3cc9748abc9', 0, 'user', 'Razorscale'),
(144, 'Zy5taXNoZWxsQGdtYWlsLmNvbQ==', 'fabce06af921fca91258a0bad19cd158', 110, 'user', 'Doom Lord Kazzak'),
(145, 'YW50b2hhMjAzMEBpbmJveC5ydQ==', '4c7189c89dc35cdaebcadf19bf9395ef', 0, 'user', 'Shade of Akama'),
(147, 'bHl0dWsxQGdtYWlsLmNvbQ==', 'e48c90939907019958128bd1f216aa47', 0, 'user', 'Highlord Darion Mograine'),
(148, 'a29zdHlhMTJfOTFAbWFpbC5ydQ==', '5dac1dc4446f216be6801becd27d3c9d', 0, 'user', 'Gul''dan'),
(149, 'Z29yb2tob3Zza3lAenNucHouY29t', 'fae7a48fad10428068f594742546639a', 0, 'user', 'Image of Maexxna'),
(152, 'ZGRkQGRkZC5ydQ==', 'e7847b28f9dd908bc68d76de8130f725', 0, 'user', 'Lord Jaraxxus'),
(153, 'ZGRkQGRkZDEucnU=', 'aae269ca5a7610416034af45f83b0b52', 0, 'user', 'Icehowl'),
(154, 'dXNlcl8zNTdAbWFpbC5ydQ==', '0bc5ecb7473584918878e072943612c6', 60, 'user', 'Bear Spirit Transform Visual'),
(155, 'YW50b2hhMjAzMEBzaWJtYWlsLmNvbQ==', '11a00ac2291824acc8189c1f662ad40a', 0, 'user', 'Prince Malchezaar'),
(156, 'UnVzaC52ckBnbWFpbC5jb20=', '73e16405f49cde6e234447aefa5258d6', 0, 'user', 'Grand Apothecary Putress'),
(157, 'c2VhLWtnQHlhMS5ydQ==', '4daa34d015d33d1777386b90396ea100', 0, 'user', 'Void Reaver'),
(164, 'c2VhLWtnQHlhMy5ydQ==', '7ccee6d47e9bff1fe0d12afb8435c39f', 0, 'user', 'Zul''jin'),
(165, 'c2VhLWtnQHlhNS5ydQ==', 'bc660fbaa1a2ade11d968efb4f9c6644', 0, 'user', 'Emeriss'),
(166, 'c2VhLWtnQHlhNi5ydQ==', '2a789d79db8dc936bb77ccb9fb7bf4ad', 0, 'user', 'Illidan Stormrage'),
(167, 'c2VhLWtnQHlhNy5ydQ==', '64c4ddb2071e0daa573c956db2372e15', 0, 'user', 'Reliquary of the Lost'),
(168, 'c2VhLWtnQHlhOC5ydQ==', 'cde88a33376774f7393a720a299f764f', 0, 'user', 'Lethon'),
(169, 'c2VhLWtnQHlhOS5ydQ==', 'e1f06d3ea1dc8476076580a1bab41355', 0, 'user', 'Baelnor Lightbearer'),
(170, 'c2VhLWtkZGdAeWEucnU=', '1abab7cd27007b3730705674efb9e740', 0, 'user', 'Prince Arthas'),
(171, 'c2VhLWtkZGdAeWFkLnJ1', '33637afa196be3b21e5f1c0b7039df61', 0, 'user', 'Jan''alai'),
(172, 'c2VhLWtkc0B5YS5ydQ==', 'deb266a738b05356d1ded485cc91bce4', 0, 'user', 'Baltharus the Warborn'),
(173, 'c2VhLWtkc0B5YTEwLnJ1', 'f3a21debe45c98cd080539dfe54455a9', 0, 'user', 'Lady Sylvanas Windrunner'),
(174, 'c2VhLWtkZGRqQHlhLnJ1', '822b3a927599534c2f0508ec7fb78320', 0, 'user', 'Vol''jin'),
(176, 'c2VhLWtnQHQtc2sucnU=', '8ff1081e494e3b4009a8e930f60bc3ba', 0, 'user', 'Vem'),
(177, 'c2VhLWtnQHlhZi5ydQ==', '4f1f82cbfa7001b04f536496724d2404', 0, 'user', 'Elder Brightleaf'),
(178, 'c2VhLWtnQHlhay5ydQ==', '8a0780e49aea7f8097995e40e9e3be28', 0, 'user', 'Vesperon'),
(179, 'c2VhLWtnQHlheS5ydQ==', '3c566a744923b21d70631991828ad8db', 0, 'user', 'The Lich King'),
(180, 'c2VhLWtnQHlhZS5ydQ==', '539fd8eb2859ec734577d04ea6901b7c', 0, 'user', 'Ysondre'),
(181, 'c2VhLWtnQHlhcy5ydQ==', '9e2340a62d530953eeb3e08748914bc4', 0, 'user', 'Baron Rivendare'),
(182, 'c2VhLWtnQHlhdC5ydQ==', '056e590fbacb1326248d616a693f1840', 0, 'user', 'Reinforced Training Dummy'),
(183, 'c2VhLWtnQHlhcS5ydQ==', '1d84725a71adda16bba695a2d6be4a4f', 0, 'user', 'Kael''thas Sunstrider'),
(184, 'c2VhLWtnQHlhdy5ydQ==', 'db2d65c8c885018aac06c634d693eac1', 0, 'user', 'Left Arm'),
(185, 'c2VhLWtnQHlhci5ydQ==', 'c43827a91942f2f8c5c52418fd6f84fd', 0, 'user', 'King Haldor'),
(186, 'c2VhLWtnQHlhaS5ydQ==', 'dc9f586d91285480f5040a7df25a02fc', 0, 'user', 'Hydross the Unstable'),
(187, 'c2VhLWtnQHNpYm1haWwuY29t', '2a1f404b20dc4cd1660f23c343565ce8', 0, 'user', 'High Marshal Whirlaxis'),
(188, 'a3J1Y2hrb2ZmLmtAZ21haWwuY29t', 'adb6b42126a8e185538c734394c566f3', 110, 'user', 'The Etymidian'),
(189, 'amZsbWFpbEB5YW5kZXgucnU=', 'db383ba0d2786eac34cc7d095d2b7c9a', 0, 'user', 'Akil''zon'),
(190, 'SXN0bGV2c2hpaUBtYWlsLnJ1', '881613c986ba8f0be86f64b1a71c4821', 0, 'user', 'Akali'),
(191, 'aW52ZXJzZWRAbWFpbC5ydQ==', '8257d6df4731a4623d982cfeae57607a', 10, 'user', 'Auriaya'),
(192, 'ZGVmb3JtZXJAbWFpbC5ydQ==', '6470aa0fcd6f59db25f993b5f15ea3ad', 0, 'user', 'Kologarn'),
(193, 'cGFsYW4xN3RlZXJAZ21haWwuY29t', '858fe4fbfb0ff41b476ce7c9f29f2b5f', 0, 'user', 'Heigan the Unclean'),
(194, 'c3J2LWhhcmRAbWFpbC5ydQ==', '8a29a2efca77c75b03757de8a72bff63', 0, 'user', 'Vol''jin'),
(195, 'Z0BnLnJ1', 'e2c387bd8b53f39477e319f4b3ba6fbe', 0, 'user', 'The Ebon Watcher'),
(196, 'c2hhYmx5YS15dkBtYWlsLnJ1', '4e1dc4232f8eb67549ef8b58e67d094f', 110, 'user', 'Shadow of Leotheras'),
(197, 'YS51LnRva3JhdGllQGdtYWlsLmNvbQ==', '92487ec719295d72035f6d81adf9fa9e', 0, 'user', 'Ooze Covered Tentacle'),
(198, 'ZGVrb25peEBnbWFpbC5jb20=', '7ae0d1656344d9f346c4140f40395db6', 0, 'user', 'Vol''jin'),
(199, 'b2xlZ2F0YXJAeWFuZGV4LnJ1', '148ee32830b2960c519121458f5e806f', 10, 'user', 'High Tinker Mekkatorque'),
(200, 'dnNlLnByb2R5bWFub0BnbWFpbC5jb20=', '06dcee882179984a26d1e7e2d50d52cf', 260, 'user', 'Owen Test Creature');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `userquest`
--

INSERT INTO `userquest` (`iduser`, `idquest`, `stopdate`, `startdate`) VALUES
(9, 3, '2012-07-25 17:45:30', '2012-07-25 17:43:34'),
(9, 2, '0000-00-00 00:00:00', '2012-07-25 18:18:30'),
(11, 3, '2012-07-27 21:05:33', '2012-07-27 21:05:24'),
(9, 1, '2012-07-28 16:41:29', '2012-07-28 16:41:03'),
(12, 1, '2012-09-07 21:10:49', '2012-09-07 21:10:23'),
(12, 10, '2012-09-08 20:23:54', '2012-09-08 20:19:33'),
(12, 11, '2012-09-08 20:30:54', '2012-09-08 20:30:49'),
(23, 10, '2012-09-08 20:36:09', '2012-09-08 20:35:56'),
(23, 11, '2012-09-08 20:39:56', '2012-09-08 20:39:50'),
(23, 1, '2012-09-08 20:40:10', '2012-09-08 20:40:05'),
(23, 12, '2012-09-08 20:51:30', '2012-09-08 20:51:26'),
(31, 10, '0000-00-00 00:00:00', '2012-09-10 00:39:36'),
(32, 1, '2012-09-10 23:57:44', '2012-09-10 02:42:34'),
(32, 10, '2012-09-10 02:57:09', '2012-09-10 02:57:05'),
(32, 11, '2012-09-11 00:01:35', '2012-09-10 03:09:50'),
(32, 12, '2012-09-15 22:04:05', '2012-09-10 06:06:17'),
(33, 1, '0000-00-00 00:00:00', '2012-09-10 12:47:27'),
(34, 1, '2012-09-11 17:04:29', '2012-09-10 15:23:42'),
(37, 13, '2012-09-10 16:47:49', '2012-09-10 16:47:46'),
(37, 1, '2012-09-10 22:09:47', '2012-09-10 16:51:39'),
(37, 12, '2012-09-10 17:58:16', '2012-09-10 17:58:14'),
(37, 10, '2012-09-10 18:05:43', '2012-09-10 18:05:42'),
(38, 1, '2012-09-10 22:40:31', '2012-09-10 22:09:03'),
(39, 1, '2012-09-10 22:17:55', '2012-09-10 22:16:51'),
(39, 13, '2012-09-18 16:19:33', '2012-09-10 22:18:05'),
(39, 10, '2012-09-10 22:22:25', '2012-09-10 22:21:54'),
(39, 11, '2012-09-10 22:23:58', '2012-09-10 22:22:31'),
(54, 1, '0000-00-00 00:00:00', '2012-09-10 22:38:21'),
(37, 11, '0000-00-00 00:00:00', '2012-09-10 22:57:55'),
(23, 13, '2012-09-10 22:59:40', '2012-09-10 22:59:13'),
(53, 1, '2012-09-10 23:05:08', '2012-09-10 23:03:01'),
(53, 13, '2012-09-15 19:37:44', '2012-09-10 23:08:25'),
(67, 1, '2012-09-10 23:15:47', '2012-09-10 23:14:12'),
(67, 13, '0000-00-00 00:00:00', '2012-09-10 23:17:12'),
(67, 10, '2012-09-10 23:17:25', '2012-09-10 23:17:23'),
(36, 1, '2012-09-10 23:24:51', '2012-09-10 23:20:06'),
(71, 1, '0000-00-00 00:00:00', '2012-09-10 23:21:35'),
(89, 1, '0000-00-00 00:00:00', '2012-09-10 23:25:56'),
(89, 12, '0000-00-00 00:00:00', '2012-09-10 23:26:20'),
(36, 13, '0000-00-00 00:00:00', '2012-09-10 23:26:54'),
(89, 10, '0000-00-00 00:00:00', '2012-09-10 23:28:05'),
(53, 10, '2012-09-10 23:32:58', '2012-09-10 23:32:55'),
(67, 12, '2012-09-10 23:36:39', '2012-09-10 23:36:36'),
(53, 11, '2012-09-10 23:36:54', '2012-09-10 23:36:52'),
(53, 12, '2012-09-10 23:41:01', '2012-09-10 23:37:53'),
(67, 11, '0000-00-00 00:00:00', '2012-09-10 23:39:21'),
(90, 1, '2012-09-10 23:52:02', '2012-09-10 23:51:46'),
(90, 12, '2012-09-10 23:59:45', '2012-09-10 23:59:42'),
(56, 1, '0000-00-00 00:00:00', '2012-09-11 00:08:35'),
(49, 1, '2012-09-11 00:10:57', '2012-09-11 00:10:04'),
(90, 11, '0000-00-00 00:00:00', '2012-09-11 00:16:59'),
(78, 13, '0000-00-00 00:00:00', '2012-09-11 00:24:46'),
(49, 13, '2012-09-11 03:04:59', '2012-09-11 00:29:15'),
(39, 12, '2012-09-11 00:34:20', '2012-09-11 00:32:12'),
(97, 1, '0000-00-00 00:00:00', '2012-09-11 00:46:27'),
(49, 12, '2012-09-11 00:59:49', '2012-09-11 00:59:43'),
(78, 1, '2012-09-11 01:09:33', '2012-09-11 01:08:30'),
(49, 11, '2012-09-11 01:25:29', '2012-09-11 01:11:39'),
(49, 10, '2012-09-11 01:26:21', '2012-09-11 01:26:15'),
(32, 13, '0000-00-00 00:00:00', '2012-09-11 01:43:50'),
(23, 14, '2012-09-11 01:58:00', '2012-09-11 01:54:11'),
(85, 1, '2012-09-11 07:51:22', '2012-09-11 07:50:56'),
(85, 12, '2012-09-11 08:03:54', '2012-09-11 08:03:19'),
(85, 10, '2012-09-11 08:05:53', '2012-09-11 08:05:51'),
(85, 11, '2012-09-11 12:33:48', '2012-09-11 08:08:55'),
(98, 1, '2012-09-11 10:51:05', '2012-09-11 10:50:01'),
(98, 10, '2012-09-11 10:53:52', '2012-09-11 10:53:50'),
(98, 11, '2012-09-11 11:00:19', '2012-09-11 10:58:48'),
(98, 13, '2012-09-11 11:39:22', '2012-09-11 11:39:21'),
(101, 1, '2012-09-11 12:15:57', '2012-09-11 11:51:07'),
(63, 1, '2012-09-11 12:15:37', '2012-09-11 11:51:08'),
(92, 1, '2012-09-11 12:29:49', '2012-09-11 12:26:57'),
(98, 12, '2012-09-11 12:28:24', '2012-09-11 12:28:22'),
(92, 13, '0000-00-00 00:00:00', '2012-09-11 12:29:59'),
(92, 10, '2012-09-11 12:39:30', '2012-09-11 12:39:23'),
(92, 12, '2012-09-11 12:43:05', '2012-09-11 12:42:15'),
(70, 1, '2012-09-12 02:07:46', '2012-09-11 12:47:01'),
(63, 13, '0000-00-00 00:00:00', '2012-09-11 12:53:35'),
(98, 14, '0000-00-00 00:00:00', '2012-09-11 13:05:22'),
(92, 11, '2012-09-11 13:30:08', '2012-09-11 13:23:26'),
(102, 1, '2012-09-11 13:23:57', '2012-09-11 13:23:44'),
(91, 1, '2012-09-11 14:00:57', '2012-09-11 14:00:18'),
(91, 13, '0000-00-00 00:00:00', '2012-09-11 14:01:49'),
(103, 1, '2012-09-11 15:38:34', '2012-09-11 15:38:15'),
(92, 14, '0000-00-00 00:00:00', '2012-09-11 15:43:12'),
(105, 1, '2012-09-11 16:30:02', '2012-09-11 16:28:45'),
(37, 15, '2012-09-11 16:33:42', '2012-09-11 16:33:21'),
(32, 15, '0000-00-00 00:00:00', '2012-09-11 16:36:14'),
(46, 1, '2012-09-11 17:17:57', '2012-09-11 16:42:00'),
(46, 12, '0000-00-00 00:00:00', '2012-09-11 16:48:26'),
(105, 13, '0000-00-00 00:00:00', '2012-09-11 16:59:07'),
(34, 13, '0000-00-00 00:00:00', '2012-09-11 17:04:44'),
(75, 1, '2012-09-11 17:07:22', '2012-09-11 17:06:48'),
(37, 14, '0000-00-00 00:00:00', '2012-09-11 17:41:32'),
(85, 14, '0000-00-00 00:00:00', '2012-09-11 17:54:40'),
(46, 13, '0000-00-00 00:00:00', '2012-09-11 18:05:33'),
(74, 13, '0000-00-00 00:00:00', '2012-09-11 18:30:28'),
(105, 10, '2012-09-11 19:34:20', '2012-09-11 19:34:01'),
(105, 11, '2012-09-11 20:19:46', '2012-09-11 19:52:58'),
(46, 10, '2012-09-11 21:03:46', '2012-09-11 20:49:37'),
(46, 11, '2012-09-11 22:35:33', '2012-09-11 21:05:26'),
(105, 15, '0000-00-00 00:00:00', '2012-09-11 22:38:52'),
(46, 15, '0000-00-00 00:00:00', '2012-09-11 22:39:20'),
(23, 15, '2012-09-12 02:33:07', '2012-09-11 23:34:12'),
(49, 15, '2012-09-15 21:47:48', '2012-09-11 23:49:58'),
(57, 1, '2012-09-12 01:24:41', '2012-09-12 01:24:09'),
(57, 10, '2012-09-12 01:26:53', '2012-09-12 01:26:52'),
(57, 13, '2012-09-15 22:29:06', '2012-09-12 01:34:06'),
(34, 10, '0000-00-00 00:00:00', '2012-09-12 01:49:26'),
(83, 1, '2012-09-12 01:59:38', '2012-09-12 01:57:56'),
(70, 13, '0000-00-00 00:00:00', '2012-09-12 02:07:53'),
(57, 11, '2012-09-12 02:41:14', '2012-09-12 02:18:21'),
(83, 13, '0000-00-00 00:00:00', '2012-09-12 02:27:40'),
(73, 1, '2012-09-12 02:49:22', '2012-09-12 02:47:36'),
(106, 1, '2012-09-12 09:53:44', '2012-09-12 09:53:19'),
(67, 15, '0000-00-00 00:00:00', '2012-09-12 10:21:30'),
(78, 12, '2012-09-12 10:34:40', '2012-09-12 10:33:28'),
(106, 10, '2012-09-12 11:16:43', '2012-09-12 10:57:27'),
(106, 11, '2012-09-12 16:04:52', '2012-09-12 11:34:13'),
(106, 12, '2012-09-12 12:17:46', '2012-09-12 11:57:20'),
(106, 13, '2012-09-24 15:41:59', '2012-09-12 12:11:33'),
(106, 15, '0000-00-00 00:00:00', '2012-09-12 12:54:14'),
(83, 10, '0000-00-00 00:00:00', '2012-09-12 13:26:29'),
(91, 10, '2012-09-21 11:32:35', '2012-09-12 15:44:42'),
(106, 14, '2012-09-24 15:51:26', '2012-09-12 16:33:20'),
(57, 12, '2012-09-12 20:16:31', '2012-09-12 20:14:03'),
(59, 1, '2012-09-12 20:31:33', '2012-09-12 20:20:53'),
(59, 13, '2012-09-15 22:16:22', '2012-09-12 20:23:24'),
(113, 10, '0000-00-00 00:00:00', '2012-09-12 20:57:54'),
(59, 10, '2012-09-15 22:22:22', '2012-09-12 21:07:21'),
(41, 10, '0000-00-00 00:00:00', '2012-09-12 22:14:48'),
(41, 1, '2012-09-12 22:24:05', '2012-09-12 22:23:40'),
(53, 14, '0000-00-00 00:00:00', '2012-09-13 01:15:13'),
(112, 1, '0000-00-00 00:00:00', '2012-09-13 12:04:43'),
(47, 1, '0000-00-00 00:00:00', '2012-09-13 19:37:11'),
(47, 13, '0000-00-00 00:00:00', '2012-09-13 19:44:29'),
(117, 1, '2012-09-13 21:32:59', '2012-09-13 21:08:11'),
(117, 10, '2012-09-13 21:10:03', '2012-09-13 21:09:03'),
(117, 11, '2012-09-13 22:23:47', '2012-09-13 21:11:33'),
(117, 12, '2012-09-13 23:17:37', '2012-09-13 21:14:25'),
(118, 1, '2012-09-13 21:18:55', '2012-09-13 21:16:39'),
(23, 16, '2012-09-13 21:17:21', '2012-09-13 21:16:59'),
(118, 13, '0000-00-00 00:00:00', '2012-09-13 21:20:50'),
(118, 12, '2012-09-13 23:13:44', '2012-09-13 21:24:46'),
(117, 13, '0000-00-00 00:00:00', '2012-09-13 21:28:13'),
(118, 10, '2012-09-13 21:35:30', '2012-09-13 21:34:13'),
(119, 1, '2012-09-19 11:25:48', '2012-09-13 21:45:30'),
(118, 11, '2012-09-13 22:59:41', '2012-09-13 21:46:23'),
(118, 14, '0000-00-00 00:00:00', '2012-09-13 23:17:16'),
(119, 10, '0000-00-00 00:00:00', '2012-09-13 23:20:27'),
(118, 15, '0000-00-00 00:00:00', '2012-09-13 23:24:14'),
(47, 10, '0000-00-00 00:00:00', '2012-09-14 17:57:07'),
(95, 1, '2012-09-14 18:48:21', '2012-09-14 18:37:03'),
(120, 1, '2012-09-14 18:45:40', '2012-09-14 18:41:43'),
(120, 13, '0000-00-00 00:00:00', '2012-09-14 18:46:10'),
(95, 13, '0000-00-00 00:00:00', '2012-09-14 18:48:32'),
(95, 12, '2012-09-14 19:31:15', '2012-09-14 19:11:23'),
(120, 12, '2012-09-14 19:28:40', '2012-09-14 19:27:50'),
(120, 11, '2012-09-14 19:35:38', '2012-09-14 19:30:12'),
(95, 11, '2012-09-14 19:36:06', '2012-09-14 19:31:45'),
(120, 10, '2012-09-14 19:37:40', '2012-09-14 19:37:22'),
(95, 10, '2012-09-14 19:40:03', '2012-09-14 19:38:13'),
(120, 15, '0000-00-00 00:00:00', '2012-09-14 19:41:25'),
(120, 14, '0000-00-00 00:00:00', '2012-09-14 19:51:01'),
(95, 14, '0000-00-00 00:00:00', '2012-09-14 19:51:27'),
(95, 15, '0000-00-00 00:00:00', '2012-09-14 19:53:39'),
(79, 13, '2012-09-15 14:29:27', '2012-09-14 19:59:19'),
(121, 13, '2012-09-15 17:46:10', '2012-09-14 20:42:19'),
(121, 1, '2012-09-15 18:05:05', '2012-09-14 20:53:58'),
(122, 10, '2012-09-14 21:21:54', '2012-09-14 21:21:51'),
(122, 12, '2012-09-14 21:59:38', '2012-09-14 21:58:10'),
(82, 10, '2012-09-15 13:47:52', '2012-09-14 22:42:58'),
(122, 11, '0000-00-00 00:00:00', '2012-09-14 22:44:24'),
(122, 1, '2012-09-14 22:56:48', '2012-09-14 22:56:22'),
(107, 1, '0000-00-00 00:00:00', '2012-09-15 00:16:02'),
(124, 13, '0000-00-00 00:00:00', '2012-09-15 13:36:31'),
(124, 1, '0000-00-00 00:00:00', '2012-09-15 13:41:38'),
(74, 1, '0000-00-00 00:00:00', '2012-09-15 13:43:28'),
(82, 1, '2012-09-15 13:52:20', '2012-09-15 13:51:54'),
(79, 10, '2012-09-15 13:54:23', '2012-09-15 13:54:19'),
(82, 12, '2012-09-15 13:58:49', '2012-09-15 13:55:05'),
(82, 13, '2012-09-15 14:08:08', '2012-09-15 14:00:40'),
(79, 1, '2012-09-15 14:07:10', '2012-09-15 14:06:22'),
(131, 12, '2012-09-15 14:12:42', '2012-09-15 14:12:02'),
(82, 11, '2012-09-15 14:17:48', '2012-09-15 14:12:14'),
(131, 1, '2012-09-15 14:17:54', '2012-09-15 14:13:21'),
(132, 1, '2012-09-15 14:17:27', '2012-09-15 14:15:14'),
(79, 11, '2012-09-15 14:26:29', '2012-09-15 14:15:42'),
(131, 11, '2012-09-15 14:27:20', '2012-09-15 14:18:19'),
(131, 10, '0000-00-00 00:00:00', '2012-09-15 14:27:58'),
(131, 13, '0000-00-00 00:00:00', '2012-09-15 14:30:48'),
(133, 1, '2012-09-15 16:23:03', '2012-09-15 16:22:48'),
(133, 10, '2012-09-15 16:24:21', '2012-09-15 16:24:17'),
(133, 13, '2012-09-15 16:27:08', '2012-09-15 16:25:02'),
(133, 12, '2012-09-15 16:37:04', '2012-09-15 16:37:00'),
(133, 11, '2012-09-15 16:37:58', '2012-09-15 16:37:16'),
(133, 14, '0000-00-00 00:00:00', '2012-09-15 16:45:38'),
(63, 10, '2012-09-15 21:36:56', '2012-09-15 17:51:26'),
(101, 10, '2012-09-15 18:16:33', '2012-09-15 17:56:13'),
(101, 11, '2012-09-15 19:04:03', '2012-09-15 18:34:02'),
(79, 12, '2012-09-15 21:04:37', '2012-09-15 21:04:34'),
(63, 11, '2012-09-15 21:38:18', '2012-09-15 21:38:02'),
(79, 15, '2012-09-15 23:03:23', '2012-09-15 22:35:53'),
(49, 14, '0000-00-00 00:00:00', '2012-09-15 22:50:15'),
(79, 14, '0000-00-00 00:00:00', '2012-09-16 00:07:39'),
(57, 14, '0000-00-00 00:00:00', '2012-09-16 00:18:09'),
(58, 1, '2012-09-16 01:19:47', '2012-09-16 01:18:40'),
(58, 10, '2012-09-16 01:22:41', '2012-09-16 01:22:39'),
(58, 12, '2012-09-16 01:29:00', '2012-09-16 01:28:57'),
(68, 1, '2012-09-16 02:43:35', '2012-09-16 02:42:56'),
(58, 13, '0000-00-00 00:00:00', '2012-09-16 13:38:55'),
(138, 13, '0000-00-00 00:00:00', '2012-09-18 05:45:08'),
(39, 14, '2012-09-18 16:21:15', '2012-09-18 16:20:46'),
(39, 15, '0000-00-00 00:00:00', '2012-09-18 16:22:33'),
(119, 12, '2012-09-19 21:28:39', '2012-09-19 12:25:55'),
(41, 12, '2012-09-19 17:20:28', '2012-09-19 17:20:16'),
(41, 13, '0000-00-00 00:00:00', '2012-09-19 17:20:46'),
(41, 11, '0000-00-00 00:00:00', '2012-09-19 17:20:51'),
(119, 13, '0000-00-00 00:00:00', '2012-09-19 23:06:04'),
(154, 1, '2012-09-20 20:13:57', '2012-09-20 19:47:31'),
(154, 13, '0000-00-00 00:00:00', '2012-09-20 19:52:01'),
(154, 12, '2012-09-25 22:00:25', '2012-09-20 20:22:29'),
(155, 1, '0000-00-00 00:00:00', '2012-09-20 23:55:53'),
(149, 13, '0000-00-00 00:00:00', '2012-09-21 11:48:34'),
(149, 10, '0000-00-00 00:00:00', '2012-09-21 11:49:53'),
(156, 10, '0000-00-00 00:00:00', '2012-09-21 21:39:15'),
(133, 15, '0000-00-00 00:00:00', '2012-09-23 16:40:06'),
(144, 1, '2012-09-23 19:05:21', '2012-09-23 19:05:06'),
(144, 12, '0000-00-00 00:00:00', '2012-09-23 19:05:32'),
(144, 13, '2012-09-23 19:06:42', '2012-09-23 19:06:40'),
(144, 10, '2012-09-23 19:07:22', '2012-09-23 19:07:19'),
(56, 12, '2012-09-24 01:26:27', '2012-09-24 01:26:22'),
(188, 12, '2012-11-03 20:24:43', '2012-09-24 16:48:39'),
(188, 13, '2012-10-31 04:03:48', '2012-09-24 16:51:35'),
(188, 1, '2012-09-24 17:08:28', '2012-09-24 17:08:22'),
(124, 10, '2012-09-25 15:48:23', '2012-09-25 15:48:20'),
(154, 10, '0000-00-00 00:00:00', '2012-09-25 22:01:11'),
(154, 11, '0000-00-00 00:00:00', '2012-09-25 22:01:17'),
(191, 1, '2012-09-27 09:17:41', '2012-09-27 09:16:47'),
(194, 13, '0000-00-00 00:00:00', '2012-10-03 07:02:17'),
(196, 1, '2012-10-07 18:54:37', '2012-10-07 18:54:24'),
(196, 10, '2012-10-07 19:02:26', '2012-10-07 19:02:23'),
(196, 11, '0000-00-00 00:00:00', '2012-10-07 19:06:18'),
(196, 12, '2012-10-07 19:29:16', '2012-10-07 19:28:20'),
(188, 11, '0000-00-00 00:00:00', '2012-10-31 04:07:53'),
(188, 10, '0000-00-00 00:00:00', '2012-10-31 04:08:17'),
(199, 13, '0000-00-00 00:00:00', '2012-12-01 18:04:34'),
(78, 11, '0000-00-00 00:00:00', '2013-02-13 23:58:51'),
(200, 1, '2013-02-15 20:12:17', '2013-02-15 20:11:00'),
(200, 10, '2013-02-15 20:13:04', '2013-02-15 20:13:02'),
(200, 11, '2013-02-16 21:32:47', '2013-02-15 20:18:55'),
(200, 13, '2013-02-26 02:21:15', '2013-02-15 20:32:32'),
(200, 15, '0000-00-00 00:00:00', '2013-02-18 00:55:28'),
(200, 12, '2013-02-18 01:21:55', '2013-02-18 01:21:41'),
(200, 14, '0000-00-00 00:00:00', '2013-02-18 01:24:14'),
(199, 1, '2013-02-23 18:01:22', '2013-02-23 17:59:00');

-- --------------------------------------------------------

--
-- Структура таблицы `web_50`
--

CREATE TABLE IF NOT EXISTS `web_50` (
  `password` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
