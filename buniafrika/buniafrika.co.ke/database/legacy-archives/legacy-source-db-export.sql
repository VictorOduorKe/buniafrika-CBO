-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2026 at 08:40 AM
-- Server version: 10.11.16-MariaDB-cll-lve
-- PHP Version: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ypl_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--
CREATE DATABASE IF NOT EXISTS ypl_db;

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_type`, `user_id`, `username`, `activity`, `details`, `ip_address`, `created_at`) VALUES
(85, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-02 18:38:01'),
(86, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-02 18:38:09'),
(87, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-02 18:44:47'),
(88, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-02 19:17:33'),
(89, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-02 20:20:48'),
(90, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-02 20:21:00'),
(91, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:12:14'),
(92, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:12:42'),
(93, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:12:48'),
(94, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:12:52'),
(95, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:12:58'),
(96, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 8) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:13:27'),
(97, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 7) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:13:37'),
(98, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:13:41'),
(99, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:13:47'),
(100, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 5) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:13:53'),
(101, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:14:03'),
(102, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:14:11'),
(103, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 18:14:16'),
(104, 'user', NULL, 'ypl', 'Guest Article Submission', 'Guest submitted draft: testing articles by ypl | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:13:24'),
(105, 'user', NULL, 'ypl', 'Guest Article Submission', 'Guest submitted draft: testing articles by ypl | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:13:28'),
(106, 'user', NULL, 'Amara Diop', 'Guest Article Submission', 'Guest submitted draft: Coastal Youth Eco-Champions Clean Mombasa Beaches by Amara Diop | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-03 19:15:09'),
(107, 'user', NULL, 'ypl', 'Guest Article Submission', 'Guest submitted draft: testing articles by ypl | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:17:35'),
(108, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:17:42'),
(109, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:17:49'),
(110, 'admin', 1, 'admin', 'Toggle News Visibility', 'Admin toggled news visibility (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:19:18'),
(111, 'user', NULL, 'Amara Diop', 'Guest Article Submission', 'Guest submitted draft: Coastal Youth Eco-Champions Clean Mombasa Beaches by Amara Diop | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-03 19:20:27'),
(112, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:33:04'),
(113, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:34:09'),
(114, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:39:10'),
(115, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:40:39'),
(116, 'system', NULL, 'System', 'Update News Article', 'Updated news article (ID: 7): Coastal Youth Eco-Champions Clean Mombasa Beaches | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-03 19:41:41'),
(117, 'system', NULL, 'System', 'Update News Article', 'Updated news article (ID: 7): Coastal Youth Eco-Champions Clean Mombasa Beaches - Updated Success | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-03 19:44:36'),
(118, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:45:19'),
(119, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:46:17'),
(120, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 6): testing articles | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-03 19:58:58'),
(121, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: admin | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-03 20:02:50'),
(122, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-03 20:03:05'),
(123, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '127.0.0.1', '2026-07-03 20:03:05'),
(124, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:42:25'),
(125, 'admin', 1, 'admin', 'Delete Gallery Item', 'Admin deleted gallery item (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:42:36'),
(126, 'admin', 1, 'admin', 'Delete Gallery Item', 'Admin deleted gallery item (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:42:41'),
(127, 'admin', 1, 'admin', 'Delete Gallery Item', 'Admin deleted gallery item (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:45:48'),
(128, 'admin', 1, 'admin', 'Delete Gallery Item', 'Admin deleted gallery item (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:45:54'),
(129, 'admin', 1, 'admin', 'Delete Gallery Item', 'Admin deleted gallery item (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:45:58'),
(130, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 7) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:07'),
(131, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 6) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:14'),
(132, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:18'),
(133, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:23'),
(134, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:27'),
(135, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:31'),
(136, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 5) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:36'),
(137, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:44'),
(138, 'admin', 1, 'admin', 'Delete Registration', 'Admin deleted registration record (ID: 1) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '::1', '2026-07-09 17:46:54'),
(139, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '217.199.144.61', '2026-07-09 18:52:58'),
(140, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '217.199.144.61', '2026-07-09 19:23:50'),
(141, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '217.199.144.61', '2026-07-09 19:23:59'),
(142, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: kendayroh1@gmail.com | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '196.207.163.198', '2026-07-09 19:36:01'),
(143, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '196.207.163.198', '2026-07-09 19:36:30'),
(144, 'system', NULL, 'System', 'Admin Login Failed', 'Failed login attempt for username: admin@gmail.com | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '41.212.18.91', '2026-07-10 09:14:58'),
(145, 'user', NULL, 'dayroh', 'Guest Article Submission', 'Guest submitted draft: Tree Planting by dayroh | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 10:28:50'),
(146, 'user', NULL, 'kendayroh1@gmail.com', 'Newsletter Subscription', 'Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 10:29:27'),
(147, 'user', NULL, 'kendayroh1@gmail.com', 'Newsletter Subscription', 'Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 10:29:27'),
(148, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 12:44:18'),
(149, 'admin', 1, 'admin', 'Toggle News Visibility', 'Admin toggled news visibility (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 12:50:33'),
(150, 'admin', 1, 'admin', 'Admin Logout', 'Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 12:51:02'),
(151, 'user', NULL, 'Kennedy Muuo', 'User Registration', 'Registered as Volunteer: Kennedy Muuo (kendayroh1@gmail.com) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 12:52:08'),
(152, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 12:52:19'),
(153, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-10 12:58:12'),
(154, 'user', NULL, 'Rahma Kassim', 'User Registration', 'Registered as Volunteer: Rahma Kassim (rahmakassim90@gmail.com) | UA: Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1', '129.222.147.74', '2026-07-10 15:24:32'),
(155, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 17:57:19'),
(156, 'admin', 1, 'admin', 'Create News Article', 'Published news article: news test | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 18:01:35'),
(157, 'admin', 1, 'admin', 'Update Admin', 'Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 18:11:23'),
(158, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1', '129.222.147.74', '2026-07-10 18:25:09'),
(159, 'admin', 1, 'admin', 'Update Admin', 'Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 18:27:38'),
(160, 'admin', 1, 'admin', 'Admin Logout', 'Admin logged out successfully | UA: Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/426.7.931869700 Mobile/15E148 Safari/604.1', '129.222.147.74', '2026-07-10 18:28:22'),
(161, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:17:47'),
(162, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:17:54'),
(163, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:18:05'),
(164, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:20:10'),
(165, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:20:24'),
(166, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:23:08'),
(167, 'admin', 1, 'admin', 'Update Admin', 'Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:23:53'),
(168, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:23:57'),
(169, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:26:02'),
(170, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:29:04'),
(171, 'admin', 1, 'admin', 'Update Admin', 'Updated details for admin ID 1 | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:29:55'),
(172, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:30:05'),
(173, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:47:15'),
(174, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.211.208', '2026-07-10 19:47:26'),
(175, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 08:28:56'),
(176, 'user', NULL, 'Test user', 'Guest Article Submission', 'Guest submitted draft: Testing article by Test user | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 08:33:33'),
(177, 'admin', 1, 'admin', 'Toggle News Visibility', 'Admin toggled news visibility (ID: 10) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 08:35:55'),
(178, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 09:44:26'),
(179, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 10) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 09:44:39'),
(180, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 9) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 09:44:42'),
(181, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 09:44:59'),
(182, 'user', NULL, 'Victor Oduor', 'User Registration', 'Registered as Volunteer: Victor Oduor (victoroduor723@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '154.152.67.196', '2026-07-11 12:24:40'),
(183, 'user', NULL, 'eee aaah', 'User Registration', 'Registered as Volunteer: eee aaah (atienojoy2@gmail.com) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '129.222.147.29', '2026-07-11 15:59:31'),
(184, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.213.67', '2026-07-11 19:50:14'),
(185, 'admin', 1, 'admin', 'Create Event', 'Created event: Testing events | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.213.67', '2026-07-11 19:51:51'),
(186, 'admin', 1, 'admin', 'Create Event', 'Created event: Testing events | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.22.213.67', '2026-07-11 19:52:11'),
(187, 'user', NULL, 'testing donation', 'Donation Submission', 'Donation of KES 1000 by testing donation (admin@school.com) - Ref: yggfhjjhbh | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 11:50:24'),
(188, 'user', NULL, 'testing donation', 'Donation Submission', 'Donation of KES 1000 by testing donation (admin@school.com) - Ref: yggfhjjhbh | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 11:50:24'),
(189, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 11:51:34'),
(190, 'admin', 1, 'admin', 'Approve Donation', 'Admin marked donation as completed (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 11:51:50'),
(191, 'admin', 1, 'admin', 'Request Password Reset', 'Admin requested password reset link | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:14:49'),
(192, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 2) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:23:17'),
(193, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 3) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:23:22'),
(194, 'admin', 1, 'admin', 'Create Event', 'Created event: EVENT TEST | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:24:08'),
(195, 'admin', 1, 'admin', 'Admin Logout', 'Admin logged out successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:27:44'),
(196, 'user', NULL, 'test user', 'Guest Article Submission', 'Guest submitted draft: Testing Article by test user | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:37:27'),
(197, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:37:49'),
(198, 'admin', 1, 'admin', 'Toggle News Visibility', 'Admin toggled news visibility (ID: 11) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:38:11'),
(199, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 4) | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:58:19'),
(200, 'admin', 1, 'admin', 'Create Event', 'Created event: TESTING ADDRESS | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '102.208.180.81', '2026-07-12 12:59:08'),
(201, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '196.207.163.198', '2026-07-12 20:14:39'),
(202, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '196.207.163.198', '2026-07-12 20:19:21'),
(203, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '196.207.163.198', '2026-07-12 20:20:14'),
(204, 'admin', 1, 'admin', 'Create Event', 'Created event: Website check | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '196.207.163.198', '2026-07-12 20:30:21'),
(205, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 6) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '196.207.163.198', '2026-07-12 20:32:17'),
(206, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:23:52'),
(207, 'admin', 1, 'admin', 'Create Event', 'Created event: The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:29:44'),
(208, 'admin', 1, 'admin', 'Admin Logout', 'Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:32:11'),
(209, 'user', NULL, 'dayroh', 'Guest Article Submission', 'Guest submitted draft: 122333 by dayroh | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:33:26'),
(210, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:33:39'),
(211, 'admin', 1, 'admin', 'Toggle News Visibility', 'Admin toggled news visibility (ID: 12) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:33:55'),
(212, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:57:49'),
(213, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 09:57:54'),
(214, 'admin', 1, 'admin', 'Create News Article', 'Published news article: Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:00:11'),
(215, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:02:08'),
(216, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:07:16'),
(217, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:37:56'),
(218, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:38:00'),
(219, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 12) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:38:09'),
(220, 'admin', 1, 'admin', 'Delete News Article', 'Admin deleted news article (ID: 11) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:38:13'),
(221, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 10:38:58'),
(222, 'user', NULL, 'kendayroh1@gmail.com', 'Newsletter Subscription', 'Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 11:30:55'),
(223, 'user', NULL, 'kendayroh1@gmail.com', 'Newsletter Subscription', 'Subscribed to newsletter: kendayroh1@gmail.com | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 11:30:55'),
(224, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 11:31:01'),
(225, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 11:31:05'),
(226, 'admin', 1, 'admin', 'Create Project', 'Created project: teqa | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 11:56:23'),
(227, 'admin', 1, 'admin', 'Create Project', 'Created project: teqa | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 11:56:35'),
(228, 'admin', 1, 'admin', 'Create Project', 'Created project: teqa | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:00:25'),
(229, 'admin', 1, 'admin', 'Create Project', 'Created project: sken testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:01:39'),
(230, 'admin', 1, 'admin', 'Create Gallery Item', 'Created gallery item: gallery testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:18:41'),
(231, 'admin', 1, 'admin', 'Create Project', 'Created project: last test | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:19:35'),
(232, 'admin', 1, 'admin', 'Create Gallery Item', 'Created gallery item: gallery 2 testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:23:06'),
(233, 'user', NULL, 'Kennedy Muuo', 'Contact Submission', 'Inquiry from Kennedy Muuo (kendayroh1@gmail.com) - Subject: testing message | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:44:19'),
(234, 'user', NULL, 'Ken Vicky', 'Contact Submission', 'Inquiry from Ken Vicky (kendayroh1@gmail.com) - Subject: testing 2 | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:44:54'),
(235, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:45:17'),
(236, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:45:20'),
(237, 'admin', 1, 'admin', 'Create Event', 'Created event: event testing | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:47:31'),
(238, 'admin', 1, 'admin', 'Update Event', 'Updated event (ID: 7): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:48:31'),
(239, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 5) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:48:40'),
(240, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:49:10'),
(241, 'admin', 1, 'admin', 'Toggle Event Visibility', 'Admin toggled event visibility (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:49:40'),
(242, 'admin', 1, 'admin', 'Toggle Event Visibility', 'Admin toggled event visibility (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:49:48'),
(243, 'admin', 1, 'admin', 'Update Event', 'Updated event (ID: 7): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:50:21'),
(244, 'admin', 1, 'admin', 'Update Event', 'Updated event (ID: 7): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:51:42'),
(245, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:51:48'),
(246, 'admin', 1, 'admin', 'Create Event', 'Created event: The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:53:06'),
(247, 'admin', 1, 'admin', 'Create Event', 'Created event: rfcsc | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-13 12:54:00'),
(248, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '154.159.124.50', '2026-07-13 14:01:09'),
(249, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:11:54'),
(250, 'admin', 1, 'admin', 'Update Event', 'Updated event (ID: 9): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:13:56'),
(251, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 10) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:03'),
(252, 'admin', 1, 'admin', 'Update Event', 'Updated event (ID: 9): The Price of Bleeding 2 – Theatre Performance & Pad Drive | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:11'),
(253, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 10) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:24'),
(254, 'admin', 1, 'admin', 'Delete Contact', 'Admin deleted contact record (ID: 9) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:28'),
(255, 'admin', 1, 'admin', 'Delete Registration', 'Admin deleted registration record (ID: 2) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:38'),
(256, 'admin', 1, 'admin', 'Delete Registration', 'Admin deleted registration record (ID: 3) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:42'),
(257, 'admin', 1, 'admin', 'Delete Registration', 'Admin deleted registration record (ID: 4) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:46'),
(258, 'admin', 1, 'admin', 'Delete Registration', 'Admin deleted registration record (ID: 5) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:14:49'),
(259, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 5) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:15:44'),
(260, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 6) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:15:49'),
(261, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 9) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:15:56'),
(262, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:16:00'),
(263, 'admin', 1, 'admin', 'Delete Project', 'Admin deleted project (ID: 7) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:16:04'),
(264, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 13): Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:17:32'),
(265, 'user', NULL, 'Kennedy Vicky', 'Donation Submission', 'Donation of KES 2000 by Kennedy Vicky (kendayroh1@gmail.com) - Ref: RJRGYFIWHVYS | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:19:22'),
(266, 'user', NULL, 'Kennedy Vicky', 'Donation Submission', 'Donation of KES 2000 by Kennedy Vicky (kendayroh1@gmail.com) - Ref: RJRGYFIWHVYS | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:19:22'),
(267, 'admin', 1, 'admin', 'Approve Donation', 'Admin marked donation as completed (ID: 4) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:20:21'),
(268, 'admin', 1, 'admin', 'Approve Donation', 'Admin marked donation as completed (ID: 3) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:21:53'),
(269, 'admin', 1, 'admin', 'Delete Donation', 'Admin deleted donation record (ID: 1) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:22:04'),
(270, 'admin', 1, 'admin', 'Delete Donation', 'Admin deleted donation record (ID: 1) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:22:06'),
(271, 'admin', 1, 'admin', 'Delete Donation', 'Admin deleted donation record (ID: 2) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:22:11'),
(272, 'admin', 1, 'admin', 'Delete Donation', 'Admin deleted donation record (ID: 3) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:22:15'),
(273, 'admin', 1, 'admin', 'Delete Donation', 'Admin deleted donation record (ID: 4) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:22:33'),
(274, 'admin', 1, 'admin', 'Delete Gallery Item', 'Admin deleted gallery item (ID: 8) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:27:57'),
(275, 'admin', 1, 'admin', 'Update Gallery Item', 'Updated gallery item (ID: 7): Celebrating Samburu Cultural Heritage | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 08:29:52'),
(276, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-16 09:00:48'),
(277, 'user', NULL, 'kathinieunice4@gmail.com', 'Newsletter Subscription', 'Subscribed to newsletter: kathinieunice4@gmail.com | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '196.201.218.79', '2026-07-16 11:56:21'),
(278, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 13; 23028RNCAG Build/TP1A.220624.014) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.46 Mobile Safari/537.36', '196.207.163.198', '2026-07-24 10:30:34'),
(279, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-25 09:08:03'),
(280, 'admin', 1, 'admin', 'Create Event', 'Created event: 6gyuhij | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-25 09:08:45'),
(281, 'admin', 1, 'admin', 'Delete Event', 'Admin deleted event (ID: 11) | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-25 09:09:28'),
(282, 'admin', 1, 'admin', 'Admin Logout', 'Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '196.207.163.198', '2026-07-25 09:22:54'),
(283, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '102.215.33.40', '2026-08-04 16:13:42'),
(284, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-08-09 16:12:55'),
(285, 'admin', 1, 'admin', 'Create News Article', 'Published news article: Eyes Unseen: When the Digital Age Watches the Watchers | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-08-09 16:21:55'),
(286, 'admin', 1, 'admin', 'Update News Article', 'Updated news article (ID: 14): Eyes Unseen: When the Digital Age Watches the Watchers | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-08-09 16:26:47');
INSERT INTO `activity_logs` (`id`, `user_type`, `user_id`, `username`, `activity`, `details`, `ip_address`, `created_at`) VALUES
(287, 'admin', 1, 'admin', 'Create News Article', 'Published news article: Securing Democracy: From Code to Constitution | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-08-09 16:31:14'),
(288, 'admin', 1, 'admin', 'Create News Article', 'Published news article: THE ELECTION AFTER THE PROMISES: KENYA’S 2027 RECKONING | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-08-09 16:34:23'),
(289, 'user', NULL, 'Constant Ouma', 'User Registration', 'Registered as Volunteer: Constant Ouma (wanyamakizzy@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', '41.90.192.162', '2026-08-10 11:44:49'),
(290, 'user', NULL, 'Ouma Felix', 'User Registration', 'Registered as Partner: Ouma Felix (oumaf6436@gmail.com) | UA: Mozilla/5.0 (Linux; U; Android 12; Nokia C31 Build/SP1A.210812.016; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/150.0.7871.181 Mobile Safari/537.36 OPR/99.5.2254.2012', '141.0.12.13', '2026-08-12 19:09:28'),
(291, 'user', NULL, 'Joshua Otieno', 'User Registration', 'Registered as Volunteer: Joshua Otieno (ayugijoshua282@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '102.215.79.169', '2026-08-12 19:22:22'),
(292, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Linux; Android 13; 23028RNCAG Build/TP1A.220624.014) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.7871.181 Mobile Safari/537.36', '196.200.38.69', '2026-08-18 02:49:45'),
(293, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-08-29 14:44:18'),
(294, 'admin', 1, 'admin', 'Admin Auto-Logout', 'Admin logged out due to inactivity. | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-08-29 15:05:57'),
(295, 'admin', 1, 'admin', 'Admin Login Success', 'Admin logged in successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-09-01 11:24:51'),
(296, 'admin', 1, 'admin', 'Create Gallery Item', 'Created gallery item: YPL LOGO | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-09-01 11:25:43'),
(297, 'admin', 1, 'admin', 'Admin Logout', 'Admin logged out successfully | UA: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '196.200.38.69', '2026-09-01 11:25:49'),
(298, 'user', NULL, 'Abel Otieno', 'User Registration', 'Registered as Volunteer: Abel Otieno (okeyoabel98@gmail.com) | UA: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '197.155.73.50', '2026-09-10 13:50:24');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`, `reset_token`, `reset_expires`) VALUES
(1, 'admin', 'victoroduorxh2@gmail.com', '$2y$12$yxH3FzQiSAi4ET4O3lMWRecZC51AK/RZL0tKVboyCa6OLJOti0yg6', '2026-06-08 14:31:10', 'b7f0722d70ef223f5b9bf202f872241b87239dcdea1458973fab76499902dd0f', '2026-07-12 13:14:49');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(2, 'Blue Economy'),
(4, 'Civic Tech Awareness'),
(15, 'Civic Technology'),
(5, 'Community Engagement'),
(1, 'Culture & Heritage'),
(13, 'Cybersecurity, Digital Rights & Civic Activism'),
(14, 'Democratic Accountability'),
(3, 'Drug & Substance Prevention'),
(10, 'education'),
(11, 'health'),
(9, 'Healthcare'),
(12, 'mine'),
(16, 'Our Identity'),
(6, 'Press Releases'),
(8, 'Project Update'),
(7, 'Success Stories');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'KES',
  `payment_method` varchar(50) NOT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_items`
--

CREATE TABLE `gallery_items` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `caption` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `gallery_items`
--

INSERT INTO `gallery_items` (`id`, `title`, `category`, `image_url`, `video_url`, `caption`, `created_at`, `published`, `category_id`) VALUES
(7, 'Celebrating Samburu Cultural Heritage', NULL, 'assets/uploads/gallery/resized_a2164dde190d08a6c423a3db3e6831d1.webp', '', 'Our Media Director proudly showcases the rich Samburu cultural heritage through traditional attire, celebrating Kenya\'s diverse communities and promoting cultural appreciation, identity, and unity.', '2026-07-13 12:18:41', 1, 1),
(9, 'YPL LOGO', NULL, 'assets/uploads/gallery/resized_84845b3f0142c700d71752a5f723e576.webp', '', 'Our Logo Represents Our Identity', '2026-09-01 11:25:43', 1, 16);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `attempted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailing_list`
--

CREATE TABLE `mailing_list` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mailing_list`
--

