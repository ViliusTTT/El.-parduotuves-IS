-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u6
-- http://www.phpmyadmin.net
--
-- Darbinė stotis: localhost
-- Atlikimo laikas: 2016 m. Grd 07 d. 21:36
-- Serverio versija: 1.0.27
-- PHP versija: 5.6.28-1~dotdeb+zts+7.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Duomenų bazė: `viltur`
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

--
-- Sukurta duomenų kopija lentelei `active_guests`
--

INSERT INTO `active_guests` (`ip`, `timestamp`) VALUES
('127.0.0.1', 1481146562);

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
('SandelioVadovas2', 1481146547),
('Administratorius', 1481146447),
('Kurjeris', 1481146562);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `algolapis`
--

CREATE TABLE IF NOT EXISTS `algolapis` (
  `kiekis` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_Darbuotojasid_Darbuotojas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priima` (`fk_Darbuotojasid_Darbuotojas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Sukurta duomenų kopija lentelei `darbuotojas`
--

INSERT INTO `darbuotojas` (`slapyvardis`, `vardas`, `pavarde`, `prisijungimo_data`, `id`, `pareigos`, `fk_Imoneid_Imone`) VALUES
('SandelioVadovas', 'Algimantas', 'Lopata', '2016-12-07 23:29:46', 10, 1, 1),
('Sandelininkas', 'Arunce', 'Siskauskas', '2016-12-07 23:30:29', 11, 2, 1),
('Kurjeris', 'Mindaugelis', 'Stasiuklis', '2016-12-07 23:31:15', 12, 3, 1),
('Pardaveja', 'Eugenija', 'Sedziene', '2016-12-07 23:32:09', 13, 4, 1),
('ImonesVadovas', 'Raulis', 'Mamadlou', '2016-12-07 23:32:39', 14, 5, 1),
('SandelioVadovas2', 'Julius', 'Juliauskas', '2016-12-07 23:35:11', 15, 1, 1);

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
  KEY `naudojasi_paslaugomis` (`fk_Imoneid_Imone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Sukurta duomenų kopija lentelei `klientas`
--

INSERT INTO `klientas` (`slapyvardis`, `vardas`, `pavarde`, `el_pastas`, `prisijungimo_data`, `adresas`, `tel_numeris`, `slaptazodis`, `id`, `fk_Imoneid_Imone`) VALUES
('Klientas', 'Remigijus', 'Einikis', 'Remigijus@one.lt', '2016-12-07 23:28:37', 'Baltupiu 18 Kaunas', 86546514, 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 1),
('Klientas2', 'Linas', 'Karalius', 'Linux@one.lt', '2016-12-07 23:34:35', 'Karaliu g Skuodas', 45687, 'fe01ce2a7fbac8fafaed7c982a04e229', 2, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `krepselis`
--

CREATE TABLE IF NOT EXISTS `krepselis` (
  `id` int(11) NOT NULL,
  `prekesKiekis` int(11) DEFAULT NULL,
  `prekesModelis` varchar(255) DEFAULT NULL,
  `prekesPrioritetas` int(11) DEFAULT NULL,
  `fk_Uzsakymasid_Uzsakymas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_Uzsakymasid_Uzsakymas` (`fk_Uzsakymasid_Uzsakymas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`fk_prekesid_prekes`,`fk_PrUzsakymasid_PrUzsakymas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekes`
--

CREATE TABLE IF NOT EXISTS `prekes` (
  `id` int(11) NOT NULL,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  `Galiojimas` date DEFAULT NULL,
  `Kaina` double DEFAULT NULL,
  `Kiekis` int(11) DEFAULT NULL,
  `fk_sandeliaiid_sandeliai` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priklauso` (`fk_sandeliaiid_sandeliai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekestiekejo`
--

CREATE TABLE IF NOT EXISTS `prekestiekejo` (
  `id` int(11) NOT NULL,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  `Galiojimas` date DEFAULT NULL,
  `Kaina` double DEFAULT NULL,
  `Kiekis` int(11) DEFAULT NULL,
  `fk_TiekejoSandeliai` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priklauso` (`fk_TiekejoSandeliai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `prekiusarasas`
--

CREATE TABLE IF NOT EXISTS `prekiusarasas` (
  `uzsakymoPatvirtinimas` tinyint(1) DEFAULT NULL,
  `uzsakymoData` date DEFAULT NULL,
  `pristatymoData` date DEFAULT NULL,
  `norimasPristatymas` date DEFAULT NULL,
  `uzsakymoKodas` varchar(255) NOT NULL,
  `fk_Tiekejas` int(11) NOT NULL,
  PRIMARY KEY (`uzsakymoKodas`),
  KEY `tiekejas` (`fk_Tiekejas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `prekesKaina` double DEFAULT NULL,
  `id` int(11) NOT NULL,
  `fk_PrekiuSarasasid_PrekiuSarasas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uzsake` (`fk_PrekiuSarasasid_PrekiuSarasas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `id` int(11) NOT NULL,
  `Adresas` varchar(255) DEFAULT NULL,
  `Talpa` int(11) DEFAULT NULL,
  `Kontaktinis_nr` varchar(255) DEFAULT NULL,
  `Miestas` varchar(255) DEFAULT NULL,
  `fk_Imoneid_Imone` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `sandeliaitiekejo`
--

CREATE TABLE IF NOT EXISTS `sandeliaitiekejo` (
  `id` int(11) NOT NULL,
  `Adresas` varchar(255) DEFAULT NULL,
  `Talpa` int(11) DEFAULT NULL,
  `Kontaktinis_nr` varchar(255) DEFAULT NULL,
  `Miestas` varchar(255) DEFAULT NULL,
  `fk_Tiekejas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tiekejas`
--

CREATE TABLE IF NOT EXISTS `tiekejas` (
  `Pavadinimas` varchar(255) DEFAULT NULL,
  `Ikurimolaikas` date DEFAULT NULL,
  `imonesKodas` int(11) NOT NULL,
  `miestas` varchar(255) DEFAULT NULL,
  `gatve` varchar(255) DEFAULT NULL,
  `telefonoNumeris` int(11) DEFAULT NULL,
  PRIMARY KEY (`imonesKodas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tiekejoatstovas`
--

CREATE TABLE IF NOT EXISTS `tiekejoatstovas` (
  `atstovas` tinyint(1) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `id` int(11) NOT NULL,
  `fk_Darbuotojasid_Darbuotojas` int(11) NOT NULL,
  `fk_Tiekejas` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `atstovauja` (`fk_Darbuotojasid_Darbuotojas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `fk_Tiekejas` int(11) NOT NULL,
  `fk_Imone` int(11) NOT NULL,
  PRIMARY KEY (`sutartiesKodas`),
  KEY `prekesTieke` (`fk_Tiekejas`),
  KEY `fk_Imone` (`fk_Imone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tipai`
--

CREATE TABLE IF NOT EXISTS `tipai` (
  `id` int(11) NOT NULL,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `tipaitiekejo`
--

CREATE TABLE IF NOT EXISTS `tipaitiekejo` (
  `id` int(11) NOT NULL,
  `Pavadinimas` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `turi3`
--

CREATE TABLE IF NOT EXISTS `turi3` (
  `fk_tipaiTiekejo` int(11) NOT NULL,
  `fk_PrekesTiekejo` int(11) NOT NULL,
  PRIMARY KEY (`fk_tipaiTiekejo`,`fk_PrekesTiekejo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('Klientas', 'fe01ce2a7fbac8fafaed7c982a04e229', 'c2eaa841fbfe184f7700726de0c876ce', 1, 'Remigijus@one.lt', 1481146128, 1),
('SandelioVadovas', 'fe01ce2a7fbac8fafaed7c982a04e229', '0', 5, 'Algimantux@one.lt', 1481146186, 1),
('Sandelininkas', 'fe01ce2a7fbac8fafaed7c982a04e229', '0', 5, 'Aruniux@one.lt', 1481146229, 1),
('Kurjeris', 'fe01ce2a7fbac8fafaed7c982a04e229', '5227ce1a006945b27d704b10f7a2e9d8', 5, 'Mindaugelis@one.lt', 1481146562, 1),
('Pardaveja', 'fe01ce2a7fbac8fafaed7c982a04e229', '0', 5, 'Zosiux@one.lt', 1481146329, 1),
('ImonesVadovas', 'fe01ce2a7fbac8fafaed7c982a04e229', '0', 5, 'Rauliux@one.lt', 1481146359, 1),
('Administratorius', '21232f297a57a5a743894a0e4a801fc3', '36850ff31e12a5cd5c101425a1c7f50c', 9, 'Administratorius@Administratorius.com', 1481146447, 1),
('Klientas2', 'fe01ce2a7fbac8fafaed7c982a04e229', '0', 1, 'Linux@one.lt', 1481146475, 1),
('SandelioVadovas2', 'fe01ce2a7fbac8fafaed7c982a04e229', '92f36a9f3e0d5ac1214fd49c2df8ad69', 5, 'Juliux@one.lt', 1481146547, 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `uzsakymas`
--

CREATE TABLE IF NOT EXISTS `uzsakymas` (
  `atsiemimoAdresas` varchar(255) DEFAULT NULL,
  `uzsakymoNr` int(11) NOT NULL,
  `uzsakymoData` date DEFAULT NULL,
  `uzsakymoSuma` double DEFAULT NULL,
  `uzsakymoPirkiniuKrepselis` int(11) DEFAULT NULL,
  `uzsakymoBusena` int(11) DEFAULT NULL,
  `fk_Klientasid_Klientas` int(11) NOT NULL,
  `fk_uzsakymoGavejasid_uzsakymoGavejas` int(11) NOT NULL,
  `fk_uzsakymo_busenosid_uzsakymo_busenos` int(11) NOT NULL,
  `fk_mok?jimaiid_mok?jimai` int(11) NOT NULL,
  PRIMARY KEY (`uzsakymoNr`),
  KEY `uzsakymoBusena` (`uzsakymoBusena`),
  KEY `sudaro` (`fk_Klientasid_Klientas`),
  KEY `gauna` (`fk_uzsakymoGavejasid_uzsakymoGavejas`),
  KEY `turi` (`fk_uzsakymo_busenosid_uzsakymo_busenos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Apribojimai lentelei `turi3`
--
ALTER TABLE `turi3`
  ADD CONSTRAINT `turi3_ibfk_1` FOREIGN KEY (`fk_tipaiTiekejo`) REFERENCES `tipaitiekejo` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
