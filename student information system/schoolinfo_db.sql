-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2024 at 04:25 PM
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
-- Database: `schoolinfo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `coursedescription_tbl`
--

CREATE TABLE `coursedescription_tbl` (
  `courseid` int(11) NOT NULL,
  `coursename` varchar(100) DEFAULT NULL,
  `strandid` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `roomid` int(11) DEFAULT NULL,
  `insname` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades_tbl`
--

CREATE TABLE `grades_tbl` (
  `grade_id` int(11) NOT NULL,
  `LRN` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `first_quarter` decimal(5,2) DEFAULT NULL,
  `second_quarter` decimal(5,2) DEFAULT NULL,
  `overall_grade` decimal(5,2) GENERATED ALWAYS AS (case when `second_quarter` is not null then (`first_quarter` + `second_quarter`) / 2 else NULL end) STORED,
  `remarks` tinyint(1) GENERATED ALWAYS AS (case when `overall_grade` is not null then if(`overall_grade` >= 75,0,1) else NULL end) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructorinfo_tbl`
--

CREATE TABLE `instructorinfo_tbl` (
  `instructorid` int(11) NOT NULL,
  `insname` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`insname`)),
  `contactNum` bigint(20) DEFAULT NULL,
  `strandid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_tbl`
--

CREATE TABLE `room_tbl` (
  `roomid` int(11) NOT NULL,
  `roomname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_tbl`
--

INSERT INTO `room_tbl` (`roomid`, `roomname`) VALUES
(1, 'acad'),
(2, 'new building'),
(3, 'old building'),
(4, 'dasalla');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `sectionid` int(11) NOT NULL,
  `sectionname` varchar(100) DEFAULT NULL,
  `strandid` int(11) DEFAULT NULL,
  `courseid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`sectionid`, `sectionname`, `strandid`, `courseid`) VALUES
(1, 'SBIT-2B', 1, NULL),
(2, 'SBIT-2A', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `strandinfo_tbl`
--

CREATE TABLE `strandinfo_tbl` (
  `strandid` int(11) NOT NULL,
  `strandname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `strandinfo_tbl`
--

INSERT INTO `strandinfo_tbl` (`strandid`, `strandname`) VALUES
(1, 'STEM'),
(2, 'ABM'),
(3, 'GAS'),
(4, 'HUMSS'),
(5, 'TVL');

-- --------------------------------------------------------

--
-- Table structure for table `studinfo_tbl`
--

CREATE TABLE `studinfo_tbl` (
  `LRN` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `studAge` int(2) NOT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `birthplace` varchar(100) DEFAULT NULL,
  `studaddress` varchar(255) DEFAULT NULL,
  `contactNum` bigint(20) DEFAULT NULL,
  `strandid` int(11) DEFAULT NULL,
  `sectionid` int(11) DEFAULT NULL,
  `guardian_firstname` varchar(50) DEFAULT NULL,
  `guardian_middlename` varchar(50) DEFAULT NULL,
  `guardian_lastname` varchar(50) DEFAULT NULL,
  `guardian_extension` varchar(10) DEFAULT NULL,
  `guardiancontact` bigint(20) DEFAULT NULL,
  `guardianaddress` varchar(255) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studinfo_tbl`
--

INSERT INTO `studinfo_tbl` (`LRN`, `firstname`, `middlename`, `lastname`, `extension`, `studAge`, `gender`, `birthDate`, `birthplace`, `studaddress`, `contactNum`, `strandid`, `sectionid`, `guardian_firstname`, `guardian_middlename`, `guardian_lastname`, `guardian_extension`, `guardiancontact`, `guardianaddress`, `relationship`) VALUES
(1112934, 'RJ', 'bagaforo', 'GONZALEZ', NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2183984, 'Edward John', 'Mercado', 'Rubonal', NULL, 0, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Triggers `studinfo_tbl`
--
DELIMITER $$
CREATE TRIGGER `agetrigger1` BEFORE INSERT ON `studinfo_tbl` FOR EACH ROW BEGIN
    SET NEW.studAge = TIMESTAMPDIFF(YEAR, NEW.birthDate, CURDATE());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `agetrigger2` BEFORE UPDATE ON `studinfo_tbl` FOR EACH ROW BEGIN
    SET NEW.studAge = TIMESTAMPDIFF(YEAR, NEW.birthDate, CURDATE());
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coursedescription_tbl`
--
ALTER TABLE `coursedescription_tbl`
  ADD PRIMARY KEY (`courseid`),
  ADD KEY `strandid` (`strandid`),
  ADD KEY `roomid` (`roomid`),
  ADD KEY `insname` (`insname`);

--
-- Indexes for table `grades_tbl`
--
ALTER TABLE `grades_tbl`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `LRN` (`LRN`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `instructorinfo_tbl`
--
ALTER TABLE `instructorinfo_tbl`
  ADD PRIMARY KEY (`instructorid`),
  ADD KEY `strandid` (`strandid`);

--
-- Indexes for table `room_tbl`
--
ALTER TABLE `room_tbl`
  ADD PRIMARY KEY (`roomid`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`sectionid`),
  ADD KEY `strandid` (`strandid`),
  ADD KEY `courseid` (`courseid`);

--
-- Indexes for table `strandinfo_tbl`
--
ALTER TABLE `strandinfo_tbl`
  ADD PRIMARY KEY (`strandid`);

--
-- Indexes for table `studinfo_tbl`
--
ALTER TABLE `studinfo_tbl`
  ADD PRIMARY KEY (`LRN`),
  ADD KEY `strandid` (`strandid`),
  ADD KEY `sectionid` (`sectionid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coursedescription_tbl`
--
ALTER TABLE `coursedescription_tbl`
  ADD CONSTRAINT `coursedescription_tbl_ibfk_1` FOREIGN KEY (`strandid`) REFERENCES `strandinfo_tbl` (`strandid`),
  ADD CONSTRAINT `coursedescription_tbl_ibfk_2` FOREIGN KEY (`roomid`) REFERENCES `room_tbl` (`roomid`),
  ADD CONSTRAINT `coursedescription_tbl_ibfk_3` FOREIGN KEY (`insname`) REFERENCES `instructorinfo_tbl` (`instructorid`);

--
-- Constraints for table `grades_tbl`
--
ALTER TABLE `grades_tbl`
  ADD CONSTRAINT `grades_tbl_ibfk_1` FOREIGN KEY (`LRN`) REFERENCES `studinfo_tbl` (`LRN`),
  ADD CONSTRAINT `grades_tbl_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `coursedescription_tbl` (`courseid`);

--
-- Constraints for table `instructorinfo_tbl`
--
ALTER TABLE `instructorinfo_tbl`
  ADD CONSTRAINT `instructorinfo_tbl_ibfk_1` FOREIGN KEY (`strandid`) REFERENCES `strandinfo_tbl` (`strandid`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`strandid`) REFERENCES `strandinfo_tbl` (`strandid`),
  ADD CONSTRAINT `sections_ibfk_2` FOREIGN KEY (`courseid`) REFERENCES `coursedescription_tbl` (`courseid`);

--
-- Constraints for table `studinfo_tbl`
--
ALTER TABLE `studinfo_tbl`
  ADD CONSTRAINT `studinfo_tbl_ibfk_1` FOREIGN KEY (`strandid`) REFERENCES `strandinfo_tbl` (`strandid`),
  ADD CONSTRAINT `studinfo_tbl_ibfk_2` FOREIGN KEY (`sectionid`) REFERENCES `sections` (`sectionid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
