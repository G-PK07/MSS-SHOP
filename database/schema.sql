-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql-database
-- Generation Time: Dec 23, 2025 at 11:40 AM
-- Server version: 8.3.0
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `shop_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `ID` int NOT NULL COMMENT 'รหัสผู้ใช้',
  `AdminName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อ-สกุล',
  `UserName` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อผู้ใช้',
  `Password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสผ่าน',
  `Email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'อีเมล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`ID`, `AdminName`, `UserName`, `Password`, `Email`) VALUES
(4, 'user3', 'Ceepack2', 'b3ddbc502e307665f346cbd6e52cc10d', 'asda@gamil.com'),
(5, 'user4', 'Ceepack5', '827ccb0eea8a706c4c34a16891f84e7b', 'asdsd11a@gamil.com'),
(6, 'user77', 'Ceepack77', '827ccb0eea8a706c4c34a16891f84e7b', 'asda12354@gamil.com');

-- --------------------------------------------------------

--
-- Table structure for table `tb_catagory`
--

CREATE TABLE `tb_catagory` (
  `ID` int NOT NULL COMMENT 'ประเภทสินค้า',
  `NameCatagory` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อประเภทสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_catagory`
--

INSERT INTO `tb_catagory` (`ID`, `NameCatagory`) VALUES
(1, 'เครื่องดื่มม'),
(2, 'ขนม'),
(4, 'อาหารสำเร็จรูปนะจ๊ะ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_product`
--

CREATE TABLE `tb_product` (
  `ID` int NOT NULL COMMENT 'รหัสสินค้า',
  `ID_catagory` int NOT NULL COMMENT 'รหัสประเภทสินค้า',
  `Pro_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อสินค้า',
  `Pro_price` int NOT NULL COMMENT 'ราคาขาย',
  `Pro_cost` int NOT NULL COMMENT 'ราคาทุน',
  `Pro_image` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รูปภาพ',
  `Pro_total` int NOT NULL COMMENT 'จำนวนสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_product`
--

INSERT INTO `tb_product` (`ID`, `ID_catagory`, `Pro_name`, `Pro_price`, `Pro_cost`, `Pro_image`, `Pro_total`) VALUES
(1, 1, 'Cola', 15, 10, 'cola.jpg', 100),
(2, 2, 'Lays', 5, 3, 'lasy.jpg', 100);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tb_catagory`
--
ALTER TABLE `tb_catagory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tb_product`
--
ALTER TABLE `tb_product`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_catagory` (`ID_catagory`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ใช้', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_catagory`
--
ALTER TABLE `tb_catagory`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT COMMENT 'ประเภทสินค้า', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_product`
--
ALTER TABLE `tb_product`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสสินค้า', AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_product`
--
ALTER TABLE `tb_product`
  ADD CONSTRAINT `tb_product_ibfk_1` FOREIGN KEY (`ID_catagory`) REFERENCES `tb_catagory` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;
