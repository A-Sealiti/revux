-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 24 nov 2024 om 22:02
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scooters`
--
CREATE DATABASE IF NOT EXISTS `scooters` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `scooters`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `merk`
--

CREATE TABLE `merk` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `merk`
--

INSERT INTO `merk` (`id`, `naam`) VALUES
(1, 'vespa'),
(2, 'piaggio'),
(3, 'sym');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `scooter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `review_tekst` text NOT NULL,
  `datum` date NOT NULL,
  `sterren` int(11) NOT NULL,
  `scooter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `reviews`
--

INSERT INTO `reviews` (`id`, `naam`, `review_tekst`, `datum`, `sterren`, `scooter_id`) VALUES
(1, 'appie', 'hello', '0000-00-00', 2, 1),
(2, 'se\\a', 'prima', '0000-00-00', 1, 1),
(3, 'appie', 'prima', '0000-00-00', 1, 1),
(4, 'appie', 'prima', '0000-00-00', 1, 1),
(5, 'appie', 'prima', '0000-00-00', 1, 1),
(6, 'appie', 'prima', '0000-00-00', 1, 1),
(7, 'appie', 'prima', '0000-00-00', 1, 1),
(8, 'appie', 'prima', '0000-00-00', 1, 1),
(9, 'appie', 'prima', '0000-00-00', 1, 1),
(10, 'appie', 'prima', '0000-00-00', 1, 1),
(11, 'appie', 'prima', '0000-00-00', 1, 1),
(12, 'appie', 'qq', '0000-00-00', 1, 1),
(13, 'appie', 'qq', '0000-00-00', 1, 1),
(14, 'appie', 'prima', '0000-00-00', 1, 1),
(15, 'appie', 'prima', '0000-00-00', 1, 1),
(16, 'appie', 'sddd', '0000-00-00', 1, 1),
(17, 'appie', 'sddd', '0000-00-00', 1, 1),
(18, 'johan', 'uitstekend', '0000-00-00', 1, 1),
(19, 'vince', 'prima service', '0000-00-00', 2, 1),
(20, 'appie', 'prima', '0000-00-00', 3, 1),
(21, 'appie', 'prima', '0000-00-00', 1, 1),
(22, 'appie', 'prima', '0000-00-00', 1, 1),
(23, 'sea', 'goed', '0000-00-00', 2, 1),
(24, 'Leandro', 'scammers ', '2024-10-23', 1, 2),
(26, 'Johan ', 'goed', '2024-10-29', 4, 2),
(27, 'pieter', 'goed', '2024-10-30', 4, 4),
(29, 'ouassim', 'prima ', '2024-11-14', 4, 2),
(30, 'vic', 'goed', '2024-11-14', 4, 3),
(31, 'nasim', 'prima', '2024-11-14', 5, 5),
(32, 'hakim', 'prima', '2024-11-14', 4, 2),
(33, 'chahid', 'prima', '2024-11-15', 4, 13),
(34, 'abdel ', 'qq', '2024-11-20', 5, 1),
(35, 'sqsa', 'qsq', '2024-11-20', 3, 13),
(36, 'faf', 'dfggd', '2024-11-20', 4, 4),
(37, 'Johan ', 'assa', '2024-11-20', 5, 1),
(38, 'abdel ', 'asas', '2024-11-20', 4, 1),
(39, 'Milan ', 'sdxd', '2024-11-20', 3, 13),
(40, 'Johan ', 'sss', '2024-11-20', 4, 11),
(41, 'abdel ', 'wws', '2024-11-20', 4, 2),
(42, 'abdel ', 'wws', '2024-11-20', 4, 2),
(43, 'abdel ', 'wws', '2024-11-20', 4, 2),
(44, 'Johan ', 'szsas', '2024-11-20', 5, 2),
(45, 'Johan ', 'szsas', '2024-11-20', 5, 2),
(46, 'faf', 'prima', '2024-11-20', 4, 6),
(47, 'safae', 'goeie service\r\n', '2024-11-20', 3, 2),
(48, 'Johan ', 'sas', '2024-11-20', 4, 11),
(49, 'Johan ', 'qqq', '2024-11-20', 4, 5),
(50, 'Johan ', 'asas', '2024-11-20', 4, 4),
(51, 'Johan ', 'asas', '2024-11-20', 4, 4),
(52, 'faf', 'sAS', '2024-11-20', 3, 4),
(53, 'Milan ', 'sdas', '2024-11-21', 4, 7),
(54, '54', 'cvsfdgf', '2024-11-21', 4, 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scooter_brands`
--

