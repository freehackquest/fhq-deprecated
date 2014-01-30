SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE ctfengine;
USE ctfengine;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ctfengine`
--

-- --------------------------------------------------------

--
-- Table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid_user` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `nick` varchar(30) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `rating` int(11) NOT NULL,
  `activated` int(1) NOT NULL,
  `activation_code` varchar(40) NOT NULL,
  `json_data` text NOT NULL,
  `date_create` datetime NOT NULL,
  `date_activated` datetime DEFAULT NULL,
  `date_last_signup` datetime NOT NULL
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

-- --------------------------------------------------------

--
-- export grants
--
-- todo: change password from freehackquest_u to ctfengine_u

GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'ctfengine_u'@'localhost' IDENTIFIED BY PASSWORD '*0CB00E22DD160D523F903AAE07ADD9255C89480A';

GRANT ALL PRIVILEGES ON `freehackquest`.* TO 'ctfengine_u'@'localhost';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
