-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2018 at 06:06 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pos_petct`
--
CREATE DATABASE IF NOT EXISTS `db_pos_petct` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_pos_petct`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctor`
--

DROP TABLE IF EXISTS `tbl_doctor`;
CREATE TABLE `tbl_doctor` (
  `doctor_id` varchar(11) NOT NULL,
  `doctor_name` varchar(45) NOT NULL,
  `doctor_gender` varchar(6) NOT NULL,
  `doctor_date_of_birth` varchar(45) NOT NULL,
  `doctor_address` varchar(45) NOT NULL,
  `doctor_contact_number` varchar(45) NOT NULL,
  `doctor_professional_fee` float NOT NULL,
  `doctor_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Active, 2 = Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_doctor`
--

INSERT INTO `tbl_doctor` (`doctor_id`, `doctor_name`, `doctor_gender`, `doctor_date_of_birth`, `doctor_address`, `doctor_contact_number`, `doctor_professional_fee`, `doctor_status`) VALUES
('doc_000001', 'Dr. Martinez', 'Male', '1997-05-12', 'Quezon City Manila', '09157744075', 4500, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

DROP TABLE IF EXISTS `tbl_invoice`;
CREATE TABLE `tbl_invoice` (
  `invoice_id` varchar(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `patient_id_fk` varchar(11) NOT NULL,
  `user_id_fk` varchar(11) DEFAULT NULL,
  `invoice_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Active, 2 = Deleted',
  `invoice_type` varchar(9) NOT NULL,
  `invoice_cost` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `invoice_date`, `patient_id_fk`, `user_id_fk`, `invoice_status`, `invoice_type`, `invoice_cost`) VALUES
('180404_001', '2018-04-04', 'ptnt_000001', 'usr_0000001', 1, 'invoice', 8300),
('180413_001', '2018-04-13', 'ptnt_000001', 'usr_0000001', 1, 'invoice', 10000),
('180413_002', '2018-04-13', 'ptnt_000003', 'usr_0000001', 1, 'receipt', 10000),
('180519_002', '2018-05-19', 'ptnt_000003', 'usr_0000001', 1, 'invoice', 7500),
('180524_001', '2018-05-24', 'ptnt_000003', 'usr_0000001', 2, 'quotation', 7500),
('180524_002', '2018-05-24', 'ptnt_000003', 'usr_0000001', 2, 'quotation', 5900),
('180524_003', '2018-05-24', 'ptnt_000003', 'usr_0000001', 2, 'quotation', 12500),
('180524_004', '2018-05-24', 'ptnt_000003', 'usr_0000001', 1, 'receipt', 12500),
('180524_005', '2018-05-24', 'ptnt_000003', 'usr_0000001', 1, 'quotation', 5800);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_doctor`
--

DROP TABLE IF EXISTS `tbl_invoice_doctor`;
CREATE TABLE `tbl_invoice_doctor` (
  `doctor_id_fk` varchar(11) NOT NULL,
  `invoice_id_fk` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_invoice_doctor`
--

INSERT INTO `tbl_invoice_doctor` (`doctor_id_fk`, `invoice_id_fk`) VALUES
('doc_000001', '180413_001'),
('doc_000001', '180413_002'),
('doc_000001', '180519_002'),
('doc_000001', '180524_001'),
('doc_000001', '180524_002'),
('doc_000001', '180524_003'),
('doc_000001', '180524_004'),
('doc_000001', '180524_005'),
('doc_000001', '180404_001');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_procedure`
--

DROP TABLE IF EXISTS `tbl_invoice_procedure`;
CREATE TABLE `tbl_invoice_procedure` (
  `invoice_id_fk` varchar(11) NOT NULL,
  `procedure_id_fk` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_invoice_procedure`
--

INSERT INTO `tbl_invoice_procedure` (`invoice_id_fk`, `procedure_id_fk`) VALUES
('180413_001', 'proc_000001'),
('180413_002', 'proc_000002'),
('180519_002', 'proc_000001'),
('180524_001', 'proc_000001'),
('180524_002', 'proc_000003'),
('180524_002', 'proc_000004'),
('180524_003', 'proc_000001'),
('180524_003', 'proc_000002'),
('180524_004', 'proc_000001'),
('180524_004', 'proc_000002'),
('180524_005', 'proc_000003'),
('180404_001', 'proc_000001'),
('180404_001', 'proc_000003');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

DROP TABLE IF EXISTS `tbl_item`;
CREATE TABLE `tbl_item` (
  `item_id` varchar(11) NOT NULL,
  `item_name` varchar(45) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Active, 2 = Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`item_id`, `item_name`, `item_qty`, `item_status`) VALUES
