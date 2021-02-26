-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.29 - MySQL Community Server (GPL)
-- Server OS:                    Linux
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for cartrack-db
CREATE DATABASE IF NOT EXISTS `cartrack-db` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `cartrack-db`;

-- Dumping structure for table cartrack-db.refresh_token
CREATE TABLE IF NOT EXISTS `refresh_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT '0',
  `hash` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cartrack-db.refresh_token: ~0 rows (approximately)
/*!40000 ALTER TABLE `refresh_token` DISABLE KEYS */;
REPLACE INTO `refresh_token` (`id`, `uid`, `hash`, `created_at`) VALUES
	(1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODA4MVwvdXNlclwvdXBkYXRlXC97aWR9IiwiaWF0IjoxNjE0MzQxNDM1LCJleHAiOjE2MTQzNDUwMzUsImRhdGEiOnsiaWQiOiIxIiwibmFtZSI6Ik1hcmsgQW50aG9ueSBOYWx1eiIsInVzZXJuYW1lIjoiY2FydHJhY2siLCJwYXNzd29yZCI6IiQyeSQxMiQ2WnhpOFRjUmRaNE5WbHR3eEpxR0FPLmxEUnVhdTR1MHBHbGlPTUY4MkQ2Ym1FNHVXaWE1NiIsImxhc3RfYWN0aXZpdHkiOm51bGwsInVzZXJfc3RhdHVzIjoiMCIsImNyZWF0ZWRfYXQiOiIyMDIxLTAyLTI2IDA2OjE0OjM0Ljg3OCIsImRlbGV0ZWRfYXQiOm51bGwsInVwZGF0ZWRfYXQiOiIyMDIxLTAyLTI2IDEyOjEwOjM1LjQwNSJ9fQ.f9HTIR1uJR9D-QET7qg203bTZA20tWYfR_Wqztlrs7c', '2021-02-26 12:10:35.509');
/*!40000 ALTER TABLE `refresh_token` ENABLE KEYS */;

-- Dumping structure for event cartrack-db.remove_one_hour_old
DELIMITER //
CREATE EVENT `remove_one_hour_old` ON SCHEDULE EVERY 15 MINUTE STARTS '2021-02-26 17:51:27' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
	DELETE FROM refresh_token WHERE created_at <= DATE_SUB(NOW(), INTERVAL 1 HOUR);
END//
DELIMITER ;

-- Dumping structure for table cartrack-db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity` datetime(3) DEFAULT NULL,
  `user_status` tinyint(2) DEFAULT '0' COMMENT '1 = is active',
  `created_at` datetime(3) DEFAULT CURRENT_TIMESTAMP(3),
  `deleted_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table cartrack-db.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `username`, `password`, `last_activity`, `user_status`, `created_at`, `deleted_at`, `updated_at`) VALUES
	(1, 'Mark Anthony Naluz', 'cartrack', '$2y$12$OBkAxMUbzkR.VqhjdAnxpeAzl.K4bcKWZAfLDXQcs5rn4eNZtXcOC', NULL, 0, '2021-02-26 12:15:42.151', NULL, NULL),
	(2, 'Juan Dela Cruz', 'cartracks', '$2y$12$XsylbCtPU0Amzo.4fclQdOSA6RjD9IRlRSG.AQQABehZe/.HLci06', NULL, 0, '2021-02-26 13:06:12.108', NULL, NULL),
	(3, 'Juan Dela Cruzs', 'cartracks', '$2y$12$VJOLNjynLqdb23CSP0FKIu1PwAs.e0QIvO/UZKCNtc.7EcCoVzQ3G', NULL, 0, '2021-02-26 13:09:29.133', NULL, NULL),
	(4, 'Juan Dela Cruzs', 'cartracks', '$2y$12$UGtJ0JB03Lm05B/pHhPaU.IeqUqbhjEsi4NctMpn8/3biyhFAJeEu', NULL, 0, '2021-02-26 13:09:56.184', NULL, NULL),
	(5, 'Juan Dela Cruzs', 'cartracks', '$2y$12$C9SwHlidZuBB0kWoHXEDyOJ25Vs4gJmXraa/J0r2Sqe3IU5GL1Rby', NULL, 0, '2021-02-26 13:10:37.858', NULL, NULL),
	(6, 'Juan Dela Cruzs', 'cartracks', '$2y$12$nbXhM7Qz6nDrCfuf/J0jx.xwMCwPnMTX1z5EpaMVXqhq19QZA1NRq', NULL, 0, '2021-02-26 13:11:09.058', NULL, NULL),
	(7, 'Juan Dela Cruzs', 'cartracks', '$2y$12$CKYQsx7HP9EYwXkMgv/oq.u1aGFVSndgm5UsiPfG1JzEZiJu7tZ26', NULL, 0, '2021-02-26 13:14:22.818', NULL, NULL),
	(8, 'Juan Dela Cruzs', 'cartracks', '$2y$12$l.EAWqiKPOxYvZ9R5H3flu7K6Hlpyaz6FYnjPZNTL6vtaVexldUPu', NULL, 0, '2021-02-26 13:15:42.400', NULL, NULL),
	(9, 'Juan Dela Cruzs', 'cartracks', '$2y$12$vfqgrWs9e1FoE6HrIjbriu9EupS0sO98QutWadqCbdFm1dBo7kHWO', NULL, 0, '2021-02-26 13:17:38.618', NULL, NULL),
	(10, 'Juan Dela Cruzs', 'cartracks', '$2y$12$xRRKWXOyBGQd1aY55lEEheR7Jbjs4Kx4wcw8y8JgslTTf3WRzM90S', NULL, 0, '2021-02-26 13:19:09.039', NULL, NULL),
	(11, 'Juan Dela Cruzs', 'cartracks', '$2y$12$XT40IYQClwwwurxp90LYou03awG7x2YsvN3bTw3aVlfGxJ.3KV/WG', NULL, 0, '2021-02-26 13:22:08.666', NULL, NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