INSERT INTO `mailing_list` (`id`, `email`, `created_at`) VALUES
(1, 'kendayroh1@gmail.com', '2026-07-10 10:29:27'),
(5, 'kathinieunice4@gmail.com', '2026-07-16 11:56:20');

-- --------------------------------------------------------

--
-- Table structure for table `news_articles`
--

CREATE TABLE `news_articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'Lumina Trust',
  `published_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  `featured_media` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `news_articles`
--

INSERT INTO `news_articles` (`id`, `title`, `category`, `summary`, `content`, `image_url`, `author`, `published_date`, `created_at`, `published`, `video_url`, `featured_media`, `category_id`) VALUES
(13, 'Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity', 'health', 'Young Patriotic Lieutenants (YPL) will stage The Price of Bleeding 2 on 18th July 2026 at the Little Theatre Club, Mbaraki, combining the power of theatre with a community pad drive to support menstrual dignity for girls and women.', '<p>Young Patriotic Lieutenants to Stage &quot;The Price of Bleeding 2&quot; in Support of Menstrual Dignity</p><p></p><p>Category</p><p>Health &amp; Community Outreach (If your website doesn&#39;t have this category, use Community, Events, or Youth Empowerment.)</p><p></p><p>Publish Date</p><p>07/13/2026</p><p></p><p>Author</p><p>YPL</p><p></p><p>Short Summary</p><p>Young Patriotic Lieutenants (YPL) will stage The Price of Bleeding 2 on 18th July 2026 at the Little Theatre Club, Mbaraki, combining the power of theatre with a community pad drive to support menstrual dignity for girls and women.</p><p></p><p>Full Article Content</p><p>Young Patriotic Lieutenants (YPL) is proud to announce the staging of its inspiring theatre production, The Price of Bleeding 2, scheduled for Saturday, 18th July 2026, at the Little Theatre Club, Mbaraki, beginning at 4:30 PM.</p><p></p><p>The production uses theatre as a powerful tool to raise awareness about menstrual health, break stigma surrounding menstruation, and encourage communities to support girls and women who struggle to access menstrual hygiene products.</p><p></p><p>In line with the organization&#39;s commitment to community service, entry to the event will be one pack of sanitary pads instead of a ticket. All donated sanitary pads will be distributed through YPL&#39;s Pad Drive initiative to support school-going girls and women in need, helping them manage their menstrual health with dignity and confidence.</p><p></p><p>YPL believes that no girl should miss school or feel ashamed because of a lack of menstrual hygiene products. Through creative arts and collective action, the organization continues to champion youth-led solutions that create lasting social impact.</p><p></p><p>Members of the public, partners, youth organizations, and community leaders are warmly invited to attend this meaningful event, support the talented cast, and contribute to a cause that transforms lives.</p><p></p><p>Event Details</p><p></p><p>Event: The Price of Bleeding 2</p><p></p><p>Date: Saturday, 18th July 2026</p><p></p><p>Time: 4:30 PM</p><p></p><p>Venue: Little Theatre Club, Mbaraki, Mombasa</p><p></p><p>Entry: One pack of sanitary pads</p><p></p><p>Together, we can use the power of art to inspire change, promote menstrual dignity, and build stronger, more compassionate communities.</p>', 'assets/uploads/news/resized_ae638a75c06b2827c34d8eee0a949343.webp', 'YPL', '2026-07-13', '2026-07-13 10:00:11', 1, 'assets/uploads/news/video_c6b3c2c51171b1427ea89fbcab9eba85.mp4', NULL, 11),
(14, 'Eyes Unseen: When the Digital Age Watches the Watchers', 'Cybersecurity, Digital Rights & Civic Activism', 'A Civic-Tech Reflection on Misinformation, Safety, and Surveillance', '<p>We live in a time where a single click can ignite a movement — or silently place someone under watch. Digital platforms have become modern public squares, giving young people the power to speak, organize, and challenge injustice. Yet beneath this promise lies an uncomfortable truth: the same spaces that amplify our voices can quietly observe, record, and restrain them. Across the globe, and increasingly within Kenya, digital technologies are no longer neutral tools. They are being woven into systems of control — tracking online behavior, mapping social networks, and shaping narratives long before citizens ever step into the streets. Surveillance today does not always arrive with uniforms or force; it arrives through screens, algorithms, and invisible data trails. Misinformation and disinformation fuel this reality. False stories spread faster than truth, playing on fear, anger, and uncertainty. Artificial intelligence has raised the stakes even higher, blurring the line between what is real and what is manufactured. In this confusion, trust fractures, communities divide, and surveillance finds justification. Young people carry the heaviest burden. Online harassment, identity misuse, account takeovers, and psychological harm are not abstract threats — they are daily experiences. What begins as digital harm often spills into real life, silencing voices and shrinking civic space. Civic technology offers hope. When paired with digital literacy, rights awareness, and ethical design, technology becomes a shield rather than a weapon. It empowers youth to question manipulation, protect their privacy, and reclaim digital spaces as arenas of dignity, truth, and participation. In an age of unseen eyes, courage means choosing visibility with agency, integrity, and care.</p>', 'assets/uploads/news/resized_13f9c5b9023d5220531bdf84055825cd.webp', 'Rahma Kassim', '2026-03-04', '2026-08-09 16:21:55', 1, '', NULL, 13),
(15, 'Securing Democracy: From Code to Constitution', 'Democratic Accountability', 'Integrated, High-Level Civic Tech & Human Rights Speech', '<p>In&nbsp;cybersecurity,&nbsp;we&nbsp;learn&nbsp;that&nbsp;the&nbsp;greatest&nbsp;threat&nbsp;to&nbsp;any&nbsp;system&nbsp;is&nbsp;not&nbsp;always&nbsp;an&nbsp;external&nbsp;attack&nbsp;—&nbsp;it&nbsp;is&nbsp;silence.&nbsp;Silence&nbsp;when&nbsp;anomalies&nbsp;go&nbsp;unchecked.&nbsp;Silence&nbsp;when&nbsp;vulnerabilities&nbsp;are&nbsp;ignored.&nbsp;Kenya’s&nbsp;democracy&nbsp;faces&nbsp;the&nbsp;same&nbsp;risk.&nbsp;The&nbsp;Constitution&nbsp;of&nbsp;Kenya&nbsp;2010&nbsp;is&nbsp;not&nbsp;just&nbsp;a&nbsp;legal&nbsp;document;&nbsp;it&nbsp;is&nbsp;our&nbsp;national&nbsp;architecture&nbsp;—&nbsp;a&nbsp;carefully&nbsp;designed&nbsp;framework&nbsp;that&nbsp;guarantees&nbsp;dignity,&nbsp;participation,&nbsp;and&nbsp;accountability.&nbsp;But&nbsp;like&nbsp;any&nbsp;system,&nbsp;it&nbsp;only&nbsp;remains&nbsp;secure&nbsp;when&nbsp;it&nbsp;is&nbsp;actively&nbsp;monitored.&nbsp;Rights&nbsp;that&nbsp;are&nbsp;not&nbsp;exercised&nbsp;become&nbsp;permissions&nbsp;that&nbsp;are&nbsp;quietly&nbsp;withdrawn.&nbsp;The&nbsp;Finance&nbsp;Bill&nbsp;2024&nbsp;marked&nbsp;a&nbsp;defining&nbsp;moment.&nbsp;Citizens&nbsp;did&nbsp;not&nbsp;merely&nbsp;protest&nbsp;—&nbsp;they&nbsp;analyzed.&nbsp;They&nbsp;did&nbsp;not&nbsp;speculate&nbsp;—&nbsp;they&nbsp;verified.&nbsp;They&nbsp;did&nbsp;not&nbsp;shout&nbsp;—&nbsp;they&nbsp;traced&nbsp;budget&nbsp;lines,&nbsp;interrogated&nbsp;data,&nbsp;exposed&nbsp;inconsistencies,&nbsp;and&nbsp;challenged&nbsp;misinformation&nbsp;with&nbsp;evidence.&nbsp;In&nbsp;that&nbsp;moment,&nbsp;OSINT&nbsp;evolved&nbsp;from&nbsp;a&nbsp;technical&nbsp;practice&nbsp;into&nbsp;a&nbsp;democratic&nbsp;duty.&nbsp;Civic&nbsp;technology&nbsp;transformed&nbsp;engagement&nbsp;into&nbsp;protection.&nbsp;Dashboards&nbsp;became&nbsp;instruments&nbsp;of&nbsp;oversight.&nbsp;Open&nbsp;data&nbsp;became&nbsp;a&nbsp;shield&nbsp;against&nbsp;manipulation.&nbsp;Public&nbsp;participation&nbsp;became&nbsp;an&nbsp;act&nbsp;of&nbsp;national&nbsp;defense.&nbsp;Petitions&nbsp;were&nbsp;no&nbsp;longer&nbsp;paperwork&nbsp;—&nbsp;they&nbsp;became&nbsp;patches.&nbsp;Social&nbsp;audits&nbsp;were&nbsp;no&nbsp;longer&nbsp;activism&nbsp;—&nbsp;they&nbsp;became&nbsp;stress&nbsp;tests.&nbsp;Verification&nbsp;became&nbsp;trust.&nbsp;And&nbsp;citizens&nbsp;became&nbsp;sentinels&nbsp;—&nbsp;distributed&nbsp;across&nbsp;the&nbsp;nation,&nbsp;connected&nbsp;by&nbsp;technology,&nbsp;grounded&nbsp;in&nbsp;constitutional&nbsp;authority.&nbsp;This&nbsp;convergence&nbsp;taught&nbsp;us&nbsp;something&nbsp;critical:&nbsp;digital&nbsp;rights&nbsp;are&nbsp;human&nbsp;rights.&nbsp;When&nbsp;civic&nbsp;space&nbsp;moves&nbsp;online,&nbsp;so&nbsp;must&nbsp;our&nbsp;protections.&nbsp;When&nbsp;governance&nbsp;becomes&nbsp;data-driven,&nbsp;justice&nbsp;must&nbsp;be&nbsp;evidence-driven.&nbsp;When&nbsp;power&nbsp;hides&nbsp;behind&nbsp;complexity,&nbsp;clarity&nbsp;becomes&nbsp;resistance.&nbsp;Cybersecurity&nbsp;gives&nbsp;us&nbsp;the&nbsp;discipline&nbsp;to&nbsp;think&nbsp;in&nbsp;threats&nbsp;and&nbsp;safeguards.&nbsp;Civic&nbsp;tech&nbsp;gives&nbsp;us&nbsp;the&nbsp;tools&nbsp;to&nbsp;translate&nbsp;rights&nbsp;into&nbsp;action.&nbsp;OSINT&nbsp;gives&nbsp;us&nbsp;the&nbsp;evidence&nbsp;to&nbsp;confront&nbsp;power&nbsp;responsibly.&nbsp;And&nbsp;the&nbsp;Constitution&nbsp;gives&nbsp;us&nbsp;the&nbsp;legitimacy&nbsp;to&nbsp;do&nbsp;so&nbsp;without&nbsp;fear.&nbsp;This&nbsp;is&nbsp;the&nbsp;future&nbsp;of&nbsp;Kenyan&nbsp;democracy&nbsp;—&nbsp;not&nbsp;one&nbsp;exercised&nbsp;once&nbsp;every&nbsp;five&nbsp;years,&nbsp;but&nbsp;one&nbsp;maintained&nbsp;daily.&nbsp;Monitored&nbsp;like&nbsp;critical&nbsp;infrastructure.&nbsp;Verified&nbsp;like&nbsp;sensitive&nbsp;data.&nbsp;Protected&nbsp;by&nbsp;informed,&nbsp;engaged,&nbsp;and&nbsp;courageous&nbsp;citizens.&nbsp;Kenya&nbsp;does&nbsp;not&nbsp;simply&nbsp;need&nbsp;active&nbsp;citizens.&nbsp;It&nbsp;needs&nbsp;digitally&nbsp;literate&nbsp;youth,&nbsp;constitutionally&nbsp;grounded&nbsp;leaders,&nbsp;and&nbsp;human&nbsp;rights&nbsp;defenders&nbsp;who&nbsp;understand&nbsp;that&nbsp;in&nbsp;this&nbsp;era,&nbsp;defending&nbsp;democracy&nbsp;means&nbsp;defending&nbsp;information,&nbsp;access,&nbsp;and&nbsp;truth.&nbsp;When&nbsp;citizens&nbsp;are&nbsp;equipped&nbsp;with&nbsp;knowledge,&nbsp;technology,&nbsp;and&nbsp;constitutional&nbsp;courage&nbsp;—&nbsp;democracy&nbsp;does&nbsp;not&nbsp;weaken.&nbsp;It&nbsp;becomes&nbsp;unbreakable.</p>', 'assets/uploads/news/resized_1ea5b3befd245619c7a43168801770a1.webp', 'Rahma Kassim', '2026-05-20', '2026-08-09 16:31:14', 1, '', NULL, 14),
(16, 'THE ELECTION AFTER THE PROMISES: KENYA’S 2027 RECKONING', 'Civic Technology', 'Power, Performance and the Kenyan Citizen', '<p>Kenya&nbsp;is&nbsp;already&nbsp;entering&nbsp;the&nbsp;political&nbsp;season&nbsp;of&nbsp;2027.&nbsp;President&nbsp;William&nbsp;Ruto’s&nbsp;administration&nbsp;is&nbsp;positioning&nbsp;itself&nbsp;for&nbsp;another&nbsp;mandate,&nbsp;while&nbsp;political&nbsp;formations&nbsp;across&nbsp;the&nbsp;country&nbsp;are&nbsp;reorganising&nbsp;and&nbsp;new&nbsp;alliances&nbsp;continue&nbsp;to&nbsp;emerge.&nbsp;Yet&nbsp;beneath&nbsp;the&nbsp;politics&nbsp;of&nbsp;coalitions,&nbsp;personalities,&nbsp;and&nbsp;campaigns&nbsp;lies&nbsp;a&nbsp;more&nbsp;important&nbsp;question:&nbsp;What&nbsp;are&nbsp;Kenyan&nbsp;citizens&nbsp;demanding&nbsp;from&nbsp;the&nbsp;next&nbsp;government?&nbsp;The&nbsp;politics&nbsp;of&nbsp;2027&nbsp;cannot&nbsp;simply&nbsp;be&nbsp;about&nbsp;who&nbsp;succeeds&nbsp;whom.&nbsp;It&nbsp;must&nbsp;be&nbsp;about&nbsp;what&nbsp;leadership&nbsp;delivers,&nbsp;what&nbsp;institutions&nbsp;protect,&nbsp;and&nbsp;what&nbsp;citizens&nbsp;are&nbsp;willing&nbsp;to&nbsp;hold&nbsp;power&nbsp;accountable&nbsp;for.&nbsp;Kenya’s&nbsp;young&nbsp;population&nbsp;has&nbsp;already&nbsp;demonstrated&nbsp;that&nbsp;it&nbsp;can&nbsp;influence&nbsp;national&nbsp;conversations&nbsp;beyond&nbsp;traditional&nbsp;political&nbsp;structures.&nbsp;The&nbsp;challenge&nbsp;now&nbsp;is&nbsp;transforming&nbsp;that&nbsp;energy&nbsp;into&nbsp;sustained&nbsp;civic&nbsp;participation—not&nbsp;only&nbsp;during&nbsp;protests&nbsp;or&nbsp;elections,&nbsp;but&nbsp;in&nbsp;the&nbsp;everyday&nbsp;monitoring&nbsp;of&nbsp;government,&nbsp;budgets,&nbsp;policies,&nbsp;and&nbsp;public&nbsp;institutions.&nbsp;The&nbsp;next&nbsp;election&nbsp;will&nbsp;test&nbsp;more&nbsp;than&nbsp;political&nbsp;popularity.&nbsp;It&nbsp;will&nbsp;test&nbsp;whether&nbsp;Kenya&nbsp;has&nbsp;moved&nbsp;from&nbsp;personality-driven&nbsp;politics&nbsp;to&nbsp;issue-driven&nbsp;citizenship.&nbsp;As&nbsp;we&nbsp;approach&nbsp;2027,&nbsp;citizens&nbsp;should&nbsp;not&nbsp;only&nbsp;ask&nbsp;“Who&nbsp;should&nbsp;I&nbsp;vote&nbsp;for?”&nbsp;“What&nbsp;have&nbsp;they&nbsp;delivered?&nbsp;What&nbsp;do&nbsp;they&nbsp;stand&nbsp;for?&nbsp;And&nbsp;who&nbsp;will&nbsp;hold&nbsp;them&nbsp;accountable&nbsp;when&nbsp;the&nbsp;cameras&nbsp;are&nbsp;gone?”&nbsp;Democracy&nbsp;matures&nbsp;when&nbsp;citizens&nbsp;stop&nbsp;choosing&nbsp;leaders&nbsp;only&nbsp;by&nbsp;promises—and&nbsp;start&nbsp;measuring&nbsp;them&nbsp;by&nbsp;performance.</p>', 'assets/uploads/news/resized_20fb40cf085826d0af192681bb883749.webp', 'Rahma Kassim', '2026-08-01', '2026-08-09 16:34:23', 1, '', NULL, 15);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  `featured_media` longtext DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `first_name`, `last_name`, `email`, `phone`, `role`, `message`, `created_at`, `ip_address`) VALUES
(6, 'Constant', 'Ouma', 'wanyamakizzy@gmail.com', '+254 115608315', 'volunteer', 'My name is constant, a comrade from TUM,a very jovial and friendly person who likes establishing new links and friendships especially when helping those in need.', '2026-08-10 11:44:49', '41.90.192.162'),
(7, 'Ouma', 'Felix', 'oumaf6436@gmail.com', '+254 701168167', 'partner', 'I would like to educate my fellow youths on leadership', '2026-08-12 19:09:28', '141.0.12.13'),
(8, 'Joshua', 'Otieno', 'ayugijoshua282@gmail.com', '+254 726063060', 'volunteer', 'I wuold like to join for engagement and exposure to the society and better understanding', '2026-08-12 19:22:22', '102.215.79.169'),
(9, 'Abel', 'Otieno', 'okeyoabel98@gmail.com', '+254 723068468', 'volunteer', 'Interest of nation building. Peace enhancement', '2026-09-10 13:50:24', '197.155.73.50');

