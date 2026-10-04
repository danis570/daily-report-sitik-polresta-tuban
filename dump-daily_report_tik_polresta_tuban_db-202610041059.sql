-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: daily_report_tik_polresta_tuban_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_profiles_users` (`user_id`),
  CONSTRAINT `fk_profiles_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (7,'Danish','default-avatar.png',33);
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_items`
--

DROP TABLE IF EXISTS `report_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(10) unsigned NOT NULL,
  `item_no` int(10) unsigned NOT NULL,
  `target_option_id` int(10) unsigned NOT NULL,
  `activity_option_id` int(10) unsigned NOT NULL,
  `personnel_strength_option_id` int(10) unsigned NOT NULL,
  `location_option_id` int(10) unsigned NOT NULL,
  `person_in_charge_option_id` int(10) unsigned NOT NULL,
  `expected_result_option_id` int(10) unsigned NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_report_items_no` (`report_id`,`item_no`),
  KEY `idx_report_items_target` (`target_option_id`),
  KEY `idx_report_items_activity` (`activity_option_id`),
  KEY `idx_report_items_personnel_strength` (`personnel_strength_option_id`),
  KEY `idx_report_items_location` (`location_option_id`),
  KEY `idx_report_items_person_in_charge` (`person_in_charge_option_id`),
  KEY `idx_report_items_expected_result` (`expected_result_option_id`),
  CONSTRAINT `fk_report_items_activity` FOREIGN KEY (`activity_option_id`) REFERENCES `report_options` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_report_items_expected_result` FOREIGN KEY (`expected_result_option_id`) REFERENCES `report_options` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_report_items_location` FOREIGN KEY (`location_option_id`) REFERENCES `report_options` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_report_items_person_in_charge` FOREIGN KEY (`person_in_charge_option_id`) REFERENCES `report_options` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_report_items_personnel_strength` FOREIGN KEY (`personnel_strength_option_id`) REFERENCES `report_options` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_report_items_report` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_report_items_target` FOREIGN KEY (`target_option_id`) REFERENCES `report_options` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=529 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_items`
--

