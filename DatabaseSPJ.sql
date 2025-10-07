/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 8.4.3 : Database - spj-pmd
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`spj-pmd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `spj-pmd`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `kwitansis` */

DROP TABLE IF EXISTS `kwitansis`;

CREATE TABLE `kwitansis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spj_id` bigint unsigned NOT NULL,
  `no_rekening` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_rekening_tujuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bank` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penerima_kwitansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telah_diterima_dari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_nominal` bigint NOT NULL,
  `uang_terbilang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_penerima` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `npwp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pembayaran` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_spjs_idss` (`spj_id`),
  CONSTRAINT `FK_spjs_idss` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NIP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `password_reset_tokens_nip_unique` (`NIP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `pemeriksaans` */

DROP TABLE IF EXISTS `pemeriksaans`;

CREATE TABLE `pemeriksaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spj_id` bigint unsigned DEFAULT NULL,
  `pesanan_id` bigint unsigned DEFAULT NULL,
  `tanggal_diterima` varchar(255) NOT NULL,
  `hari_diterima` varchar(255) NOT NULL,
  `bulan_diterima` varchar(255) NOT NULL,
  `tahun_diterima` varchar(255) NOT NULL,
  `nama_pihak_kedua` varchar(255) NOT NULL,
  `jabatan_pihak_kedua` varchar(255) NOT NULL,
  `alamat_pihak_kedua` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_spj_ids` (`spj_id`),
  KEY `FK_pesanan_id` (`pesanan_id`),
  CONSTRAINT `FK_pesanan_id` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`),
  CONSTRAINT `FK_spj_ids` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `penerimaan_details` */

DROP TABLE IF EXISTS `penerimaan_details`;

CREATE TABLE `penerimaan_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `penerimaan_id` bigint unsigned DEFAULT NULL,
  `pesanan_item_id` bigint unsigned DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlah` int NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `harga_satuan` int NOT NULL,
  `total` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `penerimaans` */

DROP TABLE IF EXISTS `penerimaans`;

CREATE TABLE `penerimaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pemeriksaan_id` bigint unsigned DEFAULT NULL,
  `pesanan_id` bigint unsigned DEFAULT NULL,
  `pesanan_item_id` bigint unsigned DEFAULT NULL,
  `spj_id` bigint unsigned DEFAULT NULL,
  `surat_dibuat` date DEFAULT NULL,
  `no_surat` varchar(255) NOT NULL,
  `nama_pihak_kedua` varchar(255) NOT NULL,
  `jabatan_pihak_kedua` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `subtotal` int NOT NULL,
  `ppn` int NOT NULL,
  `grandtotal` int NOT NULL,
  `dibulatkan` int NOT NULL,
  `terbilang` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_spjs_id` (`spj_id`),
  KEY `FK_pesanan_ids` (`pesanan_id`),
  KEY `FK_pemeriksaan_id` (`pemeriksaan_id`),
  KEY `FK_pesanan_item_id` (`pesanan_item_id`),
  CONSTRAINT `FK_pemeriksaan_id` FOREIGN KEY (`pemeriksaan_id`) REFERENCES `pemeriksaans` (`id`),
  CONSTRAINT `FK_pesanan_ids` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`),
  CONSTRAINT `FK_pesanan_item_id` FOREIGN KEY (`pesanan_item_id`) REFERENCES `pesanan_items` (`id`),
  CONSTRAINT `FK_spjs_id` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `pesanan_items` */

DROP TABLE IF EXISTS `pesanan_items`;

CREATE TABLE `pesanan_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint unsigned DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `pesanans` */

DROP TABLE IF EXISTS `pesanans`;

CREATE TABLE `pesanans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spj_id` bigint unsigned DEFAULT NULL,
  `no_surat` varchar(255) NOT NULL,
  `nama_pt` varchar(255) NOT NULL,
  `alamat_pt` varchar(255) NOT NULL,
  `tanggal_diterima` date DEFAULT NULL,
  `surat_dibuat` date DEFAULT NULL,
  `nomor_tlp_pt` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_spj_id` (`spj_id`),
  CONSTRAINT `FK_spj_id` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NIP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_tlp` int NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sessions_nip_unique` (`NIP`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `spjs` */

DROP TABLE IF EXISTS `spjs`;

CREATE TABLE `spjs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `penerimaan_id` bigint unsigned DEFAULT NULL,
  `pesanan_id` bigint unsigned DEFAULT NULL,
  `pemeriksaan_id` bigint unsigned DEFAULT NULL,
  `kwitansi_id` bigint unsigned DEFAULT NULL,
  `nomor_spj` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `surat_dibuat` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','tidak disetujui','divalidasi bendahara','belum di validasi kasubag') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'draft',
  PRIMARY KEY (`id`),
  KEY `FK_penerimaan_ids` (`penerimaan_id`),
  KEY `FK_pesanan_idsss` (`pesanan_id`),
  KEY `FK_pemeriksaan_ids` (`pemeriksaan_id`),
  KEY `FK_kwitansi_ids` (`kwitansi_id`),
  CONSTRAINT `FK_kwitansi_ids` FOREIGN KEY (`kwitansi_id`) REFERENCES `kwitansis` (`id`),
  CONSTRAINT `FK_pemeriksaan_ids` FOREIGN KEY (`pemeriksaan_id`) REFERENCES `pemeriksaans` (`id`),
  CONSTRAINT `FK_penerimaan_ids` FOREIGN KEY (`penerimaan_id`) REFERENCES `penerimaans` (`id`),
  CONSTRAINT `FK_pesanan_idsss` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `NIP` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_tlp` int NOT NULL,
  `role` enum('superadmin','admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_nip_unique` (`NIP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
