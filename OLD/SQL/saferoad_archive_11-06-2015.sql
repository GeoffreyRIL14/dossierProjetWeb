-- phpMyAdmin SQL Dump
-- version 4.4.8
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 11 Juin 2015 à 13:08
-- Version du serveur :  5.5.41-0+wheezy1-log
-- Version de PHP :  5.4.39-0+deb7u1

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
  `idCommentaire` int(11) NOT NULL,
  `description` text,
  `dateCommentaire` datetime DEFAULT NULL,
  `idUser` int(11) NOT NULL,
  `idIncident` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `credibilite`
--

CREATE TABLE IF NOT EXISTS `credibilite` (
  `idCredibilite` int(11) NOT NULL,
  `libelleCredibilite` varchar(20) DEFAULT NULL,
  `valeurCredibilite` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

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
  `idIncident` int(11) NOT NULL,
  `descriptionIncident` varchar(255) DEFAULT NULL,
  `lattitudeIncident` double DEFAULT NULL,
  `longitudeIncident` double DEFAULT NULL,
  `dateHeureIncident` datetime DEFAULT NULL,
  `incidentEnCours` tinyint(4) DEFAULT NULL,
  `idCredibilite` int(11) NOT NULL,
  `idType` int(11) NOT NULL,
  `nbPoint` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `incident`
--

INSERT INTO `incident` (`idIncident`, `descriptionIncident`, `lattitudeIncident`, `longitudeIncident`, `dateHeureIncident`, `incidentEnCours`, `idCredibilite`, `idType`, `nbPoint`) VALUES
(76, 'Une mégane de la gendarmerie a été aperçue sur l''A9', 43.6024424, 3.8701396000000003, '2015-06-10 17:36:05', 1, 1, 3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `masquer`
--

CREATE TABLE IF NOT EXISTS `masquer` (
  `idMasque` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idIncident` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `masquer`
--

INSERT INTO `masquer` (`idMasque`, `idUser`, `idIncident`) VALUES
(1, 1, 40),
(2, 1, 41),
(3, 1, 61),
(4, 1, 62),
(5, 1, 36),
(6, 1, 37),
(7, 1, 54),
(8, 1, 69),
(9, 1, 35),
(10, 1, 68),
(11, 1, 34),
(12, 2, 34),
(13, 2, 36),
(14, 2, 70),
(15, 2, 72),
(16, 2, 71),
(17, 1, 72),
(18, 1, 71),
(19, 1, 70),
(20, 1, 73),
(21, 1, 74),
(22, 1, 75),
(23, 2, 35),
(24, 2, 37),
(25, 2, 77);

-- --------------------------------------------------------

--
-- Structure de la table `noter`
--

CREATE TABLE IF NOT EXISTS `noter` (
  `idNotation` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idIncident` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `noter`
--

INSERT INTO `noter` (`idNotation`, `idUser`, `idIncident`) VALUES
(1, 1, 54),
(2, 1, 38),
(3, 1, 33),
(4, 1, 34),
(5, 0, 54),
(6, 1, 75),
(7, 2, 34);

-- --------------------------------------------------------

--
-- Structure de la table `signale`
--

CREATE TABLE IF NOT EXISTS `signale` (
  `idUser` int(11) NOT NULL,
  `idIncident` int(11) NOT NULL,
  `idDeclaration` int(11) DEFAULT NULL,
  `dateDeclaration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `type_incident`
--

CREATE TABLE IF NOT EXISTS `type_incident` (
  `idType` int(11) NOT NULL,
  `nomType` varchar(30) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

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
  `idUser` int(11) NOT NULL,
  `pseudoUser` varchar(50) DEFAULT NULL,
  `motDePasse` varchar(50) DEFAULT NULL,
  `pathAvatar` varchar(50) DEFAULT NULL,
  `enableNotif` tinyint(4) DEFAULT NULL,
  `seuilCredibMin` int(11) DEFAULT NULL,
  `seuilDistanceMax` int(11) DEFAULT NULL,
  `blacklist` tinyint(1) NOT NULL,
  `identifier` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`idUser`, `pseudoUser`, `motDePasse`, `pathAvatar`, `enableNotif`, `seuilCredibMin`, `seuilDistanceMax`, `blacklist`, `identifier`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6', NULL, 1, 41, 14, 0, 'ff7d13bea44d1188165fbb7053c3d324'),
(2, 'toto', 'd2104a400c7f629a197f33bb33fe80c0', NULL, NULL, NULL, NULL, 0, '359f595e55d5e9607cf0a0180b16a56f'),
(3, 'geoffrey.rannou@viacesi.fr', '528b01020fa3ca864ce9fce3407cbf68', NULL, NULL, NULL, NULL, 0, NULL),
(4, 'test_2', 'ab4f63f9ac65152575886860dde480a1', NULL, NULL, NULL, NULL, 0, NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`idCommentaire`),
  ADD KEY `FK_Commentaire_idUser` (`idUser`),
  ADD KEY `FK_Commentaire_idIncident` (`idIncident`);

--
-- Index pour la table `credibilite`
--
ALTER TABLE `credibilite`
  ADD PRIMARY KEY (`idCredibilite`);

--
-- Index pour la table `incident`
--
ALTER TABLE `incident`
  ADD PRIMARY KEY (`idIncident`),
  ADD KEY `FK_Incident_idCredibilite` (`idCredibilite`),
  ADD KEY `FK_Incident_idType` (`idType`);

--
-- Index pour la table `masquer`
--
ALTER TABLE `masquer`
  ADD PRIMARY KEY (`idMasque`);

--
-- Index pour la table `noter`
--
ALTER TABLE `noter`
  ADD PRIMARY KEY (`idNotation`);

--
-- Index pour la table `signale`
--
ALTER TABLE `signale`
  ADD PRIMARY KEY (`idUser`,`idIncident`),
  ADD KEY `FK_Signale_idIncident` (`idIncident`);

--
-- Index pour la table `type_incident`
--
ALTER TABLE `type_incident`
  ADD PRIMARY KEY (`idType`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `idCommentaire` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `credibilite`
--
ALTER TABLE `credibilite`
  MODIFY `idCredibilite` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `incident`
--
ALTER TABLE `incident`
  MODIFY `idIncident` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT pour la table `masquer`
--
ALTER TABLE `masquer`
  MODIFY `idMasque` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `noter`
--
ALTER TABLE `noter`
  MODIFY `idNotation` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `signale`
--
ALTER TABLE `signale`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `type_incident`
--
ALTER TABLE `type_incident`
  MODIFY `idType` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
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
