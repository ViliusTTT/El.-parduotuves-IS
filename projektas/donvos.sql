-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u6
-- http://www.phpmyadmin.net
--
-- Darbinė stotis: localhost
-- Atlikimo laikas: 2016 m. Grd 12 d. 20:33
-- Serverio versija: 1.0.27
-- PHP versija: 5.6.28-1~dotdeb+zts+7.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Duomenų bazė: `donvos`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `active_guests`
--

CREATE TABLE IF NOT EXISTS `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `active_users`
--

CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Sukurta duomenų kopija lentelei `active_users`
--

INSERT INTO `active_users` (`username`, `timestamp`) VALUES
('SandelioVadovas', 1481535690),
('Klientas', 1481535682);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `algolapis`
--

CREATE TABLE IF NOT EXISTS `algolapis` (
  `kiekis` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_Darbuotojasid_Darbuotojas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priima` (`fk_Darbuotojasid_Darbuotojas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Sukurta duomenų kopija lentelei `algolapis`
--

INSERT INTO `algolapis` (`kiekis`, `data`, `id`, `fk_Darbuotojasid_Darbuotojas`) VALUES
(250, '2016-12-09 00:00:00', 1, 11),
(100, '2016-12-09 00:00:00', 2, 12),
(452, '2016-12-04 00:00:00', 5, 11),
(1000, '2016-12-10 15:59:14', 6, 10),
(555, '2016-12-10 18:34:43', 8, 11),
(10, '2016-12-12 00:39:51', 10, 14),
(100, '2016-12-12 11:17:47', 11, 12);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `banned_users`
--

CREATE TABLE IF NOT EXISTS `banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `darbuotojas`
--

CREATE TABLE IF NOT EXISTS `darbuotojas` (
  `slapyvardis` varchar(255) DEFAULT NULL,
  `vardas` varchar(255) DEFAULT NULL,
  `pavarde` varchar(255) DEFAULT NULL,
  `prisijungimo_data` datetime DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pareigos` int(11) DEFAULT NULL,
  `fk_Imoneid_Imone` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pareigos` (`pareigos`),
  KEY `Dirba` (`fk_Imoneid_Imone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Sukurta duomenų kopija lentelei `darbuotojas`
--

INSERT INTO `darbuotojas` (`slapyvardis`, `vardas`, `pavarde`, `prisijungimo_data`, `id`, `pareigos`, `fk_Imoneid_Imone`) VALUES
('SandelioVadovas', 'Algimantas', 'Lopata', '2016-12-07 23:29:46', 10, 1, 1),
('Sandelininkas', 'Arunce', 'Siskauskas', '2016-12-07 23:30:29', 11, 2, 1),
('Kurjeris', 'Mindaugelis', 'Stasiuklis', '2016-12-07 23:31:15', 12, 3, 1),
('ImonesVadovas', 'Raulis', 'Mamadlou', '2016-12-07 23:32:39', 14, 5, 1),
('SandelioVadovas2', 'Julius', 'Juliauskas', '2016-12-07 23:35:11', 15, 1, 1),
('belekas', 'belekas', 'belekas', '2016-12-12 11:03:23', 21, 1, 1),
('demodemo2', 'demoo', 'demoo', '2016-12-12 11:16:22', 22, 1, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `imone`
--

CREATE TABLE IF NOT EXISTS `imone` (
  `imones_pavadinimas` varchar(255) DEFAULT NULL,
  `Ikurimo_data` date DEFAULT NULL,
  `vadovas` varchar(255) DEFAULT NULL,
  `imones_kodas` int(11) unsigned NOT NULL,
  `fk_InternetinisPuslapisid_InternetinisPuslapis` int(11) NOT NULL,
  PRIMARY KEY (`imones_kodas`),
  UNIQUE KEY `fk_InternetinisPuslapisid_InternetinisPuslapis` (`fk_InternetinisPuslapisid_InternetinisPuslapis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `imone`
--

INSERT INTO `imone` (`imones_pavadinimas`, `Ikurimo_data`, `vadovas`, `imones_kodas`, `fk_InternetinisPuslapisid_InternetinisPuslapis`) VALUES
('UAB DrymTym', '2016-12-01', 'Saulius', 1, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `internetinispuslapis`
--

CREATE TABLE IF NOT EXISTS `internetinispuslapis` (
  `adresas` varchar(255) NOT NULL,
  `administratorius` varchar(255) DEFAULT NULL,
  `serveris` varchar(255) DEFAULT NULL,
  `vartotoju_skaicius` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `internetinispuslapis`
--

INSERT INTO `internetinispuslapis` (`adresas`, `administratorius`, `serveris`, `vartotoju_skaicius`, `id`) VALUES
('www.DrymTymShop.com', 'Administratorius', 'db.if.ktu.lt', 10, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `klientas`
--

CREATE TABLE IF NOT EXISTS `klientas` (
  `slapyvardis` varchar(255) DEFAULT NULL,
  `vardas` varchar(255) DEFAULT NULL,
  `pavarde` varchar(255) DEFAULT NULL,
  `el_pastas` varchar(255) DEFAULT NULL,
  `prisijungimo_data` datetime DEFAULT NULL,
  `adresas` varchar(255) DEFAULT NULL,
  `tel_numeris` int(11) DEFAULT NULL,
  `slaptazodis` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_Imoneid_Imone` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slapyvardis` (`slapyvardis`),
  KEY `naudojasi_paslaugomis` (`fk_Imoneid_Imone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Sukurta duomenų kopija lentelei `klientas`
--

INSERT INTO `klientas` (`slapyvardis`, `vardas`, `pavarde`, `el_pastas`, `prisijungimo_data`, `adresas`, `tel_numeris`, `slaptazodis`, `id`, `fk_Imoneid_Imone`) VALUES
('Klientas', 'Remigijus', 'Einikis', 'Remigijus@one.lt', '2016-12-07 23:28:37', 'Baltupiu 18 Kaunas', 86546514, 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 1),
('Klientas2', 'Linas', 'Karalius', 'Linux@one.lt', '2016-12-07 23:34:35', 'Karaliu g Skuodas', 45687, 'fe01ce2a7fbac8fafaed7c982a04e229', 2, 1),
('Magician', 'Petras', 'Pauliukaitis', 'Petriux@klientas.com', '2016-12-10 19:17:36', 'Varni? g. 8 Kaunas', 86451342, 'fe01ce2a7fbac8fafaed7c982a04e229', 8, 1),
('Klientas3', 'Tomas', 'Tomauskas', 'Tomauskas@gmail.com', '2016-12-10 23:12:43', 'Jotvingiu g. 54', 863822201, 'fe01ce2a7fbac8fafaed7c982a04e229', 9, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `krepselis`
--

CREATE TABLE IF NOT EXISTS `krepselis` (
  `id` int(11) NOT NULL,
  `prekesKiekis` int(11) DEFAULT NULL,
  `fk_preke` int(11) DEFAULT NULL,
  `fk_Uzsakymasid_Uzsakymas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_preke` (`fk_preke`),
  KEY `fk_Uzsakymasid_Uzsakymas` (`fk_Uzsakymasid_Uzsakymas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `krepselis`
--

INSERT INTO `krepselis` (`id`, `prekesKiekis`, `fk_preke`, `fk_Uzsakymasid_Uzsakymas`) VALUES
(1, 2, 1, 1),
(2, 1, 3, 1),
(3, 5, 9, 2);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `kuponai`
--

CREATE TABLE IF NOT EXISTS `kuponai` (
  `id` int(11) NOT NULL,
  `klientoID` int(11) DEFAULT NULL,
  `kuponoKodas` varchar(255) DEFAULT NULL,
  `nuolaidosProc` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `leaderbord`
--

CREATE TABLE IF NOT EXISTS `leaderbord` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `score` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Sukurta duomenų kopija lentelei `leaderbord`
--

INSERT INTO `leaderbord` (`id`, `name`, `score`) VALUES
(5, 'Player_1', 500),
(6, 'Player_2', 50),
(7, 'Player_3', 60);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `mokejimai`
--

CREATE TABLE IF NOT EXISTS `mokejimai` (
  `mokejimoNr` int(11) NOT NULL,
  `galutineKaina` double DEFAULT NULL,
  `pristatymoBudas` int(11) DEFAULT NULL,
  `fk_pristatymo_budaiid_pristatymo_budai` int(11) NOT NULL,
  `fk_kuponaiid_kuponai` int(11) NOT NULL,
  PRIMARY KEY (`mokejimoNr`),
  KEY `pristatymoBudas` (`pristatymoBudas`),
  KEY `turi` (`fk_pristatymo_budaiid_pristatymo_budai`),
  KEY `pritaikomas` (`fk_kuponaiid_kuponai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `pareigostipai`
--

CREATE TABLE IF NOT EXISTS `pareigostipai` (
  `id_PareigosTipai` int(11) NOT NULL,
  `name` char(27) NOT NULL,
  PRIMARY KEY (`id_PareigosTipai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `pareigostipai`
--

INSERT INTO `pareigostipai` (`id_PareigosTipai`, `name`) VALUES
(1, 'Sandelio vadovas'),
(2, 'Sandelininkas'),
(3, 'Kurjeris'),
(4, 'Pardaveja'),
(5, 'Imones vadovas');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `perkamaissandelio`
--

CREATE TABLE IF NOT EXISTS `perkamaissandelio` (
  `fk_prekesid_prekes` int(11) NOT NULL,
  `fk_PrUzsakymasid_PrUzsakymas` int(11) NOT NULL,
  PRIMARY KEY (`fk_prekesid_prekes`,`fk_PrUzsakymasid_PrUzsakymas`),
  KEY `fk_PrUzsakymasid_PrUzsakymas` (`fk_PrUzsakymasid_PrUzsakymas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekes`
--

CREATE TABLE IF NOT EXISTS `prekes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prekesKodas` varchar(255) NOT NULL,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  `Galiojimas` date DEFAULT NULL,
  `Kaina` double DEFAULT NULL,
  `Kiekis` int(11) DEFAULT NULL,
  `fk_sandeliaiid_sandeliai` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priklauso` (`fk_sandeliaiid_sandeliai`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Sukurta duomenų kopija lentelei `prekes`
--

INSERT INTO `prekes` (`id`, `prekesKodas`, `Pavadinimas`, `Galiojimas`, `Kaina`, `Kiekis`, `fk_sandeliaiid_sandeliai`) VALUES
(1, '2555555', 'Televizorius', '2015-12-31', 1500, 50, 1),
(3, '2555556', 'Blynai', '2015-12-31', 9999, 200, 1),
(9, 'a6sd4asdas4', 'Stalas', '2019-12-31', 300, 10, 1),
(10, 'assa54da6sd5456', 'kede', '2019-01-24', 150, 10, 1),
(11, 'sadfsadfasdfsadf', 'labas', '2016-12-31', 100, 480, 1),
(12, '2555555', 'Televizorius', '2015-12-31', 1500, 20, 3),
(13, 'sadfsadfasdfsadf', 'labas', '2016-12-31', 100, 20, 3);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekestiekejo`
--

CREATE TABLE IF NOT EXISTS `prekestiekejo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prekesKodas` varchar(255) NOT NULL,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  `Galiojimas` date DEFAULT NULL,
  `Kaina` double DEFAULT NULL,
  `Kiekis` int(11) DEFAULT NULL,
  `fk_TiekejoSandeliai` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priklauso` (`fk_TiekejoSandeliai`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Sukurta duomenų kopija lentelei `prekestiekejo`
--

INSERT INTO `prekestiekejo` (`id`, `prekesKodas`, `Pavadinimas`, `Galiojimas`, `Kaina`, `Kiekis`, `fk_TiekejoSandeliai`) VALUES
(1, '2555555', 'Televizorius', '2015-12-31', 1500, 30, 1),
(8, 'a6sd4asdas4', 'Stalas', '2019-12-31', 300, 0, 4),
(9, 'assa54da6sd5456', 'kede', '2019-01-24', 150, 0, 1),
(10, 'sadfsadfasdfsadf', 'labas', '2016-12-31', 100, 30, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekiusarasas`
--

CREATE TABLE IF NOT EXISTS `prekiusarasas` (
  `uzsakymoPatvirtinimas` tinyint(1) DEFAULT NULL,
  `uzsakymoData` date DEFAULT NULL,
  `pristatymoData` date DEFAULT NULL,
  `atsauktas` int(1) DEFAULT '0',
  `norimasPristatymas` date DEFAULT NULL,
  `uzsakymoKodas` varchar(255) NOT NULL,
  `fk_Tiekejas` varchar(255) NOT NULL,
  `fk_ImonesSandelys` int(11) NOT NULL,
  PRIMARY KEY (`uzsakymoKodas`),
  KEY `tiekejas` (`fk_Tiekejas`),
  KEY `fk_Imone` (`fk_ImonesSandelys`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `prekiusarasas`
--

INSERT INTO `prekiusarasas` (`uzsakymoPatvirtinimas`, `uzsakymoData`, `pristatymoData`, `atsauktas`, `norimasPristatymas`, `uzsakymoKodas`, `fk_Tiekejas`, `fk_ImonesSandelys`) VALUES
(1, '2016-12-12', '2016-12-12', 0, '2016-01-02', '4546', '45aaa45852a', 1),
(1, '2016-12-12', '2016-12-12', 0, '0001-02-02', '5555', '45aaa45852a', 1),
(1, '2016-12-12', '2016-12-12', 0, '2016-12-23', '65465', '45aaa45852a', 1),
(1, '2016-12-12', '2016-12-12', 0, '2016-12-15', 'a6s4dd4sd5asda', '45aaa45852a', 1),
(1, '2016-12-11', '2016-12-11', 0, '2016-12-22', 'as65d4a6s5d4asd5', '45aaa45852a', 1),
(1, '2016-12-11', '2016-12-12', 0, '2016-12-14', 'asd4a6sd4ad45as', '45aaa45852a', 1),
(1, '2016-12-12', '2016-12-12', 0, '2016-12-14', 'asdasdas', '45aaa45852a', 1),
(1, '2016-12-12', '2016-12-12', 0, '2016-12-22', 'khgghk', '45aaa45852a', 3),
(1, '2016-12-12', NULL, 1, '2016-12-16', 'saddasdasd', '45aaa45852a', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `pristatymo_budai`
--

CREATE TABLE IF NOT EXISTS `pristatymo_budai` (
  `id_pristatymo_budai` int(11) NOT NULL,
  `name` char(21) NOT NULL,
  PRIMARY KEY (`id_pristatymo_budai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `pristatymo_budai`
--

INSERT INTO `pristatymo_budai` (`id_pristatymo_budai`, `name`) VALUES
(1, 'kurjeriu'),
(2, 'pastu'),
(3, 'atsiimti_is_personalo');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `pruzsakymas`
--

CREATE TABLE IF NOT EXISTS `pruzsakymas` (
  `prekiuKiekis` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_Preke` int(11) NOT NULL,
  `fk_PrekiuSarasasid_PrekiuSarasas` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uzsake` (`fk_PrekiuSarasasid_PrekiuSarasas`),
  KEY `fk_Preke` (`fk_Preke`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Sukurta duomenų kopija lentelei `pruzsakymas`
--

INSERT INTO `pruzsakymas` (`prekiuKiekis`, `id`, `fk_Preke`, `fk_PrekiuSarasasid_PrekiuSarasas`) VALUES
(5, 35, 8, 'asd4a6sd4ad45as'),
(10, 40, 1, 'as65d4a6s5d4asd5'),
(5, 41, 8, 'as65d4a6s5d4asd5'),
(5, 43, 9, 'a6s4dd4sd5asda'),
(5, 44, 1, 'a6s4dd4sd5asda'),
(5, 45, 1, 'asdasdas'),
(5, 46, 9, 'asdasdas'),
(10, 47, 10, '4546'),
(10, 48, 10, '5555'),
(400, 50, 10, 'saddasdasd'),
(10, 51, 1, 'saddasdasd'),
(400, 53, 10, '65465'),
(20, 54, 1, 'khgghk'),
(20, 55, 10, 'khgghk');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `registruojasi`
--

CREATE TABLE IF NOT EXISTS `registruojasi` (
  `fk_InternetinisPuslapisid_InternetinisPuslapis` int(11) NOT NULL,
  `fk_AktyvusSveciasid_AktyvusSvecias` int(11) NOT NULL,
  PRIMARY KEY (`fk_InternetinisPuslapisid_InternetinisPuslapis`,`fk_AktyvusSveciasid_AktyvusSvecias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `sandeliai`
--

CREATE TABLE IF NOT EXISTS `sandeliai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Adresas` varchar(255) DEFAULT NULL,
  `Talpa` int(11) DEFAULT NULL,
  `Kontaktinis_nr` varchar(255) DEFAULT NULL,
  `Miestas` varchar(255) DEFAULT NULL,
  `fk_Imoneid_Imone` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Sukurta duomenų kopija lentelei `sandeliai`
--

INSERT INTO `sandeliai` (`id`, `Adresas`, `Talpa`, `Kontaktinis_nr`, `Miestas`, `fk_Imoneid_Imone`) VALUES
(1, 'Siaures pr. 24', 112, '123456789', 'Toli', 1),
(3, 'kaskur 3', 20, '44444444', 'Vilnius', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `sandeliaitiekejo`
--

CREATE TABLE IF NOT EXISTS `sandeliaitiekejo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Adresas` varchar(255) DEFAULT NULL,
  `Talpa` int(11) DEFAULT NULL,
  `Kontaktinis_nr` varchar(255) DEFAULT NULL,
  `Miestas` varchar(255) DEFAULT NULL,
  `fk_Tiekejas` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Tiekejas` (`fk_Tiekejas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Sukurta duomenų kopija lentelei `sandeliaitiekejo`
--

INSERT INTO `sandeliaitiekejo` (`id`, `Adresas`, `Talpa`, `Kontaktinis_nr`, `Miestas`, `fk_Tiekejas`) VALUES
(1, 'Partizanu g. 65', 500, '865432541', 'Kaunas', '45aaa45852a'),
(4, 'Partizanu g. 81', 400, '865481470', 'Kaunas', '45aaa45852a'),
(5, 'Partizanu g. 50', 500, '86542156', 'Kaunas', '45aaa45852a');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tiekejas`
--

CREATE TABLE IF NOT EXISTS `tiekejas` (
  `imones_kodas` varchar(255) NOT NULL,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  `Ikurimolaikas` date DEFAULT NULL,
  `miestas` varchar(255) DEFAULT NULL,
  `gatve` varchar(255) DEFAULT NULL,
  `telefonoNumeris` int(11) DEFAULT NULL,
  PRIMARY KEY (`imones_kodas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `tiekejas`
--

INSERT INTO `tiekejas` (`imones_kodas`, `Pavadinimas`, `Ikurimolaikas`, `miestas`, `gatve`, `telefonoNumeris`) VALUES
('45aaa45852a', 'DaugNori', '2006-12-11', 'Kaunas', 'Partizanu g. 85', 865432154);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tiekejoatstovas`
--

CREATE TABLE IF NOT EXISTS `tiekejoatstovas` (
  `redagavimas` int(1) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_Darbuotojasid_Darbuotojas` varchar(255) NOT NULL,
  `fk_Tiekejas` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `atstovauja` (`fk_Darbuotojasid_Darbuotojas`),
  KEY `fk_Tiekejas` (`fk_Tiekejas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Sukurta duomenų kopija lentelei `tiekejoatstovas`
--

INSERT INTO `tiekejoatstovas` (`redagavimas`, `id`, `fk_Darbuotojasid_Darbuotojas`, `fk_Tiekejas`) VALUES
(1, 20, 'Klientas', '45aaa45852a'),
(0, 21, 'Klientas2', '45aaa45852a'),
(0, 22, 'Magician', '45aaa45852a');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tiekimokontraktas`
--

CREATE TABLE IF NOT EXISTS `tiekimokontraktas` (
  `prVardas` varchar(255) DEFAULT NULL,
  `prPavarde` varchar(255) DEFAULT NULL,
  `tkVardas` varchar(255) DEFAULT NULL,
  `tkPavarde` varchar(255) DEFAULT NULL,
  `sudData` date DEFAULT NULL,
  `nutData` date DEFAULT NULL,
  `patvirtinta` tinyint(1) DEFAULT NULL,
  `miestas` varchar(255) DEFAULT NULL,
  `sutartiesKodas` varchar(255) NOT NULL,
  `fk_Tiekejas` varchar(255) NOT NULL,
  `fk_Imone` int(11) unsigned NOT NULL,
  PRIMARY KEY (`sutartiesKodas`),
  KEY `prekesTieke` (`fk_Tiekejas`),
  KEY `fk_Imone` (`fk_Imone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `tiekimokontraktas`
--

INSERT INTO `tiekimokontraktas` (`prVardas`, `prPavarde`, `tkVardas`, `tkPavarde`, `sudData`, `nutData`, `patvirtinta`, `miestas`, `sutartiesKodas`, `fk_Tiekejas`, `fk_Imone`) VALUES
('Antanas', 'Antanauskas', 'Jonas', 'Jonas', '2016-12-16', NULL, 1, 'Kaunas', '456as4da64a', '45aaa45852a', 1),
('asdasda', 'asdas', 'Petras', 'asdasda', '2016-12-23', NULL, 1, 'Kaunas', '6as5d4s', '45aaa45852a', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tipai`
--

CREATE TABLE IF NOT EXISTS `tipai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Sukurta duomenų kopija lentelei `tipai`
--

INSERT INTO `tipai` (`id`, `Pavadinimas`) VALUES
(1, 'LED'),
(2, 'generictype2'),
(5, 'tipas'),
(13, '500g'),
(14, '250g'),
(15, 'medine'),
(16, 'asdasd');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tipaitiekejo`
--

CREATE TABLE IF NOT EXISTS `tipaitiekejo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Sukurta duomenų kopija lentelei `tipaitiekejo`
--

INSERT INTO `tipaitiekejo` (`id`, `Pavadinimas`) VALUES
(1, '500g'),
(2, '250g'),
(6, 'LED'),
(7, 'asd'),
(8, 'medine'),
(9, 'asdasd'),
(10, 'Gelezinei remai');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `turi1`
--

CREATE TABLE IF NOT EXISTS `turi1` (
  `fk_prekesid_prekes` int(11) NOT NULL,
  `fk_krepselisid_krepselis` int(11) NOT NULL,
  PRIMARY KEY (`fk_prekesid_prekes`,`fk_krepselisid_krepselis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `turi2`
--

CREATE TABLE IF NOT EXISTS `turi2` (
  `fk_tipaiid_tipai` int(11) NOT NULL,
  `fk_prekesid_prekes` int(11) NOT NULL,
  PRIMARY KEY (`fk_tipaiid_tipai`,`fk_prekesid_prekes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `turi2`
--

INSERT INTO `turi2` (`fk_tipaiid_tipai`, `fk_prekesid_prekes`) VALUES
(1, 1),
(1, 11),
(5, 1),
(13, 1),
(14, 9),
(14, 11),
(15, 9),
(16, 11);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `turi3`
--

CREATE TABLE IF NOT EXISTS `turi3` (
  `fk_tipaiTiekejo` int(11) NOT NULL,
  `fk_PrekesTiekejo` int(11) NOT NULL,
  PRIMARY KEY (`fk_tipaiTiekejo`,`fk_PrekesTiekejo`),
  KEY `fk_PrekesTiekejo` (`fk_PrekesTiekejo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `turi3`
--

INSERT INTO `turi3` (`fk_tipaiTiekejo`, `fk_PrekesTiekejo`) VALUES
(1, 1),
(2, 9),
(2, 10),
(6, 10),
(8, 9),
(9, 10);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `fk_InternetinisPuslapisid_InternetinisPuslapis` int(11) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`username`, `password`, `userid`, `userlevel`, `email`, `timestamp`, `fk_InternetinisPuslapisid_InternetinisPuslapis`) VALUES
('Klientas', 'fe01ce2a7fbac8fafaed7c982a04e229', '997f48efbd77e12d194c960c2299bbd5', 1, 'Remigijus@one.lt', 1481535682, 1),
('SandelioVadovas', 'fe01ce2a7fbac8fafaed7c982a04e229', '5659d8bcfaebb548f366305e59cbece7', 5, 'Algimantux@one.lt', 1481535690, 1),
('Sandelininkas', 'fe01ce2a7fbac8fafaed7c982a04e229', 'accd5a51cd529caa4d42750992200b03', 5, 'Aruniux@one.lt', 1481496644, 1),
('Kurjeris', 'fe01ce2a7fbac8fafaed7c982a04e229', 'd6ef242380990d0c3d666a92d06865e2', 5, 'Mindaugelis@one.lt', 1481459725, 1),
('ImonesVadovas', 'fe01ce2a7fbac8fafaed7c982a04e229', '0', 5, 'Rauliux@one.lt', 1481146359, 1),
('Administratorius', '21232f297a57a5a743894a0e4a801fc3', '90999d40daa94751a16f3f34ebde18fc', 9, 'Administratorius@Administratorius.com', 1481534687, 1),
('Klientas2', 'fe01ce2a7fbac8fafaed7c982a04e229', 'f99018758475ec087587c1ce0c311772', 1, 'Linux@one.lt', 1481534460, 1),
('SandelioVadovas2', 'fe01ce2a7fbac8fafaed7c982a04e229', 'dc719b5008714808757df8faa1107a22', 5, 'Juliux@one.lt', 1481379160, 1),
('belekas', '2a7fb93af9f75024fa25606ed74e1b81', '0', 5, 'belekas@belekas.com', 1481533403, 1),
('Klientas3', 'fe01ce2a7fbac8fafaed7c982a04e229', '9a5e9f8f1b51af1220e5942f073e858a', 1, 'Tomauskas@gmail.com', 1481470304, 1),
('demodemo2', 'fe01ce2a7fbac8fafaed7c982a04e229', 'fb8e4667ccf1891fe5fe2acfc54e9437', 5, 'demoo@demo.cpm', 1481534202, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `uzsakymas`
--

CREATE TABLE IF NOT EXISTS `uzsakymas` (
  `atsiemimoAdresas` varchar(255) DEFAULT NULL,
  `uzsakymoNr` int(11) NOT NULL AUTO_INCREMENT,
  `uzsakymoData` date DEFAULT NULL,
  `uzsakymoSuma` double DEFAULT NULL,
  `uzsakymoPirkiniuKrepselis` int(11) DEFAULT NULL,
  `uzsakymoBusena` int(11) DEFAULT NULL,
  `fk_Klientasid_Klientas` int(11) NOT NULL,
  `fk_uzsakymoGavejasid_uzsakymoGavejas` int(11) NOT NULL,
  `fk_uzsakymo_busenosid_uzsakymo_busenos` int(11) NOT NULL,
  `fk_mokejimaiid_mokejimai` int(11) NOT NULL,
  PRIMARY KEY (`uzsakymoNr`),
  KEY `uzsakymoBusena` (`uzsakymoBusena`),
  KEY `sudaro` (`fk_Klientasid_Klientas`),
  KEY `gauna` (`fk_uzsakymoGavejasid_uzsakymoGavejas`),
  KEY `turi` (`fk_uzsakymo_busenosid_uzsakymo_busenos`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Sukurta duomenų kopija lentelei `uzsakymas`
--

INSERT INTO `uzsakymas` (`atsiemimoAdresas`, `uzsakymoNr`, `uzsakymoData`, `uzsakymoSuma`, `uzsakymoPirkiniuKrepselis`, `uzsakymoBusena`, `fk_Klientasid_Klientas`, `fk_uzsakymoGavejasid_uzsakymoGavejas`, `fk_uzsakymo_busenosid_uzsakymo_busenos`, `fk_mokejimaiid_mokejimai`) VALUES
('Jotvingiu g. 43', 2, '2016-12-08', 1500, 2, 4, 2, 2, 4, 2);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `uzsakymogavejas`
--

CREATE TABLE IF NOT EXISTS `uzsakymogavejas` (
  `id` int(11) NOT NULL,
  `vardas` varchar(255) DEFAULT NULL,
  `pavarde` varchar(255) DEFAULT NULL,
  `elpastas` varchar(255) DEFAULT NULL,
  `telefono_nr` varchar(255) DEFAULT NULL,
  `atsiemimo_adresas` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `uzsakymogavejas`
--

INSERT INTO `uzsakymogavejas` (`id`, `vardas`, `pavarde`, `elpastas`, `telefono_nr`, `atsiemimo_adresas`) VALUES
(1, 'Paulius', 'Paulius', 'Paulius@mail.com', '846542178', 'Birzu g. 7'),
(2, 'Andrius', 'Andriauskas', 'Andriauskas@gmail.com', '863822250', 'Jotvingiu g. 45');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `uzsakymo_busenos`
--

CREATE TABLE IF NOT EXISTS `uzsakymo_busenos` (
  `id_uzsakymo_busenos` int(11) NOT NULL,
  `name` char(19) NOT NULL,
  PRIMARY KEY (`id_uzsakymo_busenos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `uzsakymo_busenos`
--

INSERT INTO `uzsakymo_busenos` (`id_uzsakymo_busenos`, `name`) VALUES
(1, 'nepatvirtintas'),
(2, 'laukiama_apmokejimo'),
(3, 'patvirtintas'),
(4, 'atsauktas'),
(5, 'sugrazintas'),
(6, 'pristatytas');

--
-- Apribojimai eksportuotom lentelėm
--

--
-- Apribojimai lentelei `algolapis`
--
ALTER TABLE `algolapis`
  ADD CONSTRAINT `algolapis_ibfk_1` FOREIGN KEY (`fk_Darbuotojasid_Darbuotojas`) REFERENCES `darbuotojas` (`id`);

--
-- Apribojimai lentelei `darbuotojas`
--
ALTER TABLE `darbuotojas`
  ADD CONSTRAINT `darbuotojas_ibfk_1` FOREIGN KEY (`fk_Imoneid_Imone`) REFERENCES `imone` (`imones_kodas`),
  ADD CONSTRAINT `yra` FOREIGN KEY (`pareigos`) REFERENCES `pareigostipai` (`id_PareigosTipai`) ON DELETE NO ACTION;

--
-- Apribojimai lentelei `imone`
--
ALTER TABLE `imone`
  ADD CONSTRAINT `imone_ibfk_1` FOREIGN KEY (`fk_InternetinisPuslapisid_InternetinisPuslapis`) REFERENCES `internetinispuslapis` (`id`);

--
-- Apribojimai lentelei `klientas`
--
ALTER TABLE `klientas`
  ADD CONSTRAINT `klientas_ibfk_1` FOREIGN KEY (`fk_Imoneid_Imone`) REFERENCES `imone` (`imones_kodas`);

--
-- Apribojimai lentelei `perkamaissandelio`
--
ALTER TABLE `perkamaissandelio`
  ADD CONSTRAINT `perkamaissandelio_ibfk_1` FOREIGN KEY (`fk_PrUzsakymasid_PrUzsakymas`) REFERENCES `pruzsakymas` (`id`),
  ADD CONSTRAINT `perkamaissandelio_ibfk_2` FOREIGN KEY (`fk_prekesid_prekes`) REFERENCES `prekestiekejo` (`id`);

--
-- Apribojimai lentelei `prekestiekejo`
--
ALTER TABLE `prekestiekejo`
  ADD CONSTRAINT `prekestiekejo_ibfk_1` FOREIGN KEY (`fk_TiekejoSandeliai`) REFERENCES `sandeliaitiekejo` (`id`);

--
-- Apribojimai lentelei `prekiusarasas`
--
ALTER TABLE `prekiusarasas`
  ADD CONSTRAINT `prekiusarasas_ibfk_1` FOREIGN KEY (`fk_Tiekejas`) REFERENCES `tiekejas` (`imones_kodas`),
  ADD CONSTRAINT `prekiusarasas_ibfk_2` FOREIGN KEY (`fk_ImonesSandelys`) REFERENCES `sandeliai` (`id`);

--
-- Apribojimai lentelei `pruzsakymas`
--
ALTER TABLE `pruzsakymas`
  ADD CONSTRAINT `pruzsakymas_ibfk_1` FOREIGN KEY (`fk_PrekiuSarasasid_PrekiuSarasas`) REFERENCES `prekiusarasas` (`uzsakymoKodas`),
  ADD CONSTRAINT `pruzsakymas_ibfk_2` FOREIGN KEY (`fk_Preke`) REFERENCES `prekestiekejo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Apribojimai lentelei `sandeliaitiekejo`
--
ALTER TABLE `sandeliaitiekejo`
  ADD CONSTRAINT `sandeliaitiekejo_ibfk_1` FOREIGN KEY (`fk_Tiekejas`) REFERENCES `tiekejas` (`imones_kodas`);

--
-- Apribojimai lentelei `tiekejoatstovas`
--
ALTER TABLE `tiekejoatstovas`
  ADD CONSTRAINT `tiekejoatstovas_ibfk_2` FOREIGN KEY (`fk_Tiekejas`) REFERENCES `tiekejas` (`imones_kodas`),
  ADD CONSTRAINT `tiekejoatstovas_ibfk_3` FOREIGN KEY (`fk_Darbuotojasid_Darbuotojas`) REFERENCES `klientas` (`slapyvardis`);

--
-- Apribojimai lentelei `tiekimokontraktas`
--
ALTER TABLE `tiekimokontraktas`
  ADD CONSTRAINT `tiekimokontraktas_ibfk_1` FOREIGN KEY (`fk_Tiekejas`) REFERENCES `tiekejas` (`imones_kodas`),
  ADD CONSTRAINT `tiekimokontraktas_ibfk_2` FOREIGN KEY (`fk_Imone`) REFERENCES `imone` (`imones_kodas`);

--
-- Apribojimai lentelei `turi3`
--
ALTER TABLE `turi3`
  ADD CONSTRAINT `turi3_ibfk_1` FOREIGN KEY (`fk_tipaiTiekejo`) REFERENCES `tipaitiekejo` (`id`),
  ADD CONSTRAINT `turi3_ibfk_2` FOREIGN KEY (`fk_PrekesTiekejo`) REFERENCES `prekestiekejo` (`id`);

--
-- Apribojimai lentelei `uzsakymas`
--
ALTER TABLE `uzsakymas`
  ADD CONSTRAINT `uzsakymas_ibfk_1` FOREIGN KEY (`fk_Klientasid_Klientas`) REFERENCES `klientas` (`id`),
  ADD CONSTRAINT `uzsakymas_ibfk_3` FOREIGN KEY (`fk_uzsakymoGavejasid_uzsakymoGavejas`) REFERENCES `uzsakymogavejas` (`id`),
  ADD CONSTRAINT `uzsakymas_ibfk_4` FOREIGN KEY (`fk_uzsakymo_busenosid_uzsakymo_busenos`) REFERENCES `uzsakymo_busenos` (`id_uzsakymo_busenos`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
