-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for perpustakaan_sekolah
CREATE DATABASE IF NOT EXISTS `perpustakaan_sekolah` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `perpustakaan_sekolah`;

-- Dumping structure for table perpustakaan_sekolah.buku
CREATE TABLE IF NOT EXISTS `buku` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `judul_buku` varchar(74) NOT NULL,
  `kode_buku` int NOT NULL,
  `kategori` varchar(144) NOT NULL,
  `penulis` varchar(144) NOT NULL,
  `penerbit` varchar(144) NOT NULL,
  `image` varchar(64) NOT NULL,
  `tahun_terbit` date NOT NULL,
  `isbn` varchar(144) NOT NULL,
  `jumlah_halaman` int NOT NULL,
  `jumlah_buku` int NOT NULL,
  `sinopsis` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_sekolah.buku: ~2 rows (approximately)
DELETE FROM `buku`;
INSERT INTO `buku` (`id`, `judul_buku`, `kode_buku`, `kategori`, `penulis`, `penerbit`, `image`, `tahun_terbit`, `isbn`, `jumlah_halaman`, `jumlah_buku`, `sinopsis`) VALUES
	(56, 'Filosifi teras', 1234567654, 'Filsafat', 'Verdi', 'Adi', '6577024434ef0.png', '2023-12-12', '234-2345-2345-43345', 231, 8, 'qwertyuiop\r\nasdghjkl;\r\nzxcvbn'),
	(57, 'Sebuah Buku Untuk Bersikap Bodoamat', 1234567657, 'Pengembangan Diri', 'Verdi', 'Adi', '6577024434ef1.jpg', '2023-12-12', '234-2345-2345-43346', 231, 8, 'qwertyuiop\r\nasdghjkl;\r\nzxcvbn');

-- Dumping structure for table perpustakaan_sekolah.data_user
CREATE TABLE IF NOT EXISTS `data_user` (
  `user_id` int unsigned NOT NULL,
  `gambar` varchar(64) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_bergabung` varchar(64) NOT NULL,
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `data_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `loginuser` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_sekolah.data_user: ~0 rows (approximately)
DELETE FROM `data_user`;
INSERT INTO `data_user` (`user_id`, `gambar`, `deskripsi`, `tanggal_bergabung`) VALUES
	(64, '65770ebbce6f7.jpg', 'qwertyuiop', '13:10 11/12/2023');

-- Dumping structure for table perpustakaan_sekolah.loginadmin
CREATE TABLE IF NOT EXISTS `loginadmin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gambar` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_sekolah.loginadmin: ~0 rows (approximately)
DELETE FROM `loginadmin`;
INSERT INTO `loginadmin` (`id`, `username`, `password`, `gambar`) VALUES
	(9, 'verdi', '$2y$10$eNpNnWwfCLLVo2kFeoVHtuAdNgf5bIXcrMu7CP0HfMKxaY8G2NJs2', 'default.jpg');

-- Dumping structure for table perpustakaan_sekolah.loginuser
CREATE TABLE IF NOT EXISTS `loginuser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(144) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_sekolah.loginuser: ~1 rows (approximately)
DELETE FROM `loginuser`;
INSERT INTO `loginuser` (`id`, `username`, `email`, `pass`) VALUES
	(64, 'verdiansyah', 'e01010010or@gmail.com', '$2y$10$DdDl5f2HziaBKvOQdMHVO.yLF99dDDk4doa9YqhuR07F14d.e82fa');

-- Dumping structure for table perpustakaan_sekolah.peminjam
CREATE TABLE IF NOT EXISTS `peminjam` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `buku_id` int unsigned NOT NULL,
  `jumlah_pinjam` int NOT NULL,
  `tanggal_pinjam` varchar(64) NOT NULL,
  `tanggal_pengembalian` varchar(64) NOT NULL,
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `alasan` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `buku_id` (`buku_id`) USING BTREE,
  KEY `user_id_2` (`user_id`,`buku_id`),
  KEY `buku_id_2` (`buku_id`),
  CONSTRAINT `peminjam_ibfk_1` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE,
  CONSTRAINT `peminjam_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `loginuser` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_sekolah.peminjam: ~0 rows (approximately)
DELETE FROM `peminjam`;
INSERT INTO `peminjam` (`id`, `user_id`, `buku_id`, `jumlah_pinjam`, `tanggal_pinjam`, `tanggal_pengembalian`, `status`, `alasan`) VALUES
	(210, 64, 56, 2, '20:15 11/12/2023', '2023-12-12', '0', 'Tidak ada alasan');

-- Dumping structure for table perpustakaan_sekolah.ulasan
CREATE TABLE IF NOT EXISTS `ulasan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `isi_ulasan` varchar(255) DEFAULT 'Belum ada ulasan',
  `rating` int NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `buku_id` int unsigned NOT NULL,
  `tanggal_komentar` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `buku_id` (`buku_id`) USING BTREE,
  CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `loginuser` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table perpustakaan_sekolah.ulasan: ~0 rows (approximately)
DELETE FROM `ulasan`;
INSERT INTO `ulasan` (`id`, `isi_ulasan`, `rating`, `user_id`, `buku_id`, `tanggal_komentar`) VALUES
	(34, 'jsdvjshvdas', 2, 64, 56, '20:13 11/12/2023');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
