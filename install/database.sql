-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 02, 2021 at 01:57 PM
-- Server version: 10.1.44-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_category`
--

CREATE TABLE `{prefix}_category` (
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT 0,
  `topic` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `{prefix}_category`
--

INSERT INTO `{prefix}_category` (`type`, `category_id`, `topic`, `color`, `published`) VALUES
('department', 1, 'บริหาร', NULL, 1),
('department', 2, 'จัดซื้อจัดจ้าง', NULL, 1),
('department', 3, 'บุคคล', NULL, 1);
-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_language`
--

CREATE TABLE `{prefix}_language` (
  `id` int(11) NOT NULL,
  `key` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `owner` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `js` tinyint(1) NOT NULL,
  `th` text COLLATE utf8_unicode_ci,
  `en` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_leave`
--

CREATE TABLE `{prefix}_leave` (
  `id` int(11) NOT NULL,
  `topic` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `num_days` tinyint(4) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `{prefix}_leave`
--

INSERT INTO `{prefix}_leave` (`id`, `topic`, `detail`, `num_days`, `published`) VALUES
(1, 'ลาป่วย', 'พนักงานที่ประสงค์จะลาหยุดงาน จะต้องกรอกข้อมูลการลาในระบบ และยื่นขออนุญาตเป็นการล่วงหน้าต่อผู้บังคับบัญชาตามลำดับขั้น และจะหยุดได้ก็ต่อเมื่อผู้บังคับบัญชาได้อนุมัติการลาแล้วเท่านั้น\r\nการลาป่วยต้องแนบใบรับรองแพทย์เสมอ\r\nสามารถลาย้อนหลังได้', 15, 1),
(2, 'ลากิจส่วนตัว', 'พนักงานที่ประสงค์จะลาหยุดงาน จะต้องกรอกข้อมูลการลาในระบบ และยื่นขออนุญาตเป็นการล่วงหน้าต่อผู้บังคับบัญชาตามลำดับขั้น และจะหยุดได้ก็ต่อเมื่อผู้บังคับบัญชาได้อนุมัติการลาแล้วเท่านั้น', 10, 1),
(3, 'ลาคลอดบุตร', 'พนักงานที่ประสงค์จะลาหยุดงาน จะต้องกรอกข้อมูลการลาในระบบ และยื่นขออนุญาตเป็นการล่วงหน้าต่อผู้บังคับบัญชาตามลำดับขั้น และจะหยุดได้ก็ต่อเมื่อผู้บังคับบัญชาได้อนุมัติการลาแล้วเท่านั้น', 60, 1),
(4, 'ลาไปช่วยเหลือภรรยาที่คลอดบุตร', 'พนักงานที่ประสงค์จะลาหยุดงาน จะต้องกรอกข้อมูลการลาในระบบ และยื่นขออนุญาตเป็นการล่วงหน้าต่อผู้บังคับบัญชาตามลำดับขั้น และจะหยุดได้ก็ต่อเมื่อผู้บังคับบัญชาได้อนุมัติการลาแล้วเท่านั้น', 30, 1),
(5, 'ลาเข้ารับการตรวจเลือกทหารหรือเข้ารับการเตรียมพล', 'พนักงานที่ประสงค์จะลาหยุดงาน จะต้องกรอกข้อมูลการลาในระบบ และยื่นขออนุญาตเป็นการล่วงหน้าต่อผู้บังคับบัญชาตามลำดับขั้น และจะหยุดได้ก็ต่อเมื่อผู้บังคับบัญชาได้อนุมัติการลาแล้วเท่านั้น', 10, 1),
(6, 'ลาไปศึกษา ฝึกอบรม ปฏิบัติการวิจัย หรือดูงาน', 'พนักงานที่ประสงค์จะลาหยุดงาน จะต้องกรอกข้อมูลการลาในระบบ และยื่นขออนุญาตเป็นการล่วงหน้าต่อผู้บังคับบัญชาตามลำดับขั้น และจะหยุดได้ก็ต่อเมื่อผู้บังคับบัญชาได้อนุมัติการลาแล้วเท่านั้น', 5, 1),
(8, 'ลาพักผ่อน', '**', 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_leave_items`
--

CREATE TABLE `{prefix}_leave_items` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `leave_id` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL,
  `department` int(11) NOT NULL,
  `detail` text COLLATE utf8_unicode_ci NOT NULL,
  `communication` text COLLATE utf8_unicode_ci NOT NULL,
  `days` float NOT NULL,
  `start_date` date NOT NULL,
  `start_period` tinyint(1) NOT NULL,
  `end_date` date NOT NULL,
  `end_period` tinyint(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}_user`
--

CREATE TABLE `{prefix}_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `permission` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_card` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provinceID` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci DEFAULT 'TH',
  `visited` int(11) DEFAULT 0,
  `lastvisited` int(11) DEFAULT 0,
  `session_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `social` tinyint(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Indexes for table `{prefix}_category`
--
ALTER TABLE `{prefix}_category`
  ADD KEY `type` (`type`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `{prefix}_language`
--
ALTER TABLE `{prefix}_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}_leave`
--
ALTER TABLE `{prefix}_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}_leave_items`
--
ALTER TABLE `{prefix}_leave_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `{prefix}_user`
--
ALTER TABLE `{prefix}_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `token` (`token`),
  ADD KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for table `{prefix}_language`
--
ALTER TABLE `{prefix}_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}_leave`
--
ALTER TABLE `{prefix}_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}_leave_items`
--
ALTER TABLE `{prefix}_leave_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}_user`
--
ALTER TABLE `{prefix}_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
