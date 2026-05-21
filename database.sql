-- phpMyAdmin SQL Dump
-- Database: `data_profile`
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Database: `data_profile`
--

CREATE DATABASE IF NOT EXISTS `data_profile` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `data_profile`;

-- --------------------------------------------------------

--
-- Table structure for table `biodata`
--

CREATE TABLE `biodata` (
  `id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `nama`   VARCHAR(100) NOT NULL,
  `email`  VARCHAR(100) NOT NULL,
  `alamat` TEXT         NOT NULL,
  `gambar` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `biodata`
--

INSERT INTO `biodata` (`nama`, `email`, `alamat`, `gambar`) VALUES
('Budi Santoso',   'budi@example.com',   'Jl. Merdeka No. 1, Jakarta',   NULL),
('Siti Rahayu',    'siti@example.com',    'Jl. Sudirman No. 5, Bandung',  NULL),
('Andi Wijaya',    'andi@example.com',    'Jl. Diponegoro No. 10, Surabaya', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `email`    VARCHAR(100) NOT NULL,
  `Password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--
-- Default credentials: admin@admin.com / admin123
--

INSERT INTO `login` (`email`, `Password`) VALUES
('admin@admin.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `nama_brg` VARCHAR(100) NOT NULL,
  `harga`    INT(11)      NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`nama_brg`, `harga`) VALUES
('Beras 5kg',       65000),
('Minyak Goreng 1L', 18000),
('Gula Pasir 1kg',  14000),
('Tepung Terigu 1kg', 12000),
('Telur 1kg',       28000),
('Sabun Mandi',      5000),
('Shampo Sachet',    2000),
('Kopi Sachet',      2500),
('Teh Celup',        8000),
('Mie Instan',       3500);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_jual`   INT(11)      NOT NULL AUTO_INCREMENT,
  `no_struk`  INT(11)      NOT NULL,
  `id_brg`    INT(11)      NOT NULL,
  `tanggal`   DATETIME     NOT NULL,
  `nama_brg`  VARCHAR(100) NOT NULL,
  `qty_jual`  INT(11)      NOT NULL,
  `harga_brg` INT(11)      NOT NULL,
  `total`     INT(11)      NOT NULL,
  PRIMARY KEY (`id_jual`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table `penjualan` starts empty; data is populated via the application.
--

COMMIT;
