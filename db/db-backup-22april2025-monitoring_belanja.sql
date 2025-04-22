-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 22, 2025 at 12:58 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitoring_belanja`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval`
--

DROP TABLE IF EXISTS `approval`;
CREATE TABLE IF NOT EXISTS `approval` (
  `id_approval` int NOT NULL AUTO_INCREMENT,
  `id_permohonan` int NOT NULL,
  `id_pejabat` int NOT NULL,
  `level_pejabat` int NOT NULL,
  `status_approval` enum('Disetujui','Ditolak') NOT NULL,
  `keterangan` text,
  `tanggal_approval` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `qr_code` text,
  PRIMARY KEY (`id_approval`),
  KEY `id_permohonan` (`id_permohonan`),
  KEY `id_pejabat` (`id_pejabat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_permohonan`
--

DROP TABLE IF EXISTS `detail_permohonan`;
CREATE TABLE IF NOT EXISTS `detail_permohonan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permohonan_id` int NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `banyaknya` int NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`id`),
  KEY `permohonan_id` (`permohonan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_barang_jasa` varchar(255) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `keterangan` text,
  `permohonan_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permohonan_id` (`permohonan_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `nama_barang_jasa`, `jumlah`, `harga_satuan`, `total_harga`, `keterangan`, `permohonan_id`) VALUES
(1, 'Laptop', 2, 10000000.00, 20000000.00, 'Laptop untuk kebutuhan kantor', 2),
(2, 'Meja Kantor', 5, 200000.00, 1000000.00, 'Meja kantor untuk divisi A', 2);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_kegiatan`
--

DROP TABLE IF EXISTS `kategori_kegiatan`;
CREATE TABLE IF NOT EXISTS `kategori_kegiatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori_kegiatan`
--

INSERT INTO `kategori_kegiatan` (`id`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Belanja Pegawai', 'Kebutuhan Belanja Pegawai', '2025-03-06 04:38:39'),
(2, 'Belanja Modal', 'Kebutuhan Belanja Peralatan Modal', '2025-03-06 04:39:04'),
(5, 'Belanja ATK', 'Belanja ATK', '2025-03-06 05:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE IF NOT EXISTS `level` (
  `id_level` int NOT NULL AUTO_INCREMENT,
  `level_jabatan` varchar(100) NOT NULL,
  `deskripsi_jabatan` varchar(100) NOT NULL,
  `folder_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id_level`, `level_jabatan`, `deskripsi_jabatan`, `folder_name`) VALUES
(1, 'Pemohon', 'Pengaju permohonan', 'pemohon'),
(2, 'Katimja', 'Ketua Tim Kerja', 'katimja'),
(3, 'Plt. Kasubbag Umum /PPK,', 'Plt. Kasubbag Umum /PPK,', 'ppk'),
(4, 'Kuasa Pengguna Anggaran,', 'Kuasa Pengguna Anggaran', 'kpa'),
(5, 'Pengawas', 'Pengawasan Transaksi Data 1', 'pengawas'),
(6, 'Keuangan', 'Pejabat  Administrasi Keuangan', 'keuangan'),
(7, 'Pejabat SPM', 'Pejabat Surat Perintah Pembayaran', 'spm'),
(8, 'Administrator', 'Administrator', 'administrator');

-- --------------------------------------------------------

--
-- Table structure for table `log_activity`
--

DROP TABLE IF EXISTS `log_activity`;
CREATE TABLE IF NOT EXISTS `log_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `aktivitas` text NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_activity`
--

INSERT INTO `log_activity` (`id`, `user_id`, `aktivitas`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 14, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-19 22:48:21'),
(2, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-19 23:47:11'),
(3, 14, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-19 23:57:34'),
(4, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-20 00:56:20'),
(5, 14, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-20 00:59:28'),
(6, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-20 05:40:37'),
(7, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-20 06:31:53'),
(8, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-20 23:22:33'),
(9, 14, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-21 01:53:49'),
(10, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-21 05:41:38'),
(11, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-21 06:38:16'),
(12, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-21 12:39:28'),
(13, 8, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-21 12:41:29'),
(14, 14, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-22 13:55:25'),
(15, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 04:29:56'),
(16, 40, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 06:21:11'),
(17, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 06:25:37'),
(18, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 06:25:50'),
(19, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 06:29:51'),
(20, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 11:56:11'),
(21, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 22:29:34'),
(22, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 22:46:35'),
(23, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 23:50:38'),
(24, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-23 23:56:41'),
(25, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 00:32:49'),
(26, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 06:35:24'),
(27, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 06:56:33'),
(28, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 06:57:26'),
(29, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 06:57:39'),
(30, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:01:09'),
(31, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:03:03'),
(32, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:05:23'),
(33, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:12:06'),
(34, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:13:19'),
(35, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:13:32'),
(36, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:38:53'),
(37, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:40:41'),
(38, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:51:10'),
(39, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:51:44'),
(40, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 07:51:59'),
(41, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 08:01:36'),
(42, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 08:06:36'),
(43, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 08:09:57'),
(44, 12, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 08:15:08'),
(45, 12, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:28:11'),
(46, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:30:23'),
(47, 13, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:31:32'),
(48, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:49:54'),
(49, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:50:35'),
(50, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:50:47'),
(51, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:51:13'),
(52, 12, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:51:24'),
(53, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:51:45'),
(54, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:54:20'),
(55, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-24 13:54:31'),
(56, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-25 00:03:48'),
(57, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-25 14:12:59'),
(58, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-25 14:19:20'),
(59, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 04:20:25'),
(60, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 04:29:15'),
(61, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-26 06:39:21'),
(62, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-27 00:01:39'),
(63, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-27 07:25:20'),
(64, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:24:24'),
(65, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:25:31'),
(66, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:26:47'),
(67, 9, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:31:15'),
(68, 10, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:33:04'),
(69, 10, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:39:45'),
(70, 10, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:40:08'),
(71, 10, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:42:36'),
(72, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:48:04'),
(73, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-28 14:53:01'),
(74, 12, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 03:19:41'),
(75, 13, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 03:21:35'),
(76, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 03:39:12'),
(77, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 04:49:27'),
(78, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 12:23:54'),
(79, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 14:52:22'),
(80, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 15:04:53'),
(81, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-29 15:09:48'),
(82, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 14:15:34'),
(83, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:12:05'),
(84, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:21:21'),
(85, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:28:00'),
(86, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:28:29'),
(87, 10, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:28:46'),
(88, 10, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:58:26'),
(89, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:58:38'),
(90, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 15:59:43'),
(91, 12, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:00:06'),
(92, 13, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:00:25'),
(93, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:08:10'),
(94, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:09:42'),
(95, 9, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:09:57'),
(96, 10, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:10:17'),
(97, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:10:28'),
(98, 12, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:10:43'),
(99, 13, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:10:58'),
(100, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:11:42'),
(101, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:11:54'),
(102, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:13:30'),
(103, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-03-31 16:14:29'),
(104, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-05 13:13:13'),
(105, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-05 13:19:15'),
(106, 42, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-05 14:05:08'),
(107, 42, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-05 14:23:00'),
(108, 42, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-05 14:28:19'),
(109, 42, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-05 14:36:09'),
(110, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-08 15:59:14'),
(111, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-08 16:05:40'),
(112, 1, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-08 16:07:52'),
(113, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-08 16:43:48'),
(114, 2, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 00:25:06'),
(115, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 04:14:33'),
(116, 11, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 04:33:11'),
(117, 42, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 04:35:34'),
(118, 42, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 04:35:58'),
(119, 42, 'User login ke sistem.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 04:51:15'),
(120, 42, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 04:59:43'),
(121, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 05:02:09'),
(122, 43, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 05:49:01'),
(123, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 05:56:21'),
(124, 2, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 05:58:13'),
(125, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 05:58:24'),
(126, 43, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-09 05:58:51'),
(127, 43, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 00:27:58'),
(128, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 00:44:23'),
(129, 42, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 02:23:48'),
(130, 42, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 13:05:11'),
(131, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 13:11:49'),
(132, 42, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 13:13:38'),
(133, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 13:21:50'),
(134, 42, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', '2025-04-10 14:49:41'),
(135, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-11 00:32:23'),
(136, 42, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-11 00:32:42'),
(137, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-11 13:06:48'),
(138, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-12 14:32:17'),
(139, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-12 16:33:32'),
(140, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-12 16:49:26'),
(141, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-12 17:52:50'),
(142, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-12 18:07:40'),
(143, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-14 01:46:22'),
(144, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-14 01:46:48'),
(145, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 14:24:02'),
(146, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 14:24:48'),
(147, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 15:07:14'),
(148, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 15:25:17'),
(149, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 15:25:44'),
(150, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 15:59:14'),
(151, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:04:12'),
(152, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:04:59'),
(153, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:05:19'),
(154, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:07:32'),
(155, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:31:48'),
(156, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:38:48'),
(157, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:39:22'),
(158, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:42:35'),
(159, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 16:59:50'),
(160, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-19 17:00:31'),
(161, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 04:09:45'),
(162, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 04:12:23'),
(163, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 04:36:49'),
(164, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 04:38:47'),
(165, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 04:44:45'),
(166, 42, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 04:50:21'),
(167, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 12:17:20'),
(168, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 12:18:03'),
(169, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 15:27:10'),
(170, 46, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 15:34:06'),
(171, 46, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 23:01:32'),
(172, 2, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 23:29:25'),
(173, 43, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 23:38:34'),
(174, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-20 23:45:04'),
(175, 1, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-21 04:30:12'),
(176, 46, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-21 14:35:39'),
(177, 46, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-21 23:06:37'),
(178, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-21 23:45:56'),
(179, 13, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 00:14:32'),
(180, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 00:16:43'),
(181, 12, 'Login ke sistem', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-22 00:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `pejabat_penandatangan`
--

DROP TABLE IF EXISTS `pejabat_penandatangan`;
CREATE TABLE IF NOT EXISTS `pejabat_penandatangan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jabatan` enum('Pejabat 1','Pejabat 2','Pejabat 3','Pejabat Keuangan','Pejabat Pengawas') NOT NULL,
  `user_id` int NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permohonan`
--

DROP TABLE IF EXISTS `permohonan`;
CREATE TABLE IF NOT EXISTS `permohonan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `mata_anggaran` varchar(50) NOT NULL,
  `detail_kegiatan` text NOT NULL,
  `nomor_permohonan` varchar(50) NOT NULL,
  `tanggal_permohonan` datetime NOT NULL,
  `grand_total_harga` decimal(15,2) DEFAULT NULL,
  `keterangan` text,
  `status` enum('Menunggu Persetujuan SPM','Menunggu Persetujuan Plt.Kasubbag Umum / PPK','Menunggu Persetujuan KPA','Ditolak') NOT NULL DEFAULT 'Menunggu Persetujuan SPM',
  `status2` int NOT NULL DEFAULT '1',
  `qr_code_pemohon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `qr_code_pejabat1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `waktu_ttd_katimja` datetime DEFAULT NULL,
  `keterangan_SPM` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `qr_code_ppk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `keterangan_PPK` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `qr_code_kpa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `keterangan_KPA` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `waktu_ttd_ppk` datetime DEFAULT NULL,
  `waktu_ttd_kpa` datetime DEFAULT NULL,
  `bukti_nota_pdf` varchar(255) DEFAULT NULL,
  `foto_geotagging` varchar(255) DEFAULT NULL,
  `dokumen_pendukung` varchar(255) DEFAULT NULL,
  `keterangan_bukti` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_permohonan` (`nomor_permohonan`),
  UNIQUE KEY `nomor_permohonan_2` (`nomor_permohonan`),
  KEY `fk_permohonan_user` (`user_id`),
  KEY `fk_status_permohonan` (`status2`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permohonan`
--

INSERT INTO `permohonan` (`id`, `user_id`, `mata_anggaran`, `detail_kegiatan`, `nomor_permohonan`, `tanggal_permohonan`, `grand_total_harga`, `keterangan`, `status`, `status2`, `qr_code_pemohon`, `qr_code_pejabat1`, `waktu_ttd_katimja`, `keterangan_SPM`, `qr_code_ppk`, `keterangan_PPK`, `qr_code_kpa`, `keterangan_KPA`, `created_at`, `updated_at`, `waktu_ttd_ppk`, `waktu_ttd_kpa`, `bukti_nota_pdf`, `foto_geotagging`, `dokumen_pendukung`, `keterangan_bukti`) VALUES
(16, 2, '', 'xx', 'PB-1741525999', '2025-03-09 00:00:00', 25000.00, NULL, 'Ditolak', 4, 'test_qrcode_1741788593.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-09 13:13:19', '2025-03-21 12:34:34', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 2, '', 'xx', 'PB-1741526124', '2025-03-09 00:00:00', 25000.00, NULL, 'Ditolak', 4, 'qrcodes/permohonan_17.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-09 13:15:24', '2025-03-21 12:34:40', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 2, '', 'Pasang Lampu', 'PB-1741569871', '2025-03-10 00:00:00', 87500.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcode/PB-1741569871_2025-03-10_Tri Yuli Nugrahanto.png', 'QR_SPM_PB-1741569871_Tri_Yuli_Nugrahanto_2025-03-12_10-29-07.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-10 01:24:31', '2025-03-21 12:34:55', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 2, '-', 'KEgiatan ABC', 'PB-1741571801', '2025-03-10 00:00:00', 68600.00, NULL, 'Ditolak', 4, 'uploads/qrcode/PB-1741571801_2025-03-10_Tri Yuli Nugrahanto.png', 'QR_SPM_PB-1741571801_Tri_Yuli_Nugrahanto_2025-03-12_10-28-41.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-10 01:56:41', '2025-03-21 12:35:07', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 2, '-', 'Membuat Input Data Barang', 'PB-1741583281', '2025-03-10 00:00:00', 47000.00, NULL, 'Ditolak', 3, 'PB-1741583281_2025-03-10_Tri Yuli Nugrahanto.png', '/uploads/qrcode/katimja/PB-1741583281_2025-04-13_Rahmawati-Umasugi.png', NULL, NULL, '/uploads/qrcode/ppk/PB-1741583281_2025-04-13_Rahmawati-Umasugi.png', NULL, '/uploads/qrcode/pejabat/PB-1741583281_2025-04-21_ABUBAKAR-SStPi-MSi.png', NULL, '2025-03-10 05:08:01', '2025-04-20 23:46:49', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 2, 'Untuk diisi Pejabat PPK', 'Tes Isi Kegiatan', 'PB-1741592840', '2025-03-10 00:00:00', 44500.00, NULL, 'Ditolak', 3, 'uploads/qrcode/PB-1741592840_2025-03-10_Tri Yuli Nugrahanto.png', 'QR_SPM_PB-1741592840_Tri_Yuli_Nugrahanto_2025-03-12_01-14-02.png', NULL, NULL, '/uploads/qrcode/ppk/PB-1741592840_2025-04-13_Rahmawati-Umasugi.png', NULL, '/uploads/qrcode/pejabat/PB-1741592840_2025-04-21_ABUBAKAR-SStPi-MSi.png', NULL, '2025-03-10 07:47:21', '2025-04-20 23:46:58', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 2, 'Untuk diisi Pejabat PPK', 'random data ', 'PB-1741640848', '2025-03-10 00:00:00', 22000.00, NULL, 'Menunggu Persetujuan KPA', 2, 'uploads/qrcode/PB-1741640848_2025-03-10_Tri Yuli Nugrahanto.png', 'QR_SPM_PB-1741640848_Tri_Yuli_Nugrahanto_2025-03-12_09-51-28.png', NULL, NULL, '/uploads/qrcode/pejabat/PB-1741640848_2025-04-22_Rahmawati-Umasugi.png', NULL, NULL, NULL, '2025-03-10 21:07:28', '2025-04-22 00:49:18', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 2, 'Untuk diisi Pejabat PPK', 'JJJ', 'PB-1741663543', '2025-03-11 00:00:00', 30000.00, NULL, 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK', 1, 'uploads/qrcode/PB-1741663543_2025-03-11_Tri Yuli Nugrahanto.png', 'QR_SPM_PB-1741663543_Tri_Yuli_Nugrahanto_2025-03-12_01-06-09.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-11 03:25:43', '2025-03-13 04:04:14', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 2, 'Untuk diisi Pejabat PPK', 'abc de', 'PB-1741704837', '2025-03-11 00:00:00', 1000000.00, NULL, 'Menunggu Persetujuan SPM', 0, NULL, 'QR_SPM_PB-1741704837_Tri_Yuli_Nugrahanto_2025-03-12_01-10-15.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-11 14:53:57', '2025-03-23 23:18:37', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 2, 'Untuk diisi Pejabat PPK', 'abc de', 'PB-1741704936', '2025-03-11 00:00:00', 1000000.00, NULL, 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK', 1, NULL, '../uploads/qrcode/QR_SPM_PB-1741704936_Tri_Yuli_Nugrahanto_2025-03-12_08-52-59.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-11 14:55:36', '2025-03-13 04:04:07', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 2, 'Untuk diisi Pejabat PPK', 'abc de', 'PB-1741705269', '2025-03-11 00:00:00', 1000000.00, NULL, 'Menunggu Persetujuan KPA', 1, 'uploads/qrcode/PB-1741705269_2025-03-11_Tri Yuli Nugrahanto.png', '../uploads/qrcode/QR_SPM_PB-1741705269_Tri_Yuli_Nugrahanto_2025-03-12_11-11-52.png', NULL, NULL, 'uploads/qrcode/Qrcode_PPK_PB-1741705269_13-March-2025,-14_35_06_Pejabat_Kasubbag_Umum___PPK.png', NULL, NULL, NULL, '2025-03-11 15:01:09', '2025-03-13 05:35:06', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 2, 'tes edit-1234', 'tes esign simola lagi', 'PB-1741705392', '2025-03-11 00:00:00', 11000.00, NULL, 'Menunggu Persetujuan KPA', 1, 'uploads/qrcode/PB-1741705392_2025-03-11 15:03:12_Tri Yuli Nugrahanto.png', '../uploads/qrcode/QR_SPM_PB-1741705392_Tri_Yuli_Nugrahanto_2025-03-12_10-44-40.png', NULL, NULL, 'uploads/qrcode/Qrcode_PPK_PB-1741705392_13-March-2025,-14_34_54_Pejabat_Kasubbag_Umum___PPK.png', NULL, NULL, NULL, '2025-03-11 15:03:12', '2025-03-13 05:34:55', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 2, 'KODE-12345', 'tes esign simola lagi', 'PB-1741705690', '2025-03-11 00:00:00', 11000.00, NULL, 'Menunggu Persetujuan KPA', 3, 'uploads/qrcode/PB-1741705690_2025-03-11_15-08-10_Tri Yuli Nugrahanto.png', '../uploads/qrcode/Qrcode_SPM_PB-1741705690_13-March-2025,-15_39_01_ABUBAKAR, S.St.Pi, M.Si.png', NULL, NULL, 'uploads/qrcode/Qrcode_PPK_PB-1741753103_Rachmawati%20U.,%20S.Kom_13-March-2025_02-36-28.png', NULL, NULL, NULL, '2025-03-11 15:08:10', '2025-03-20 06:09:14', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 2, '5212345', 'TEs esign simola dengan logo ', 'PB-1741705726', '2025-03-11 00:00:00', 12000.00, NULL, 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK', 3, 'uploads/qrcode/PB-1741705726_2025-03-11_15-08-46_Tri Yuli Nugrahanto.png', '../uploads/qrcode/Qrcode_SPM_PB-1741705726_13-March-2025,-15_38_15_ABUBAKAR, S.St.Pi, M.Si.png', NULL, NULL, 'uploads/qrcode/Qrcode_PPK_PB-1741705726_13-March-2025,-14_15_58_Pejabat_Kasubbag_Umum___PPK.png', NULL, '/uploads/qrcode/pejabat/PB-1741705726_2025-04-21_ABUBAKAR-SStPi-MSi.png', NULL, '2025-03-11 15:08:46', '2025-04-20 23:46:26', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 2, 'Untuk diisi Pejabat PPK', 'Tes Qrcode 12 Maret 2025', 'PB-1741753103', '2025-03-12 13:18:23', 40500.00, NULL, 'Menunggu Persetujuan Plt.Kasubbag Umum / PPK', 3, 'uploads/qrcode/PB-1741753103_2025-03-12_13-18-23_Tri Yuli Nugrahanto.png', 'Qrcode_sPm_PB-1741753103_Rachel S_13-March-2025_11-28-23.png', NULL, NULL, NULL, NULL, '/uploads/qrcode/pejabat/PB-1741753103_2025-04-21_ABUBAKAR-SStPi-MSi.png', NULL, '2025-03-12 04:18:23', '2025-04-20 15:06:24', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 2, 'Untuk diisi Pejabat PPK', '', 'PB-20250318-001', '2025-03-18 09:08:00', 459500.00, NULL, 'Menunggu Persetujuan SPM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-18 00:08:00', '2025-03-21 12:35:30', NULL, NULL, NULL, NULL, NULL, NULL),
(41, 2, 'Untuk diisi Pejabat PPK', '', 'PB-20250318-002', '2025-03-18 09:10:56', 459500.00, NULL, 'Menunggu Persetujuan SPM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-18 00:10:56', '2025-03-21 12:35:36', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 2, '', 'Dananjaya Sabda Nugraha', 'PB-19032025-0002', '0000-00-00 00:00:00', 37500000.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcodes/PB-19032025-0002.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-18 15:38:30', '2025-03-21 12:35:43', NULL, NULL, NULL, NULL, NULL, NULL),
(50, 2, '', 'Danendra', 'PB-19032025-0003', '2025-03-19 00:53:45', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0003.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-18 15:53:45', '2025-03-18 15:53:45', NULL, NULL, NULL, NULL, NULL, NULL),
(52, 2, '', 'xx', 'PB-19032025-0001', '2025-03-19 01:00:53', 0.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcode/PB-19032025-0001.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-18 16:00:53', '2025-03-23 23:18:50', NULL, NULL, NULL, NULL, NULL, NULL),
(53, 2, '', 'KEgiatan ABC', 'PB-19032025-0004', '2025-03-19 09:14:44', 0.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcode/PB-19032025-0004.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 00:14:45', '2025-03-23 23:18:58', NULL, NULL, NULL, NULL, NULL, NULL),
(54, 2, '', 'tes id 54', 'PB-19032025-0005', '2025-03-19 09:23:42', 0.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcode/PB-19032025-0005.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 00:23:42', '2025-03-23 23:19:07', NULL, NULL, NULL, NULL, NULL, NULL),
(55, 2, '', 'tes id 54', 'PB-19032025-0006', '2025-03-19 09:26:50', 0.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcode/PB-19032025-0006.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 00:26:50', '2025-03-23 23:19:18', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 2, '', 'tes id 54', 'PB-19032025-0007', '2025-03-19 09:29:12', 0.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcode/PB-19032025-0007.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 00:29:13', '2025-03-23 23:19:14', NULL, NULL, NULL, NULL, NULL, NULL),
(57, 2, '', 'tes id 54', 'PB-19032025-0008', '2025-03-19 09:37:29', 0.00, NULL, 'Menunggu Persetujuan SPM', 0, 'uploads/qrcode/PB-19032025-0008.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 00:37:29', '2025-03-23 23:19:24', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 2, '', 'tes id 54', 'PB-19032025-0009', '2025-03-19 09:44:27', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0009.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 00:44:27', '2025-03-19 00:44:27', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 2, '', 'tes id 54', 'PB-19032025-0010', '2025-03-19 09:48:49', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0010.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 00:48:49', '2025-03-19 00:48:49', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 2, '', 'xxxxx', 'PB-19032025-0011', '2025-03-19 10:03:28', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0011.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:03:29', '2025-03-19 01:03:29', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 2, '', 'xxxxx', 'PB-19032025-0012', '2025-03-19 10:05:18', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0012.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:05:19', '2025-03-19 01:05:19', NULL, NULL, NULL, NULL, NULL, NULL),
(62, 2, '', 'xxxxx', 'PB-19032025-0013', '2025-03-19 10:05:40', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0013.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:05:41', '2025-03-19 01:05:41', NULL, NULL, NULL, NULL, NULL, NULL),
(63, 2, '', 'xxxxx', 'PB-19032025-0014', '2025-03-19 10:08:04', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0014.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:08:04', '2025-03-19 01:08:04', NULL, NULL, NULL, NULL, NULL, NULL),
(64, 2, '', 'xxxxx', 'PB-19032025-0015', '2025-03-19 10:08:29', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0015.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:08:30', '2025-03-19 01:08:30', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 2, '', 'xxxxx', 'PB-19032025-0016', '2025-03-19 10:15:13', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0016.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:15:13', '2025-03-19 01:15:13', NULL, NULL, NULL, NULL, NULL, NULL),
(66, 2, '', 'xxxxx', 'PB-19032025-0017', '2025-03-19 10:20:31', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0017.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:20:31', '2025-03-19 01:20:31', NULL, NULL, NULL, NULL, NULL, NULL),
(67, 2, '', 'xxxxx', 'PB-19032025-0018', '2025-03-19 10:21:32', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, 'uploads/qrcode/PB-19032025-0018.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:21:32', '2025-03-19 01:21:32', NULL, NULL, NULL, NULL, NULL, NULL),
(68, 2, '', 'Judul Kegiatan abc', 'PB-19032025-0019', '2025-03-19 10:43:01', 0.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0019.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 01:43:01', '2025-03-19 01:43:01', NULL, NULL, NULL, NULL, NULL, NULL),
(75, 2, '', 'APATU', 'PB-19032025-0020', '2025-03-19 14:38:16', 227000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0020.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 05:38:17', '2025-03-19 05:38:17', NULL, NULL, NULL, NULL, NULL, NULL),
(76, 2, '', 'APATU', 'PB-19032025-0021', '2025-03-19 14:39:40', 227000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0021.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 05:39:40', '2025-03-19 05:39:40', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 2, '', 'APATU', 'PB-19032025-0022', '2025-03-19 14:40:56', 227000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0022.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 05:40:57', '2025-03-19 05:40:57', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 2, '', '', 'PB-19032025-0023', '2025-03-19 21:19:49', 2250000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0023.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 12:19:50', '2025-03-19 12:19:50', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 2, '', 'Danendra Aqsa', 'PB-19032025-0024', '2025-03-19 21:23:17', 2250000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0024.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 12:23:18', '2025-03-19 12:23:18', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 2, '', 'Danendra Aqsa', 'PB-19032025-0025', '2025-03-19 21:24:38', 2250000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0025.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 12:24:39', '2025-03-19 12:24:39', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 2, '', 'Danendra Aqsa', 'PB-19032025-0026', '2025-03-19 21:26:31', 2250000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0026.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 12:26:32', '2025-03-19 12:26:32', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 2, '', 'xcodein', 'PB-19032025-0027', '2025-03-19 22:28:59', 3000000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0027.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 13:29:00', '2025-03-19 13:29:00', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 2, '', 'xcodein', 'PB-19032025-0028', '2025-03-19 22:40:19', 3000000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0028.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 13:40:20', '2025-03-19 13:40:20', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 2, '', 'xcodein', 'PB-19032025-0029', '2025-03-19 22:44:34', 3000000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0029.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 13:44:35', '2025-03-19 13:44:35', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 2, '', 'xcodein', 'PB-19032025-0030', '2025-03-19 22:45:11', 3000000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0030.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 13:45:12', '2025-03-19 13:45:12', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 2, '', 'xcodein', 'PB-19032025-0031', '2025-03-19 22:49:39', 3000000.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0031.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 13:49:40', '2025-03-19 13:49:40', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 2, '', 'coffe break', 'PB-19032025-0032', '2025-03-19 22:52:00', 25600.00, NULL, 'Menunggu Persetujuan SPM', 1, '/uploads/qrcode/PB-19032025-0032.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 13:52:01', '2025-03-19 13:52:01', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 2, 'ABC123', 'Stock Inventaris Pekerjaan', 'PB-19032025-0033', '2025-03-19 23:15:03', 207000.00, NULL, 'Menunggu Persetujuan SPM', 2, '/uploads/qrcode/PB-19032025-0033.png', NULL, NULL, NULL, '/uploads/qrcode/pejabat/PB-19032025-0033_2025-04-22_Rahmawati-Umasugi.png', NULL, NULL, NULL, '2025-03-19 14:15:04', '2025-04-22 00:40:57', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 2, 'sudah diisi ppk', 'a', 'PB-19032025-0034', '2025-03-19 23:15:56', 2400.00, NULL, 'Menunggu Persetujuan SPM', 2, '/uploads/qrcode/PB-19032025-0034.png', NULL, NULL, NULL, '/uploads/qrcode/pejabat/PB-19032025-0034_2025-04-22_Rahmawati-Umasugi.png', NULL, NULL, NULL, '2025-03-19 14:15:56', '2025-04-22 00:48:43', NULL, NULL, NULL, NULL, NULL, NULL),
(90, 2, '', 'KAPAL', 'PB-21032025-0001', '2025-03-21 15:36:59', 1050000.00, NULL, 'Menunggu Persetujuan SPM', 3, '/uploads/qrcode/PB-21032025-0001.png', '/uploads/qrcode/katimja/PB-21032025-0001_2025-04-13_Rahmawati-Umasugi.png', NULL, NULL, '/uploads/qrcode/kpa/PB-21032025-0001_2025-04-20_Rahmawati-Umasugi.png', NULL, NULL, NULL, '2025-03-21 06:36:59', '2025-04-19 16:01:31', NULL, NULL, NULL, NULL, NULL, NULL),
(91, 2, '', 'Tes permohonan tanggal 9 April 2025', 'PB-09042025-0001', '2025-04-09 10:36:48', 81000.00, NULL, 'Menunggu Persetujuan SPM', 0, '/uploads/qrcode/PB-09042025-0001.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-09 01:36:48', '2025-04-09 01:36:48', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 2, '', 'Tes Isi Kegiatan 9 April 2025', 'PB-09042025-0002', '2025-04-09 11:15:02', 5000.00, NULL, 'Menunggu Persetujuan SPM', 0, '/uploads/qrcode/PB-09042025-0002.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-09 02:15:03', '2025-04-09 02:15:03', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 43, '12345X', 'halo pak eka', 'PB-09042025-0003', '2025-04-09 14:49:34', 59500.00, NULL, 'Menunggu Persetujuan SPM', 6, '/uploads/qrcode/PB-09042025-0003.png', '/uploads/qrcode/katimja/PB-09042025-0003_2025-04-20_EKADASA-PRIANTARA.png', NULL, NULL, '/uploads/qrcode/pejabat/PB-09042025-0003_2025-04-20_Rahmawati-Umasugi.png', NULL, '/uploads/qrcode/pejabat/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', NULL, '2025-04-09 05:49:35', '2025-04-20 16:32:09', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 43, '0101010101010', 'gkgk', 'PB-10042025-0001', '2025-04-10 09:40:53', 45000.00, NULL, 'Menunggu Persetujuan SPM', 2, '/uploads/qrcode/PB-10042025-0001.png', '/uploads/qrcode/katimja/PB-10042025-0001_2025-04-11_EKADASA-PRIANTARA.png', NULL, NULL, '/uploads/qrcode/pejabat/PB-10042025-0001_2025-04-22_Rahmawati-Umasugi.png', NULL, '', NULL, '2025-04-10 00:40:54', '2025-04-22 00:50:01', NULL, NULL, '1745209662_15ee251b-9cf2-465d-aa24-1a071a9f0c5d.jpeg', '1745209662_b443736a-993c-4820-98fd-487d35406cff.jpeg', '1745206606_1._JANUARI_Rekap_PNBP_Kementerian.pdf', 'update keterangan1');

-- --------------------------------------------------------

--
-- Table structure for table `permohonan_detail`
--

DROP TABLE IF EXISTS `permohonan_detail`;
CREATE TABLE IF NOT EXISTS `permohonan_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permohonan_id` int NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `harga_satuan` bigint NOT NULL,
  `jumlah_barang` int DEFAULT NULL,
  `subtotal_harga` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permohonan_id` (`permohonan_id`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permohonan_detail`
--

INSERT INTO `permohonan_detail` (`id`, `permohonan_id`, `nama_barang`, `satuan`, `harga_satuan`, `jumlah_barang`, `subtotal_harga`) VALUES
(1, 1, 'paket a', 'buah', 2000, 5, 10000.00),
(2, 2, 'paket a', 'buah', 2000, 5, 10000.00),
(3, 3, 'paket a', 'buah', 2000, 5, 10000.00),
(4, 4, 'paket a', 'buah', 2000, 5, 10000.00),
(5, 5, 'paket a', 'buah', 2000, 5, 10000.00),
(6, 6, 'paket a', 'buah', 2000, 5, 10000.00),
(7, 29, 'paket a', 'buah', 2000, 5, 10000.00),
(8, 29, 'paket a', 'buah', 2500, 1, 2500.00),
(9, 29, 'paket a', 'buah', 2500, 1, 2500.00),
(10, 29, 'paket a', 'buah', 2500, 1, 2500.00),
(11, 28, 'A', 'buah', 12500, 2, 25000.00),
(12, 29, 'paket a', 'buah', 2500, 3, 7500.00),
(13, 28, 'paket a', 'buah', 2500, 3, 7500.00),
(14, 30, 'Barang 1', 'Pax', 4500, 5, 22500.00),
(15, 30, 'Barang 2', 'Paket', 5000, 2, 10000.00),
(16, 30, 'Barang 3', 'Lusin', 12000, 1, 12000.00),
(17, 31, 'Random A', 'Pax', 2500, 4, 10000.00),
(18, 31, 'Random B', 'Pcs', 3000, 4, 12000.00),
(19, 32, 'JJ', 'pax', 6000, 5, 30000.00),
(20, 33, 'tes qrcode logo', 'pax', 1000000, 1, 1000000.00),
(21, 34, 'tes qrcode logo', 'pax', 1000000, 1, 1000000.00),
(22, 35, 'tes qrcode logo', 'pax', 1000000, 1, 1000000.00),
(23, 36, 'tes esign logo', 'buah', 11000, 1, 11000.00),
(24, 37, 'tes esign logo', 'buah', 11000, 1, 11000.00),
(25, 38, 'tes esign simola', 'Pcs', 12000, 1, 12000.00),
(26, 39, 'Tes Qrcode 12 Maret 2025', 'buah', 13500, 3, 40500.00),
(27, 40, 'Paket A', 'pcs', 75000, 5, 375000.00),
(28, 40, 'Paket B', 'Pcs', 8500, 7, 59500.00),
(29, 40, 'Paket C', 'Buah', 5000, 5, 25000.00),
(30, 41, 'Paket A', 'pcs', 75000, 5, 375000.00),
(31, 41, 'Paket B', 'Pcs', 8500, 7, 59500.00),
(32, 41, 'Paket C', 'Buah', 5000, 5, 25000.00),
(33, 42, 'OBat A', 'Dus', 55000, 10, 550000.00),
(34, 43, 'OBat A', 'Dus', 55000, 10, 550000.00),
(35, 0, 'Sabda', 'Person', 5000, 12, 60000.00),
(36, 0, 'Sabda', 'Person', 5000, 12, 60000.00),
(37, 45, 'Max A', 'Bh', 14500, 8, 116000.00),
(38, 45, 'Mac B', 'Pcs', 15000, 9, 135000.00),
(39, 45, 'Mac B', 'Pcs', 15000, 9, 135000.00),
(40, 46, 'Mac', 'Pcs', 12500000, 3, 37500000.00),
(41, 48, 'Mac', 'Pcs', 12500000, 3, 37500000.00),
(42, 50, 'Barang 1', 'pax', 4700000, 4, 18800000.00),
(43, 52, 'xy', 'Buah', 4850000, 4, 19400000.00),
(44, 53, 'Lampu', 'pcs', 2300, 4, 9200.00),
(45, 56, 'id 54', 'pcs', 4500, 15, 1.00),
(46, 57, 'id 54', 'pcs', 4500, 15, 1.00),
(47, 58, 'id 54', 'pcs', 4500, 15, 1.00),
(48, 59, 'id 54', 'pcs', 4500, 15, 67500.00),
(49, 60, 'b', 'pax', 1200, 31, 37200.00),
(50, 61, 'b', 'pax', 1200, 31, 37200.00),
(51, 62, 'b', 'pax', 1200, 31, 37200.00),
(52, 63, 'b', 'pax', 1200, 31, 37200.00),
(53, 64, 'b', 'pax', 1200, 31, 37200.00),
(54, 65, 'b', 'pax', 1200, 31, 0.00),
(55, 66, 'b', 'pax', 1200, 31, 0.00),
(56, 67, 'b', 'pax', 1200, 31, 37200.00),
(57, 68, 'x', 'Buah', 4300, 12, 51600.00),
(58, 75, 'Ap', 'pcs', 45000, 0, 0.00),
(59, 75, 'tuh', 'Buah', 23000, 0, 0.00),
(60, 76, 'Ap', 'pcs', 45000, 3, 0.00),
(61, 76, 'tuh', 'Buah', 23000, 4, 0.00),
(62, 77, 'Ap', 'pcs', 45000, 3, 0.00),
(63, 77, 'tuh', 'Buah', 23000, 4, 0.00),
(64, 78, 'Aqsa', 'orang', 450000, 5, 2250000.00),
(65, 79, 'Aqsa', 'orang', 450000, 5, 2250000.00),
(66, 80, 'Aqsa', 'orang', 450000, 5, 2250000.00),
(67, 81, 'Aqsa', 'orang', 450000, 5, 2250000.00),
(68, 82, 'xcodein 1', 'Buah', 1000000, 3, 3000000.00),
(69, 83, 'xcodein 1', 'Buah', 1000000, 3, 3000000.00),
(70, 84, 'xcodein 1', 'Buah', 1000000, 3, 3000000.00),
(71, 85, 'xcodein 1', 'Buah', 1000000, 3, 3000000.00),
(72, 86, 'xcodein 1', 'Buah', 1000000, 3, 3000000.00),
(73, 87, 'Coffee', 'bungkus', 12800, 2, 25600.00),
(74, 88, 'Gula', 'Kg', 15000, 1, 15000.00),
(75, 88, 'Kopi', 'Bungkus', 32000, 1, 32000.00),
(76, 88, 'Rokok', 'Slop', 160000, 1, 160000.00),
(77, 89, 'a', 'a', 1200, 2, 2400.00),
(78, 90, 'OLI', 'DRUM', 1050000, 1, 1050000.00),
(79, 91, 'nama barang 1', 'Pcs', 5000, 12, 60000.00),
(80, 91, 'nama Barang B', 'Buah', 7000, 3, 21000.00),
(81, 92, 'Test 1', 'Pcx', 2500, 2, 5000.00),
(82, 93, 'Kopi', 'Bungkus', 8500, 7, 59500.00),
(83, 94, 'lakban', 'pax', 5000, 5, 25000.00),
(84, 94, 'lem', 'pcs', 4000, 5, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pemohon` varchar(255) NOT NULL,
  `detail_kegiatan` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `tanggal_permintaan` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_permohonan`
--

DROP TABLE IF EXISTS `riwayat_permohonan`;
CREATE TABLE IF NOT EXISTS `riwayat_permohonan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nomor_permohonan` varchar(50) NOT NULL,
  `tanggal_permohonan` date NOT NULL,
  `status` enum('menunggu approve','disetujui','ditolak') DEFAULT 'menunggu approve',
  `qr_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_permohonan` (`nomor_permohonan`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `set_pejabat`
--

DROP TABLE IF EXISTS `set_pejabat`;
CREATE TABLE IF NOT EXISTS `set_pejabat` (
  `id_set_pejabat` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_level` int NOT NULL,
  PRIMARY KEY (`id_set_pejabat`),
  KEY `id_user` (`id_user`),
  KEY `id_level` (`id_level`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `set_pejabat`
--

INSERT INTO `set_pejabat` (`id_set_pejabat`, `id_user`, `id_level`) VALUES
(1, 12, 3),
(2, 13, 4),
(3, 45, 5),
(4, 11, 7),
(5, 46, 6);

-- --------------------------------------------------------

--
-- Table structure for table `signatures`
--

DROP TABLE IF EXISTS `signatures`;
CREATE TABLE IF NOT EXISTS `signatures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permohonan_id` int NOT NULL,
  `pejabat_id` int NOT NULL,
  `signature` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `permohonan_id` (`permohonan_id`),
  KEY `pejabat_id` (`pejabat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `signatures`
--

INSERT INTO `signatures` (`id`, `permohonan_id`, `pejabat_id`, `signature`, `created_at`) VALUES
(82, 93, 12, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 12:26:34'),
(83, 94, 12, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 12:27:02'),
(80, 93, 13, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 12:18:10'),
(81, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 12:23:13'),
(69, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:04:40'),
(68, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:53:04'),
(67, 93, 12, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 04:52:15'),
(66, 93, 42, '/uploads/qrcode/katimja/PB-09042025-0003_2025-04-20_EKADASA-PRIANTARA.png', '2025-04-20 04:51:36'),
(65, 93, 42, '/uploads/qrcode/katimja/PB-09042025-0003_2025-04-20_EKADASA-PRIANTARA.png', '2025-04-20 04:50:45'),
(60, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:40:22'),
(61, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:44:07'),
(62, 94, 12, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 04:44:54'),
(63, 94, 12, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 04:47:02'),
(64, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:47:19'),
(79, 93, 13, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:44:07'),
(78, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:42:05'),
(77, 93, 13, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:37:33'),
(76, 93, 12, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 05:27:50'),
(75, 93, 13, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:27:07'),
(74, 93, 13, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:25:12'),
(73, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:23:20'),
(72, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:12:22'),
(71, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:08:09'),
(70, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 05:06:16'),
(59, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:39:42'),
(58, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:19:04'),
(57, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:17:26'),
(56, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:14:48'),
(55, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:13:17'),
(54, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:12:39'),
(53, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:11:13'),
(52, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 04:09:53'),
(51, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-19 17:00:42'),
(50, 94, 12, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_Rahmawati-Umasugi.png', '2025-04-19 17:00:12'),
(49, 94, 13, '/uploads/qrcode/ppk/PB-10042025-0001_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-19 16:50:45'),
(84, 93, 13, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 12:43:57'),
(85, 93, 13, '/uploads/qrcode/ppk/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 12:46:00'),
(86, 93, 13, '/uploads/qrcode/pejabat/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 12:58:44'),
(87, 93, 12, '/uploads/qrcode/pejabat/PB-09042025-0003_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 13:00:31'),
(88, 93, 12, '/uploads/qrcode/pejabat/PB-09042025-0003_2025-04-20_Rahmawati-Umasugi.png', '2025-04-20 13:02:27'),
(89, 93, 13, '/uploads/qrcode/pejabat/PB-09042025-0003_2025-04-20_ABUBAKAR-SStPi-MSi.png', '2025-04-20 13:03:19'),
(90, 39, 13, '/uploads/qrcode/pejabat/PB-1741753103_2025-04-21_ABUBAKAR-SStPi-MSi.png', '2025-04-20 15:06:24'),
(91, 94, 12, '/uploads/qrcode/pejabat/PB-10042025-0001_2025-04-21_Rahmawati-Umasugi.png', '2025-04-20 15:19:50'),
(92, 94, 12, '/uploads/qrcode/pejabat/PB-10042025-0001_2025-04-21_Rahmawati-Umasugi.png', '2025-04-20 15:21:09'),
(93, 38, 13, '/uploads/qrcode/pejabat/PB-1741705726_2025-04-21_ABUBAKAR-SStPi-MSi.png', '2025-04-20 23:46:25'),
(94, 29, 13, '/uploads/qrcode/pejabat/PB-1741583281_2025-04-21_ABUBAKAR-SStPi-MSi.png', '2025-04-20 23:46:48'),
(95, 30, 13, '/uploads/qrcode/pejabat/PB-1741592840_2025-04-21_ABUBAKAR-SStPi-MSi.png', '2025-04-20 23:46:57'),
(96, 88, 12, '/uploads/qrcode/pejabat/PB-19032025-0033_2025-04-22_Rahmawati-Umasugi.png', '2025-04-22 00:40:53'),
(97, 89, 12, '/uploads/qrcode/pejabat/PB-19032025-0034_2025-04-22_Rahmawati-Umasugi.png', '2025-04-22 00:48:42'),
(98, 31, 12, '/uploads/qrcode/pejabat/PB-1741640848_2025-04-22_Rahmawati-Umasugi.png', '2025-04-22 00:49:17'),
(99, 94, 12, '/uploads/qrcode/pejabat/PB-10042025-0001_2025-04-22_Rahmawati-Umasugi.png', '2025-04-22 00:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `status_permohonan`
--

DROP TABLE IF EXISTS `status_permohonan`;
CREATE TABLE IF NOT EXISTS `status_permohonan` (
  `id_status` int NOT NULL,
  `nama_status` varchar(100) NOT NULL,
  `deskripsi_status` varchar(255) NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status_permohonan`
--

INSERT INTO `status_permohonan` (`id_status`, `nama_status`, `deskripsi_status`) VALUES
(0, 'Diajukan', 'Permohonan telah diajukan oleh pemohon dan menunggu verifikasi Pejabat Katimja'),
(1, 'Disetujui Katimja', 'Permohonan telah diverifikasi oleh Pejabat 1 (KATIMJA )dan menunggu verifikasi Pejabat  2'),
(2, 'Disetujui PPK', 'Permohonan telah diverifikasi oleh Pejabat Kasubbag Umum / PPK dan menunggu verifikasi Pejabat KPA'),
(3, 'Disetujui KPA', 'Permohonan telah disetujui oleh Pejabat KPA'),
(4, 'Ditolak', 'Permohonan ditolak oleh salah satu pejabat'),
(5, 'Revisi', 'Permohonan perlu diperbaiki oleh pemohon sebelum diajukan kembali'),
(6, 'Menunggu Pencairan', 'Permohonan telah diterima oleh bagian administrasi dan siap untuk diproses'),
(7, 'Proses Belanja', 'Menunggu Mengupload Data Pembelian Barang'),
(8, 'Selesai', 'Proses Permohonan selesai');

-- --------------------------------------------------------

--
-- Table structure for table `tanda_tangan_permohonan`
--

DROP TABLE IF EXISTS `tanda_tangan_permohonan`;
CREATE TABLE IF NOT EXISTS `tanda_tangan_permohonan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permohonan_id` int NOT NULL,
  `pejabat_id` int NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `permohonan_id` (`permohonan_id`),
  KEY `pejabat_id` (`pejabat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tim_kerja`
--

DROP TABLE IF EXISTS `tim_kerja`;
CREATE TABLE IF NOT EXISTS `tim_kerja` (
  `id_tim_kerja` int NOT NULL AUTO_INCREMENT,
  `nama_tim` varchar(100) NOT NULL,
  `id_ketua_tim` int DEFAULT NULL,
  PRIMARY KEY (`id_tim_kerja`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tim_kerja`
--

INSERT INTO `tim_kerja` (`id_tim_kerja`, `nama_tim`, `id_ketua_tim`) VALUES
(1, 'TIMJA Sarpras Serta Pengelolaan PNBP', 11),
(2, 'Tim Kerja Pelatihan', 42),
(6, 'TIM KERJA DUKUNGAN MANAJERIAL', 12),
(8, 'TIM KERJA PENYULUHAN', 35);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `atasan_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `nip_nik` varchar(50) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level_user` int NOT NULL DEFAULT '1',
  `role` enum('pemohon','pejabat_spm','pejabat_ppk','pejabat_kpa','pengawas','operator','admin') NOT NULL DEFAULT 'pemohon',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_tim_kerja` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip_nik` (`nip_nik`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_level_user` (`level_user`),
  KEY `fk_users_timkerja` (`id_tim_kerja`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `atasan_id`, `name`, `nip_nik`, `jabatan`, `email`, `password`, `level_user`, `role`, `created_at`, `id_tim_kerja`) VALUES
(1, NULL, 'Administrator', '000000', 'Administrator', 'Administrator@simola.com', '$2y$10$s5/bXR8F13yHD.NUf1dmF.JIP9k.Fro5cbI1ChmLqtt.ohEAA.Fwe', 8, 'admin', '2025-03-12 06:10:51', NULL),
(2, 11, 'Tri Yuli Nugrahanto S.Kom', '123456', 'AHLI PERTAMA - PRANATA KOMPUTER ', 'xcodein@x.com', '$2y$10$N/rjTz6Ui8jirJacpaTNt.n.f62hYL645vB4W087ccGgvcskg9lcK', 1, 'pemohon', '2025-03-04 19:54:05', NULL),
(5, 42, 'andromeda melodyc S.kom', '98999', 'AHLI PERTAMA - PRANATA KOMPUTER /', 'xcodein@1.xc', '$2y$10$ee48al3yoDwlG95JfM.jOuedBohTmYbq1TAfq6.qoKel7BuLLENkW', 1, 'operator', '2025-03-04 20:05:14', NULL),
(7, NULL, 'Namomera', '45678', 'none', 'a@gmail.com', '$2y$10$DQXaAZ.QgW8AsI6oaDP.p.PEqf/E3ExgJ1U1ndfKtwXZW43k6Kqyy', 1, 'pemohon', '2025-03-05 04:52:40', NULL),
(9, NULL, 'Operator User', '222222', 'Operator', 'operator@example.com', '$2y$10$rvPf29CaRxGovhH55GP3tOlIZG9Iyd2ewYgtdL.F4j0FxRSCZ87/W', 8, 'operator', '2025-03-05 15:33:39', NULL),
(10, NULL, 'Pengawas User', '333333', 'Pengawas', 'pengawas@example.com', '$2y$10$rvPf29CaRxGovhH55GP3tOlIZG9Iyd2ewYgtdL.F4j0FxRSCZ87/W', 5, 'pengawas', '2025-03-05 15:33:39', NULL),
(11, NULL, 'Rachel Wattimena S.St', '444444', 'Pejabat SPM', 'pejabatspm@example.com', '$2y$10$rvPf29CaRxGovhH55GP3tOlIZG9Iyd2ewYgtdL.F4j0FxRSCZ87/W', 7, 'pejabat_spm', '2025-03-05 15:33:39', NULL),
(12, NULL, 'Rahmawati Umasugi', '555555', 'Pejabat Plt.Kasubbag Umum / PPK', 'kasubbagppk@example.com', '$2y$10$rvPf29CaRxGovhH55GP3tOlIZG9Iyd2ewYgtdL.F4j0FxRSCZ87/W', 3, 'pejabat_ppk', '2025-03-05 15:33:39', NULL),
(13, NULL, 'ABUBAKAR, S.St.Pi, M.Si', '666666', 'Pejabat Kuasa Pengguna Anggaran', 'kuasapengguna@example.com', '$2y$10$rvPf29CaRxGovhH55GP3tOlIZG9Iyd2ewYgtdL.F4j0FxRSCZ87/W', 4, 'pejabat_kpa', '2025-03-05 15:33:39', NULL),
(35, 12, 'User Test 3', '2345', 'jabatan', 'usertest3@example.com', '123456', 1, 'pemohon', '2025-03-23 04:43:56', NULL),
(40, 42, 'Danendra', '21042024', 'Kasubbag Umum', 'danendra@gmail.com', '$2y$10$Pq8OQMdhu/sjEEEEWQPOoOGFeGg53HN1GQQZm4pfV.OnmmfCCcpPG', 3, 'pemohon', '2025-03-23 06:19:03', NULL),
(41, NULL, 'Dananjaya', '10122020', 'Kepala', 'dananjaya@gmail.com', '$2y$10$kZDb1SvCl.bwDqJimrSKae9pKLouhiQe/bdk13LTDsHxGsNcoiIiO', 2, 'pengawas', '2025-03-23 06:46:16', NULL),
(42, 0, 'EKADASA PRIANTARA', '888888', 'KETUA TIM KERJA PELATIHAN', 'KATIMJAPELATIHAN@SIPATRA.COM', '$2y$10$XTsNej8qUw9TwL//2RVs5OwZ9/OAaum6WOwLjMe577O/A42drVlme', 2, 'pejabat_spm', '2025-03-25 00:09:04', NULL),
(43, 42, 'CARLO', '654321', 'TIMJA - PELATIHAN', 'CARLO@SIPATRA.COM', '$2y$10$cllmXQWVj/5aG2JTvyRpreXa5MTObq9wWzuj4QKkIAVbBXQJX41Ly', 1, 'pemohon', '2025-03-25 06:11:19', NULL),
(44, 11, 'ACHMAD', '909090', 'ABK BAWAL PUTIH III', 'ACHMAD@SIPATRA.COM', '$2y$10$ODgO/HkamgjXPUJ3s75/Le2dBKP45E3OwT.l9.jTChTnlJf8y5WIG', 1, 'pemohon', '2025-03-25 06:18:30', NULL),
(45, NULL, 'Pejabat Pengawas S.ST S.Pi', '101010', 'Jabatan Pengawas', 'pengawas@sipatra.com', '$2y$10$WWW3w4r88NtVjKeznq/91e45B0RSHpSG1mkxRTk0oi1.7Kl.Rb4Qy', 5, 'pemohon', '2025-03-31 15:00:34', NULL),
(46, 12, 'ASTY MARCE TITAPASANEA, A.Md, SE', '121212', 'Jabatan Keuangan', 'keuangan@sipatra.com', '$2y$10$j01Y1co8uvnIFbkZv04TieODFUL2zfdFfIA0.rItPD/0SuCQYGpcC', 6, '', '2025-03-31 15:03:35', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `permohonan`
--
ALTER TABLE `permohonan`
  ADD CONSTRAINT `fk_permohonan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status_permohonan` FOREIGN KEY (`status2`) REFERENCES `status_permohonan` (`id_status`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_level_user` FOREIGN KEY (`level_user`) REFERENCES `level` (`id_level`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_timkerja` FOREIGN KEY (`id_tim_kerja`) REFERENCES `tim_kerja` (`id_tim_kerja`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
