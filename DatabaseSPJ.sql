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
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `kwitansis` */

DROP TABLE IF EXISTS `kwitansis`;

CREATE TABLE `kwitansis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spj_id` bigint unsigned NOT NULL,
  `no_rekening` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_rekening_tujuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penerima_kwitansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_kegiatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telah_diterima_dari` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_nominal` bigint NOT NULL,
  `uang_terbilang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_penerima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `npwp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pembayaran` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_pptk` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_spjs_idss` (`spj_id`),
  KEY `FK_PPTK` (`id_pptk`),
  CONSTRAINT `FK_PPTK` FOREIGN KEY (`id_pptk`) REFERENCES `pptk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_spjs_idss` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kwitansis` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `NIP` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `password_reset_tokens_nip_unique` (`NIP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `pemeriksaans` */

DROP TABLE IF EXISTS `pemeriksaans`;

CREATE TABLE `pemeriksaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spj_id` bigint unsigned DEFAULT NULL,
  `pesanan_id` bigint unsigned DEFAULT NULL,
  `tanggals_diterima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hari_diterima` varchar(255) NOT NULL,
  `bulan_diterima` varchar(255) NOT NULL,
  `tahun_diterima` varchar(255) NOT NULL,
  `nama_pihak_kedua` varchar(255) NOT NULL,
  `jabatan_pihak_kedua` varchar(255) NOT NULL,
  `alamat_pihak_kedua` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_plt` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_spj_ids` (`spj_id`),
  KEY `FK_pesanan_id` (`pesanan_id`),
  KEY `FK_Plt` (`id_plt`),
  CONSTRAINT `FK_pesanan_id` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_Plt` FOREIGN KEY (`id_plt`) REFERENCES `plt` (`id`),
  CONSTRAINT `FK_spj_ids` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pemeriksaans` */

/*Table structure for table `penerimaan_details` */

DROP TABLE IF EXISTS `penerimaan_details`;

CREATE TABLE `penerimaan_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `penerimaan_id` bigint unsigned DEFAULT NULL,
  `pesanan_item_id` bigint unsigned DEFAULT NULL,
  `satuan` varchar(255) NOT NULL,
  `harga_satuan` int NOT NULL,
  `total` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penerimaan_details_penerimaan_id_foreign` (`penerimaan_id`),
  KEY `penerimaan_details_pesanan_item_id_foreign` (`pesanan_item_id`),
  CONSTRAINT `penerimaan_details_penerimaan_id_foreign` FOREIGN KEY (`penerimaan_id`) REFERENCES `penerimaans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penerimaan_details_pesanan_item_id_foreign` FOREIGN KEY (`pesanan_item_id`) REFERENCES `pesanan_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `penerimaan_details` */

/*Table structure for table `penerimaans` */

DROP TABLE IF EXISTS `penerimaans`;

CREATE TABLE `penerimaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pemeriksaan_id` bigint unsigned DEFAULT NULL,
  `pesanan_id` bigint unsigned DEFAULT NULL,
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
  CONSTRAINT `FK_pemeriksaan_id` FOREIGN KEY (`pemeriksaan_id`) REFERENCES `pemeriksaans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_pesanan_ids` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_spjs_id` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `penerimaans` */

/*Table structure for table `pesanan_items` */

DROP TABLE IF EXISTS `pesanan_items`;

CREATE TABLE `pesanan_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint unsigned DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pesanan_parent` (`pesanan_id`),
  CONSTRAINT `FK_pesanan_parent` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pesanan_items` */

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
  `nomor_tlp_pt` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_spj_id` (`spj_id`),
  CONSTRAINT `FK_spj_id` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pesanans` */

/*Table structure for table `plt` */

DROP TABLE IF EXISTS `plt`;

CREATE TABLE `plt` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_pihak_pertama` varchar(255) NOT NULL,
  `jabatan_pihak_pertama` varchar(255) NOT NULL,
  `nip_pihak_pertama` varchar(255) NOT NULL,
  `gol_pihak_pertama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `plt` */

insert  into `plt`(`id`,`nama_pihak_pertama`,`jabatan_pihak_pertama`,`nip_pihak_pertama`,`gol_pihak_pertama`,`created_at`,`updated_at`) values 
(1,'Drs. I Wayan Arsana, MAP','Plt. Kepala Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Gianyar','19660127 199402 1 001','Pembina Utama Muda (IV/c)','2025-10-20 03:08:56','2025-10-24 03:46:23');

/*Table structure for table `pptk` */

DROP TABLE IF EXISTS `pptk`;

CREATE TABLE `pptk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subkegiatan` varchar(255) NOT NULL,
  `nama_pptk` varchar(255) NOT NULL,
  `jabatan_pptk` varchar(255) NOT NULL,
  `nip_pptk` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pptk` */

insert  into `pptk`(`id`,`subkegiatan`,`nama_pptk`,`jabatan_pptk`,`nip_pptk`,`created_at`,`updated_at`) values 
(1,'Sub Kegiatan A','I Made Agus','Pengatur (II/c)','19801010 200502 1 001',NULL,NULL),
(2,'Sub Kegiatan B','Ni Luh Ayu','Penata (III/a)','19851212 200902 2 002',NULL,NULL),
(101,'Sub Kegiatan Teknologi Dan Pengetahuan Dalam Bidang Laravel Dan php serta menggunakan flutter untuk membuat aplikasi mobile programing sangat keren sekali dan ganteng','I Gede Wahyu Aditya','Pengatur (II/c)','2301010564',NULL,'2025-10-15 06:21:18'),
(103,'Sub Kegiatan Penyediaan Jasa Pemeliaraan, Biaya, Pemeliharaan, Pajak dan Perizinan Kendaraan Dinas Operasional atau Lapangan','Ni Wayan Sriyani, S.Sn., M.Si','Pembina (IV/a)','19750909 200003 2 004','2025-10-15 05:52:13','2025-10-15 05:52:13');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `NIP` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_tlp` int NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sessions_nip_unique` (`NIP`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`key`,`value`,`created_at`,`updated_at`) values 
(1,'ppn_rate','10',NULL,'2025-10-15 05:13:07');

/*Table structure for table `spj_feedbacks` */

DROP TABLE IF EXISTS `spj_feedbacks`;

CREATE TABLE `spj_feedbacks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spj_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `field_name` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `role` enum('bendahara','kasubag','admin','superadmin') DEFAULT 'bendahara',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_spj_feedback_spj` (`spj_id`),
  KEY `FK_user_id2` (`user_id`),
  CONSTRAINT `fk_spj_feedback_spj` FOREIGN KEY (`spj_id`) REFERENCES `spjs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_user_id2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `spj_feedbacks` */

/*Table structure for table `spjs` */

DROP TABLE IF EXISTS `spjs`;

CREATE TABLE `spjs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','diajukan','valid','belum_valid') NOT NULL DEFAULT 'draft',
  `user_id` bigint unsigned DEFAULT NULL,
  `status2` enum('draft','diajukan','valid','belum_valid') NOT NULL DEFAULT 'draft',
  `komentar_kasubag` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `komentar_bendahara` text,
  PRIMARY KEY (`id`),
  KEY `FK_user_id` (`user_id`),
  CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `spjs` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `NIP` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `nomor_tlp` int NOT NULL,
  `role` enum('Kasubag','Bendahara','user') NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NIP` (`NIP`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`nama`,`NIP`,`password`,`jabatan`,`Alamat`,`nomor_tlp`,`role`,`remember_token`,`created_at`,`updated_at`) values 
(6,'Ari Nindya','1002','$2y$12$vtpCILEUNfi6uw1L6q88bOJUv.Qqb0v0EvjTJzJR8F22rVFuyJwVK','Staff','Jl. Mawar No.3',812777,'Bendahara',NULL,'2025-10-24 02:14:00','2025-10-24 02:14:00'),
(7,'Budhi','1003','$2y$12$12dv975Pl87In0UILcNzR.iC28vqNI1TDYzd2GOdzsq852OtSZkve','Staff','Jl. Mawar No.3',812777,'Kasubag',NULL,'2025-10-24 02:14:01','2025-10-24 02:14:01'),
(8,'Ade','1004','$2y$12$gGvnrAvL3GmbhTrcI.QFXuU94kd.vI3CF.cNuCbYh6C0vTjBUFuPi','Bos Besar','Br. Pengembungan',812777,'user',NULL,'2025-10-24 02:14:01','2025-10-24 03:24:25'),
(9,'I Gede Wahyu Aditya','1006','$2y$12$4uJa21VE6fPYWq/kn2Zk0u4jIorgpR3EMhcEUIeyDtihe0Bmwbvma','Staff IT','Jln.Anggrek Mekar Pontianak',81351568,'Kasubag',NULL,'2025-10-24 02:17:26','2025-10-24 02:17:26'),
(10,'Alex','1007','$2y$12$0z5q.DohV4RCYmiCE7ZgpeHqAYngskQ8nZpBrW1HCgfHmS.maPcdG','Bos Besar 2','Batubulan',811234,'user',NULL,'2025-10-24 03:28:14','2025-10-24 03:28:14');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
