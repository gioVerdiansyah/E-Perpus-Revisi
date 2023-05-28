-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2023 at 03:14 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan_sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(255) NOT NULL,
  `judul_buku` varchar(74) NOT NULL,
  `kategori` varchar(144) NOT NULL,
  `penulis` varchar(144) NOT NULL,
  `penerbit` varchar(144) NOT NULL,
  `image` varchar(64) NOT NULL,
  `tahun_terbit` date NOT NULL,
  `isbn` varchar(144) NOT NULL,
  `jumlah_halaman` int(10) NOT NULL,
  `jumlah_buku` int(2) NOT NULL,
  `sinopsis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul_buku`, `kategori`, `penulis`, `penerbit`, `image`, `tahun_terbit`, `isbn`, `jumlah_halaman`, `jumlah_buku`, `sinopsis`) VALUES
(12, 'Filosifi Teras', 'Filsafat', 'Henry Manampiring', 'Buku Kompas', '643f44932cc1d.png', '2018-11-26', '978-602-412-518-9', 346, 2, 'Filosofi Teras menjelaskan tentang filsafat Yunani-Romawi kuno yang bisa membantu Anda mengatasi emosi negatif dan menghasilkan mental yang tangguh dalam menghadapi masalah hidup. Di dalam pimtar ini Anda akan mempelajari definisi Filosofi Teras dan penerapannya dalam kehidupan sehari-hari.'),
(13, 'Sebuah Seni Untuk Bersikap Bodo Amat', 'Self Improvement', 'Mark Manson', 'PT. Gramedia Widiasarana Indonesia (Grasindo).', '643f6a8794065.jpg', '2016-09-13', '978-602-452-698-6', 246, 0, 'Sebuah Seni untuk Bersikap Bodo Amat (2018) menjelaskan kunci agar Anda menjadi lebih kuat dan lebih bahagia. Di dalam buku ini, Anda akan mendapatkan pemahaman tentang sumber kekuatan yang paling nyata, yaitu mengetahui batasan-batasan yang ada dalam diri dan menerimanya. Sehingga Anda mampu menghadapi kenyataan-kenyataan dan mulai menemukan keberanian yang selama ini Anda cari.');

-- --------------------------------------------------------

--
-- Table structure for table `loginadmin`
--

CREATE TABLE `loginadmin` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gambar` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loginadmin`
--

INSERT INTO `loginadmin` (`id`, `username`, `password`, `gambar`) VALUES
(6, 'verdi', '$2y$10$RmWyR8x7xNT5GFW8wk/OpebaY5.naqnkXHe0wx7/S.Z5N35AkWYJq', '6433ee9dcef97.jpg'),
(7, 'adi', '$2y$10$kT3TAFRjDcPgVeWIpZcu1Ov49gZLC/Uacdcqzn1Bx3qSHHvILHZ1G', '6434097854649.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `loginuser`
--

CREATE TABLE `loginuser` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `pass` varchar(144) NOT NULL,
  `gambar` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loginuser`
--

INSERT INTO `loginuser` (`id`, `username`, `pass`, `gambar`) VALUES
(28, 'verdi', '$2y$10$hiew46.aJ5BeMdoX6D3JE.E/kjF29FtU74evO20rFMvIuCS3P5iDW', '643b410968d19.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `peminjam`
--

CREATE TABLE `peminjam` (
  `id` int(11) NOT NULL,
  `pp_user` varchar(64) NOT NULL,
  `username` varchar(144) NOT NULL,
  `bukunya` varchar(144) NOT NULL,
  `kategori` varchar(144) NOT NULL,
  `jumlah_pinjam` int(2) NOT NULL,
  `tanggal_pinjam` varchar(64) NOT NULL,
  `tanggal_pengembalian` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `peminjam`
--

INSERT INTO `peminjam` (`id`, `pp_user`, `username`, `bukunya`, `kategori`, `jumlah_pinjam`, `tanggal_pinjam`, `tanggal_pengembalian`, `status`) VALUES
(157, '643b410968d19.jpg', 'verdi', 'Filosifi Teras', 'Filsafat', 2, '13:26 28/05/2023', '2023-06-01', 0),
(158, '643b410968d19.jpg', 'verdi', 'Sebuah Seni Untuk Bersikap Bodo Amat', 'Self Improvement', 2, '13:27 28/05/2023', '2023-06-05', 1),
(159, '643b410968d19.jpg', 'verdi', 'Filosifi Teras', 'Filsafat', 1, '13:28 28/05/2023', '2023-06-01', 1),
(160, '643b410968d19.jpg', 'verdi', 'Filosifi Teras', 'Filsafat', 1, '18:54 28/05/2023', '2023-05-30', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loginadmin`
--
ALTER TABLE `loginadmin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loginuser`
--
ALTER TABLE `loginuser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjam`
--
ALTER TABLE `peminjam`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `loginadmin`
--
ALTER TABLE `loginadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `loginuser`
--
ALTER TABLE `loginuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `peminjam`
--
ALTER TABLE `peminjam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