CREATE TABLE `scooter_brands` (
  `id` int(11) NOT NULL,
  `naam` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `jaar` varchar(255) NOT NULL,
  `kleur` varchar(255) NOT NULL,
  `afbeelding` varchar(255) NOT NULL,
  `beschrijving` text NOT NULL,
  `prijs` decimal(7,0) NOT NULL,
  `merk_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `scooter_brands`
--

INSERT INTO `scooter_brands` (`id`, `naam`, `model`, `jaar`, `kleur`, `afbeelding`, `beschrijving`, `prijs`, `merk_id`) VALUES
(1, 'Vespa ', 'Sprint 50 4T', '2023', 'Matgrijs', 'Sprint504T.png', 'De Vespa is een van de populairste scooters ter wereld. Een uniek design met een afwerking die we niet snel zien bij andere scooters. De Vespa Sprint is de enige scooter met een volledige stalen body. De Sprint uitvoering is de meest sportieve variant. Kenmerken zijn de ovale spiegels en de vierkante koplamp. Deze Vespa is uitgevoerd met het splinternieuwe injectie euro- 4-takt motorblok.', 5299, '1'),
(2, 'Vespa', 'GTV-300', '2024', ' Beige Avvolgente Matt', 'GTV_300.jpg', 'The GTV 300 HPE is the perfect blend of vintage design and modern performance. Immerse yourself in the nostalgia of classic Vespa charm while experiencing the cutting-edge technology and the power of the GTV 300.', 8190, '1'),
(3, 'Vespa', '946 Dragon 125', '2023', ' Beige Dragon', '946_Dragon_125.jpg', 'Het DNA van de Vespa wordt gecombineerd met de legendarische kracht van de draak in deze exclusieve collectie. Deze genummerde limited edition van slechts 1888 stuks is een viering van dit majestueuze symbool van de maankalender. De Vespa 946 Dragon bruist van energie en explosieve kracht en is een collector\'s item dat een samensmelting is van cultuur, innovatie en buitengewone aandacht voor detail.', 12300, '1'),
(4, 'Vespa', 'Primavera Tech 125', '2022', ' Blu Energico Matt', 'Primavera_Tech_125jpg.jpg', 'De Vespa Primavera Tech voegt technologische innovatie toe aan de authentieke Vespa Primavera om een stijl te creëren die modern en exclusief is. Verchroomde afwerkingen en iriserende kleuren combineren perfect in een opvallend lichtspel, terwijl contrasterende fluogroene details een hightech, dynamische touch geven.', 6478, '1'),
(5, 'Piaggio', 'Medley 125 ABS', '2023', 'Nero Abisso', 'Piaggio_Medley_125_ABS.jpg', 'Het vloeistofgekoelde 125cc iGet motorblok van de Medley 125 is vloeistofgekoeld en heeft een elektronisch injectiesysteem. Hierdoor is de motor zuiniger en zijn de start prestaties veel beter.\r\n\r\n \r\n\r\nDe Medley 125 is een echte stadsmotorscooter en is bijzonder goed hanteerbaar door het lage gewicht en grote wielen. Het voorwiel is 16 inch groot.\r\n\r\n \r\n\r\nDe Piaggio Medley 125 heeft een Stop-Start systeem dat automatisch afslaar als de scooter 3 tot 7 seconden stilstaat. Om opnieuw te starten hoef je alleen een beetje aan het gas te draaien en de eencilinder motor start soepel en stil. Ideaal bij stadsritten waar u regelmatig voor het soplicht stilstaat! De Medley 125 is hierdoor extreem zuinig in verbruik.\r\n\r\n \r\n\r\nDe Piaggio Medley is de enige motorscooter in zijn soort die bergruimte heeft voor maar liefst twee integraalhelmen onder het zadel. Het ruime opbergvak in het beenschild heeft een USB-stekker en de Medley 125 heeft ook nog een handige tassenhaak.\r\n\r\n \r\n\r\nOp het digitale scherm leest u zeer overzichtelijk de kilometerstand, teller, temperatuur, accutoestand en overige gegevens af. ', 3627, '2'),
(6, 'Piaggio', 'Zip 50 4S3V', '2019', 'nero lucido ', 'Zip_50_4S3.jpg', 'De verovering van ruimtes\r\n\r\nZijn functionele details zijn ontworpen voor maximaal rendement. Onder het zadel vinden we een opbergvak voor een integraalhelm, terwijl het handschoenenkastje in het beenschild een afsluitbare klep heeft om documenten en andere essentiële zaken in op te bergen. De uitrusting wordt afgerond met een tassenhaak onder het stuur.\r\nVan alle markten thuis\r\n\r\nMet een keuze uit drie zithoogtes, van 750, 765 en 780 mm, vindt u op de Zip 50 in een oogwenk de perfecte rijpositie om snel mee door de stad te zoeven en als u stilstaat de voeten veilig op de grond te zetten. Over comfort gesproken: monteer een USB-poort op uw Zip EURO5 (optional)!\r\nFris en jong design\r\n\r\nHij valt op door zijn lichte lijnen en veelzijdige stijl; aan de voorkant zien we langwerpige optische elementen met de richtingaanwijzers en de uniek gevormde koplamp in het stuur.\r\ni-get motor\r\n\r\nDe Zip wordt aangedreven door een moderne ééncilinder viertakt met i-get-technologie en geoptimaliseerd klepbedieningsmechanisme met drie kleppen om het brandstofverbruik en de vervuilende emissies te verminderen.\r\n', 2429, '2'),
(7, 'Vespa ', 'Elettrica Aluminium/Azzurro Blau', '2023', ' aluminium grijze kleur', 'vespa-elettrica-azzurro-elettrico.png', 'De Vespa Elettrica wordt geleverd in een aluminium grijze kleur. Deze blauwe uitvoering heeft blauwe accenten. Namelijk de sierlijsten, zijkant van de velgen, de grille en de zadelbies. Qua uiterlijk is hij gelijk aan de welbekende Vespa Primavera. Je levert dus niets in op het uiterlijk als je deze elektrische scooter gaat rijden. Hij ziet er net zo retro uit als de andere Vespa modellen. Maar dan elektrisch.\r\n\r\nDe Vespa Elettrica heeft een mooi TFT-kleurenbeeldscherm en een mogelijkheid om je telefoon te koppelen aan de e-scooter. Zo kun je handsfree bellen en muziek luisteren. Hiervoor heb je wel een Bluetooth helm nodig. Op het scherm kun je zien hoe vol je accu nog is, hoeveel kilometer je nog kunt rijden, je snelheid, de temperatuur en hoe laat het is.\r\n\r\nQua zitcomfort is de Vespa Elettrica ook gelijk aan de Vespa Primavera. Je kunt dus heel makkelijk met twee personen rijden. En beide hebben genoeg ruimte om comfortabel te zitten.', 7259, '1'),
(8, 'Piaggio', 'Piaggio Liberty S', '2023', 'Mat Grijs', 'Piaggio_Liberty_S.jpg', 'De Piaggio Liberty S is een 50cc bromscooter met een laag brandstofverbruik en een hoog comfort.\r\n\r\nHet instrumentenpaneel combineert analoge en digitale displays.\r\n\r\n \r\n\r\nDe Piaggio Liberty S valt meteen op met zijn grote 16 inch wielen, deze dragen ook bij aan het comfort en veiligheid van de scooter. Door zijn grote wielen heeft u meer contact met het wegdek en hierdoor krijgt u een betere wegligging.\r\n\r\n \r\n\r\nDe Piaggio Liberty Sport is uitgerust met de nieuwste generatie zuinige iGet-motoren. Deze injectie motoren zijn vergeleken met de vorige Liberty een stuk zuiniger, efficiënter, schoner en stiller geworden.', 2719, '2'),
(9, 'Piaggio', 'One Elektrische Scooter', '2024', 'Grijs', 'Piaggio_One.png', 'DE PIAGGIO ONE ELEKTRISCHE SCOOTER! DE EERSTE ELEKTRISCHE PIAGGIO.\r\n\r\nEen compacte elektrische scooter van het merk Piaggio. De One is de eerste Piaggio die volledig elektrisch op de markt gezet wordt. Het design is een combinatie tussen de Zip2000 en de Zip SP. Door zijn scherpe lijnen en verschillende kleurstellingen is het uiterlijk meer dan stoer te noemen.\r\n\r\nDe Piaggio One is uitgevoerd met een 48V 29AH accu in combinatie met een 1200watt Piaggio motor.\r\nDe aangeven actieradius van Piaggio is maximaal 55km met de 45km/u uitvoering.', 2929, '2'),
(10, 'SYM', 'E-Fiddle IV', '2024', 'Matt Black ', 'SYM_eFiddle.jpg', '\r\n\r\nOnze nieuwste toevoeging aan de Fiddle-familie is de E-Fiddle IV. Bij SYM blijft de tijdloze combinatie van klassieke elegantie en moderne technologie onze drijfveer. De Fiddle-serie is het bewijs dat we het beste van vroeger combineren met de technologische innovaties van vandaag.\r\n\r\nDe E-Fiddle IV is uitgerust met een krachtige en onderhoudsvrije elektromotor in het achterwiel. Deze motor is fluisterstil en zorgt voor een plezierige rijervaring. Onder het zadel vind je een krachtige accu met hoogwaardige LG-cellen.\r\n\r\nDoor te kiezen voor de SYM E-Fiddle IV draag je niet alleen bij aan stijlvol vervoer, maar ook aan een schoner milieu. Ontdek alle voordelen van de SYM E-Fiddle IV\r\n', 2999, '3'),
(11, 'SYM', 'Jet 4 RX', '2023', 'Blue/Black ', 'Jet4RX_50_YM.jpg', 'De Jet 4 RX is de perfecte keuze voor jonge scooterrijders die avontuur en sportiviteit in stedelijke omgevingen zoeken. De Jet 4 RX onderscheidt zich door zijn sportieve uitstraling, wat wordt versterkt door zijn handkappen, \'naked\' stuur en dynamische ontwerp.\r\n\r\nDe Jet 4 RX is de ultieme metgezel voor stedelijke avonturiers die op zoek zijn naar een mix van stijl, prestaties en zuinigheid. Ervaar de opwinding van de stad in stijl met deze scooter en profiteer van zijn moderne functies, geavanceerde verlichting en solide remmen voor een veilige en plezierige rit.\r\n\r\nOntdek alle voordelen van de Jet 4 RX', 2498, '3'),
(12, 'SYM', 'Jet14 125 AC', '2023', 'Matt Black ', 'Jet14_125AC_SYM.jpg', 'stadsscooterrijder. Met 14-inch wielen en een korte wielbasis biedt deze scooter de perfecte combinatie van stabiliteit en wendbaarheid, waardoor \'urban touring\' een geheel nieuwe betekenis krijgt.\r\n\r\nDe SYM Jet 14 AC beschikt over een ruimte tankinhoud van 7,5 liter. Brengt optimaal comfort met zich mee en heeft een overzichtelijk dashboard voor alle belangrijke informatie binnen handbereik', 3569, '3'),
(13, 'SYM', 'Fiddle II Injectie ', '2023', '\r\nLake Blue ', 'Fiddle2_M4_SYM.jpg', 'De best verkochte SYM, de Fiddle II, is nu beschikbaar met injectietechnologie! Deze vernieuwde Fiddle II behoudt zijn vertrouwde kenmerken, maar profiteert van de voordelen van injectie.\r\n\r\nZo heeft de Fiddle II een lager brandstofverbruik, minder CO2-uitstoot en een verbeterde verbranding. De SYM Fiddle II met injectie is verkrijgbaar in zowel een 25 km/u als een 45 km/u versie. Welke past het beste bij jou?', 2398, '3'),
(19, 'vespa', 'Vespa Primavera 50 4T Euro 5 RST', '2024', 'zwart', 'vespa_1732218312.jpeg', 'fsdfrw', 28909, '1');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `role` enum('member','admin') CHARACTER SET macce COLLATE macce_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `first_name`, `last_name`, `role`) VALUES
(1, 'a.sealiti@al-yaqeen.com', 'Abdel!2009', 'abdel', 'sealiti', 'member'),
(2, 'gebruiker0-70@gmail.com', 'Geenstress', 'abd', 'sea', 'member'),
(3, 'seaappie324@gmail.com', 'geenstress]', 'abd', 'sea', 'member'),
(4, 'gebruiker@gmail.com', 'grr', 'abd', 'sea', 'member'),
(5, 'a.sealiti@al-ya.com', 'geenstrees', 'al', 'yaqeen', 'member'),
(6, '302947338@student.rocmondriaan.nl', '$2y$10$.o26wqMiXkbYhFP8keGLCuQrMhbtjs100ToQQOFX0iYZrDF2T9uDm', 'abdel', 'sealiti', 'member'),
(7, 'Abdel.wahad.sea@gmail.com', '$2y$10$xDiOV3hrM1ktrXKy2eGyEOWnzzwJfVCd1GTfSWMBqghoIjWm8tFAe', 'abdel', 'sealiti', 'member'),
(8, 'gebruiker.070@gmail.com', '$2y$10$uNnwbI3viG6hN4.DzzYM0.kBoAXYUDKZaAIODtsXX101O7yzJ5..a', 'abd', 'sea', 'member'),
(9, 'Admin.070@gmail.com', '$2y$10$behnLEZWv4zvDFvOjGujW.buXCMxoa5YlPQa5kYuHFKhYZrPFzqZK', 'abdel', 'sealiti', 'member'),
(10, 'Admin.07@gmail.com', '$2y$10$ylfJ7E9a7SKBoxtCUg0C0uTf/23XbLWrVyOR2P7rwPTh2hBeynzWW', 'abdel', 'sealiti', 'member'),
(11, 'app.sea@outlook.be', '$2y$10$OQV2D9EHHSu/BUSTt5Drt.3O/GieevATvo1bmscDO1pDdMznBHibe', 'sea', 'app', 'member'),
(12, 'Admin.0@gmail.com', '$2y$10$fFDnMT5DpOGBTF4a4L1.4uEPx9/4sciiZ0M4ASDhl2pK/Zyc5OC2.', 'sea', 'sea', 'member'),
(13, 'Admin@gmail.com', '$2y$10$0UmyQPG6WY8OgqCrnwhB9.e01sRD7VZnDrSv/xaWhjPIXq5NQm2r6', 'admin', 'admin', 'member'),
(14, 'Admin.27@gmail.com', '$2y$10$vF6EEVwRv0PfWBtKoewFKekgePvJKyTEIYvYw9Al34KjN8H7mVwlO', 'abd', 'sealiti', 'member'),
(15, 'wahad.sea@etf.nl', '$2y$10$iRHo0PqtMeaSHdH73VkVJuouKSdQ275tCQwW2yIckQMUBCZlFzPQK', 'wahad', 'sealiti', 'member'),
(16, 'wahad.seali@etf.nl', '$2y$10$MltPCzA1LkD34YWH1AecauhftflGGVa7jJXRxU9nEaINqpYWg9kPW', 'abdel', 'sea', 'member'),
(17, 'abdel@now.etf.nl', '$2y$10$LGq1tIZxqYR/NlUsEDBk4.rwbyxU353x3efUAPLMg.taiRZZKBdiW', 'abdel', 'sealiti', 'member'),
(18, 'abdel.sea@now.etf.nl', '$2y$10$6nnR/lPJgl1ws6tNOgicyu6bhiuJqzJpA65osdgNYu5vzjMI98sv6', 'abdel', 'sealiti', 'admin'),
(19, 'abdel.sea21@now.etf.nl', '$2y$10$IUboamDrakoYvxv4XuiSG.1rgUdJ.U9I8g8npEKPlnZnRfAJ/aL8i', 'seal', 'abdel', 'admin');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `merk`
--
ALTER TABLE `merk`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scooter_id` (`scooter_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scooter_id` (`scooter_id`);

--
-- Indexen voor tabel `scooter_brands`
--
ALTER TABLE `scooter_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `merk`
--
ALTER TABLE `merk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT voor een tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT voor een tabel `scooter_brands`
--
ALTER TABLE `scooter_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `merk`
--
ALTER TABLE `merk`
  ADD CONSTRAINT `merk_ibfk_1` FOREIGN KEY (`id`) REFERENCES `scooter_brands` (`id`);

--
-- Beperkingen voor tabel `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`scooter_id`) REFERENCES `scooter_brands` (`id`),
  ADD CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Beperkingen voor tabel `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`scooter_id`) REFERENCES `scooter_brands` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
