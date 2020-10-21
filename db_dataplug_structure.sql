-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 19, 2020 at 05:23 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_dataplug`
--

-- --------------------------------------------------------

--
-- Table structure for table `api`
--

CREATE TABLE IF NOT EXISTS `api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=232 ;

-- --------------------------------------------------------

--
-- Table structure for table `app`
--

CREATE TABLE IF NOT EXISTS `app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `full_description` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `splash_icon` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('external','internal') NOT NULL DEFAULT 'internal',
  `module_name` varchar(255) DEFAULT NULL,
  `is_authorized` tinyint(1) NOT NULL DEFAULT '0',
  `is_secure` varchar(5) NOT NULL DEFAULT 'no',
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `name` (`name`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14779 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_build_request`
--

CREATE TABLE IF NOT EXISTS `app_build_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=714 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_comments`
--

CREATE TABLE IF NOT EXISTS `app_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comments` varchar(500) DEFAULT NULL,
  `created_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=112 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_installed`
--

CREATE TABLE IF NOT EXISTS `app_installed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `imei_no` varchar(100) NOT NULL,
  `change_status` enum('0','1') NOT NULL,
  `changed_form` varchar(20) DEFAULT NULL,
  `app_version` varchar(10) DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id` (`id`),
  KEY `user_id` (`app_id`),
  KEY `app_id` (`imei_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=150326 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_released`
--

CREATE TABLE IF NOT EXISTS `app_released` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `version_code` int(11) NOT NULL DEFAULT '1',
  `version` decimal(3,1) NOT NULL DEFAULT '1.0',
  `app_file` varchar(255) NOT NULL,
  `qr_code_file` varchar(255) DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `release_note` text NOT NULL,
  `change_status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `version_code` (`version_code`),
  KEY `version` (`version`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15180 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `latitude` varchar(255) NOT NULL DEFAULT '31.58219141239757',
  `longitude` varchar(255) NOT NULL DEFAULT '73.7677001953125',
  `zoom_level` int(11) NOT NULL DEFAULT '7',
  `map_type_filter` enum('On','Off') NOT NULL DEFAULT 'Off',
  `map_type` varchar(255) NOT NULL DEFAULT 'Pin',
  `district_filter` enum('On','Off') NOT NULL DEFAULT 'Off',
  `uc_filter` enum('On','Off') NOT NULL DEFAULT 'Off',
  `sent_by_filter` enum('On','Off') NOT NULL DEFAULT 'Off',
  `app_language` varchar(255) DEFAULT 'english',
  `list_view_filters` varchar(255) DEFAULT NULL,
  `map_view_filters` varchar(255) DEFAULT NULL,
  `default_view_builder` int(11) DEFAULT '1',
  `setting_type` enum('GENERAL_SETTINGS','FORM_SETTINGS','RESULT_VIEW_SETTINGS','MAP_VIEW_SETTINGS','GRAPH_VIEW_SETTINGS','SMS_SETTINGS') NOT NULL,
  `filters` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `setting_type` (`setting_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52102 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_temp`
--

CREATE TABLE IF NOT EXISTS `app_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `full_description` text NOT NULL,
  `icon` blob,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2051 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_users`
--

CREATE TABLE IF NOT EXISTS `app_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `view_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `imei_no` varchar(100) NOT NULL,
  `cnic` varchar(13) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `mobile_network` varchar(10) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `login_user` varchar(255) DEFAULT NULL,
  `login_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `is_deleted` (`is_deleted`),
  KEY `department_id` (`department_id`),
  KEY `view_id` (`view_id`),
  KEY `imei_no` (`imei_no`),
  KEY `login_user` (`login_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17513 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_users_view`
--

CREATE TABLE IF NOT EXISTS `app_users_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=98 ;

-- --------------------------------------------------------

--
-- Table structure for table `app_views`
--

CREATE TABLE IF NOT EXISTS `app_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `view_id` int(11) NOT NULL,
  `description` longblob NOT NULL,
  `full_description` longblob NOT NULL,
  `post_url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `view_id` (`view_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE IF NOT EXISTS `complaint` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_department_id` int(11) NOT NULL,
  `c_app_id` int(11) NOT NULL,
  `c_app_user_id` int(11) DEFAULT NULL,
  `c_type` varchar(100) NOT NULL,
  `c_title` varchar(200) NOT NULL,
  `c_description` text NOT NULL,
  `c_complaint_by_id` int(11) NOT NULL,
  `c_priority` int(11) NOT NULL,
  `c_status` enum('pending','completed','processing','closed') NOT NULL,
  `c_date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_resolution_time` varchar(200) DEFAULT NULL,
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
  `unautherized_user_imei` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_history`
--

CREATE TABLE IF NOT EXISTS `complaint_history` (
  `ch_id` int(11) NOT NULL AUTO_INCREMENT,
  `ch_complaint_id_Fk` int(11) NOT NULL,
  `ch_status` varchar(255) DEFAULT NULL,
  `ch_description` text NOT NULL,
  `ch_changed_by_id` int(11) NOT NULL,
  `ch_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_photo`
--

CREATE TABLE IF NOT EXISTS `complaint_photo` (
  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
  `cp_complaint_id_Fk` int(11) NOT NULL,
  `cp_photo` varchar(200) NOT NULL,
  `cp_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `run_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_public` enum('yes','no') NOT NULL DEFAULT 'no',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `public_group` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_public` (`is_public`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE IF NOT EXISTS `downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `created_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1290 ;

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE IF NOT EXISTS `form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` longblob,
  `full_description` longblob NOT NULL,
  `filter` longtext NOT NULL,
  `possible_filters` longtext NOT NULL,
  `row_key` text,
  `next` varchar(255) DEFAULT NULL,
  `post_url` varchar(500) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `security_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16088 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_column_settings`
--

CREATE TABLE IF NOT EXISTS `form_column_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(6) NOT NULL,
  `columns` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_history`
--

CREATE TABLE IF NOT EXISTS `form_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `view_id` int(11) NOT NULL,
  `description` longblob NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `view_id` (`view_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91574 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_multiselectFieldLabels`
--

CREATE TABLE IF NOT EXISTS `form_multiselectFieldLabels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formId` varchar(200) NOT NULL,
  `fieldLabel` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `formId` (`formId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=214 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_result_temp`
--

CREATE TABLE IF NOT EXISTS `form_result_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `query_user` longblob,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=172722 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_temp`
--

CREATE TABLE IF NOT EXISTS `form_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_temp_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` blob,
  `description` longblob NOT NULL,
  `full_description` longblob NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_form_temp` (`app_temp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4122 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_views`
--

CREATE TABLE IF NOT EXISTS `form_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `view_id` int(11) NOT NULL,
  `description` longblob NOT NULL,
  `full_description` longblob NOT NULL,
  `post_url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `view_id` (`view_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Table structure for table `kml_poligon`
--

CREATE TABLE IF NOT EXISTS `kml_poligon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `type` enum('distence','poligon') DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `poligon` longtext,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `matching_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1645 ;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `changed_by_id` int(11) NOT NULL,
  `changed_by_name` varchar(255) NOT NULL,
  `action_type` enum('insert','update','delete','view','build','login','logout') NOT NULL,
  `action_description` varchar(255) NOT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `before_record` text,
  `after_record` text,
  `app_id` int(11) DEFAULT NULL,
  `app_name` varchar(255) DEFAULT NULL,
  `form_id` int(11) DEFAULT NULL,
  `form_name` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `changed_by_id` (`changed_by_id`),
  KEY `app_id` (`app_id`),
  KEY `form_id` (`form_id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=364414 ;

-- --------------------------------------------------------

--
-- Table structure for table `map_pin_settings`
--

CREATE TABLE IF NOT EXISTS `map_pin_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(10) NOT NULL,
  `pins` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_activity_log`
--

CREATE TABLE IF NOT EXISTS `mobile_activity_log` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `form_data` longtext,
  `form_data_decoded` longtext,
  `form_images` text,
  `imei_no` varchar(200) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `dateTime` varchar(255) DEFAULT NULL,
  `time_source` varchar(10) DEFAULT NULL,
  `location_source` varchar(10) DEFAULT NULL,
  `version_name` varchar(10) DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `error` text,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  KEY `form_id` (`form_id`),
  KEY `imei_no` (`imei_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=677557 ;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_tracking_log`
--

CREATE TABLE IF NOT EXISTS `mobile_tracking_log` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `data_save` longtext,
  `data_type` enum('single','bulk') NOT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `error` text,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=590 ;

-- --------------------------------------------------------

--
-- Table structure for table `network_support`
--

CREATE TABLE IF NOT EXISTS `network_support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `network_name` varchar(10) NOT NULL,
  `support_email` varchar(50) NOT NULL,
  `support_user` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `directory_path` varchar(500) DEFAULT NULL,
  `android_target` int(11) DEFAULT NULL,
  `s3_access_key` varchar(500) DEFAULT NULL,
  `s3_secret_key` varchar(500) DEFAULT NULL,
  `s3_bucket` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `verification_code` varchar(255) DEFAULT NULL,
  `forgot_password` varchar(100) DEFAULT NULL,
  `default_url` varchar(500) DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `district` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `group_id` (`group_id`),
  KEY `department_id` (`department_id`),
  KEY `email_password` (`email`,`password`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9994 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_app`
--

CREATE TABLE IF NOT EXISTS `users_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45650 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_group`
--

CREATE TABLE IF NOT EXISTS `users_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT '0',
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=107 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_group_permissions`
--

CREATE TABLE IF NOT EXISTS `users_group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=340899 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_permissions`
--

CREATE TABLE IF NOT EXISTS `users_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44064953 ;

-- --------------------------------------------------------

--
-- Table structure for table `zform_images`
--

CREATE TABLE IF NOT EXISTS `zform_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zform_result_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `zform_result_id` (`zform_result_id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2045995 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app`
--
ALTER TABLE `app`
  ADD CONSTRAINT `app_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `form`
--
ALTER TABLE `form`
  ADD CONSTRAINT `form_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `app` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `form_temp`
--
ALTER TABLE `form_temp`
  ADD CONSTRAINT `FK_form_temp` FOREIGN KEY (`app_temp_id`) REFERENCES `app_temp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
