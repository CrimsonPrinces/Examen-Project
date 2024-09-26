-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 26, 2024 at 04:30 PM
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
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `idcategorie` int NOT NULL AUTO_INCREMENT,
  `beschrijving` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`idcategorie`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`idcategorie`, `beschrijving`) VALUES
(1, 'Aardappelen, groente, fruit'),
(2, 'Kaas, vleeswaren'),
(3, 'Zuivel, plantaardig en eieren'),
(4, 'Bakkerij en banket'),
(5, 'Frisdrank, sappen, koffie en thee'),
(6, 'Pasta, rijst en wereldkeuken'),
(7, 'Soepen, sauzen, kruiden en olie'),
(8, 'Snoep, koek, chips en chocolade'),
(9, 'Baby, verzorging en hygiene');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `klant`
--

INSERT INTO `klant` (`idklant`, `naam`, `adres`, `telefoonnummer`, `email`, `aantalvolwassen`, `aantalkind`, `aantalbaby`, `wensen`) VALUES
(1, 'klant1', 'klantlaan 1', '1234567890', 'klant1@email.com', 1, 2, 3, 'Veganistisch'),
(2, 'klant2', 'klantlaan 2', '0987654321', 'klant2@email.com', 3, 2, 1, 'Geen varkensvlees'),
(3, 'Damon Tyranel', 'The Goblet', '2097088', 'totallyarealemail@totally.com', 1, 0, 0, ''),
(14, 'A', 'A', '1', 'A', 1, 1, 1, 'a');

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
  `volgendelevering` datetime DEFAULT NULL,
  PRIMARY KEY (`idleverancier`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leverancier`
--

INSERT INTO `leverancier` (`idleverancier`, `bedrijfsnaam`, `adres`, `naamcontact`, `emailadres`, `telefoonnummer`, `volgendelevering`) VALUES
(1, 'leverancier1', 'leverancierstraat 1', 'contactleverancier', 'leverancier1@email.com', '1234567891', '2024-12-25 00:00:00'),
(4, 'a', 'a', 'a', 'a', '1', '0001-01-01 01:01:00');

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
  `aantal` int NOT NULL,
  `verderfdatum` date DEFAULT NULL,
  `idcategorie` int NOT NULL,
  PRIMARY KEY (`streepjescode`),
  KEY `idcategorie` (`idcategorie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`streepjescode`, `productnaam`, `aantal`, `verderfdatum`, `idcategorie`) VALUES
(5304826896055, 'product1', 1, '2025-09-11', 1),
(6963736455087, 'product2', 3, '2024-12-31', 2),
(5907127347355, 'nietproduct3', 3, '1996-12-05', 4),
(2286399857443, 'Borgar', 44, '2024-09-19', 3),
(2404990412945, 'Smackaroo', 24, '4048-12-31', 8),
(1234567890123, 'Nieuw Product', 1, '1996-06-26', 6);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`iduser`, `gebruikersnaam`, `wachtwoord`, `idusertype`) VALUES
(1, 'admin', '$2y$10$U0BeUHYED7eExYiiw2ltYea1195OGAu8GRBZa/2JWrTh0YmLNuOG2', 1),
(2, 'magazijn', '$2y$10$HUCCilrmBcfuqNL8UneBFuJ9wtGFZGfe8Y3mfyJ2kN.MiGWLEW..C', 2),
(3, 'vrijwilliger', '$2y$10$ZJMER3L9gUMLClGnn87K4e.Sxoad5De2ipFo0J4ZhrGixwjDIKxmW', 3),
(4, 'Damian', '$2y$10$24zabtmPQaOg1jKngiCYO.CvFCE7/Gub/vueHXol8GhzrkdEUoRM.', 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voedselpakket`
--

INSERT INTO `voedselpakket` (`idvoedselpakket`, `samensteldatum`, `uitgiftedatum`, `idklant`) VALUES
(22, '2024-09-26', NULL, 2),
(11, '2024-09-21', NULL, 2),
(12, '2024-09-21', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voedselpakket_has_product`
--

DROP TABLE IF EXISTS `voedselpakket_has_product`;
CREATE TABLE IF NOT EXISTS `voedselpakket_has_product` (
  `idvoedselpakket` int NOT NULL,
  `idklant` int NOT NULL,
  `streepjescode` bigint NOT NULL,
  `aantal` int DEFAULT NULL,
  PRIMARY KEY (`idvoedselpakket`,`streepjescode`),
  KEY `idklant` (`idklant`),
  KEY `streepjescode` (`streepjescode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voedselpakket_has_product`
--

INSERT INTO `voedselpakket_has_product` (`idvoedselpakket`, `idklant`, `streepjescode`, `aantal`) VALUES
(1, 1, 5304826896055, 1),
(10, 2, 0, 1),
(22, 2, 2286399857443, 1),
(20, 14, 2286399857443, 5),
(21, 3, 6963736455087, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
