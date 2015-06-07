-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 07 Juin 2015 à 19:18
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

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `nettoyerAnciensIncidents`()
    MODIFIES SQL DATA
DELETE FROM incident
WHERE (Now() - incident.dateHeureIncident > 14400)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE IF NOT EXISTS `commentaire` (
  `idCommentaire` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `dateCommentaire` datetime DEFAULT NULL,
  `idUser` int(11) NOT NULL,
  `idIncident` int(11) NOT NULL,
  PRIMARY KEY (`idCommentaire`),
  KEY `FK_Commentaire_idUser` (`idUser`),
  KEY `FK_Commentaire_idIncident` (`idIncident`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `credibilite`
--

CREATE TABLE IF NOT EXISTS `credibilite` (
  `idCredibilite` int(11) NOT NULL AUTO_INCREMENT,
  `libelleCredibilite` varchar(20) DEFAULT NULL,
  `valeurCredibilite` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCredibilite`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `credibilite`
--

INSERT INTO `credibilite` (`idCredibilite`, `libelleCredibilite`, `valeurCredibilite`) VALUES
(1, 'crédible', 10);

-- --------------------------------------------------------

--
-- Structure de la table `incident`
--

CREATE TABLE IF NOT EXISTS `incident` (
  `idIncident` int(11) NOT NULL AUTO_INCREMENT,
  `descriptionIncident` varchar(255) DEFAULT NULL,
  `lattitudeIncident` double DEFAULT NULL,
  `longitudeIncident` double DEFAULT NULL,
  `dateHeureIncident` datetime DEFAULT NULL,
  `incidentEnCours` tinyint(4) DEFAULT NULL,
  `idCredibilite` int(11) NOT NULL,
  `idType` int(11) NOT NULL,
  `nbPoint` int(11) NOT NULL,
  PRIMARY KEY (`idIncident`),
  KEY `FK_Incident_idCredibilite` (`idCredibilite`),
  KEY `FK_Incident_idType` (`idType`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Contenu de la table `incident`
--

INSERT INTO `incident` (`idIncident`, `descriptionIncident`, `lattitudeIncident`, `longitudeIncident`, `dateHeureIncident`, `incidentEnCours`, `idCredibilite`, `idType`, `nbPoint`) VALUES
(39, 'Accident de trottoir! Une bonn', 44.8210323, -0.5999413, '2015-05-13 20:31:27', 1, 1, 1, 0),
(40, '1 meuf a cassé son talon. SAMU', 44.821014299999995, -0.5999644, '2015-05-13 20:37:11', 1, 1, 1, 0),
(41, 'Accident trottoir! une femme a coincé son talon dans une bouche d''égoût! Pompiers mobilisés, bouchon sur 2m. ', 44.8210051, -0.5999498, '2015-05-13 20:44:07', 1, 1, 1, 0),
(54, 'test depuis chez moi', 43.6007871, 3.8757218000000004, '2015-06-01 00:00:00', 1, 1, 1, 10),
(56, 'caca popo', 43.617377499999996, 3.8402398000000004, '2015-06-07 00:00:00', 1, 1, 1, 2),
(59, 'boom bitch', 43.6174326, 3.8402064999999994, '2015-06-07 00:00:00', 1, 1, 1, 0),
(60, 'radar a debile', 43.6173705, 3.8402087999999996, '2015-06-07 00:00:00', 1, 1, 1, 0),
(61, 'pouet', 43.6173709, 3.8402928, '2015-06-07 00:00:00', 1, 1, 1, 0),
(62, 'radar man!', 43.6173791, 3.8402966000000003, '2015-06-07 00:00:00', 1, 1, 2, 0),
(63, 'test radar', 43.6173577, 3.8402517, '2015-06-07 00:00:00', 1, 1, 2, 0),
(64, 'test travaux', 43.6173577, 3.8402517, '2015-06-07 00:00:00', 1, 1, 1, 0),
(65, 'test police', 43.6173577, 3.8402517, '2015-06-07 00:00:00', 1, 1, 3, 0),
(66, 'test accident', 43.617370699999995, 3.8401630999999994, '2015-06-07 00:00:00', 1, 1, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `noter`
--

CREATE TABLE IF NOT EXISTS `noter` (
  `idNotation` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idIncident` int(11) NOT NULL,
  PRIMARY KEY (`idNotation`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `noter`
--

INSERT INTO `noter` (`idNotation`, `idUser`, `idIncident`) VALUES
(0, 1, 54);

-- --------------------------------------------------------

--
-- Structure de la table `signale`
--

CREATE TABLE IF NOT EXISTS `signale` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `idIncident` int(11) NOT NULL,
  `idDeclaration` int(11) DEFAULT NULL,
  `dateDeclaration` datetime DEFAULT NULL,
  PRIMARY KEY (`idUser`,`idIncident`),
  KEY `FK_Signale_idIncident` (`idIncident`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `type_incident`
--

CREATE TABLE IF NOT EXISTS `type_incident` (
  `idType` int(11) NOT NULL AUTO_INCREMENT,
  `nomType` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idType`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `type_incident`
--

INSERT INTO `type_incident` (`idType`, `nomType`) VALUES
(1, 'Travaux'),
(2, 'Radar'),
(3, 'Police'),
(4, 'Accident');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `pseudoUser` varchar(50) DEFAULT NULL,
  `motDePasse` varchar(50) DEFAULT NULL,
  `pathAvatar` varchar(50) DEFAULT NULL,
  `enableNotif` tinyint(4) DEFAULT NULL,
  `seuilCredibMin` int(11) DEFAULT NULL,
  `seuilDistanceMax` int(11) DEFAULT NULL,
  `blacklist` tinyint(1) NOT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`idUser`, `pseudoUser`, `motDePasse`, `pathAvatar`, `enableNotif`, `seuilCredibMin`, `seuilDistanceMax`, `blacklist`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6', NULL, 0, 33, 99, 0);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_Commentaire_idIncident` FOREIGN KEY (`idIncident`) REFERENCES `incident` (`idIncident`),
  ADD CONSTRAINT `FK_Commentaire_idUser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `incident`
--
ALTER TABLE `incident`
  ADD CONSTRAINT `FK_Incident_idCredibilite` FOREIGN KEY (`idCredibilite`) REFERENCES `credibilite` (`idCredibilite`),
  ADD CONSTRAINT `FK_Incident_idType` FOREIGN KEY (`idType`) REFERENCES `type_incident` (`idType`);

--
-- Contraintes pour la table `signale`
--
ALTER TABLE `signale`
  ADD CONSTRAINT `FK_Signale_idIncident` FOREIGN KEY (`idIncident`) REFERENCES `incident` (`idIncident`),
  ADD CONSTRAINT `FK_Signale_idUser` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

DELIMITER $$
--
-- Événements
--
CREATE DEFINER=`root`@`localhost` EVENT `controleDatesHeuresIncidents` ON SCHEDULE EVERY '0:15' HOUR_MINUTE STARTS '2015-06-01 23:15:34' ON COMPLETION PRESERVE ENABLE DO CALL nettoyerAnciensIncidents()$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
