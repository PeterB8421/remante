-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pát 27. zář 2019, 08:08
-- Verze serveru: 10.1.39-MariaDB
-- Verze PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `remante`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `produkty`
--

CREATE TABLE `produkty` (
  `id` int(10) UNSIGNED NOT NULL,
  `cena` int(11) NOT NULL,
  `popis` text CHARACTER SET latin1,
  `kod` varchar(15) CHARACTER SET latin1 NOT NULL,
  `typy_produktu_id` int(11) NOT NULL,
  `vyrobci_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `produkty`
--

INSERT INTO `produkty` (`id`, `cena`, `popis`, `kod`, `typy_produktu_id`, `vyrobci_id`) VALUES
(3, 5313, '', '0414720313', 5, 2),
(4, 5139, 'Nový vst?ikova? Common Rail BOSCH PIEZO 0445116030\n', '0445116030', 3, 2),
(5, 8452, 'Repasované vysokotlaké ?erpadlo Common rail DELPHI DFP1 R9042A041A repasujeme s použitím originálních náhradních díl? a je kalibrováno na certifikovaném testovacím za?ízení výrobce tohoto dílu. Repasovaný výrobek se funk?n? rovná stavu nového kusu.', 'R9042A041A', 2, 3),
(6, 24109, 'Nové vysokotlaké ?erpadlo Common rail BOSCH CP4 0445010507\n', '0445010507', 2, 2),
(7, 21900, 'Podávací modul pro vst?ikovaní mo?oviny, známý také pod názvem AdBlue, repasujeme s použitím originálních náhradních díl? a následn? je repasovaný díl kalibrován na testovacím za?ízení výrobce tohoto dílu. Repasovaný výrobek se funk?n? rovná stavu nového kusu.', '0444010008', 8, 2),
(9, 4887, 'Nový zadní tlumi? pérování A1643200725\n', 'A1643200725', 6, 2),
(10, 44080, 'Repasované vst?ikovací ?erpadlo Zexel VRZ 109144-3062 repasujeme s použitím originálních náhradních díl? a je kalibrováno na certifikovaném testovacím za?ízení výrobce tohoto dílu. Repasovaný výrobek se funk?n? rovná stavu nového kusu.', '109144-3062', 1, 1),
(11, 123456, 'Nový vst?ikova? Common Rail BOSCH CRI 0445110338\n', '0445110338', 3, 2),
(12, 7093, 'Repasované turbodmychadlo s podtlakovou regulací GARRETT 454231-5010S repasujeme s použitím originálních náhradních díl? a je kalibrováno na certifikovaném testovacím za?ízení výrobce tohoto dílu. Repasovaný výrobek se funk?n? rovná stavu nového kusu.', '454231-5010S', 4, 1),
(13, 17164, 'Repasované vst?ikovací ?erpadlo BOSCH VP44 0470504005 repasujeme s použitím originálních náhradních díl? a je kalibrováno na certifikovaném testovacím za?ízení výrobce tohoto dílu. Repasovaný výrobek se funk?n? rovná stavu nového kusu.', '0470504005', 1, 2),
(14, 12500, '', '0445116030', 3, 2),
(15, 5376, 'Repasované turbodmychadlo s podtlakovou regulací GARRETT 753420-5006S repasujeme s použitím originálních náhradních díl? a je kalibrováno na certifikovaném testovacím za?ízení výrobce tohoto dílu. Repasovaný výrobek se funk?n? rovná stavu nového kusu.', '753420-5006S', 4, 4);

-- --------------------------------------------------------

--
-- Struktura tabulky `typy_produktu`
--

CREATE TABLE `typy_produktu` (
  `id` int(11) NOT NULL,
  `typ` varchar(100) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `typy_produktu`
--

INSERT INTO `typy_produktu` (`id`, `typ`) VALUES
(1, 'Vstřikovací čerpadla'),
(2, 'Vysokotlaká čerpadla'),
(3, 'Vstřikovače'),
(4, 'Turbodmychadla'),
(5, 'Čerpadlo - tryska PDE/UIS'),
(6, 'Tlumiče a kompresory pérovaní'),
(7, 'Díly řízení'),
(8, 'AdBlue - Podávací modul pro vstřikování močoviny');

-- --------------------------------------------------------

--
-- Struktura tabulky `vyrobci`
--

CREATE TABLE `vyrobci` (
  `id` int(11) NOT NULL,
  `vyrobce` varchar(45) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `vyrobci`
--

INSERT INTO `vyrobci` (`id`, `vyrobce`) VALUES
(1, 'Zexel'),
(2, 'Bosch'),
(3, 'Delphi'),
(4, 'Garett');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_typy_produktu_id` (`typy_produktu_id`),
  ADD KEY `fk_vyrobci_id` (`vyrobci_id`);

--
-- Klíče pro tabulku `typy_produktu`
--
ALTER TABLE `typy_produktu`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `vyrobci`
--
ALTER TABLE `vyrobci`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pro tabulku `typy_produktu`
--
ALTER TABLE `typy_produktu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pro tabulku `vyrobci`
--
ALTER TABLE `vyrobci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `produkty`
--
ALTER TABLE `produkty`
  ADD CONSTRAINT `fk_typy_produktu_id` FOREIGN KEY (`typy_produktu_id`) REFERENCES `typy_produktu` (`id`),
  ADD CONSTRAINT `fk_vyrobci_id` FOREIGN KEY (`vyrobci_id`) REFERENCES `vyrobci` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
