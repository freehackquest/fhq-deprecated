-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 20 2014 г., 00:18
-- Версия сервера: 5.5.35
-- Версия PHP: 5.4.4-14+deb7u8

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
-- Структура таблицы `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `global_id` varchar(255) NOT NULL,
  `game_name` varchar(255) NOT NULL,
  `game_logo` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `change_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `json_data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_global_id` (`global_id`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `author_id` (`author_id`),
  KEY `change_date` (`change_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `games`
--

INSERT INTO `games` (`id`, `global_id`, `game_name`, `logo`, `start_date`, `end_date`, `change_date`, `author_id`, `json_data`) VALUES
(1, 'f4821e16-2d23-44e2-a80a-c6f709d973fe', 'FHQ 2012', 'images/minilogo_old.png', '2012-10-06 00:00:00', '2014-03-31 00:00:00', '2014-03-19 00:00:00', 220, ''),
(3, '3e5fa32b-cc08-430b-ab75-69c805d1ecc9', 'FHQ 2013', 'images/minilogo_old.png', '2013-10-06 00:00:00', '2014-03-31 00:00:00', '2014-03-19 00:00:00', 220, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
