-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 20 2014 г., 00:17
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
-- Структура таблицы `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_global_id` varchar(255) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `team_logo` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `change_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_global_id` (`team_global_id`),
  KEY `team_name` (`team_name`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `teams`
--

INSERT INTO `teams` (`id`, `team_global_id`, `team_name`, `author_id`, `change_date`) VALUES
(1, '39e9f25f-eab8-49f7-b7cf-b92c13b64ad3', 'keva', 220, '2014-03-20 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
