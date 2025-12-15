-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 02:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `media`
--

-- --------------------------------------------------------

--
-- Table structure for table `actors`
--

CREATE TABLE `actors` (
  `ActorID` int(11) NOT NULL,
  `ActorName` varchar(30) NOT NULL,
  `GenderID` int(11) NOT NULL,
  `NationalityID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `actors`
--

INSERT INTO `actors` (`ActorID`, `ActorName`, `GenderID`, `NationalityID`) VALUES
(1, 'Bruce Willis', 1, 1),
(2, 'Arnold Schwarzenegger', 1, 2),
(3, 'Earl Boen', 1, 1),
(4, 'Xander Berkeley', 1, 1),
(5, 'Linda Hamilton', 2, 1),
(6, 'Omar Sharif', 1, 3),
(7, 'Antonio Benderass', 1, 4),
(8, 'Diane Venora', 2, 1),
(9, 'Richard Bremmer', 1, 5),
(10, 'Cameron Diaz', 2, 1),
(11, 'Dennis Farina', 1, 1),
(12, ' Ashton Kutcher', 1, 1),
(13, 'Queen Latifah', 2, 1),
(14, 'Gabriel Byrne', 1, 6),
(15, 'Miriam Margolyes', 2, 7),
(16, 'Rainer Judd', 2, 1),
(17, 'Ed Harris', 1, 1),
(18, 'Jude Law', 1, 5),
(19, 'Bob Hoskins', 1, 5),
(20, 'Rachel Weisz', 2, 8),
(21, 'Keanu Reeves', 1, 9),
(22, 'Sandra Bullock', 2, 1),
(23, 'Dennis Hopper', 1, 1),
(24, 'Joe Morton', 1, 1),
(25, 'Jeff Daniels', 1, 1),
(26, 'Chris Cooper', 1, 1),
(27, 'Mel Gibson', 1, 1),
(28, 'Tom Wilkinson', 1, 5),
(29, 'Jason Isaacs', 1, 5),
(30, 'Heath Ledger', 1, 7),
(31, 'Lisa Brenner', 2, 1),
(32, 'Gary Oldman', 1, 5),
(33, 'Milla Jovovich', 2, 1),
(34, 'Mathieu Kassovitz', 1, 10),
(35, 'Tom Cruise', 1, 1),
(36, 'Courtney B. Vance', 1, 1),
(37, 'Annabelle Wallis', 2, 5),
(38, 'Jack Johnson', 1, 1),
(39, 'Sofia Boutella', 2, 11),
(40, 'Samuel L. Jackson', 1, 1),
(41, 'Brie Larson', 2, 1),
(42, 'John Goodman', 1, 1),
(43, 'Tom Hiddleston', 1, 5),
(44, 'Jing Tian', 2, 12),
(45, 'John Carradine', 1, 1),
(46, 'Charlton Heston', 1, 1),
(47, 'Yvonne De Carlo', 2, 1),
(48, 'Judith Anderson', 2, 13),
(49, 'Edward G. Robinson', 1, 14),
(50, 'Yul Brynner', 1, 15),
(51, 'John Derek', 1, 1),
(52, 'Anne Baxter', 2, 1),
(53, 'Denzel Washington', 1, 1),
(54, 'Bill Pullman', 1, 1),
(55, 'David Harbour', 1, 1),
(56, 'Haley Bennett', 2, 1),
(57, 'Marton Csokas', 1, 16),
(58, 'Melissa Leo', 2, 1),
(59, 'Chloë Grace Moretz', 2, 1),
(60, 'Vladimir Kulich', 1, 17),
(61, 'David Meunier', 1, 1),
(62, 'Guy Pearce', 1, 13),
(63, 'Henry Cavill', 1, 18),
(64, 'Luis Guzmán', 1, 19),
(65, 'Jim Caviezel', 1, 1),
(66, 'Richard Harris', 1, 6),
(67, 'Christopher Adamson', 1, 18),
(68, 'Barry Cassin', 1, 6),
(69, 'Briana Corrigan', 2, 6),
(70, 'Vera Farmiga', 2, 1),
(71, 'Patrick Wilson', 1, 1),
(72, 'Mackenzie Foy', 2, 1),
(73, 'Shannon Kook', 1, 20),
(74, 'Joseph Bishara', 1, 1),
(75, 'John Brotherton', 1, 1),
(76, 'Shanley Caswell', 2, 1),
(77, 'Ralph Fiennes', 1, 5),
(78, 'Helena Bonham Carter', 2, 5),
(79, 'Dicken Ashworth', 1, 5),
(80, 'Mark Gatiss', 1, 5),
(81, 'Pete Atkin', 1, 18),
(82, 'Vincent Ebrahim', 1, 20),
(83, 'Robert Horvath', 1, 5),
(84, 'Peter Kay', 1, 5),
(85, 'Jeroen Krabbé', 1, 21),
(86, 'Gerard Horan', 1, 5),
(87, 'Isabella Rossellini', 2, 22),
(88, 'Luigi Diberti', 1, 22),
(89, 'Michael Culkin', 1, 6),
(90, 'Rory Edwards', 1, 5),
(91, 'Hannes Flaschberger', 1, 5),
(92, 'Christopher Fulford', 1, 18),
(93, 'Donal Gibson', 1, 1),
(94, 'Valeria Golino', 2, 22),
(95, 'Marco Hofschneider', 1, 8),
(96, 'Barry Humphries', 1, 13),
(97, 'Matthew North', 1, 5),
(98, 'Alexandra Pigg', 2, 18),
(99, 'Johanna ter Steege', 2, 21),
(100, 'Kevin Costner', 1, 1),
(101, 'Robert Duvall', 1, 1),
(102, 'Michael Gambon', 1, 6),
(103, 'Michael Jeter', 1, 1),
(104, 'Annette Bening', 2, 1),
(105, 'Peter MacNeill', 1, 9),
(106, 'James Russo', 1, 1),
(107, 'Kim Coates', 1, 9),
(108, 'Diego Luna', 1, 1),
(109, 'Abraham Benrubi', 1, 1),
(110, 'Patricia Stutz', 2, 1),
(112, 'hassan', 1, 23),
(113, 'hassan', 1, 1),
(114, 'hassan', 1, 12),
(115, 'hassan', 1, 1),
(116, 'Mhammad', 1, 18);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`) VALUES
(5, 'Family'),
(6, 'Animation'),
(7, 'Horror'),
(9, 'Comedy'),
(10, 'Adventure'),
(11, 'Musical'),
(13, 'Fantasy'),
(15, 'War'),
(17, 'Terrorism'),
(18, 'Children'),
(19, 'Classic'),
(20, 'Religion');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `CityID` int(11) NOT NULL,
  `CityName` varchar(20) NOT NULL,
  `CountryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`CityID`, `CityName`, `CountryID`) VALUES
(1, 'Beirut', 1),
(2, 'Tripoli', 1),
(3, 'Saida', 1),
(4, 'Zahle', 1),
(5, 'Nabatieh', 1),
(6, 'Halba', 1),
(7, 'Cairo', 5),
(8, 'Alexandria', 5),
(9, 'Amman', 4),
(10, 'Kuweit', 3),
(11, 'Paris', 6),
(12, 'London', 7),
(13, 'Washington', 8),
(14, 'Dallas', 8),
(15, 'New York', 8),
(16, 'Brussels', 11),
(17, 'Riad', 2),
(18, 'Mecca', 2),
(19, 'Nice', 6),
(20, 'Lyon', 6),
(21, 'Toulouse', 6),
(22, 'Cambridge', 7),
(23, 'Chester', 7),
(24, 'Buenos Aires', 9),
(25, 'Rosario', 9),
(26, 'Vienna', 10),
(27, 'Graz', 10),
(28, 'Linz', 10),
(29, 'Bilzen', 11),
(30, 'Landen', 11),
(31, 'Giza', 5),
(32, 'Banha', 5),
(33, 'Suez', 5),
(34, 'Abha', 2),
(35, 'Dhahran', 2),
(36, 'Jeddah', 2),
(37, 'Qatif', 2),
(38, 'Al Wafrah', 3);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `ClientID` int(11) NOT NULL,
  `FName` varchar(20) NOT NULL,
  `LName` varchar(20) NOT NULL,
  `GenderID` int(11) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(150) NOT NULL,
  `CountryID` int(11) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(80) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`ClientID`, `FName`, `LName`, `GenderID`, `Phone`, `Address`, `CountryID`, `Username`, `Password`, `type`) VALUES
(4, 'mohammad', 'haj', 1, '74000000', 'Beirut - Cornish Al Mazraa main street', 1, 'Georges', 'G123@500', 0),
(7, 'Majed', 'Hashem', 1, '+961 81 901 814', 'Beirut - Basta', 1, 'Majed', 'Majed12', 0),
(8, 'Roy', 'Daher', 1, '+961 3 743 627', 'Beirut - Barbir ', 1, 'Roy', 'Rr12345', 0),
(9, 'Silvia', 'Jawhar', 2, '+961 3 458 127', 'Beirut - Mar Elias', 1, 'Silvia', '123456', 0),
(10, 'Mirna', 'Salwan', 2, '+961 3 147 910', 'Beirut - Cornish Al Mazraa', 1, 'Mirna', '555555', 0),
(11, 'Hadi', 'Ghadar', 1, '+961 80 479 631', 'Beirut - Barbir', 1, 'Hadi', '333333', 0),
(12, 'Salma', 'Sayegh', 2, '+961 71 885 747', 'Beirut - Rawshi', 1, 'Salma', '757575', 0),
(13, 'Ahmad', 'Mansour', 1, '+961 3 898 894', 'Beirut - Manara main street', 1, 'Ahmad', '999888', 0),
(14, 'Sami', 'Hamoud', 1, '+961 71 632 114', 'Beirut - Cornish Al Mazraa', 1, 'Sami', '787878', 0),
(15, 'Elham', 'Allam', 2, '+961 80 445 671', 'Beirut - Haret Hreik', 1, 'Elham', '999999', 0),
(17, 'Raed', 'Intabli', 1, '+961 71 848 996', 'Tripoli - Azmi street', 1, 'Raed', '141414', 0),
(18, 'Samira', 'Farhat', 2, '+961070 556 184', 'Beirut - Bshara Al Khoury', 1, 'Samira', '123456', 0),
(19, 'Hussein', 'Al Mokdad', 1, '+961 3 991 992', 'Baalbek - Riha ', 1, 'Hussein', '111111', 0),
(20, 'Zein', 'Zaiter', 1, '+961 80 661 442', 'Byblos - Afka', 1, 'Zein', '777777', 0),
(21, 'Hassan', 'Fahes', 1, '81872206', 'Nabatieh', 1, 'Hassan123', 'Hassan123', 1),
(22, 'jfkljf', 'fksjfsdlk', 1, '874593', 'jdsffs', 2, 'snf,f', 'nsm,dnf,sd', 0),
(23, 'jfkljf', 'fksjfsdlk', 1, '874593', 'jdsffs', 2, 'snf,f', 'nsm,dnf,sd', 0),
(24, 'mohammd', 'ali', 1, '898589', 'nabtyeh toul', 1, 'root', '', 0),
(25, 'mohammd', 'ali', 1, '898589', 'nabtyeh toul', 1, 'mohammad 123', 'mohammadn123', 0),
(26, 'Hassan', 'Fahes', 1, '898589', 'nabtyeh toul', 1, 'hsn123', 'hsn123', 0),
(27, 'mohammd', 'Fahes', 1, '898589', 'nabtyeh toul', 1, 'mhm123', 'mhm123', 0),
(34, 'ali', 'Fahes', 1, '898589', 'nabtyeh toul', 1, '123', '123', 0),
(35, 'ali', 'Fahes', 1, '898589', 'nabtyeh toul', 1, '123', '123', 0),
(36, 'ali', 'Fahes', 1, '898589', 'nabtyeh toul', 1, '1234', '$2y$10$JPUpaaLwsRqjoQaHgxMV/u00SsUqxiOcp9xs2qwtd89kcilfRWnnK', 0),
(39, 'ali', 'Fahes', 1, '898589', 'nabtyeh toul', 1, 'hassan123', '$2y$10$UhRhltdJK0c2w1rdsPPzQueyFLgO4I08SqrT1pD2dXy7D95Vanzxy', 1),
(40, 'ali', 'Fahes', 1, '898589', 'nabtyeh toul', 1, '1', '$2y$10$V5ntCSd48u3QAK04vFjZIeltNBP4Ol120aFv5IGiMo2yKR3Pu2Dl.', 0),
(41, 'mohammad', 'lolo', 1, '03333333', 'nabatieh', 1, 'mhm', '$2y$10$jfWvgkVhB5Kyklr.bzK.2ei2EdiIgl1RcXv85ukvWx9L.fWGAqTfW', 1),
(42, 'George', 'haj', 1, '71878246', 'kurah', 1, '12345', '$2y$10$qmehM1BBObl09k.LcI3HFOjILFlJ6fT0RZOgKKfjoREeUE5ckBZQ2', 0),
(43, 'lalaa', 'km mlkm', 1, '74000000', 'nabatieh', 1, 'mhm222', '$2y$10$uxQSih1cyuhJ7xjqvuZfRusrrNsKbC8/DEIhMakNWVNG4SKwGmopa', 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `CountryID` int(11) NOT NULL,
  `CountryName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`CountryID`, `CountryName`) VALUES
