-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 05 2014 г., 18:56
-- Версия сервера: 5.5.33
-- Версия PHP: 5.4.4-14+deb7u7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `jury`
--

-- --------------------------------------------------------

--
-- Структура таблицы `flags`
--

CREATE TABLE IF NOT EXISTS `flags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `id_team_owner` int(11) NOT NULL,
  `dt_start` datetime NOT NULL,
  `dt_end` datetime NOT NULL,
  `id_team_passed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `flags`
--

INSERT INTO `flags` (`id`, `id_service`, `flag`, `id_team_owner`, `dt_start`, `dt_end`, `id_team_passed`) VALUES
(1, 1, '6a331fd2-133a-4713-9587-12652d34666d', 1, '2014-01-05 00:00:00', '2014-01-07 00:05:00', 2),
(3, 1, '301862f0-b3e1-463a-a4f7-3c4e9a6fb1a7', 2, '2014-01-06 00:00:00', '2014-01-06 00:05:00', 0),
(4, 2, 'dc6c3186-ab88-41ca-b256-11e065285c91', 3, '2014-01-05 10:00:00', '2014-01-05 10:05:00', -1),
(5, 2, '5d59e20c-d7cc-4706-b13e-c980f55df59d', 3, '2014-01-05 00:00:00', '2014-01-05 00:05:00', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ip_port` int(11) NOT NULL,
  `scriptpath` varchar(1024) NOT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `services`
--

INSERT INTO `services` (`id`, `name`, `ip_port`, `scriptpath`, `comment`) VALUES
(1, 'top_secret', 80, '/home/root/top_secret.sh', 'web'),
(2, 'top_secret2', 407, '/home/root/top_secret2.sh', '');

-- --------------------------------------------------------

--
-- Структура таблицы `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `comment2` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `teams`
--

INSERT INTO `teams` (`id`, `name`, `ip_address`, `comment`, `comment2`) VALUES
(1, 'Yozik', '192.168.2.12', 'Красноярск', 'yozik-team.keva.su'),
(2, 'QBiT', '192.168.2.13', 'Красноярск', 'qbit-team.keva.su'),
(3, 'Keva', '192.168.2.14', 'Томск', 'keva-team.keva.su');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