LOCK TABLES `report_items` WRITE;
/*!40000 ALTER TABLE `report_items` DISABLE KEYS */;
INSERT INTO `report_items` VALUES (528,62,1,41,64,67,87,98,118,'','2026-10-04 03:31:26','2026-10-04 03:31:26',NULL);
/*!40000 ALTER TABLE `report_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_options`
--

DROP TABLE IF EXISTS `report_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(150) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_report_options_category_name` (`category`,`name`),
  KEY `idx_report_options_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_options`
--

LOCK TABLES `report_options` WRITE;
/*!40000 ALTER TABLE `report_options` DISABLE KEYS */;
INSERT INTO `report_options` VALUES (40,'target','Apel Pagi','','2026-10-01 14:01:38','2026-10-04 03:05:10',NULL),(41,'target','Apel Fungsi TIK',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(42,'target','Apel Pimpinan',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(43,'target','Apel Pagi dan Olah Raga','','2026-10-01 14:01:38','2026-10-04 03:05:30',NULL),(45,'target','Vidcon',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(46,'target','Zoom Meeting',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(47,'target','CCTV',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(48,'target','Surat / Rengiat',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(49,'target','Supervisi Polsek Bancar,Jatirogo,Kenduruan dan Bangilan',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(50,'activity','Pengarahan PJU Polres Tuban mingguan polsek jajaran',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(51,'activity','Pengarahan Kasi Tik',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(52,'activity','Pengarahan Kabag Ops Polres tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(53,'activity','Pengarahan Bapak Waka Polres tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(54,'activity','Pengarahan Kasat SAMAPTA dan dilanjut pengajian kamis pagi',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(55,'activity','Pengecekan perangkat yang akan digunakan Vidcon dan jaringan VPN',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(56,'activity','Pengecekan jaringan dan perangkat Laptop dan dilanjutkan uji coba perangkat',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(57,'activity','Pengecekan jaringan dan perangkat Laptop',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(58,'activity','Pengecekan jaringan dilanjutkan Pengetesan perangkat alat vidcon',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(59,'activity','Pengecekan perangkat dan jaringan yang akan digunakan Vidcon dan jaringan VPN',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(60,'activity','Pengecekan Jaringan ,Monitor dan camera',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(61,'activity','Membuat rengiat Dan hasil giat, Membuat jadwal absensi harian anggota TIK',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(62,'activity','Pengecekan akom ,jaringan internet dan cctv dan pencocokan data pemegang Alkom',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(63,'activity','Pemasangan dan Pengecekan perangkat Zoom meeting dan jaringan Internet',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(64,'activity','Apel pagi dilanjutkan lari keliling lapangan dan senam','','2026-10-01 14:01:38','2026-10-01 14:23:40',NULL),(66,'personnel_strength','4 anggota TIK',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(67,'personnel_strength','4 anggota Sitipol',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(68,'personnel_strength','BRIPDA LUKY A.P',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(69,'personnel_strength','BRIPDA ANGGRY P.W',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(70,'personnel_strength','PENGDA I EDY .S',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(71,'personnel_strength','PENGDA TK I EDY .S',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(72,'personnel_strength','PENGATUR I SUMIANTO',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(73,'personnel_strength','PENGATUR TK I SUMIANTO',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(74,'personnel_strength','AIPTU INDRA.S',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(75,'personnel_strength','AIPTU INDRA',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(76,'personnel_strength','BRIPTU LUKY',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(79,'location','Lapangan Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(80,'location','Gedung serba guna Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(81,'location','Di ruang PDDO Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(82,'location','Di ruang Reskrim Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(83,'location','Di ruang Sumda Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(84,'location','Di ruang Tahti Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(85,'location','Di samping samping Masjid Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(86,'location','Di samping Gedung Humas Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(87,'location','Di depan pintu masuk Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(88,'location','Ruang TIK Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(89,'location','Di ruang Serba guna Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(90,'location','Di Gedung Serba guna',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(91,'location','Gedung SerbagunaPolres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(92,'location','Di SMPN 2 Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(93,'location','Polsek Bancar, Jatirogo,Kenduruan dan Bangilan',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(94,'location','Ds.Andong Kec. Grabagan Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(95,'location','Ds.Mliwang Kec. Kerek Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(96,'location','Mapolres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(97,'person_in_charge','Ps.KASI TIK',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(98,'person_in_charge','KASI TIK',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(99,'expected_result','Terlaksanakannya apel pagi dan giat anev mingguan polsek jajaran polres tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(100,'expected_result','Terlaksanakannya pengecekan jaringan dan alat vidco',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(101,'expected_result','Terlaksanakannya pengecekan Jaringan Internet dan alat Zoom meeting',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(102,'expected_result','Terlaksanakannya pengecekan CCTV',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(103,'expected_result','Terlaksananya membuat surat, hasil giat dan absensi',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(104,'expected_result','Terlaksanakannya BIN TIK',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(105,'expected_result','Terlaksanakannya pengecekan Jaringan VPN dan Alat vidcon',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(106,'expected_result','Terlaksanakannya pengecekan Jaringan dan perangkat CCTV',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(107,'expected_result','Terlaksanakannya apel pagi berjalan dengan lancar',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(108,'expected_result','Terlaksanakannya pengecekan Perangkat dan jaringan internet',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(109,'expected_result','Terlaksanakannya pengecekan dengan hasil maksimal',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(110,'expected_result','Terlaksanakannya pengecekan Jaringan dan Camera berfungsi dengan baik dan terpantau di monitor bagus',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(111,'expected_result','Terlaksanakannya apel pagi dan pengajian rutin kamis pagi',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(112,'expected_result','Terlaksanakannya pengecekan berjalan aman dan lancar',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(113,'expected_result','Terlaksanakannya pengecekan jaringan dan perangkat zoom meeting',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(114,'expected_result','Terlaksanakannya pengecekan CCTV berjalan aman dan lancar',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(115,'expected_result','Terlaksanakannya apel pagi dan olah raga',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(116,'expected_result','Terlaksanakan pengecekan Jaringan dan alat CCTV',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(117,'expected_result','Terlaksanakannya apel pagi dan anev mingguan polsek jajaran Polres Tuban',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(118,'expected_result','Data alkom sesuai pemegangnya dan lancarnya alat komunikasi',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(119,'expected_result','Kerja sama anggota Tik agar tetap kompak',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(120,'expected_result','Jaringan VPN baik Alat vidcon bagus,bisa di gunakan',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(121,'expected_result','Jaringan Internet lancar ,vidio dan Audio bagus bisa di gunakan',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(122,'expected_result','Jaringan lancar Camera berfungsi dengan baik dan terpantau di monitor bagus',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(123,'expected_result','Terlaksanakannya pengecekan Jaringan lancar dan alat terawat dengan baik Vidcon lancar',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(124,'expected_result','Terlaksanakannya pengecekan Jaringan dan alat Zoom meeting',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(125,'expected_result','Terlaksananya pembuatan Surat/Sprin/Ren giat',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(126,'expected_result','Terlaksanakannya Supervisi dan data Alkom sesuai dengan pemegangnya alkom terawat dengan baik',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(127,'expected_result','Terlaksanakannnya pemasangan jaringan Internet dan alat zoom meeting',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(128,'expected_result','Jaringan lancar alat terawat dengan baik',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(129,'expected_result','Pengecekan berjalan aman dan lancar',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(130,'expected_result','Jaringan lancar dan alat terawat dengan baik Vidcon lancar',NULL,'2026-10-01 14:01:38','2026-10-01 14:01:38',NULL),(139,'target','dasdasdasd',NULL,'2026-10-04 03:18:05','2026-10-04 03:18:05',NULL),(141,'target','sarapan',NULL,'2026-10-04 03:21:08','2026-10-04 03:21:08',NULL),(142,'target','Jalan pagi',NULL,'2026-10-04 03:22:11','2026-10-04 03:22:11',NULL),(143,'target','kuda kuda',NULL,'2026-10-04 03:24:00','2026-10-04 03:24:00',NULL);
/*!40000 ALTER TABLE `report_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_date` date NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_reports_date` (`report_date`),
  KEY `fk_reports_created_by` (`created_by`),
  CONSTRAINT `fk_reports_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (62,'2026-10-04',33,'2026-10-04 03:31:10','2026-10-04 03:31:10',NULL);
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sessions_users` (`user_id`),
  CONSTRAINT `fk_sessions_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@gmail.com','$2y$12$qutr/HOHpb6OS32xLSrqT.Yqs4rR7Ue66DNYhWHC5GzyEh7Vyg7nG','admin','2026-09-30 06:12:05','2026-09-30 06:15:06',NULL),(33,'ahmad56danish@gmail.com','$2y$12$tvp/w4p7uIGHut31Pq/L5uMBlPT/3XZExTKvXhuKb96ZLDo6caj6W','user','2026-10-03 11:41:53','2026-10-03 11:41:53',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'daily_report_tik_polresta_tuban_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-04 10:59:19