(1, 'Lebanon'),
(2, 'Saudi Arabia'),
(3, 'Kuwait'),
(4, 'Jordan'),
(5, 'Egypt'),
(6, 'France'),
(7, 'U.K'),
(8, 'United States'),
(9, 'Argentina'),
(10, 'Austria'),
(11, 'Belgium');

-- --------------------------------------------------------

--
-- Table structure for table `directors`
--

CREATE TABLE `directors` (
  `DirectorID` int(11) NOT NULL,
  `DirectorName` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `directors`
--

INSERT INTO `directors` (`DirectorID`, `DirectorName`) VALUES
(1, 'John McTiernan'),
(2, 'Michael Crichton'),
(3, 'Tom Vaughan'),
(4, 'Peter Hyams'),
(5, 'Jean-Jacques Annaud'),
(6, 'Jan de Bont'),
(7, 'Roland Emmerich'),
(8, 'Luc Besson'),
(9, 'Alex Kurtzman'),
(10, 'Jordan Vogt-Roberts'),
(11, 'Cecil B. DeMille'),
(12, 'Antoine Fuqua'),
(13, 'Kevin Reynolds'),
(14, 'James Wan'),
(15, 'Nick Park'),
(16, 'Bernard Rose'),
(17, 'Kevin Costner');

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `GenderID` int(11) NOT NULL,
  `GenderName` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`GenderID`, `GenderName`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `movieactors`
--

CREATE TABLE `movieactors` (
  `ID` int(11) NOT NULL,
  `MovieID` int(11) NOT NULL,
  `ActorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `movieactors`
--

INSERT INTO `movieactors` (`ID`, `MovieID`, `ActorID`) VALUES
(3, 1, 1),
(4, 2, 6),
(5, 2, 7),
(6, 2, 8),
(7, 2, 9),
(8, 5, 10),
(9, 5, 12),
(10, 5, 11),
(11, 5, 13),
(12, 1, 5),
(13, 6, 2),
(14, 6, 14),
(15, 6, 15),
(16, 6, 16),
(17, 7, 17),
(18, 7, 18),
(19, 7, 19),
(20, 7, 20),
(24, 3, 2),
(25, 3, 5),
(26, 3, 24),
(27, 8, 21),
(28, 8, 22),
(29, 8, 23),
(30, 8, 24),
(31, 8, 25),
(32, 9, 26),
(33, 9, 27),
(34, 9, 28),
(35, 9, 29),
(36, 9, 30),
(37, 9, 31),
(38, 10, 1),
(40, 10, 32),
(41, 10, 33),
(42, 10, 34),
(43, 11, 35),
(44, 11, 36),
(45, 11, 37),
(46, 11, 38),
(47, 11, 39),
(48, 12, 40),
(49, 12, 41),
(50, 12, 42),
(51, 12, 43),
(52, 12, 44),
(53, 13, 45),
(54, 13, 46),
(55, 13, 47),
(56, 13, 48),
(57, 13, 49),
(58, 13, 50),
(59, 13, 51),
(60, 13, 52),
(61, 15, 62),
(62, 15, 63),
(63, 15, 64),
(64, 15, 65),
(65, 15, 66),
(66, 15, 67),
(67, 15, 68),
(68, 15, 69),
(69, 16, 70),
(70, 16, 71),
(71, 16, 72),
(72, 16, 73),
(73, 16, 74),
(74, 16, 75),
(75, 16, 76),
(76, 17, 77),
(77, 17, 78),
(78, 17, 79),
(79, 17, 80),
(80, 17, 81),
(81, 17, 82),
(82, 17, 83),
(83, 17, 84),
(84, 18, 32),
(85, 18, 85),
(86, 18, 86),
(87, 18, 15),
(88, 18, 87),
(89, 18, 88),
(90, 18, 89),
(91, 18, 90),
(92, 18, 91),
(93, 18, 92),
(94, 18, 93),
(95, 18, 94),
(96, 18, 95),
(97, 18, 96),
(98, 18, 97),
(99, 18, 98),
(100, 18, 99),
(101, 19, 100),
(102, 19, 101),
(103, 19, 102),
(104, 19, 103),
(105, 19, 104),
(106, 19, 105),
(107, 19, 106),
(108, 19, 107),
(109, 19, 108),
(110, 19, 109),
(111, 19, 110),
(113, 1, 2),
(114, 26, 1),
(115, 27, 20),
(116, 27, 15),
(117, 2, 1),
(118, 30, 1),
(119, 30, 1),
(120, 30, 1),
(121, 30, 1),
(122, 30, 1),
(123, 30, 1),
(124, 30, 1),
(125, 31, 1),
(126, 31, 1),
(127, 31, 1),
(128, 31, 1),
(129, 31, 1),
(130, 31, 1),
(131, 31, 1),
(132, 32, 1),
(133, 32, 18),
(134, 32, 16),
(135, 32, 18),
(136, 33, 1),
(137, 34, 1),
(138, 35, 1),
(139, 36, 1),
(140, 37, 1),
(141, 37, 1),
(142, 38, 1),
(143, 38, 1),
(144, 38, 1),
(156, 43, 1),
(157, 44, 1),
(158, 45, 1),
(159, 46, 1),
(160, 47, 1),
(161, 48, 1),
(162, 49, 1);

-- --------------------------------------------------------

--
-- Table structure for table `moviecategories`
--

CREATE TABLE `moviecategories` (
  `ID` int(11) NOT NULL,
  `MovieID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `moviecategories`
--

INSERT INTO `moviecategories` (`ID`, `MovieID`, `CategoryID`) VALUES
(1, 2, 13),
(2, 2, 10),
(8, 1, 10),
(10, 1, 17),
(12, 3, 10),
(16, 5, 9),
(17, 6, 7),
(20, 6, 13),
(22, 6, 10),
(30, 7, 10),
(31, 7, 15),
(37, 8, 10),
(38, 8, 17),
(41, 9, 10),
(43, 9, 5),
(44, 9, 15),
(45, 10, 10),
(49, 10, 13),
(52, 11, 13),
(54, 11, 7),
(56, 12, 10),
(57, 12, 13),
(59, 13, 10),
(60, 13, 19),
(61, 13, 20),
(64, 14, 10),
(71, 15, 10),
(72, 15, 19),
(75, 16, 7),
(81, 17, 18),
(82, 17, 9),
(83, 17, 13),
(85, 17, 6),
(86, 17, 5),
(87, 18, 11),
(92, 18, 19),
(95, 19, 10),
(104, 27, 20),
(105, 27, 17),
(106, 27, 13),
(115, 32, 19),
(116, 33, 19),
(137, 49, 19),
(138, 49, 15);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `MovieID` int(11) NOT NULL,
  `Title` varchar(150) NOT NULL,
  `ProduceYear` int(11) NOT NULL,
  `UnitPrice` double NOT NULL,
  `Quantity` int(6) NOT NULL,
  `DirectorID` int(11) NOT NULL,
  `images` varchar(60) NOT NULL DEFAULT 'uploads/1.JPEG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`MovieID`, `Title`, `ProduceYear`, `UnitPrice`, `Quantity`, `DirectorID`, `images`) VALUES
(1, 'Die Hard', 1999, 7.35, 30, 1, 'uploads/1.JPEG'),
(2, '13th Warrior', 1988, 8.2, 42, 2, 'uploads/1.JPEG'),
(3, 'Terminator', 1984, 8.5, 27, 2, 'uploads/1.JPEG'),
(5, 'What Happens in Vegas', 2008, 8.6, 102, 3, 'uploads/1.JPEG'),
(6, 'End Of Days', 1999, 12.55, 80, 4, 'uploads/1.JPEG'),
(7, 'Enemy At The Gates', 2001, 10.6, 75, 5, 'uploads/1.JPEG'),
(8, 'Speed', 1994, 7.35, 60, 6, 'uploads/1.JPEG'),
(9, 'The Patriot', 2000, 9.4, 99, 7, 'uploads/1.JPEG'),
(10, 'The Fifth Element', 1997, 8.45, 50, 8, 'uploads/1.JPEG'),
(11, 'The Mummy', 2017, 11.35, 180, 9, 'uploads/1.JPEG'),
(12, 'Kong: Skull Island', 2017, 7.6, 33, 10, 'uploads/1.JPEG'),
(13, 'The Ten Commandments', 1956, 12.2, 100, 11, 'uploads/1.JPEG'),
(14, 'Equalizer ', 2014, 8.49, 65, 12, 'uploads/1.JPEG'),
(15, 'The Count Of Monte Cristo', 2002, 9.2, 50, 13, 'uploads/1.JPEG'),
(16, 'The Conjuring', 2013, 9.5, 120, 14, 'uploads/1.JPEG'),
(17, 'Wallace & Gromit: Curse Of The Were-Rabbit', 2018, 8.7, 250, 15, 'uploads/1.JPEG'),
(18, 'Immortal Beloved', 1999, 9.8, 66, 16, 'uploads/1.JPEG'),
(19, 'Open Range', 2003, 8.5, 100, 17, 'uploads/1.JPEG'),
(26, 'run 2', 2000, 20000, 19, 1, 'uploads/1.JPEG'),
(27, 'run 2', 2000, 20000, 19, 1, 'uploads/1.JPEG'),
(28, 'run', 1999, 20000, 9, 3, 'uploads/1.JPEG'),
(29, 'run  3333', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(30, 'run  3333', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(31, 'run  3333', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(32, 'run  333385459485498', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(33, 'run  333385459485498', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(34, 'run  333385459485498', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(35, 'run  333385459485498', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(36, 'run  333385459485498', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(37, 'run  333385459485498', 2000, 1000, 10, 1, 'uploads/1.JPEG'),
(38, 'action 2', 1988, 2500, 50, 14, 'uploads/1.JPEG'),
(43, 'hello', 2000, 20000, 19, 1, 'uploads/1.JPEG'),
(44, 'hello', 2000, 20000, 19, 1, 'uploads/1.JPEG'),
(45, 'hello', 2000, 20000, 19, 1, 'uploads/1.JPEG'),
(46, 'hello', 2000, 20000, 19, 1, 'uploads/1.JPEG'),
(47, 'hello', 2000, 20000, 19, 1, 'uploads/IMG_2b18c087d24948f0e245.jpeg'),
(48, 'hello', 2000, 20000, 19, 1, 'uploads/IMG_ff308906e7ff63229991.jpeg'),
(49, 'JHHKHKH', 2000, 20000, 19, 1, 'uploads/IMG_1ff5d5add604d5adb14d.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `nationalities`
--

CREATE TABLE `nationalities` (
  `NationalityID` int(11) NOT NULL,
  `NationalityName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `nationalities`
--

INSERT INTO `nationalities` (`NationalityID`, `NationalityName`) VALUES
(1, 'American'),
(2, 'Austrian'),
(3, 'Egyptian'),
(4, 'Spanish'),
(5, 'English'),
(6, 'Irish'),
(7, 'Australian'),
(8, 'German'),
(9, 'Canadian'),
(10, 'French'),
(11, 'Algerian'),
(12, 'Chinese'),
(13, 'Australian'),
(14, 'Romanian'),
(15, 'Russian'),
(16, 'New Zealand'),
(17, 'Czech'),
(18, 'British'),
(19, 'Puerto Rican'),
(20, 'South African'),
(21, 'Dutch'),
(22, 'Italian'),
(23, 'Lebanon'),
(24, 'palestine');

-- --------------------------------------------------------

--
-- Table structure for table `saledetail`
--

CREATE TABLE `saledetail` (
  `ID` int(11) NOT NULL,
  `SaleID` int(11) NOT NULL,
  `MovieID` int(11) NOT NULL,
  `Qty` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `saledetail`
--

INSERT INTO `saledetail` (`ID`, `SaleID`, `MovieID`, `Qty`) VALUES
(1, 36, 7, 2),
(2, 36, 6, 1),
(3, 37, 1, 2),
(4, 37, 3, 1),
(5, 37, 8, 1),
(6, 37, 2, 2),
(9, 39, 3, 2),
(10, 39, 9, 1),
(11, 40, 2, 2),
(77, 38, 15, 50),
(80, 38, 13, 2),
(85, 38, 7, 10),
(87, 38, 9, 2),
(88, 38, 16, 1),
(89, 38, 19, 1),
(90, 38, 1, 3),
(91, 38, 10, 1),
(92, 36, 2, 1),
(93, 43, 3, 29),
(94, 43, 17, 5),
(95, 43, 9, 4),
(96, 36, 2, 1),
(97, 43, 1, 9),
(98, 43, 2, 3),
(99, 45, 1, 2),
(100, 45, 2, 1),
(101, 43, 49, 1),
(102, 46, 1, 1),
(104, 48, 1, 1),
(105, 48, 2, 2),
(106, 48, 3, 5),
(107, 48, 7, 5),
(108, 48, 27, 5),
(109, 48, 49, 6),
(110, 49, 1, 0),
(111, 49, 2, 2),
(112, 50, 1, 1),
(115, 51, 1, 2),
(116, 51, 2, 2),
(117, 51, 5, 3),
(119, 52, 2, 2),
(121, 52, 9, 1),
(123, 52, 1, 1),
(126, 53, 2, 2),
(127, 54, 1, 1),
(128, 54, 3, 1),
(129, 55, 1, 1),
(134, 56, 3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SaleID` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `saleDate` timestamp NULL DEFAULT current_timestamp(),
  `Opened` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`SaleID`, `ClientID`, `saleDate`, `Opened`) VALUES
(36, 4, '2021-11-29 22:00:00', 0),
(37, 4, '2021-11-29 22:00:00', 0),
(38, 4, '2021-12-02 22:00:00', 1),
(39, 10, '2021-12-06 22:00:00', 1),
(40, 11, '2021-12-06 22:00:00', 1),
(43, 26, '2024-10-31 11:59:13', 1),
(44, 13, '2024-10-31 13:08:49', 1),
(45, 27, '2024-10-31 13:29:54', 1),
(46, 35, '2024-11-04 11:55:04', 1),
(48, 40, '2024-11-06 12:23:09', 1),
(49, 42, '2024-11-18 16:26:22', 0),
(50, 42, '2024-11-18 16:39:49', 0),
(51, 42, '2024-11-18 16:49:27', 0),
(52, 42, '2024-11-19 19:38:24', 0),
(53, 42, '2024-11-23 13:34:05', 0),
(54, 42, '2024-11-23 13:44:28', 0),
(55, 42, '2024-11-23 13:45:35', 1),
(56, 43, '2024-11-24 09:25:11', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`ActorID`),
  ADD KEY `GenderID` (`GenderID`),
  ADD KEY `NationalityID` (`NationalityID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`ClientID`),
  ADD KEY `GenderID` (`GenderID`),
  ADD KEY `CountryID` (`CountryID`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`CountryID`);

--
-- Indexes for table `directors`
--
ALTER TABLE `directors`
  ADD PRIMARY KEY (`DirectorID`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`GenderID`);

--
-- Indexes for table `movieactors`
--
ALTER TABLE `movieactors`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `MovieID` (`MovieID`),
  ADD KEY `ActorID` (`ActorID`);

--
-- Indexes for table `moviecategories`
--
ALTER TABLE `moviecategories`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `MovieID` (`MovieID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`MovieID`),
  ADD KEY `DirectorID` (`DirectorID`);

--
-- Indexes for table `nationalities`
--
ALTER TABLE `nationalities`
  ADD PRIMARY KEY (`NationalityID`);

--
-- Indexes for table `saledetail`
--
ALTER TABLE `saledetail`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `saleID` (`SaleID`),
  ADD KEY `movieID` (`MovieID`),
  ADD KEY `SaleID_2` (`SaleID`),
  ADD KEY `MovieID_2` (`MovieID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SaleID`),
  ADD KEY `clientID` (`ClientID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actors`
--
ALTER TABLE `actors`
  MODIFY `ActorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `ClientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `directors`
--
ALTER TABLE `directors`
  MODIFY `DirectorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `GenderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `movieactors`
--
ALTER TABLE `movieactors`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `moviecategories`
--
ALTER TABLE `moviecategories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `MovieID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `nationalities`
--
ALTER TABLE `nationalities`
  MODIFY `NationalityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `saledetail`
--
ALTER TABLE `saledetail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `SaleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actors`
--
ALTER TABLE `actors`
  ADD CONSTRAINT `actors_ibfk_1` FOREIGN KEY (`NationalityID`) REFERENCES `nationalities` (`NationalityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actors_ibfk_2` FOREIGN KEY (`GenderID`) REFERENCES `genders` (`GenderID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`GenderID`) REFERENCES `genders` (`GenderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`CountryID`) REFERENCES `countries` (`CountryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movieactors`
--
ALTER TABLE `movieactors`
  ADD CONSTRAINT `movieactors_ibfk_1` FOREIGN KEY (`MovieID`) REFERENCES `movies` (`MovieID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movieactors_ibfk_2` FOREIGN KEY (`ActorID`) REFERENCES `actors` (`ActorID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `moviecategories`
--
ALTER TABLE `moviecategories`
  ADD CONSTRAINT `moviecategories_ibfk_2` FOREIGN KEY (`MovieID`) REFERENCES `movies` (`MovieID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviecategories_ibfk_3` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`DirectorID`) REFERENCES `directors` (`DirectorID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `saledetail`
--
ALTER TABLE `saledetail`
  ADD CONSTRAINT `saledetail_ibfk_1` FOREIGN KEY (`SaleID`) REFERENCES `sales` (`SaleID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `saledetail_ibfk_2` FOREIGN KEY (`MovieID`) REFERENCES `movies` (`MovieID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