-- --------------------------------------------------------

--
-- Table structure for table `upcoming_events`
--

CREATE TABLE `upcoming_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `location_link` varchar(255) DEFAULT NULL,
  `meeting_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `upcoming_events`
--

INSERT INTO `upcoming_events` (`id`, `title`, `description`, `event_date`, `location`, `published`, `created_at`, `location_link`, `meeting_link`) VALUES
(9, 'The Price of Bleeding 2 – Theatre Performance & Pad Drive', 'Young Patriotic Lieutenants to Stage \"The Price of Bleeding 2\" in Support of Menstrual Dignity\r\n\r\nCategory\r\nHealth & Community Outreach (If your website doesn\'t have this category, use Community, Events, or Youth Empowerment.)\r\n\r\nPublish Date\r\n07/13/2026\r\n\r\nAuthor\r\nYPL\r\n\r\nShort Summary\r\nYoung Patriotic Lieutenants (YPL) will stage The Price of Bleeding is on 18th July 2026 at the Little Theatre Club, Mbaraki, combining the power of theater with a community pad drive to support menstrual dignity for girls and women.\r\n\r\nFull Article Content\r\nYoung Patriotic Lieutenants (YPL) is proud to announce the staging of its inspiring theater production, The Price of Bleeding 2, scheduled for Saturday, 18th July 2026, at the Little Theatre Club, Mbaraki, beginning at 4:30 PM.\r\n\r\nThe production uses theater as a powerful tool to raise awareness about menstrual health, break stigma surrounding menstruation, and encourage communities to support girls and women who struggle to access menstrual hygiene products.\r\n\r\nIn line with the organization\'s commitment to community service, entry to the event will be one pack of sanitary pads instead of a ticket. All donated sanitary pads will be distributed through YPL\'s Pad Drive initiative to support school-going girls and women in need, helping them manage their menstrual health with dignity and confidence.\r\n\r\nYPL believes that no girl should miss school or feel ashamed because of a lack of menstrual hygiene products. Through creative arts and collective action, the organization continues to champion youth-led solutions that create lasting social impact.\r\n\r\nMembers of the public, partners, youth organizations, and community leaders are warmly invited to attend this meaningful event, support the talented cast, and contribute to a cause that transforms lives.\r\n\r\nEvent Details\r\n\r\nEvent: The Price of Bleeding 2\r\n\r\nDate: Saturday, 18th July 2026\r\n\r\nTime: 4:30 PM\r\n\r\nVenue: Little Theatre Club, Mbaraki, Mombasa\r\n\r\nEntry: One pack of sanitary pads\r\n\r\nTogether, we can use the power of art to inspire change, promote menstrual dignity, and build stronger, more compassionate communities.', '2026-07-08 16:30:00', 'Little Theatre Club, Mbaraki, Mombasa', 1, '2026-07-13 12:53:06', 'https://maps.app.goo.gl/qFu7z32kD6MEhbNi6', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gallery_category` (`category_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip_ua_idx` (`ip_address`,`user_agent`(255));

--
-- Indexes for table `mailing_list`
--
ALTER TABLE `mailing_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_category` (`category_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_projects_category` (`category_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upcoming_events`
--
ALTER TABLE `upcoming_events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gallery_items`
--
ALTER TABLE `gallery_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mailing_list`
--
ALTER TABLE `mailing_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news_articles`
--
ALTER TABLE `news_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `upcoming_events`
--
ALTER TABLE `upcoming_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD CONSTRAINT `fk_gallery_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_projects_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
