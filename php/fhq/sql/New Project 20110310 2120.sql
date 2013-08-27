-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.30-community


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema freectf8
--

CREATE DATABASE IF NOT EXISTS freectf8;
USE freectf8;

--
-- Definition of table `lvl`
--

DROP TABLE IF EXISTS `lvl`;
CREATE TABLE `lvl` (
  `idlvl` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `minexp` int(10) unsigned NOT NULL,
  `maxexp` int(10) unsigned NOT NULL,
  `namelvl` varchar(45) NOT NULL,
  PRIMARY KEY (`idlvl`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `lvl`
--

/*!40000 ALTER TABLE `lvl` DISABLE KEYS */;
INSERT INTO `lvl` (`idlvl`,`minexp`,`maxexp`,`namelvl`) VALUES 
 (1,0,100,'новичок'),
 (2,100,200,'все еще новичок');
/*!40000 ALTER TABLE `lvl` ENABLE KEYS */;


--
-- Definition of table `quest`
--

DROP TABLE IF EXISTS `quest`;
CREATE TABLE `quest` (
  `idquest` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `fulldesc` varchar(2000) NOT NULL,
  `answer` varchar(45) NOT NULL,
  `exp` int(10) unsigned NOT NULL,
  `lvl` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idquest`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `quest`
--

/*!40000 ALTER TABLE `quest` DISABLE KEYS */;
INSERT INTO `quest` (`idquest`,`name`,`fulldesc`,`answer`,`exp`,`lvl`) VALUES 
 (1,'Простое преобразование данных (двойное)','Вам необходимо перевести на русский язык что тут написано:\r\nВам необходимо перевести на русский язык что тут написано:\r\nВам необходимо перевести на русский язык что тут написано: «61 70 70 6C 65»','яблоко',11,1),
 (2,'Преобразование данных','задание','простой',10,1);
/*!40000 ALTER TABLE `quest` ENABLE KEYS */;


--
-- Definition of table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `iduser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `exp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`iduser`),
  KEY `FK_user_1` (`exp`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`iduser`,`username`,`password`,`exp`) VALUES 
 (7,'U0VBLUtH','827ccb0eea8a706c4c34a16891f84e7b',0),
 (8,'UFVQUw==','827ccb0eea8a706c4c34a16891f84e7b',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
