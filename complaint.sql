-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 24, 2017 at 06:52 PM
-- Server version: 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 5.6.28-1+deb.sury.org~xenial+1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dataplug`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `c_id` int(11) NOT NULL,
  `c_department_id` int(11) NOT NULL,
  `c_app_id` int(11) NOT NULL,
  `c_type` varchar(100) NOT NULL,
  `c_title` varchar(200) NOT NULL,
  `c_description` text NOT NULL,
  `c_complaint_by_id` int(11) NOT NULL,
  `c_priority` int(11) NOT NULL,
  `c_status` enum('pending','completed','processing','') NOT NULL,
  `c_date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `duplicate_sim_address` varchar(255) DEFAULT NULL,
  `duplicate_sim_reason` varchar(255) DEFAULT NULL,
  `internet_issue_from_date` date DEFAULT NULL,
  `internet_issue_to_date` date DEFAULT NULL,
  `signal_problem_district` varchar(20) DEFAULT NULL,
  `signal_problem_tehsil` varchar(20) DEFAULT NULL,
  `signal_problem_markaz` varchar(20) DEFAULT NULL,
  `signal_problem_uc` varchar(20) DEFAULT NULL,
  `signal_problem_village` varchar(20) DEFAULT NULL,
  `balance_received_date` date DEFAULT NULL,
  `balance_deduction_date` date DEFAULT NULL,
  `sim_mapping_activation_comments` text,
  `ownership_user_name` varchar(20) DEFAULT NULL,
  `ownership_cnic` varchar(13) DEFAULT NULL,
  `ownership_designation` varchar(20) DEFAULT NULL,
  `ownership_place` varchar(20) DEFAULT NULL,
  `user_status_change` varchar(20) DEFAULT NULL,
  `imei_update` varchar(16) DEFAULT NULL,
  `leave_type` varchar(20) DEFAULT NULL,
  `leave_from_date` date DEFAULT NULL,
  `leave_to_date` date DEFAULT NULL,
  `leave_approved_doc` text,
  `login_user_name` varchar(20) DEFAULT NULL,
  `login_old_password` varchar(20) DEFAULT NULL,
  `login_new_password` varchar(20) DEFAULT NULL,
  `login_issue_reason` text,
  `transfered_district` varchar(20) DEFAULT NULL,
  `transfered_tehsil` varchar(20) DEFAULT NULL,
  `transfered_markaz` varchar(20) DEFAULT NULL,
  `transfered_uc` varchar(20) DEFAULT NULL,
  `transfered_village` varchar(20) DEFAULT NULL,
  `dashboard_not_working_comments` text,
  `showing_absent_dashboard_comments` text,
  `activities_missing_comments` text,
  `data_missing_from_date` date DEFAULT NULL,
  `data_missing_to_date` date DEFAULT NULL,
  `data_missing_comments` text,
  `app_not_working_error` varchar(255) DEFAULT NULL,
  `apk_required_email` varchar(50) DEFAULT NULL,
  `unautherized_user_imei` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`c_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT;SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
