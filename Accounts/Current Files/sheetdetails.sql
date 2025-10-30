-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2015 at 07:57 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `billingmeasurement`
--

-- --------------------------------------------------------

--
-- Table structure for table `sheetdetails`
--

CREATE TABLE IF NOT EXISTS `sheetdetails` (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `tech_sanction` varchar(150) DEFAULT NULL,
  `name_contractor` varchar(150) DEFAULT NULL,
  `agree_no` varchar(50) DEFAULT NULL,
  `sheet_id` int(11) DEFAULT NULL,
  `runn_acc_bill_no` varchar(150) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`details_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sheetdetails`
--

INSERT INTO `sheetdetails` (`details_id`, `tech_sanction`, `name_contractor`, `agree_no`, `sheet_id`, `runn_acc_bill_no`, `active`, `userid`) VALUES
(1, 'C991/IGC/2009', 'GAMMON INDIA LIMITED', 'CED/IGC/Tr.783/2010-WO-dt=29/september/2010', 1, 'RA Bill NO : 08 - Engineering Hall-IV', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



INSERT INTO `sheet` ( `sheet_name`, `work_order_no`, `work_name`, `date_upt`, `active`) VALUES ( 'Agt=783_edited_v2.xls', 'CED/IGC/Tr.783/2010-WO-dt=29/september/2010', 'CONSTRUCTION OF PHASE I BUILDINGS OF XI PLAN ACTIVITIES INCLUDING CIVIL, \r\nINTERNAL PUBLIC HEALTH, ELECTRICAL WORKS & OTHER SERVICES FOR IGCAR, KALPAKKAM', '2015-01-26', '1')