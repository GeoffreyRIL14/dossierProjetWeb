-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 09 Juin 2015 à 14:01
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `saferoad`
--

-- --------------------------------------------------------

--
-- Structure de la table `masquer`
--

CREATE TABLE IF NOT EXISTS `masquer` (
  `isMasque` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `idNotif` int(11) NOT NULL,
  PRIMARY KEY (`isMasque`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `masquer`
--

INSERT INTO `masquer` (`isMasque`, `idUser`, `idNotif`) VALUES
(1, 1, 40),
(2, 1, 41),
(3, 1, 61),
(4, 1, 62);

DELIMITER $$
--
-- Événements
--
CREATE DEFINER=`root`@`localhost` EVENT `controleDatesHeuresIncidents` ON SCHEDULE EVERY '0:15' HOUR_MINUTE STARTS '2015-06-01 23:15:34' ON COMPLETION PRESERVE ENABLE DO CALL nettoyerAnciensIncidents()$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
