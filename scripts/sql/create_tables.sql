-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 19 Janvier 2015 à 00:18
-- Version du serveur :  5.5.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `immo`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE IF NOT EXISTS `annonce` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` varchar(128) NOT NULL,
  `description` text,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `montant_charges` smallint(6) DEFAULT NULL,
  `ch_eau_froide` tinyint(1) DEFAULT NULL,
  `ch_eau_chaude` tinyint(1) DEFAULT NULL,
  `ch_entretien_commun` tinyint(1) DEFAULT NULL,
  `ch_chauffage` tinyint(1) DEFAULT NULL,
  `ch_gardien` tinyint(1) DEFAULT NULL,
  `adresse` varchar(256) DEFAULT NULL,
  `taxe_habitation` smallint(6) DEFAULT NULL,
  `taxe_fonciere` smallint(6) DEFAULT NULL,
  `etage` tinyint(4) DEFAULT NULL,
  `nb_etages` tinyint(4) DEFAULT NULL,
  `ascenceur` tinyint(1) DEFAULT NULL,
  `surface` tinyint(4) DEFAULT NULL,
  `type_stationnement` varchar(32) DEFAULT NULL,
  `cuisine_ouverte` tinyint(1) DEFAULT NULL,
  `type_logement` varchar(8) NOT NULL DEFAULT 'INDEFINI',
  `nb_chambres` tinyint(4) DEFAULT NULL,
  `cave` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `annonce`
--

INSERT INTO `annonce` (`id`, `label`, `description`, `date_creation`, `montant_charges`, `ch_eau_froide`, `ch_eau_chaude`, `ch_entretien_commun`, `ch_chauffage`, `ch_gardien`, `adresse`, `taxe_habitation`, `taxe_fonciere`, `etage`, `nb_etages`, `ascenceur`, `surface`, `type_stationnement`, `cuisine_ouverte`, `type_logement`, `nb_chambres`, `cave`) VALUES
(1, 'test 0', NULL, '2015-01-13 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'INDEFINI', NULL, NULL),
(2, 'test 1', NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'INDEFINI', NULL, NULL),
(3, 'test 2', NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'INDEFINI', NULL, NULL),
(4, 'test 3', NULL, '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'INDEFINI', NULL, NULL),
(5, 'test 72', 'ceci est la plus belle description du monde entier !!! ceci est la plus belle description du monde entier !!! ceci est la plus belle description du monde entier !!! ceci est la plus belle description du monde entier !!! ceci est la plus belle description du monde entier !!! ceci est la plus belle description du monde entier !!! ', '2015-01-17 23:30:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'INDEFINI', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
