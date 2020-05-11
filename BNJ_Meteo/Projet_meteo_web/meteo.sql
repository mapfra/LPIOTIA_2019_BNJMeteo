-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 10 mars 2020 à 16:35
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `meteo`
--

-- --------------------------------------------------------

--
-- Structure de la table `mesurehumidite`
--

CREATE TABLE `mesurehumidite` (
  `date` datetime(6) NOT NULL,
  `valeur` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `mesurehumidite`
--

INSERT INTO `mesurehumidite` (`date`, `valeur`) VALUES
('2020-03-10 12:34:00.000000', '17'),
('2020-03-10 13:42:00.000000', '67'),
('2020-03-10 13:53:00.000000', '65'),
('2020-03-10 16:29:00.000000', '69');

-- --------------------------------------------------------

--
-- Structure de la table `mesureluminosite`
--

CREATE TABLE `mesureluminosite` (
  `date` datetime(6) NOT NULL,
  `valeur` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `mesureluminosite`
--

INSERT INTO `mesureluminosite` (`date`, `valeur`) VALUES
('2020-03-10 12:34:00.000000', '120'),
('2020-03-10 13:42:00.000000', '452'),
('2020-03-10 13:53:00.000000', '303'),
('2020-03-10 16:12:00.000000', '633'),
('2020-03-10 16:29:00.000000', '621');

-- --------------------------------------------------------

--
-- Structure de la table `mesureprecipitation`
--

CREATE TABLE `mesureprecipitation` (
  `date` datetime(6) NOT NULL,
  `valeur` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `mesureprecipitation`
--

INSERT INTO `mesureprecipitation` (`date`, `valeur`) VALUES
('2020-03-10 13:42:00.000000', '14'),
('2020-03-10 13:53:00.000000', '119'),
('2020-03-10 16:12:00.000000', '6'),
('2020-03-10 16:29:00.000000', '6');

-- --------------------------------------------------------

--
-- Structure de la table `mesurepression`
--

CREATE TABLE `mesurepression` (
  `date` datetime(6) NOT NULL,
  `valeur` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `mesurepression`
--

INSERT INTO `mesurepression` (`date`, `valeur`) VALUES
('2020-03-10 13:42:00.000000', '1032'),
('2020-03-10 13:53:00.000000', '996'),
('2020-03-10 16:29:00.000000', '1028');

-- --------------------------------------------------------

--
-- Structure de la table `mesuretemperature`
--

CREATE TABLE `mesuretemperature` (
  `date` datetime(6) NOT NULL,
  `mesure` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `mesuretemperature`
--

INSERT INTO `mesuretemperature` (`date`, `mesure`) VALUES
('2020-03-10 12:34:00.000000', '9'),
('2020-03-10 13:42:00.000000', '17'),
('2020-03-10 13:53:00.000000', '15'),
('2020-03-10 16:12:00.000000', '12'),
('2020-03-10 16:29:00.000000', '36');

-- --------------------------------------------------------

--
-- Structure de la table `mesurevent`
--

CREATE TABLE `mesurevent` (
  `date` datetime(6) NOT NULL,
  `valeur` varchar(50) COLLATE utf8_bin NOT NULL,
  `valeur2` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `mesurevent`
--

INSERT INTO `mesurevent` (`date`, `valeur`, `valeur2`) VALUES
('2020-03-10 13:42:00.000000', '14', 'SE'),
('2020-03-10 13:53:00.000000', '142', 'SE'),
('2020-03-10 16:29:00.000000', '53', 'NE');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `mesurehumidite`
--
ALTER TABLE `mesurehumidite`
  ADD PRIMARY KEY (`date`);

--
-- Index pour la table `mesureluminosite`
--
ALTER TABLE `mesureluminosite`
  ADD PRIMARY KEY (`date`);

--
-- Index pour la table `mesureprecipitation`
--
ALTER TABLE `mesureprecipitation`
  ADD PRIMARY KEY (`date`);

--
-- Index pour la table `mesurepression`
--
ALTER TABLE `mesurepression`
  ADD PRIMARY KEY (`date`);

--
-- Index pour la table `mesuretemperature`
--
ALTER TABLE `mesuretemperature`
  ADD PRIMARY KEY (`date`);

--
-- Index pour la table `mesurevent`
--
ALTER TABLE `mesurevent`
  ADD PRIMARY KEY (`date`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `mesurehumidite`
--
ALTER TABLE `mesurehumidite`
  ADD CONSTRAINT `mesurehumidite_ibfk_1` FOREIGN KEY (`date`) REFERENCES `mesuretemperature` (`date`);

--
-- Contraintes pour la table `mesureluminosite`
--
ALTER TABLE `mesureluminosite`
  ADD CONSTRAINT `mesureluminosite_ibfk_1` FOREIGN KEY (`date`) REFERENCES `mesuretemperature` (`date`);

--
-- Contraintes pour la table `mesureprecipitation`
--
ALTER TABLE `mesureprecipitation`
  ADD CONSTRAINT `mesureprecipitation_ibfk_1` FOREIGN KEY (`date`) REFERENCES `mesureluminosite` (`date`);

--
-- Contraintes pour la table `mesurepression`
--
ALTER TABLE `mesurepression`
  ADD CONSTRAINT `mesurepression_ibfk_1` FOREIGN KEY (`date`) REFERENCES `mesurehumidite` (`date`);

--
-- Contraintes pour la table `mesurevent`
--
ALTER TABLE `mesurevent`
  ADD CONSTRAINT `mesurevent_ibfk_1` FOREIGN KEY (`date`) REFERENCES `mesureprecipitation` (`date`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
