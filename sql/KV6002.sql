-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2023 at 10:17 PM
-- Server version: 5.7.41-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `KV6002`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `AdminID` int(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`AdminID`, `Username`, `PasswordHash`) VALUES
(1, 'testadmin1', '$2y$10$3w8.NOOiB6UI1JdlgQYgkOZgpfFomDOR7VYDQ47FMcdgRFO63HxdC');

-- --------------------------------------------------------

--
-- Table structure for table `Analytics`
--

CREATE TABLE `Analytics` (
  `AnalyticsID` bigint(255) NOT NULL,
  `PostID` int(255) NOT NULL,
  `ParticipantID` int(255) NOT NULL,
  `HasLiked` tinyint(1) NOT NULL DEFAULT '0',
  `RetentionTime` int(255) NOT NULL DEFAULT '0' COMMENT 'Seconds',
  `MaxTimeViewed` int(255) NOT NULL DEFAULT '0' COMMENT 'Seconds',
  `TimesViewed` int(255) NOT NULL DEFAULT '0',
  `Comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Group`
--

CREATE TABLE `Group` (
  `GroupID` int(11) NOT NULL,
  `LikesAllowance` int(11) NOT NULL,
  `TimeAllowance` int(11) NOT NULL,
  `LikesReceived` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Participant`
--

CREATE TABLE `Participant` (
  `ParticipantID` int(10) NOT NULL,
  `GroupID` int(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ProfilePic` varchar(255) NOT NULL,
  `Bio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Post`
--

CREATE TABLE `Post` (
  `PostID` int(255) NOT NULL,
  `GroupID` int(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Text` varchar(255) NOT NULL,
  `LikeCount` int(255) NOT NULL DEFAULT '1',
  `Image` varchar(255) DEFAULT NULL,
  `ProfilePic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `URL`
--

CREATE TABLE `URL` (
  `URL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `URL`
--

INSERT INTO `URL` (`URL`) VALUES
('https://google.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `Analytics`
--
ALTER TABLE `Analytics`
  ADD PRIMARY KEY (`AnalyticsID`),
  ADD KEY `ParticipantID` (`ParticipantID`),
  ADD KEY `PostID` (`PostID`);

--
-- Indexes for table `Group`
--
ALTER TABLE `Group`
  ADD PRIMARY KEY (`GroupID`);

--
-- Indexes for table `Participant`
--
ALTER TABLE `Participant`
  ADD PRIMARY KEY (`ParticipantID`),
  ADD KEY `GroupID` (`GroupID`);

--
-- Indexes for table `Post`
--
ALTER TABLE `Post`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `GroupID` (`GroupID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `AdminID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Analytics`
--
ALTER TABLE `Analytics`
  MODIFY `AnalyticsID` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;
--
-- AUTO_INCREMENT for table `Group`
--
ALTER TABLE `Group`
  MODIFY `GroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Participant`
--
ALTER TABLE `Participant`
  MODIFY `ParticipantID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
  MODIFY `PostID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Analytics`
--
ALTER TABLE `Analytics`
  ADD CONSTRAINT `Analytics_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `Post` (`PostID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Analytics_ibfk_2` FOREIGN KEY (`ParticipantID`) REFERENCES `Participant` (`ParticipantID`) ON DELETE CASCADE;

--
-- Constraints for table `Participant`
--
ALTER TABLE `Participant`
  ADD CONSTRAINT `Participant_ibfk_1` FOREIGN KEY (`GroupID`) REFERENCES `Group` (`GroupID`);

--
-- Constraints for table `Post`
--
ALTER TABLE `Post`
  ADD CONSTRAINT `Post_ibfk_1` FOREIGN KEY (`GroupID`) REFERENCES `Group` (`GroupID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
