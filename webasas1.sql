-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2026 at 02:06 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `databaseweb1`
--

-- --------------------------------------------------------

--
-- Table structure for table `webasas1`
--

CREATE TABLE `webasas1` (
  `No` int(11) NOT NULL,
  `Nama_Produk` text NOT NULL,
  `Harga_Produk` int(11) NOT NULL,
  `Upload_Gambar_Produk` varchar(55) NOT NULL,
  `Deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `webasas1`
--

INSERT INTO `webasas1` (`No`, `Nama_Produk`, `Harga_Produk`, `Upload_Gambar_Produk`, `Deskripsi`) VALUES
(1, 'loppy', 122345, '94a7ac68428721ac3f59a746.jpg', 'lucu buat bikin stiker atau pp'),
(2, 'Hello Kitty', 542930, 'b79333364545e57d12d6468a.jpg', 'boneka anomali'),
(3, 'Keonho', 999999, 'ef781b5ca5e1f96a1dc2177f.jpg', 'pacar aku');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `webasas1`
--
ALTER TABLE `webasas1`
  ADD PRIMARY KEY (`No`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `webasas1`
--
ALTER TABLE `webasas1`
  MODIFY `No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
