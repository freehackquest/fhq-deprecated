-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 30 2013 г., 12:20
-- Версия сервера: 5.5.31
-- Версия PHP: 5.4.4-14+deb7u3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE DATABASE freehackquest;
USE freehackquest;

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
  `typeFB` text NOT NULL,
  `full_text` text NOT NULL,
  `author` int(11) NOT NULL,
  `dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Структура таблицы `tryanswer`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
  `for_person` bigint(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idquest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `role` varchar(10) DEFAULT 'user',
  `nick` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `username` (`username`),
  KEY `FK_user_1` (`score`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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


--
-- export grants
--

GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'freehackquest_u'@'localhost' IDENTIFIED BY PASSWORD '*0CB00E22DD160D523F903AAE07ADD9255C89480A';

GRANT ALL PRIVILEGES ON `freehackquest`.* TO 'freehackquest_u'@'localhost';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
