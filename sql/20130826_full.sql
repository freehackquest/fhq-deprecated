
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeFB` text CHARACTER SET utf8 NOT NULL,
  `full_text` text CHARACTER SET utf8 NOT NULL,
  `author` int(11) NOT NULL,
  `dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `quest` (
  `idquest` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tema` varchar(128) CHARACTER SET utf8 NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `short_text` varchar(128) CHARACTER SET utf8 NOT NULL,
  `text` varchar(4048) CHARACTER SET utf8 NOT NULL,
  `answer` text CHARACTER SET utf8 NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `min_score` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idquest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `feedback_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feedback_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `author` int(11) NOT NULL,
  `dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `quest` (
  `idquest` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tema` varchar(128) CHARACTER SET utf8 NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `short_text` varchar(128) CHARACTER SET utf8 NOT NULL,
  `text` varchar(4048) CHARACTER SET utf8 NOT NULL,
  `answer` text CHARACTER SET utf8 NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `min_score` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idquest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` text NOT NULL,
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `role` varchar(10) CHARACTER SET utf8 DEFAULT 'user',
  `nick` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `username` (`username`),
  KEY `FK_user_1` (`score`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `userquest` (
  `iduser` int(10) NOT NULL,
  `idquest` int(10) NOT NULL,
  `stopdate` datetime NOT NULL,
  `startdate` datetime NOT NULL,
  UNIQUE KEY `iduser` (`iduser`,`idquest`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
