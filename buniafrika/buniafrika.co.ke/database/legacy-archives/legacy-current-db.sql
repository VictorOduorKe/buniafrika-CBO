/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ngo_web_app
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-6 from Debian

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
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES
(1,'admin',1,'admin','Delete Logs','Admin deleted all activity logs | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 13:59:35'),
(2,'user',NULL,'john doe','User Registration','Registered as Volunteer: john doe (john@gmail.com) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 14:01:14'),
(3,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 14:02:13'),
(4,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:37:08'),
(5,'admin',1,'admin','Create Project','Created project: Planting maize | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:40:35'),
(6,'admin',1,'admin','Create Project','Created project: Tilling farming land | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:41:31'),
(7,'admin',1,'admin','Create Gallery Item','Created gallery item: Student empowerment | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:44:37'),
(8,'admin',1,'admin','Create Gallery Item','Created gallery item: Education | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:46:21'),
(9,'admin',1,'admin','Create Gallery Item','Created gallery item: Tilling community land | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:48:13'),
(10,'admin',1,'admin','Create Gallery Item','Created gallery item: Paying Visit to the sick | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:50:24'),
(11,'admin',1,'admin','Update Gallery Item','Updated gallery item (ID: 10): Tilling community land | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-10 16:52:40'),
(12,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 01:25:35'),
(13,'admin',1,'admin','Update Project','Updated project (ID: 4): School Food Distribution Initiative | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 01:37:33'),
(14,'admin',1,'admin','Update Project','Updated project (ID: 6): \"><script>console.log(\"XSS_TEST_OK\")</script> | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 01:40:52'),
(15,'admin',1,'admin','Update Project','Updated project (ID: 4): School Food Distribution Initiative | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 01:42:28'),
(16,'admin',1,'admin','Update Project','Updated project (ID: 6): Tilling farming land | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 01:47:46'),
(17,'user',NULL,'Test Name','Contact Submission','Inquiry from Test Name (test@example.com) - Subject: Test Subject | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 02:07:09'),
(18,'user',NULL,'Test Name','User Registration','Registered as Volunteer: Test Name (test@example.com) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 02:20:21'),
(19,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 11) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 02:30:06'),
(20,'user',NULL,'Test Name','Contact Submission','Inquiry from Test Name (test@example.com) - Subject: <svg onload=\"eval(document.querySelector(\'tbody tr td:nth-child(5)\').textContent)\"> | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 02:33:36'),
(21,'admin',1,'admin','Update Project','Updated project (ID: 6): Tilling farming land | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 02:52:06'),
(22,'admin',1,'admin','Update Project','Updated project (ID: 6): Tilling farming land | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 02:53:16'),
(23,'admin',1,'admin','Update News Article','Updated news article (ID: 1): Panel on Combating Hate and Its Impact on Communities | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 02:54:56'),
(24,'admin',1,'admin','Update News Article','Updated news article (ID: 1): Panel on Combating Hate and Its Impact on Communities | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:00:17'),
(25,'admin',1,'admin','Update News Article','Updated news article (ID: 1): Panel on Combating Hate and Its Impact on Communities | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:01:20'),
(26,'admin',1,'admin','Delete Project','Admin deleted project (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:01:35'),
(27,'admin',1,'admin','Delete Project','Admin deleted project (ID: 5) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:01:40'),
(28,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 12) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:02:11'),
(29,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 5) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:02:21'),
(30,'admin',1,'admin','Delete Event','Admin deleted event (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:02:47'),
(31,'admin',1,'admin','Delete Event','Admin deleted event (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','::1','2026-06-11 03:02:52'),
(32,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','::1','2026-06-11 03:17:56'),
(33,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','::1','2026-06-11 03:37:48'),
(34,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 05:59:33'),
(35,'admin',1,'admin','Update Gallery Item','Updated gallery item (ID: 9): Education | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 06:01:18'),
(36,'admin',1,'admin','Create Gallery Item','Created gallery item: Children adoption | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 06:06:16'),
(37,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 06:07:49'),
(38,'admin',1,'admin','Create Gallery Item','Created gallery item: Supporting education in Pillipines | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 06:18:27'),
(39,'admin',1,'admin','Create News Article','Published news article: SCHOOL | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 06:22:09'),
(40,'admin',1,'admin','Create Project','Created project: testing category | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 06:29:08'),
(41,'admin',1,'admin','Create Project','Created project: testing category | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 06:41:52'),
(42,'admin',1,'admin','Delete Project','Admin deleted project (ID: 8) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 06:44:35'),
(43,'admin',1,'admin','Delete Project','Admin deleted project (ID: 7) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 06:44:40'),
(44,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 07:29:06'),
(45,'admin',1,'admin','Create Project','Created project: hosipital | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 07:40:46'),
(46,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 07:41:24'),
(47,'admin',1,'admin','Create Event','Created event: YPL Web Platform Testing and Review Meeting | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 07:48:20'),
(48,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 07:49:07'),
(49,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 07:52:16'),
(50,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 07:52:29'),
(51,'user',NULL,'Kevin khaemba','User Registration','Registered as Volunteer: Kevin khaemba (khaembak019@gmail.com) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 07:59:10'),
(52,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:00:23'),
(53,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:03:39'),
(54,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:05:29'),
(55,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:06:12'),
(56,'admin',1,'admin','Update Event','Updated event (ID: 3): YPL Web Platform Testing and Review Meeting | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 08:07:29'),
(57,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:12:04'),
(58,'user',NULL,'Kevin','Contact Submission','Inquiry from Kevin (khaembak019@gmail.com) - Subject: Meeting | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','::1','2026-06-11 08:18:02'),
(59,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:18:55'),
(60,'admin',1,'admin','Delete Contact','Admin deleted contact record (ID: 13) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:19:42'),
(61,'admin',1,'admin','Delete Registration','Admin deleted registration record (ID: 6) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:20:02'),
(62,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-11 08:23:42'),
(63,'admin',1,'admin','Update Event','Updated event (ID: 3): YPL Web Platform Testing and Review Meeting | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 08:26:20'),
(64,'user',NULL,'greybond31@gmail.com','Newsletter Subscription','Subscribed to newsletter: greybond31@gmail.com | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 08:35:00'),
(65,'user',NULL,'greybond31@gmail.com','Newsletter Subscription','Subscribed to newsletter: greybond31@gmail.com | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 08:35:03'),
(66,'admin',1,'admin','Create Event','Created event: Testing mailing list | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 08:36:46'),
(67,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','::1','2026-06-11 09:24:58'),
(68,'admin',1,'admin','Download Logs','Admin downloaded activity logs | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','::1','2026-06-11 09:42:13'),
(69,'admin',1,'admin','Download Logs','Admin downloaded activity logs | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','::1','2026-06-11 09:48:24'),
(70,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 10:40:50'),
(71,'admin',1,'admin','Request Password Reset','Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-11 11:12:19'),
(72,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-12 15:38:33'),
(73,'admin',1,'admin','Create Event','Created event: testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','::1','2026-06-12 15:40:17'),
(74,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-12 16:09:53'),
(75,'admin',1,'admin','Delete Project','Admin deleted project (ID: 9) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-12 16:10:17'),
(76,'admin',1,'admin','Update News Article','Updated news article (ID: 4): SCHOOL | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-12 16:11:33'),
(77,'admin',1,'admin','Update News Article','Updated news article (ID: 1): Panel on Combating Hate and Its Impact on Communities | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-12 16:12:31'),
(78,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-12 16:33:03'),
(79,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-13 06:15:29'),
(80,'admin',1,'admin','Admin Login Success','Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-13 06:15:47'),
(81,'admin',1,'admin','Admin Logout','Admin logged out successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-13 06:22:19'),
(82,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-13 06:22:32'),
(83,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-13 06:22:41'),
(84,'system',NULL,'System','Admin Login Failed','Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','::1','2026-06-13 06:22:50');
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
(1,'admin','victoroduor723@gmail.com','$2y$12$DMienwSoZeaamm75w3g84OhT2c/4fcV.dp/kvlw8Q2isJW.klNq7C','2026-06-08 14:31:10','8658afd498d65a6f3ae07d6153a286e434891a9be3d22796195ee0ad4d4cab3d','2026-06-11 12:12:16');
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
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(4,'Community'),
(6,'conference'),
(1,'education'),
(2,'empowerment'),
(5,'environment'),
(3,'health');
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
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_submissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `contact_submissions` WRITE;
/*!40000 ALTER TABLE `contact_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_submissions` ENABLE KEYS */;
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
  `image_url` varchar(500) NOT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `caption` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pub_cat_created` (`published`,`created_at`),
  KEY `fk_gallery_category` (`category_id`),
  CONSTRAINT `fk_gallery_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gallery_items` WRITE;
/*!40000 ALTER TABLE `gallery_items` DISABLE KEYS */;
INSERT INTO `gallery_items` VALUES
(1,'Mass Tree planting Campaign','assets/uploads/gallery/resized_0fc8d3d5c1406657d64239a5a664d260.webp',NULL,'Mobilization of community members to engage in mass tree planting to conserve the environment','2026-06-08 15:42:23',1,5),
(2,'Community Farming','assets/uploads/gallery/resized_fc4a38bfe5a2ade57082b58bda77f87e.webp',NULL,'Visited one of the cumminity famers to inspect the current state of their farms.','2026-06-08 15:43:27',1,4),
(3,'School Project — Chesingei Primary School','assets/uploads/gallery/resized_388178b00e1d7cde91c0af0d9cf4dc97.webp',NULL,'Handing books to Chesngei primary school to reduce the shortage of study materials','2026-06-08 15:45:45',1,1),
(4,'Sanitary Towels Distribution','assets/uploads/gallery/resized_dd881b9310d5ef9824ba533b5a299a86.webp',NULL,'Giving out sanitary towels to primary school girls to prevent absentism and other factors that  might lead to low perfrmance.','2026-06-08 15:48:01',1,1),
(6,'Tree planting','assets/uploads/gallery/resized_e4504f28d95a9a30ac69af75ed3ab084.webp',NULL,'Engaging in tree planting activity for better tommorows environment','2026-06-08 15:51:22',1,5),
(7,'Maize Harvesting Operations','','assets/uploads/gallery/video_test.mp4','A video showing the sustainable maize harvesting process on our community farm.','2026-06-10 10:20:29',1,4),
(8,'Student empowerment','assets/uploads/gallery/resized_b671cd8937a58dfd6d284c552a74e078.webp','','Supporting students to continue with their education.','2026-06-10 16:44:37',1,1),
(9,'Education','assets/uploads/gallery/resized_36714983f2930eccdd2b01bcdacb8f4d.webp','','Susan Nakitanga one of the students I am supporting.','2026-06-10 16:46:21',1,1),
(10,'Tilling community land','assets/uploads/gallery/resized_1453f4171a08ddb729f04d892e9197b1.webp','','Preparing community land for seasonal planting','2026-06-10 16:48:13',1,4),
(11,'Paying Visit to the sick','assets/uploads/gallery/resized_870b85939c1cb21255629be68f50f6dd.webp','','One of the community work to pay visit to the sick.','2026-06-10 16:50:24',1,4),
(12,'Children adoption','assets/uploads/gallery/resized_c260804ba4820eb5cdce214576b5113b.webp','','Some of the children Fr Evans adopted','2026-06-11 06:06:16',1,4),
(13,'Supporting education in Pillipines','assets/uploads/gallery/resized_8de064301763a4b328ddd78634c723e9.webp','','dudation support in Phillipines','2026-06-11 06:18:27',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
INSERT INTO `login_attempts` VALUES
(8,'::1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-13 06:22:32'),
(9,'::1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-13 06:22:41'),
(10,'::1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-13 06:22:50');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailing_list`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `mailing_list` WRITE;
/*!40000 ALTER TABLE `mailing_list` DISABLE KEYS */;
INSERT INTO `mailing_list` VALUES
(1,'greybond31@gmail.com','2026-06-11 08:34:55');
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
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'Lumina Trust',
  `published_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  `featured_media` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pub_cat_pubdate_created` (`published`,`published_date`,`created_at`),
  KEY `fk_news_category` (`category_id`),
  CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_articles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `news_articles` WRITE;
/*!40000 ALTER TABLE `news_articles` DISABLE KEYS */;
INSERT INTO `news_articles` VALUES
(1,'Panel on Combating Hate and Its Impact on Communities','In Toronto delivering speech about hate and its impact','<h3>About This Initiative</h3><p></p><p>Addressing the root causes and societal impacts of hate speech and discrimination is essential for fostering inclusive and resilient urban spaces. Held in Toronto, this panel discussion brought together international delegates, policy experts, and community advocates to examine how hate impacts local communities. The session emphasized the shared global responsibility of countering division through constructive intercultural and political dialogue.</p><p></p><h3>Objectives</h3><p></p><p></p><p></p><h3>Key Topics Covered</h3><p></p><p>The panel addressed the evolving landscape of hate speech, exploring how systemic biases undermine the safety and stability of diverse neighborhoods. Speakers highlighted the critical role of governmental cooperation drawing attention to supporting frameworks like those from the Ministry of Foreign Affairs of Denmark, cultivating grassroots resilience. The discussion emphasized actionable public policy, legislative protection, and the intersectional realities of those targeted by hate.</p><h3>Community Dialogue</h3><p>A central pillar of the initiative was fostering open, high-level dialogue that directly connects global policy with lived community experiences. The panel facilitated a dynamic exchange where delegates actively listened, shared insights, and addressed challenging societal dynamics in a collaborative setting. By encouraging transparent communication between international bodies and local advocates, the forum created a trusted space to brainstorm scalable solutions for safer, more unified communities.</p><p></p><h3>Impact </h3><p></p><p>By analyzing the profound effects of hate on public safety and community well-being, the panel equipped attendees and organizing partners with a deeper framework for advocacy and policy reform. The insights shared during the Toronto session underscore the necessity of continuous cross-border collaboration, reinforcing a collective determination to replace divisive narratives with solidarity, equity, and mutual respect.</p><p></p><h3>Project Details</h3><p></p><p></p><p></p>','assets/uploads/news/resized_c53a6c97d2497bada721b127a41f1210.webp','YPL','2026-06-08','2026-06-08 15:08:00',1,'',NULL,6),
(4,'SCHOOL','A school official presents a certificate to a student during an award ceremony held at the school compound.','<h3>Student Recognized for Outstanding Achievement During School Award Ceremony</h3><p>A student was honored during a special award ceremony held at the school grounds in recognition of her hard work, dedication, and academic excellence.</p><p>The event brought together teachers, students, and guests to celebrate outstanding performance and encourage learners to continue striving for success. During the ceremony, a school official presented the student with a certificate as a token of appreciation for her achievement.</p><p>Speaking during the presentation, school leaders commended the student for her commitment and determination, noting that her accomplishment serves as an inspiration to other learners. They emphasized the importance of discipline, perseverance, and focus in achieving academic goals.</p><p>The colorful event was attended by members of the school community, who applauded the award recipient and celebrated her success. Organizers expressed their commitment to recognizing excellence and motivating students to reach their full potential.</p><p>The award ceremony concluded with words of encouragement to all students, urging them to continue working hard and embracing opportunities for growth and achievement.</p>','assets/uploads/news/resized_77af257184d3cdf08973e43dffd55505.webp','YPL','2026-06-11','2026-06-11 06:22:09',1,'','[\"assets\\/uploads\\/featured\\/resized_a19aa225c9902fad1b1b3a8e087ac605.webp\",\"assets\\/uploads\\/featured\\/resized_126625026d6a54a25d35be4fe268c982.webp\"]',1);
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
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  `featured_media` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pub_cat_created` (`published`,`created_at`),
  KEY `fk_projects_category` (`category_id`),
  CONSTRAINT `fk_projects_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES
(1,'Youth Empowerment — Koykoy Soccer Team','<p><strong style=\"color: rgb(0, 41, 102);\">About This Initiative</strong></p><p></p><p>Young people thrive when they have structured outlets for their energy, creativity, and ambition. This initiative centred on the Koykoy soccer team — a group of passionate youth leaders who use sport as a vehicle for discipline, community belonging, and personal development. By donating a football to the team, we made a simple but meaningful investment in their journey and their dreams.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Why This Matters</strong></p><p></p><p>Sport plays a critical role in youth development. It fosters teamwork, resilience, time management, and leadership — skills that extend far beyond the pitch. The Koykoy soccer team is not just a sports club; its members are recognised youth leaders in their community who mentor peers and champion positive values through the beautiful game.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Objectives</strong></p><p></p><p>Provide sporting equipment to support youth athletic activities.</p><p>Recognise and celebrate the role of youth leaders in the community.</p><p>Encourage youth engagement through sport and positive peer mentorship.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">The Handover</strong></p><p></p><p>A football was officially handed over to the Koykoy team&#39;s representatives in a community event. The handover was accompanied by words of encouragement from organisers and a call to keep inspiring the next generation. Team members expressed gratitude and reaffirmed their commitment to using sport as a platform for positive change.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Impact</strong></p><p></p><p>The support given to the Koykoy soccer team sends a wider message to the community: youth efforts are seen, valued, and worthy of backing. Such recognition motivates young people to stay engaged in structured, constructive activities and reduces the risk of idleness and disengagement among the youth population.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Project Details</strong></p><p></p><p><strong style=\"color: rgb(31, 56, 100);\">Category: </strong>Youth Empowerment</p><p><strong style=\"color: rgb(31, 56, 100);\">Beneficiaries: </strong>Koykoy Soccer Team members and youth leaders</p><p><strong style=\"color: rgb(31, 56, 100);\">Mode of Support: </strong>In-kind donation of football equipment</p>','assets/uploads/projects/resized_0314fa690bc436fbd1555f75a2133b58.webp','2026-06-08 14:37:32',1,'',NULL,2),
(2,'School Project — Chesingei Primary School','<p><strong style=\"color: rgb(0, 41, 102);\">About This Initiative</strong></p><p></p><p>This education drive focused on providing much-needed study books to learners at Chesingei Primary School. Access to quality learning materials remains one of the biggest barriers to academic achievement in many rural communities. Through this project, we stepped in to bridge that gap by sourcing and distributing a wide range of textbooks, exercise books, and reference materials directly to the students who need them most.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Objectives</strong></p><p></p><p>Supply essential study books to pupils who lack access to learning materials.</p><p>Support academic performance and school attendance at Chesingei Primary School.</p><p>Strengthen the partnership between the organisation and the local school community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">What We Did</strong></p><p></p><p>Community leaders, teachers, and parents were consulted to identify the most critical learning resource gaps at the school. Study books were then sourced, packaged, and handed directly to pupils during a school assembly, ensuring every child received materials in good condition. The handover was conducted with participation from school administration and local community members.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Impact</strong></p><p></p><p>With proper study materials now in hand, students can prepare more effectively for exams, engage deeper with their subjects, and build the academic foundation they need to succeed. Teachers are better equipped to assign reading and homework, and parents are encouraged by the visible investment in their children&#39;s futures.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Project Details</strong></p><p></p><p><strong style=\"color: rgb(31, 56, 100);\">Category: </strong>Education</p><p><strong style=\"color: rgb(31, 56, 100);\">Beneficiaries: </strong>Pupils of Chesingei Primary School</p><p><strong style=\"color: rgb(31, 56, 100);\">Mode of Support: </strong>In-kind donation of study books</p>','assets/uploads/projects/resized_927bb78311d44b94fa9b69ee9b65dd8e.webp','2026-06-08 14:45:01',1,'',NULL,1),
(3,'Family Planning Mass Campaign','<p><strong style=\"color: rgb(0, 41, 102);\">About This Initiative</strong></p><p></p><p>Access to accurate information about family planning is a cornerstone of healthy, thriving communities. This mass campaign brought together health professionals, community leaders, and local residents to raise awareness about the importance of family planning. The campaign was conducted in a culturally sensitive and inclusive manner, reaching community members across different ages and backgrounds.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Objectives</strong></p><p></p><p>Raise awareness about family planning options available at local health facilities.</p><p>Dispel myths and misconceptions surrounding reproductive health and contraception.</p><p>Empower families to make informed decisions about birth spacing and maternal health.</p><p>Highlight the social and economic benefits of planned families for the broader community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Key Topics Covered</strong></p><p></p><p>The campaign addressed reproductive health education including birth spacing, maternal wellbeing, and available contraception options. Open community dialogues were held to address cultural concerns and create a safe space for honest questions. Health professionals led informational sessions tailored to the needs of both men and women in the community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Community Dialogue</strong></p><p></p><p>A key component of the campaign was open dialogue. Facilitators created spaces for community members to ask questions, voice concerns, and engage with health information in a non-judgmental environment. Cultural sensitivities were respected throughout, and local leaders played an active role in encouraging community participation and normalising conversations around family health.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Media Coverage</strong></p><p></p><p>The campaign attracted attention from local media outlets, including television broadcast coverage. This amplified the message about family planning beyond the immediate event, extending its reach to a wider audience across the region. Media visibility helped reinforce the legitimacy and importance of the campaign&#39;s public health goals.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Impact</strong></p><p></p><p>By delivering clear, accurate, and respectful information, this campaign equipped families with the knowledge to make better health decisions. Greater awareness of family planning contributes to improved maternal and child health outcomes, reduced pressure on household resources, and stronger, more stable family units across the community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Project Details</strong></p><p></p><p><strong style=\"color: rgb(31, 56, 100);\">Category: </strong>Health</p><p><strong style=\"color: rgb(31, 56, 100);\">Reach: </strong>Local community members — men, women, and youth</p><p><strong style=\"color: rgb(31, 56, 100);\">Mode of Delivery: </strong>Mass campaign with community dialogue sessions</p><p><strong style=\"color: rgb(31, 56, 100);\">Media: </strong>Local television broadcast coverage</p>','assets/uploads/projects/resized_14f0ae7b890363e5137124c4b1814d8d.webp','2026-06-08 14:47:10',1,'',NULL,3),
(4,'School Food Distribution Initiative','<h3>About This Initiative</h3><p></p><p>Ensuring food security and nutritional support is essential for building resilient and thriving communities. This is community-focused initiative brought together local leaders, administration, and residents to distribute vital food supplies to those in need. The initiative was conducted in a transparent and structured manner, fostering a spirit of mutual support and community resilience.</p><p></p><h3>Objectives</h3><p></p><p></p><p></p><h3>Key Topics Covered</h3><p></p><p>The initiative centered around the efficient logistics of food aid delivery and public-private community cooperation. Organizers and local leaders engaged with community members to assess ongoing needs, discuss distribution equity, and ensure that relief packages reached the intended families.</p><p></p><h3>Community Dialogue</h3><p></p><p>A foundational aspect of this event was direct engagement and mutual respect. As seen in food.jpg, local representatives and community members shared meaningful interactions, symbolized by a handshake, confirming a shared commitment to community welfare. These open handovers provide a trusted platform for residents to express their local challenges and build transparent relationships with community organizers.</p><p></p><h3>Impact</h3><p></p><p>By delivering substantial food resources directly to the area, this distribution drive provided immediate relief to household budgets and improved daily food security. The successful collaboration reinforces community solidarity, boosts public morale, and establishes a reliable framework for future community support and development projects.</p><p></p><h3>Project Details</h3><p></p><p></p>','assets/uploads/projects/resized_da7ae576ba75e7a002a384cab45cb60a.webp','2026-06-08 14:54:19',1,'',NULL,1);
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
  KEY `idx_role_created` (`role`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
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
  `location_link` varchar(500) DEFAULT NULL,
  `meeting_link` varchar(500) DEFAULT NULL,
  `published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pub_date` (`published`,`event_date`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upcoming_events`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `upcoming_events` WRITE;
/*!40000 ALTER TABLE `upcoming_events` DISABLE KEYS */;
INSERT INTO `upcoming_events` VALUES
(3,'YPL Web Platform Testing and Review Meeting','A stakeholder meeting to test the NGO website, collect user feedback, and discuss improvements for community engagement and service delivery.https://staging.ngoproject.org','2026-06-12 09:30:00','Kitum Caves','https://maps.google.com','https://zoom.com',1,'2026-06-11 07:48:20'),
(4,'Testing mailing list','This a test to check if mailing list receives alert','2026-06-12 09:30:00','Maasai Mara lodge','https://maps.google.com','https://zoom.com',1,'2026-06-11 08:36:46'),
(5,'testing','testing','2026-06-12 18:42:00','Kitum Caves','','',1,'2026-06-12 15:40:17');
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

-- Dump completed on 2026-06-13  9:40:14
