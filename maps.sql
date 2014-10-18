-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2013 at 09:47 AM
-- Server version: 5.5.34
-- PHP Version: 5.3.10-1ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `maps`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `eventId` char(38) NOT NULL,
  `ownerId` char(38) NOT NULL,
  `name` varchar(50) NOT NULL,
  `descr` varchar(200) NOT NULL,
  `date` datetime NOT NULL,
  `country` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `road` varchar(50) DEFAULT NULL,
  `lat` varchar(20) NOT NULL,
  `lon` varchar(20) NOT NULL,
  `limits` int(11) DEFAULT NULL,
  `county` varchar(50) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`eventId`),
  KEY `ownerId` (`ownerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE IF NOT EXISTS `event_participants` (
  `userId` char(38) NOT NULL,
  `eventId` char(38) NOT NULL,
  PRIMARY KEY (`userId`,`eventId`),
  KEY `eventId` (`eventId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userid` char(38) NOT NULL,
  `name` varchar(50) CHARACTER SET utf32 COLLATE utf32_polish_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `salt` char(38) NOT NULL,
  `hashedPass` varchar(32) DEFAULT NULL,
  `gender` char(1) NOT NULL,
  `lastLogin` datetime NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `name`, `email`, `salt`, `hashedPass`, `gender`, `lastLogin`) VALUES
('8b8594a5-4a3c-11e3-8cef-2f551c8b6865', 'rafal', 'mrzuk@op.pl', '', 'c626398d2ad3e4a80a63b73cced7d4c9', 'f', '0000-00-00 00:00:00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`ownerId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`eventId`) REFERENCES `event` (`eventId`) ON DELETE CASCADE ON UPDATE CASCADE;
  
delimiter //
CREATE PROCEDURE SaveUserToEvent(IN eventIdIn char(38), IN userIdIn char(38), OUT saved int)
BEGIN
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
       START TRANSACTION;
       set @limits = (select limits from event where eventId = eventIdIn);
set @cnt = (SELECT Count(*) from event_participants where eventId = eventIdIn for update); 

 IF @cnt < @limits THEN
            INSERT INTO event_participants VALUES (userIdIn,eventIdIn);
            Set saved =1;
            ELSE 
            Set saved =0;
            END IF;
            
       COMMIT;
END; //
delimiter ;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
       BEGIN
      

        
       COMMIT;
       
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
