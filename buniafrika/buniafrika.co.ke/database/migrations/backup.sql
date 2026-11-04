/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: buniafrika
-- ------------------------------------------------------
-- Server version	11.8.8-MariaDB-1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `activity_logs`
--
-- USE cloudkazico_buniafrika;
DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=329 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES
(85,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-02 18:38:01'),
(86,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-02 18:38:09'),
(87,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-02 18:44:47'),
(88,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-02 19:17:33'),
(89,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-02 20:20:48'),
(90,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-02 20:21:00'),
(91,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:12:14'),
(92,'admin',1,'admin','Delete Project','Admin deleted project (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:12:42'),
(93,'admin',1,'admin','Delete Project','Admin deleted project (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:12:48'),
(94,'admin',1,'admin','Delete Project','Admin deleted project (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:12:52'),
(95,'admin',1,'admin','Delete Project','Admin deleted project (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:12:58'),
(96,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 8) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:13:27'),
(97,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 7) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:13:37'),
(98,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:13:41'),
(99,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:13:47'),
(100,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 5) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:13:53'),
(101,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:14:03'),
(102,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:14:11'),
(103,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 18:14:16'),
(104,'user',NULL,'ypl','Guest Article Submission','Guest submitted draft: testing articles by ypl | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:13:24'),
(105,'user',NULL,'ypl','Guest Article Submission','Guest submitted draft: testing articles by ypl | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:13:28'),
(106,'user',NULL,'Amara Diop','Guest Article Submission','Guest submitted draft: Coastal Youth Eco-Champions Clean Mombasa Beaches by Amara Diop | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-03 19:15:09'),
(107,'user',NULL,'ypl','Guest Article Submission','Guest submitted draft: testing articles by ypl | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:17:35'),
(108,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:17:42'),
(109,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:17:49'),
(110,'admin',1,'admin','Toggle News Visibility','Admin toggled news visibility (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:19:18'),
(111,'user',NULL,'Amara Diop','Guest Article Submission','Guest submitted draft: Coastal Youth Eco-Champions Clean Mombasa Beaches by Amara Diop | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-03 19:20:27'),
(112,'admin',1,'admin','Update News Article','Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:33:04'),
(113,'admin',1,'admin','Update News Article','Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:34:09'),
(114,'admin',1,'admin','Update News Article','Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:39:10'),
(115,'admin',1,'admin','Update News Article','Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:40:39'),
(116,'system',NULL,'System','Update News Article','Updated news article (ID: 7): Coastal Youth Eco-Champions Clean Mombasa Beaches | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-03 19:41:41'),
(117,'system',NULL,'System','Update News Article','Updated news article (ID: 7): Coastal Youth Eco-Champions Clean Mombasa Beaches - Updated Success | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-03 19:44:36'),
(118,'admin',1,'admin','Update News Article','Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:45:19'),
(119,'admin',1,'admin','Update News Article','Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:46:17'),
(120,'admin',1,'admin','Update News Article','Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-03 19:58:58'),
(121,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-03 20:02:50'),
(122,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-03 20:03:05'),
(123,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','127.0.0.1','2026-07-03 20:03:05'),
(124,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:42:25'),
(125,'admin',1,'admin','Delete Gallery Item','Admin deleted gallery item (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:42:36'),
(126,'admin',1,'admin','Delete Gallery Item','Admin deleted gallery item (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:42:41'),
(127,'admin',1,'admin','Delete Gallery Item','Admin deleted gallery item (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:45:48'),
(128,'admin',1,'admin','Delete Gallery Item','Admin deleted gallery item (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:45:54'),
(129,'admin',1,'admin','Delete Gallery Item','Admin deleted gallery item (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:45:58'),
(130,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 7) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:07'),
(131,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:14'),
(132,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:18'),
(133,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:23'),
(134,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:27'),
(135,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:31'),
(136,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 5) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:36'),
(137,'admin',1,'admin','Delete Event','Admin deleted event (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:44'),
(138,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-07-09 17:46:54'),
(139,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','217.199.144.61','2026-07-09 18:52:58'),
(140,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','217.199.144.61','2026-07-09 19:23:50'),
(141,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','217.199.144.61','2026-07-09 19:23:59'),
(142,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: kendayroh1@gmail.com | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','196.207.163.198','2026-07-09 19:36:01'),
(143,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','196.207.163.198','2026-07-09 19:36:30'),
(144,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin@gmail.com | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','41.212.18.91','2026-07-10 09:14:58'),
(145,'user',NULL,'dayroh','Guest Article Submission','Guest submitted draft: Tree Planting by dayroh | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 10:28:50'),
(146,'user',NULL,'kendayroh1@gmail.com','Newsletter Subscription','Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 10:29:27'),
(147,'user',NULL,'kendayroh1@gmail.com','Newsletter Subscription','Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 10:29:27'),
(148,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 12:44:18'),
(149,'admin',1,'admin','Toggle News Visibility','Admin toggled news visibility (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 12:50:33'),
(150,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 12:51:02'),
(151,'user',NULL,'Kennedy Muuo','User Registration','Registered as Volunteer: Kennedy Muuo (kendayroh1@gmail.com) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 12:52:08'),
(152,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 12:52:19'),
(153,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','196.207.163.198','2026-07-10 12:58:12'),
(154,'user',NULL,'Rahma Kassim','User Registration','Registered as Volunteer: Rahma Kassim (rahmakassim90@gmail.com) | UA: Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1','129.222.147.74','2026-07-10 15:24:32'),
(155,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 17:57:19'),
(156,'admin',1,'admin','Create News Article','Published news article: news test | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 18:01:35'),
(157,'admin',1,'admin','Update Admin','Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 18:11:23'),
(158,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1','129.222.147.74','2026-07-10 18:25:09'),
(159,'admin',1,'admin','Update Admin','Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 18:27:38'),
(160,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1','129.222.147.74','2026-07-10 18:28:22'),
(161,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:17:47'),
(162,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:17:54'),
(163,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:18:05'),
(164,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:20:10'),
(165,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:20:24'),
(166,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:23:08'),
(167,'admin',1,'admin','Update Admin','Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:23:53'),
(168,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:23:57'),
(169,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:26:02'),
(170,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:29:04'),
(171,'admin',1,'admin','Update Admin','Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:29:55'),
(172,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:30:05'),
(173,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:47:15'),
(174,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.211.208','2026-07-10 19:47:26'),
(175,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 08:28:56'),
(176,'user',NULL,'Test user','Guest Article Submission','Guest submitted draft: Testing article by Test user | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 08:33:33'),
(177,'admin',1,'admin','Toggle News Visibility','Admin toggled news visibility (ID: 10) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 08:35:55'),
(178,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 09:44:26'),
(179,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 10) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 09:44:39'),
(180,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 9) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 09:44:42'),
(181,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 09:44:59'),
(182,'user',NULL,'Victor Oduor','User Registration','Registered as Volunteer: Victor Oduor (victoroduor723@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','154.152.67.196','2026-07-11 12:24:40'),
(183,'user',NULL,'eee aaah','User Registration','Registered as Volunteer: eee aaah (atienojoy2@gmail.com) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','129.222.147.29','2026-07-11 15:59:31'),
(184,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.213.67','2026-07-11 19:50:14'),
(185,'admin',1,'admin','Create Event','Created event: Testing events | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.213.67','2026-07-11 19:51:51'),
(186,'admin',1,'admin','Create Event','Created event: Testing events | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.22.213.67','2026-07-11 19:52:11'),
(187,'user',NULL,'testing donation','Donation Submission','Donation of KES 1000 by testing donation (admin@school.com) - Ref: yggfhjjhbh | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 11:50:24'),
(188,'user',NULL,'testing donation','Donation Submission','Donation of KES 1000 by testing donation (admin@school.com) - Ref: yggfhjjhbh | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 11:50:24'),
(189,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 11:51:34'),
(190,'admin',1,'admin','Approve Donation','Admin marked donation as completed (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 11:51:50'),
(191,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:14:49'),
(192,'admin',1,'admin','Delete Event','Admin deleted event (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:23:17'),
(193,'admin',1,'admin','Delete Event','Admin deleted event (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:23:22'),
(194,'admin',1,'admin','Create Event','Created event: EVENT TEST | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:24:08'),
(195,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:27:44'),
(196,'user',NULL,'test user','Guest Article Submission','Guest submitted draft: Testing Article by test user | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:37:27'),
(197,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:37:49'),
(198,'admin',1,'admin','Toggle News Visibility','Admin toggled news visibility (ID: 11) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:38:11'),
(199,'admin',1,'admin','Delete Event','Admin deleted event (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:58:19'),
(200,'admin',1,'admin','Create Event','Created event: TESTING ADDRESS | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','102.208.180.81','2026-07-12 12:59:08'),
(201,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','196.207.163.198','2026-07-12 20:14:39'),
(202,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','196.207.163.198','2026-07-12 20:19:21'),
(203,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','196.207.163.198','2026-07-12 20:20:14'),
(204,'admin',1,'admin','Create Event','Created event: Website check | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','196.207.163.198','2026-07-12 20:30:21'),
(205,'admin',1,'admin','Delete Event','Admin deleted event (ID: 6) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','196.207.163.198','2026-07-12 20:32:17'),
(206,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:23:52'),
(207,'admin',1,'admin','Create Event','Created event: The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:29:44'),
(208,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:32:11'),
(209,'user',NULL,'dayroh','Guest Article Submission','Guest submitted draft: 122333 by dayroh | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:33:26'),
(210,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:33:39'),
(211,'admin',1,'admin','Toggle News Visibility','Admin toggled news visibility (ID: 12) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:33:55'),
(212,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:57:49'),
(213,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 09:57:54'),
(214,'admin',1,'admin','Create News Article','Published news article: Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:00:11'),
(215,'admin',1,'admin','Update News Article','Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:02:08'),
(216,'admin',1,'admin','Update News Article','Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:07:16'),
(217,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:37:56'),
(218,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:38:00'),
(219,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 12) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:38:09'),
(220,'admin',1,'admin','Delete News Article','Admin deleted news article (ID: 11) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:38:13'),
(221,'admin',1,'admin','Update News Article','Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 10:38:58'),
(222,'user',NULL,'kendayroh1@gmail.com','Newsletter Subscription','Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 11:30:55'),
(223,'user',NULL,'kendayroh1@gmail.com','Newsletter Subscription','Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 11:30:55'),
(224,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 11:31:01'),
(225,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 11:31:05'),
(226,'admin',1,'admin','Create Project','Created project: teqa | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 11:56:23'),
(227,'admin',1,'admin','Create Project','Created project: teqa | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 11:56:35'),
(228,'admin',1,'admin','Create Project','Created project: teqa | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:00:25'),
(229,'admin',1,'admin','Create Project','Created project: sken testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:01:39'),
(230,'admin',1,'admin','Create Gallery Item','Created gallery item: gallery testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:18:41'),
(231,'admin',1,'admin','Create Project','Created project: last test | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:19:35'),
(232,'admin',1,'admin','Create Gallery Item','Created gallery item: gallery 2 testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:23:06'),
(233,'user',NULL,'Kennedy Muuo','Contact Submission','Inquiry from Kennedy Muuo (kendayroh1@gmail.com) - Subject: testing message | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:44:19'),
(234,'user',NULL,'Ken Vicky','Contact Submission','Inquiry from Ken Vicky (kendayroh1@gmail.com) - Subject: testing 2 | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:44:54'),
(235,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:45:17'),
(236,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:45:20'),
(237,'admin',1,'admin','Create Event','Created event: event testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:47:31'),
(238,'admin',1,'admin','Update Event','Updated event (ID: 7): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:48:31'),
(239,'admin',1,'admin','Delete Event','Admin deleted event (ID: 5) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:48:40'),
(240,'admin',1,'admin','Delete Event','Admin deleted event (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:49:10'),
(241,'admin',1,'admin','Toggle Event Visibility','Admin toggled event visibility (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:49:40'),
(242,'admin',1,'admin','Toggle Event Visibility','Admin toggled event visibility (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:49:48'),
(243,'admin',1,'admin','Update Event','Updated event (ID: 7): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:50:21'),
(244,'admin',1,'admin','Update Event','Updated event (ID: 7): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:51:42'),
(245,'admin',1,'admin','Delete Event','Admin deleted event (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:51:48'),
(246,'admin',1,'admin','Create Event','Created event: The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:53:06'),
(247,'admin',1,'admin','Create Event','Created event: rfcsc | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-13 12:54:00'),
(248,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','154.159.124.50','2026-07-13 14:01:09'),
(249,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:11:54'),
(250,'admin',1,'admin','Update Event','Updated event (ID: 9): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:13:56'),
(251,'admin',1,'admin','Delete Event','Admin deleted event (ID: 10) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:03'),
(252,'admin',1,'admin','Update Event','Updated event (ID: 9): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:11'),
(253,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 10) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:24'),
(254,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 9) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:28'),
(255,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 2) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:38'),
(256,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 3) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:42'),
(257,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 4) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:46'),
(258,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 5) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:14:49'),
(259,'admin',1,'admin','Delete Project','Admin deleted project (ID: 5) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:15:44'),
(260,'admin',1,'admin','Delete Project','Admin deleted project (ID: 6) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:15:49'),
(261,'admin',1,'admin','Delete Project','Admin deleted project (ID: 9) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:15:56'),
(262,'admin',1,'admin','Delete Project','Admin deleted project (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:16:00'),
(263,'admin',1,'admin','Delete Project','Admin deleted project (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:16:04'),
(264,'admin',1,'admin','Update News Article','Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:17:32'),
(265,'user',NULL,'Kennedy Vicky','Donation Submission','Donation of KES 2000 by Kennedy Vicky (kendayroh1@gmail.com) - Ref: RJRGYFIWHVYS | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:19:22'),
(266,'user',NULL,'Kennedy Vicky','Donation Submission','Donation of KES 2000 by Kennedy Vicky (kendayroh1@gmail.com) - Ref: RJRGYFIWHVYS | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:19:22'),
(267,'admin',1,'admin','Approve Donation','Admin marked donation as completed (ID: 4) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:20:21'),
(268,'admin',1,'admin','Approve Donation','Admin marked donation as completed (ID: 3) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:21:53'),
(269,'admin',1,'admin','Delete Donation','Admin deleted donation record (ID: 1) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:22:04'),
(270,'admin',1,'admin','Delete Donation','Admin deleted donation record (ID: 1) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:22:06'),
(271,'admin',1,'admin','Delete Donation','Admin deleted donation record (ID: 2) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:22:11'),
(272,'admin',1,'admin','Delete Donation','Admin deleted donation record (ID: 3) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:22:15'),
(273,'admin',1,'admin','Delete Donation','Admin deleted donation record (ID: 4) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:22:33'),
(274,'admin',1,'admin','Delete Gallery Item','Admin deleted gallery item (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:27:57'),
(275,'admin',1,'admin','Update Gallery Item','Updated gallery item (ID: 7): Celebrating Samburu Cultural Heritage | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 08:29:52'),
(276,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-16 09:00:48'),
(277,'user',NULL,'kathinieunice4@gmail.com','Newsletter Subscription','Subscribed to newsletter: kathinieunice4@gmail.com | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','196.201.218.79','2026-07-16 11:56:21'),
(278,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 13; 23028RNCAG Build/TP1A.220624.014) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36','196.207.163.198','2026-07-24 10:30:34'),
(279,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-25 09:08:03'),
(280,'admin',1,'admin','Create Event','Created event: 6gyuhij | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-25 09:08:45'),
(281,'admin',1,'admin','Delete Event','Admin deleted event (ID: 11) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-25 09:09:28'),
(282,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','196.207.163.198','2026-07-25 09:22:54'),
(283,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','102.215.33.40','2026-08-04 16:13:42'),
(284,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-08-09 16:12:55'),
(285,'admin',1,'admin','Create News Article','Published news article: Eyes Unseen: When the Digital Age Watches the Watchers | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-08-09 16:21:55'),
(286,'admin',1,'admin','Update News Article','Updated news article (ID: 14): Eyes Unseen: When the Digital Age Watches the Watchers | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-08-09 16:26:47'),
(287,'admin',1,'admin','Create News Article','Published news article: Securing Democracy: From Code to Constitution | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-08-09 16:31:14'),
(288,'admin',1,'admin','Create News Article','Published news article: THE ELECTION AFTER THE PROMISES: KENYA’S 2027 RECKONING | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-08-09 16:34:23'),
(289,'user',NULL,'Constant Ouma','User Registration','Registered as Volunteer: Constant Ouma (wanyamakizzy@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36','41.90.192.162','2026-08-10 11:44:49'),
(290,'user',NULL,'Ouma Felix','User Registration','Registered as Partner: Ouma Felix (oumaf6436@gmail.com) | UA: Mozilla/5.0 (Linux; U; Android 12; Nokia C31 Build/SP1A.210812.016; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/150.0.7871.181 Mobile Safari/537.36 OPR/99.5.2254.2012','141.0.12.13','2026-08-12 19:09:28'),
(291,'user',NULL,'Joshua Otieno','User Registration','Registered as Volunteer: Joshua Otieno (ayugijoshua282@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36','102.215.79.169','2026-08-12 19:22:22'),
(292,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 13; 23028RNCAG Build/TP1A.220624.014) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.181 Mobile Safari/537.36','196.200.38.69','2026-08-18 02:49:45'),
(293,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-08-29 14:44:18'),
(294,'admin',1,'admin','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-08-29 15:05:57'),
(295,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-09-01 11:24:51'),
(296,'admin',1,'admin','Create Gallery Item','Created gallery item: YPL LOGO | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-09-01 11:25:43'),
(297,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','196.200.38.69','2026-09-01 11:25:49'),
(298,'user',NULL,'Abel Otieno','User Registration','Registered as Volunteer: Abel Otieno (okeyoabel98@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36','197.155.73.50','2026-09-10 13:50:24'),
(300,'admin',1,'infor@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 14:11:04'),
(301,'admin',1,'infor@buniafrika.co.ke','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 14:11:59'),
(302,'admin',1,'infor@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 15:41:01'),
(303,'admin',1,'infor@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 15:45:18'),
(304,'user',NULL,'Website visitor','Contact Submission','Website enquiry submitted (partnership) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 15:48:23'),
(305,'admin',1,'infor@buniafrika.co.ke','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 15:57:42'),
(306,'admin',1,'infor@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 16:02:02'),
(307,'admin',1,'infor@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 16:03:14'),
(308,'admin',1,'infor@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 17:06:48'),
(309,'admin',1,'infor@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 17:08:09'),
(310,'admin',1,'infor@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 17:08:28'),
(311,'admin',1,'infor@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 17:09:32'),
(312,'admin',1,'infor@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 17:10:41'),
(313,'admin',1,'infor@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','127.0.0.1','2026-10-20 17:20:35'),
(314,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: info@buniafrika.co.ke | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 04:56:07'),
(315,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: infor@buniafrika.co.ke | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 04:57:09'),
(316,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: info@buniafrika.co.ke | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:01:27'),
(317,'admin',1,'info@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:02:13'),
(318,'admin',1,'info@buniafrika.co.ke','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:29:06'),
(319,'admin',1,'info@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:29:11'),
(320,'admin',1,'info@buniafrika.co.ke','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:33:04'),
(321,'admin',1,'info@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','::1','2026-10-21 05:36:40'),
(322,'admin',1,'info@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','::1','2026-10-21 05:51:14'),
(323,'admin',1,'info@buniafrika.co.ke','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:52:14'),
(324,'admin',1,'info@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:52:36'),
(325,'admin',1,'info@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:52:54'),
(326,'admin',1,'info@buniafrika.co.ke','Update Website Settings','Administrator updated public organization settings | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 05:53:29'),
(327,'admin',1,'info@buniafrika.co.ke','Admin Auto-Logout','Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 06:56:38'),
(328,'admin',1,'info@buniafrika.co.ke','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','::1','2026-10-21 06:56:44');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES
(1,'info@buniafrika.co.ke','info@buniafrika.co.ke','$2y$10$aRQZ5dMk/iFbUPhTr8f9quP6hnOVlm11WGHLxjVrAIt3D8WQTo35e','2026-06-08 14:31:10','4f9c043a67a8c4beb209136784e7afb0ddfa1f4673a5e46ab78e5b4eaf58dfcd','2026-10-21 06:32:56');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_categories_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `contact_submissions`
--

DROP TABLE IF EXISTS `contact_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_submissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `contact_submissions` WRITE;
/*!40000 ALTER TABLE `contact_submissions` DISABLE KEYS */;
INSERT INTO `contact_submissions` VALUES
(1,'Kennedy Vicky','kendayroh1@gmail.com','+254705556565','Testing','testing','2026-10-20 15:48:23');
/*!40000 ALTER TABLE `contact_submissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `donations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'KES',
  `payment_method` varchar(50) NOT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `donations` WRITE;
/*!40000 ALTER TABLE `donations` DISABLE KEYS */;
/*!40000 ALTER TABLE `donations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `gallery_items`
--

DROP TABLE IF EXISTS `gallery_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `caption` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_published_created` (`published`,`created_at`),
  KEY `idx_gallery_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gallery_items` WRITE;
/*!40000 ALTER TABLE `gallery_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `attempted_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ip_ua_idx` (`ip_address`,`user_agent`(255))
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `mailing_list`
--

DROP TABLE IF EXISTS `mailing_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mailing_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailing_list`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `mailing_list` WRITE;
/*!40000 ALTER TABLE `mailing_list` DISABLE KEYS */;
INSERT INTO `mailing_list` VALUES
(1,'kendayroh1@gmail.com','2026-07-10 10:29:27'),
(5,'kathinieunice4@gmail.com','2026-07-16 11:56:20');
/*!40000 ALTER TABLE `mailing_list` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `news_articles`
--

DROP TABLE IF EXISTS `news_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'BuniAfrika',
  `published_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  `featured_media` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_news_published_date` (`published`,`published_date`),
  KEY `idx_news_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_articles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `news_articles` WRITE;
/*!40000 ALTER TABLE `news_articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_articles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  `featured_media` longtext DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_projects_published_created` (`published`,`created_at`),
  KEY `idx_projects_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_registrations_created_at` (`created_at`),
  KEY `idx_registrations_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `registrations` WRITE;
/*!40000 ALTER TABLE `registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `registrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES
('civictech_intro','Civic technology education and digital participation.','2026-10-20 15:49:52'),
('civictech_points','Democratic governance, transparency and public accountability.\r\nDigital rights and responsible technology.\r\nYouth leadership and inclusive civic participation.\r\nEvidence-based research and civic problem-solving.\r\nCollaborative policy engagement.','2026-10-20 16:03:14'),
('civictech_title','CivicTech Awareness','2026-10-20 15:49:52'),
('finance_email','finance@buniafrika.co.ke','2026-10-20 15:49:52'),
('general_email','info@buniafrika.co.ke','2026-10-20 15:49:52'),
('general_phone','0705556565','2026-10-20 15:49:52'),
('heritage_intro','Community-led approaches to documenting and responsibly preserving African cultural identity and heritage.','2026-10-20 15:49:52'),
('heritage_points','African cultural identity and heritage preservation.\r\nCommunity histories and indigenous knowledge.\r\nDigital archiving and cultural storytelling.\r\nIntergenerational knowledge transfer.\r\nCommunity-led heritage documentation.\r\nResponsible digital preservation.','2026-10-20 16:03:14'),
('heritage_title','Culture and Heritage','2026-10-20 15:49:52'),
('leader_brodox_description','','2026-10-21 05:24:05'),
('leader_brodox_email','','2026-10-20 15:49:52'),
('leader_brodox_image','','2026-10-20 15:49:52'),
('leader_brodox_name','Brodox George','2026-10-20 15:49:52'),
('leader_brodox_phone','','2026-10-20 15:49:52'),
('leader_brodox_public_visible','1','2026-10-21 05:25:15'),
('leader_brodox_role','','2026-10-20 15:49:52'),
('leader_brodox_role_verified','0','2026-10-20 16:27:58'),
('leader_custom_586a1dd422a8453282692f80fc838b58_description','','2026-10-21 05:25:15'),
('leader_custom_586a1dd422a8453282692f80fc838b58_email','','2026-10-20 17:08:09'),
('leader_custom_586a1dd422a8453282692f80fc838b58_image','','2026-10-20 17:08:09'),
('leader_custom_586a1dd422a8453282692f80fc838b58_name','Victor Oduor','2026-10-20 17:08:09'),
('leader_custom_586a1dd422a8453282692f80fc838b58_phone','','2026-10-20 17:08:09'),
('leader_custom_586a1dd422a8453282692f80fc838b58_public_visible','0','2026-10-21 05:53:29'),
('leader_custom_586a1dd422a8453282692f80fc838b58_role','Tech','2026-10-20 17:08:09'),
('leader_custom_586a1dd422a8453282692f80fc838b58_role_verified','1','2026-10-20 17:20:35'),
('leader_kennedy_description','Kennedy Muuo is the Chief Executive Officer at BuniAfrika CBO. With extensive experience in community organizing and technology-driven social change, Kennedy spearheads strategic partnerships, civic technology initiatives, and public interest accountability programs across East Africa.','2026-10-21 05:51:14'),
('leader_kennedy_email','','2026-10-20 15:49:52'),
('leader_kennedy_image','','2026-10-20 15:49:52'),
('leader_kennedy_name','Kennedy Muuo','2026-10-20 15:49:52'),
('leader_kennedy_phone','','2026-10-20 15:49:52'),
('leader_kennedy_public_visible','1','2026-10-21 05:25:15'),
('leader_kennedy_role','Chief Executive Officer','2026-10-21 05:51:14'),
('leader_kennedy_role_verified','1','2026-10-21 05:51:14'),
('leader_rahma_description','Test description for Rahma Kassim. Championing civic technology and public advocacy across Africa.','2026-10-21 05:25:15'),
('leader_rahma_email','','2026-10-20 15:49:52'),
('leader_rahma_image','assets/uploads/leadership/resized_771330c33104e8939f6cdf0fd5232c22.jpg','2026-10-20 17:08:28'),
('leader_rahma_name','Rahma Kassim','2026-10-20 15:49:52'),
('leader_rahma_phone','','2026-10-20 15:49:52'),
('leader_rahma_public_visible','1','2026-10-21 05:25:15'),
('leader_rahma_role','','2026-10-20 15:49:52'),
('leader_rahma_role_verified','0','2026-10-20 16:27:58'),
('leadership_ids','[\"rahma\",\"brodox\",\"kennedy\",\"custom_586a1dd422a8453282692f80fc838b58\"]','2026-10-20 17:08:09'),
('location_map_url','https://www.google.com/maps/search/?api=1&query=Nyali%2C+Mombasa%2C+Kenya','2026-10-20 15:49:52'),
('location_text','Nyali, Mombasa, Mombasa County, Kenya','2026-10-20 15:49:52'),
('mission','To bridge research, technology and civic action in addressing society’s most pressing challenges.','2026-10-20 16:32:27'),
('objectives','Advance democratic governance and public accountability.\r\nGenerate evidence-based research on critical public-interest challenges.\r\nDevelop innovative technological solutions to civic problems.\r\nStrengthen inclusive civic participation, digital rights and youth leadership.\r\nPromote collaborative policy reform and measurable social impact.','2026-10-20 16:03:14'),
('partnership_email','partnerships@buniafrika.co.ke','2026-10-20 15:49:52'),
('site_name','BuniAfrika','2026-10-20 17:10:41'),
('social_facebook','https://www.facebook.com/buniafrika','2026-10-20 15:49:52'),
('social_instagram','https://www.instagram.com/buniafrika/','2026-10-20 15:49:52'),
('social_linkedin','https://www.linkedin.com/company/buniafrika/','2026-10-20 15:49:52'),
('social_tiktok','https://www.tiktok.com/@buniafrika','2026-10-20 15:49:52'),
('social_whatsapp_channel','','2026-10-20 15:49:52'),
('social_x','https://x.com/buniafrika','2026-10-20 15:49:52'),
('social_youtube','https://www.youtube.com/@buniafrika','2026-10-20 15:49:52'),
('vision','A society where informed citizenship, institutional accountability and innovation drive inclusive progress.','2026-10-20 15:49:52'),
('website_url','https://buniafrika.co.ke/','2026-10-20 15:49:52'),
('whatsapp_number','254705556565','2026-10-20 15:49:52');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `upcoming_events`
--

DROP TABLE IF EXISTS `upcoming_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `upcoming_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `location_link` varchar(255) DEFAULT NULL,
  `meeting_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_events_published_date` (`published`,`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upcoming_events`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `upcoming_events` WRITE;
/*!40000 ALTER TABLE `upcoming_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `upcoming_events` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-10-21 10:37:16