('item_000001', 'Syringe', 100, 1),
('item_000002', ' Cotton', 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient`
--

DROP TABLE IF EXISTS `tbl_patient`;
CREATE TABLE `tbl_patient` (
  `patient_id` varchar(11) NOT NULL,
  `patient_name` varchar(45) NOT NULL,
  `patient_gender` varchar(6) NOT NULL,
  `patient_date_of_birth` date NOT NULL,
  `patient_address` varchar(50) NOT NULL,
  `patient_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_patient`
--

INSERT INTO `tbl_patient` (`patient_id`, `patient_name`, `patient_gender`, `patient_date_of_birth`, `patient_address`, `patient_status`) VALUES
('ptnt_000001', 'Edil Gester T. Garcia    ', 'Male', '1997-12-05', 'D-11 Bldg. 25 GSIS Metrohomes Pureza St. Sta. Mesa', 1),
('ptnt_000003', 'Bruno S. Mare', 'Male', '2000-04-12', 'Las Vegas', 1),
('ptnt_000004', 'Rose Mae S. Pusancho ', 'Female', '1998-05-21', 'Murphy Cuabo Q.C', 1),
('ptnt_000006', 'ShantiDope', 'Male', '2018-04-29', 'Andiyan ka nanaman', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_procedure`
--

DROP TABLE IF EXISTS `tbl_procedure`;
CREATE TABLE `tbl_procedure` (
  `procedure_id` varchar(11) NOT NULL,
  `procedure_name` varchar(45) NOT NULL,
  `procedure_cost` float NOT NULL,
  `procedure_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Active, 2 = Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_procedure`
--

INSERT INTO `tbl_procedure` (`procedure_id`, `procedure_name`, `procedure_cost`, `procedure_status`) VALUES
('proc_000001', 'CT-Scan', 2500, 1),
('proc_000002', 'Stiches and Burn', 5000, 1),
('proc_000003', 'Somthing', 800, 1),
('proc_000004', 'asdasda', 100, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_procedure_item`
--

DROP TABLE IF EXISTS `tbl_procedure_item`;
CREATE TABLE `tbl_procedure_item` (
  `procedure_id_fk` varchar(11) NOT NULL,
  `item_id_fk` varchar(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_procedure_item`
--

INSERT INTO `tbl_procedure_item` (`procedure_id_fk`, `item_id_fk`, `quantity`) VALUES
('proc_000001', 'item_000001', 5),
('proc_000002', 'item_000001', 4),
('proc_000002', 'item_000002', 2),
('proc_000003', 'item_000001', 2),
('proc_000004', 'item_000001', 1),
('proc_000004', 'item_000002', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `user_id` varchar(11) NOT NULL,
  `user_name` varchar(45) NOT NULL,
  `user_gender` varchar(6) NOT NULL,
  `user_date_of_birth` date NOT NULL,
  `user_address` varchar(50) NOT NULL,
  `user_contact` varchar(45) NOT NULL,
  `user_username` varchar(45) NOT NULL,
  `user_password` varchar(45) NOT NULL,
  `user_account_type` varchar(8) NOT NULL,
  `user_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_gender`, `user_date_of_birth`, `user_address`, `user_contact`, `user_username`, `user_password`, `user_account_type`, `user_status`) VALUES
('usr_0000001', 'Skusta Clee', 'Male', '2017-06-15', 'Brgy. Capalong Sitio Talisay Real, Quezon', '09051563994', 'admin', 'admin', 'admin 2', 1),
('usr_0000002', 'Bruno Marsss', 'Male', '2000-08-12', 'Malanday Marikina', '09157744075', 'user', 'mars', 'user', 1),
('usr_0000003', 'Rose Mae Pusancho', 'Female', '1998-05-21', 'Murphy Cubao', '09051563994', 'rosasmayooo', 'abc', 'user', 1),
('usr_0000004', 'Hayley Williams', 'Female', '2018-03-19', 'Paramore', '', 'paramore', 'paramore', 'user', 1),
('usr_0000005', 'James Reid', 'Male', '1999-12-15', 'PUP Main', '', 'jamesreid', 'jamesreid', 'user', 3),
('usr_0000006', 'Bon Jovi', 'Male', '1982-08-05', 'NA California', '', 'bon', 'jobi', 'admin', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_doctor`
--
ALTER TABLE `tbl_doctor`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `invoice_patient_fk_idx` (`patient_id_fk`),
  ADD KEY `invoice_cashier_fk_idx` (`user_id_fk`);

--
-- Indexes for table `tbl_invoice_doctor`
--
ALTER TABLE `tbl_invoice_doctor`
  ADD KEY `doctor_fk_indx` (`doctor_id_fk`),
  ADD KEY `invoice_fk_indx` (`invoice_id_fk`);

--
-- Indexes for table `tbl_invoice_procedure`
--
ALTER TABLE `tbl_invoice_procedure`
  ADD KEY `invoice_fk_indx` (`invoice_id_fk`),
  ADD KEY `invoice_procedure_procedure_fk_idx` (`procedure_id_fk`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `tbl_procedure`
--
ALTER TABLE `tbl_procedure`
  ADD PRIMARY KEY (`procedure_id`);

--
-- Indexes for table `tbl_procedure_item`
--
ALTER TABLE `tbl_procedure_item`
  ADD KEY `procedure_fk_indx` (`procedure_id_fk`),
  ADD KEY `item_fk_indx` (`item_id_fk`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD CONSTRAINT `invoice_cashier_fk` FOREIGN KEY (`user_id_fk`) REFERENCES `tbl_user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `invoice_patient_fk` FOREIGN KEY (`patient_id_fk`) REFERENCES `tbl_patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_invoice_doctor`
--
ALTER TABLE `tbl_invoice_doctor`
  ADD CONSTRAINT `doctor_invoice_doctor_fk` FOREIGN KEY (`doctor_id_fk`) REFERENCES `tbl_doctor` (`doctor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `doctor_invoice_invoice_fk` FOREIGN KEY (`invoice_id_fk`) REFERENCES `tbl_invoice` (`invoice_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_invoice_procedure`
--
ALTER TABLE `tbl_invoice_procedure`
  ADD CONSTRAINT `invoice_procedure_invoice_fk` FOREIGN KEY (`invoice_id_fk`) REFERENCES `tbl_invoice` (`invoice_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `invoice_procedure_procedure_fk` FOREIGN KEY (`procedure_id_fk`) REFERENCES `tbl_procedure` (`procedure_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_procedure_item`
--
ALTER TABLE `tbl_procedure_item`
  ADD CONSTRAINT `precedure_item_item_fk` FOREIGN KEY (`item_id_fk`) REFERENCES `tbl_item` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `precudure_item_procedure_fk` FOREIGN KEY (`procedure_id_fk`) REFERENCES `tbl_procedure` (`procedure_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
