-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 13, 2024 at 11:41 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examenproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `klant`
--

DROP TABLE IF EXISTS `klant`;
CREATE TABLE IF NOT EXISTS `klant` (
  `idklant` int NOT NULL AUTO_INCREMENT,
  `naam` varchar(45) NOT NULL,
  `adres` varchar(45) NOT NULL,
  `telefoonnummer` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `aantalvolwassen` int DEFAULT NULL,
  `aantalkind` int DEFAULT NULL,
  `aantalbaby` int DEFAULT NULL,
  `wensen` text,
  PRIMARY KEY (`idklant`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `klant`
--

INSERT INTO `klant` (`idklant`, `naam`, `adres`, `telefoonnummer`, `email`, `aantalvolwassen`, `aantalkind`, `aantalbaby`, `wensen`) VALUES
(1, 'klant1', 'klantlaan 1', '1234567890', 'klant1@email.com', 1, 2, 3, 'Veganistisch'),
(2, 'klant2', 'klantlaan 2', '0987654321', 'klant2@email.com', 3, 2, 1, 'Geen varkensvlees');

-- --------------------------------------------------------

--
-- Table structure for table `leverancier`
--

DROP TABLE IF EXISTS `leverancier`;
CREATE TABLE IF NOT EXISTS `leverancier` (
  `idleverancier` int NOT NULL AUTO_INCREMENT,
  `bedrijfsnaam` varchar(50) NOT NULL,
  `adres` varchar(45) NOT NULL,
  `naamcontact` varchar(45) NOT NULL,
  `emailadres` varchar(45) NOT NULL,
  `telefoonnummer` varchar(45) NOT NULL,
  `volgendelevering` date DEFAULT NULL,
  PRIMARY KEY (`idleverancier`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leverancier`
--

INSERT INTO `leverancier` (`idleverancier`, `bedrijfsnaam`, `adres`, `naamcontact`, `emailadres`, `telefoonnummer`, `volgendelevering`) VALUES
(1, 'leverancier1', 'leverancierstraat 1', 'contactleverancier', 'leverancier1@email.com', '1234567891', '2024-12-25');

-- --------------------------------------------------------

--
-- Table structure for table `levering`
--

DROP TABLE IF EXISTS `levering`;
CREATE TABLE IF NOT EXISTS `levering` (
  `idlevering` int NOT NULL AUTO_INCREMENT,
  `datum` datetime DEFAULT NULL,
  `idleverancier` int NOT NULL,
  PRIMARY KEY (`idlevering`),
  KEY `idleverancier` (`idleverancier`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `levering`
--

INSERT INTO `levering` (`idlevering`, `datum`, `idleverancier`) VALUES
(1, '2023-12-25 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `levering_has_product`
--

DROP TABLE IF EXISTS `levering_has_product`;
CREATE TABLE IF NOT EXISTS `levering_has_product` (
  `idlevering` int NOT NULL,
  `streepjescode` bigint NOT NULL,
  PRIMARY KEY (`idlevering`,`streepjescode`),
  KEY `streepjescode` (`streepjescode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `levering_has_product`
--

INSERT INTO `levering_has_product` (`idlevering`, `streepjescode`) VALUES
(1, 5304826896055);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `streepjescode` bigint NOT NULL,
  `productnaam` varchar(100) NOT NULL,
  `categorie` varchar(70) NOT NULL,
  `aantal` int NOT NULL,
  `verderfdatum` date DEFAULT NULL,
  PRIMARY KEY (`streepjescode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`streepjescode`, `productnaam`, `categorie`, `aantal`, `verderfdatum`) VALUES
(5304826896055, 'product1', 'Aardappelen, groente, fruit', 1, '2025-09-11'),
(6963736455087, 'product2', 'Kaas, vleeswaren', 5, '2024-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `gebruikersnaam` varchar(45) NOT NULL,
  `wachtwoord` varchar(200) DEFAULT NULL,
  `idusertype` int NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `gebruikersnaam` (`gebruikersnaam`),
  KEY `idusertype` (`idusertype`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `gebruikersnaam`, `wachtwoord`, `idusertype`) VALUES
(1, 'admin', '$2y$10$U0BeUHYED7eExYiiw2ltYea1195OGAu8GRBZa/2JWrTh0YmLNuOG2', 1),
(2, 'magazijn', '$2y$10$HUCCilrmBcfuqNL8UneBFuJ9wtGFZGfe8Y3mfyJ2kN.MiGWLEW..C', 2),
(3, 'vrijwilliger', '$2y$10$ZJMER3L9gUMLClGnn87K4e.Sxoad5De2ipFo0J4ZhrGixwjDIKxmW', 3);

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

DROP TABLE IF EXISTS `usertype`;
CREATE TABLE IF NOT EXISTS `usertype` (
  `idusertype` int NOT NULL,
  PRIMARY KEY (`idusertype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`idusertype`) VALUES
(1),
(2),
(3);

-- --------------------------------------------------------

--
-- Table structure for table `voedselpakket`
--

DROP TABLE IF EXISTS `voedselpakket`;
CREATE TABLE IF NOT EXISTS `voedselpakket` (
  `idvoedselpakket` int NOT NULL AUTO_INCREMENT,
  `samensteldatum` date DEFAULT NULL,
  `uitgiftedatum` date DEFAULT NULL,
  `idklant` int NOT NULL,
  PRIMARY KEY (`idvoedselpakket`),
  KEY `idklant` (`idklant`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voedselpakket`
--

INSERT INTO `voedselpakket` (`idvoedselpakket`, `samensteldatum`, `uitgiftedatum`, `idklant`) VALUES
(1, '2024-09-12', '2024-09-13', 1),
(2, '2024-09-19', NULL, 1),
(3, '2024-09-26', '2024-09-27', 2);

-- --------------------------------------------------------

--
-- Table structure for table `voedselpakket_has_product`
--

DROP TABLE IF EXISTS `voedselpakket_has_product`;
CREATE TABLE IF NOT EXISTS `voedselpakket_has_product` (
  `idvoedselpakket` int NOT NULL,
  `idklant` int NOT NULL,
  `streepjescode` bigint NOT NULL,
  PRIMARY KEY (`idvoedselpakket`,`streepjescode`),
  KEY `idklant` (`idklant`),
  KEY `streepjescode` (`streepjescode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voedselpakket_has_product`
--

INSERT INTO `voedselpakket_has_product` (`idvoedselpakket`, `idklant`, `streepjescode`) VALUES
(1, 1, 5304826896055);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
