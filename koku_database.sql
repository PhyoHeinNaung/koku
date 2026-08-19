-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2026 at 07:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koku`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  `state_region` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `district_area` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `label`, `full_name`, `phone`, `country`, `state_region`, `city`, `district_area`, `postal_code`, `address_line1`, `address_line2`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Office', 'Phyo', '099492938', 'Myanmar', 'Yangon', 'Bahan', 'Shwgondaing', '2942', 'Room 212, Building 1, Diamond Condo', NULL, 0, '2026-07-22 00:20:08', '2026-08-03 03:25:41'),
(2, 1, 'Home', 'Phyo Hein Naung', '09421073207', 'Myanmar', 'Yangon', 'Thaketa', '10/North Ward ', NULL, '480/B, Tharlarwaddy Street', NULL, 1, '2026-08-03 03:25:25', '2026-08-03 03:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `tier` enum('luxury','premium','everyday','smart_sport') NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `tier`, `logo`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Longines', 'longines', 'luxury', 'brands/lV47lQT0BS8WDwtP6aGyA5W04EijubnVDNUI6FfF.jpg', 'Elegant Swiss watches grounded in precision, sport and classic proportion.', 1, '2026-07-15 20:39:27', '2026-08-16 18:57:58'),
(4, 'Casio', 'casio', 'everyday', 'brands/QvjeYSN4nbyjMjpFHqHi8jOQoUdXjsP4RUh745Kr.jpg', 'Practical digital and analog watches made for everyday reliability.', 1, '2026-08-16 18:13:13', '2026-08-16 18:57:58'),
(5, 'Seiko', 'seiko', 'premium', NULL, 'Japanese watchmaking known for dependable mechanical movements and purposeful design.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(6, 'Citizen', 'citizen', 'premium', NULL, 'Japanese precision with a strong tradition of light-powered timekeeping.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(7, 'Orient', 'orient', 'premium', NULL, 'Japanese mechanical watches built around in-house movements and accessible value.', 1, '2026-08-16 18:57:58', '2026-08-16 20:00:12'),
(8, 'Tissot', 'tissot', 'premium', NULL, 'Swiss watchmaking balancing heritage, sport and contemporary detail.', 1, '2026-08-16 18:57:58', '2026-08-16 20:00:12'),
(9, 'Hamilton', 'hamilton', 'premium', NULL, 'Swiss-made watches with American roots and a long connection to field timekeeping.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(10, 'Rado', 'rado', 'luxury', NULL, 'Modern Swiss design recognised for material experimentation and clean silhouettes.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(11, 'Garmin', 'garmin', 'smart_sport', NULL, 'Connected sport watches built around training, navigation and daily health.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(12, 'Omega', 'omega', 'luxury', NULL, 'Swiss precision with enduring links to exploration, timing and ocean performance.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `expired_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'N5aAlEdXiD4L4EtQOuAFs6Xb7wYYCSx9snzsJLXG', NULL, '2026-07-17 00:42:37', '2026-07-17 00:42:37'),
(2, NULL, 'ycdxT9l4DNmVZ8ZsLbIggC6mE5Oa8a8hC59H3xgg', NULL, '2026-07-17 01:00:12', '2026-07-17 01:00:12'),
(3, 1, NULL, '2026-07-31 02:00:17', '2026-07-17 02:34:30', '2026-07-31 02:00:17'),
(4, NULL, 'qEC5jr2N5b8JDY4sdJGhlxlHFmampMFRFjh6Rc5m', NULL, '2026-07-20 01:55:06', '2026-07-20 01:55:06'),
(5, NULL, 'yOuuzwNie2wGwCk8TNf5PTEqAcKRoCgLsMJdMvnR', NULL, '2026-07-21 01:55:57', '2026-07-21 01:55:57'),
(6, NULL, 'fhRnakgh7uv4Xq6ZGppAFqFwN4iXYLEyZD3TaEhx', NULL, '2026-07-22 00:05:56', '2026-07-22 00:05:56'),
(7, NULL, 'cZf4zhihNqQiXvHXGe6MsM61kxVJUm3nG94BWKq7', NULL, '2026-07-23 01:09:08', '2026-07-23 01:09:08'),
(8, NULL, 'HH0LKA1thlIS4P2pKw9H12MPUI9JyR314IuTa6v9', '2026-07-23 07:13:57', '2026-07-23 01:12:12', '2026-07-23 07:13:57'),
(9, NULL, 'HH0LKA1thlIS4P2pKw9H12MPUI9JyR314IuTa6v9', '2026-07-23 07:21:27', '2026-07-23 07:20:39', '2026-07-23 07:21:27'),
(10, NULL, 'HH0LKA1thlIS4P2pKw9H12MPUI9JyR314IuTa6v9', '2026-07-23 07:23:47', '2026-07-23 07:22:23', '2026-07-23 07:23:47'),
(11, NULL, 'HH0LKA1thlIS4P2pKw9H12MPUI9JyR314IuTa6v9', '2026-07-23 07:30:02', '2026-07-23 07:28:28', '2026-07-23 07:30:02'),
(12, NULL, 'taSqGCSfZbViyw5vN3KKpRjtXeBQqwKUR2NzuKF2', '2026-07-23 23:39:28', '2026-07-23 23:37:30', '2026-07-23 23:39:28'),
(13, NULL, 'taSqGCSfZbViyw5vN3KKpRjtXeBQqwKUR2NzuKF2', '2026-07-23 23:55:00', '2026-07-23 23:54:04', '2026-07-23 23:55:00'),
(14, NULL, 'taSqGCSfZbViyw5vN3KKpRjtXeBQqwKUR2NzuKF2', '2026-07-24 00:04:32', '2026-07-24 00:03:42', '2026-07-24 00:04:32'),
(15, NULL, 'taSqGCSfZbViyw5vN3KKpRjtXeBQqwKUR2NzuKF2', '2026-07-24 00:19:07', '2026-07-24 00:17:45', '2026-07-24 00:19:07'),
(17, NULL, 'FCWx4Yyvux5lJhHwRQZm5vERbrlnNLb1zTsGPgwu', NULL, '2026-07-30 02:08:09', '2026-07-30 02:08:09'),
(18, 1, NULL, '2026-07-31 02:55:27', '2026-07-31 02:51:06', '2026-07-31 02:55:27'),
(19, 1, NULL, '2026-07-31 02:57:30', '2026-07-31 02:57:22', '2026-07-31 02:57:30'),
(20, 1, NULL, '2026-07-31 02:58:56', '2026-07-31 02:58:49', '2026-07-31 02:58:56'),
(21, 1, NULL, '2026-08-03 02:40:55', '2026-08-02 01:52:25', '2026-08-03 02:40:55'),
(22, NULL, 'FDDvRYk1rTEgVkr4zFGbeu0XTL3KADCHSwvDE9ze', '2026-08-02 14:44:23', '2026-08-02 14:12:33', '2026-08-02 14:44:23'),
(23, NULL, 'eq3g8poxvWnhen2N0p4l6np2D188bSurkX5t2Hi3', NULL, '2026-08-03 00:01:50', '2026-08-03 00:01:50'),
(24, 1, NULL, '2026-08-03 23:44:01', '2026-08-03 02:52:40', '2026-08-03 23:44:01'),
(25, 1, NULL, '2026-08-04 02:17:23', '2026-08-04 01:37:26', '2026-08-04 02:17:23'),
(27, 1, NULL, '2026-08-04 02:23:02', '2026-08-04 02:22:45', '2026-08-04 02:23:02'),
(28, NULL, 'Y1yS2Hq0DUuDi2hF5j8LoRsMDnEGqh63YkYACgzA', '2026-08-13 01:15:25', '2026-08-13 00:33:52', '2026-08-13 01:15:25'),
(29, 1, NULL, '2026-08-13 11:53:32', '2026-08-13 11:53:24', '2026-08-13 11:53:32'),
(30, 1, NULL, '2026-08-17 00:59:20', '2026-08-17 00:48:50', '2026-08-17 00:59:20'),
(31, NULL, 'YglYMjTzMOvL1NftTWxTuGsOGUkX1gaM3WbEXQ12', '2026-08-17 00:53:52', '2026-08-17 00:52:26', '2026-08-17 00:53:52'),
(33, 1, NULL, NULL, '2026-08-17 21:07:32', '2026-08-17 21:07:32');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `variant_id`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(9, 5, 8, 2, 1500000.00, '2026-07-21 01:55:58', '2026-07-21 03:05:59'),
(10, 6, 7, 1, 1200000.00, '2026-07-22 00:05:56', '2026-07-22 00:05:56'),
(17, 8, 8, 1, 1500.00, '2026-07-23 07:12:38', '2026-07-23 07:12:38'),
(18, 9, 10, 1, 1600.00, '2026-07-23 07:20:39', '2026-07-23 07:20:39'),
(19, 10, 10, 1, 1600.00, '2026-07-23 07:22:23', '2026-07-23 07:22:23'),
(20, 11, 8, 1, 1500.00, '2026-07-23 07:28:28', '2026-07-23 07:28:28'),
(22, 12, 7, 1, 1200.00, '2026-07-23 23:37:30', '2026-07-23 23:37:30'),
(23, 13, 7, 1, 1200.00, '2026-07-23 23:54:04', '2026-07-23 23:54:04'),
(24, 14, 8, 1, 1500.00, '2026-07-24 00:03:42', '2026-07-24 00:03:42'),
(25, 15, 9, 1, 1500.00, '2026-07-24 00:17:45', '2026-07-24 00:17:45'),
(27, 3, 7, 2, 1200.00, '2026-07-30 02:06:03', '2026-07-30 02:50:56'),
(28, 17, 7, 1, 1200.00, '2026-07-30 02:08:09', '2026-07-30 02:08:09'),
(29, 18, 10, 1, 1600.00, '2026-07-31 02:51:06', '2026-07-31 02:51:06'),
(30, 19, 9, 1, 1500.00, '2026-07-31 02:57:22', '2026-07-31 02:57:22'),
(31, 20, 9, 1, 1500.00, '2026-07-31 02:58:50', '2026-07-31 02:58:50'),
(33, 22, 7, 1, 1200.00, '2026-08-02 14:12:33', '2026-08-02 14:12:33'),
(34, 23, 7, 1, 1200.00, '2026-08-03 00:01:50', '2026-08-03 00:01:50'),
(36, 21, 7, 1, 1200.00, '2026-08-03 02:10:53', '2026-08-03 02:10:53'),
(38, 24, 23, 1, 3000.00, '2026-08-03 23:43:08', '2026-08-03 23:43:08'),
(39, 25, 7, 1, 1200.00, '2026-08-04 01:37:26', '2026-08-04 01:37:26'),
(41, 27, 23, 1, 3000.00, '2026-08-04 02:22:45', '2026-08-04 02:22:45'),
(42, 28, 6, 1, 1200.00, '2026-08-13 00:33:52', '2026-08-13 00:33:52'),
(43, 28, 23, 1, 3000.00, '2026-08-13 00:34:14', '2026-08-13 00:34:14'),
(44, 29, 6, 1, 1200.00, '2026-08-13 11:53:24', '2026-08-13 11:53:24');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(2, NULL, 'Dress & Formal', 'dress-formal', 'Refined watches with balanced proportions for considered occasions.', 1, '2026-07-15 20:27:05', '2026-08-16 18:57:58'),
(6, NULL, 'Dive & Sport', 'dive-sport', 'Robust watches designed for water, activity and clear reading at a glance.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(7, NULL, 'Field & Adventure', 'field-adventure', 'Legible, resilient watches influenced by exploration and utility.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(8, NULL, 'Casual & Everyday', 'casual-everyday', 'Versatile watches made to settle naturally into a daily rotation.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(9, NULL, 'Chronographs', 'chronographs', 'Multi-register timing watches with technical character and sporting roots.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58'),
(10, NULL, 'Smart Watches', 'smart-watches', 'Connected watches for training, navigation and everyday health insights.', 1, '2026-08-16 18:57:58', '2026-08-16 18:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `community_comments`
--

CREATE TABLE `community_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `body` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_comments`
--

INSERT INTO `community_comments` (`id`, `post_id`, `user_id`, `parent_id`, `body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, NULL, 'Wow', 'published', '2026-08-04 01:12:41', '2026-08-04 01:12:41', NULL),
(2, 2, 1, NULL, 'Awesome', 'published', '2026-08-04 01:31:36', '2026-08-04 01:31:36', NULL),
(4, 10, 1, NULL, 'rge', 'published', '2026-08-04 02:10:26', '2026-08-04 02:10:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `caption` text DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `visibility` varchar(20) NOT NULL DEFAULT 'public',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `likes_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `comments_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `product_id`, `order_item_id`, `caption`, `location`, `status`, `visibility`, `is_featured`, `likes_count`, `comments_count`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 3, 21, 'OOTD', 'Yangon, Myanmar', 'published', 'public', 0, 1, 1, '2026-08-04 01:11:37', '2026-08-04 01:11:29', '2026-08-04 01:12:41', NULL),
(2, 1, 3, 21, 'sfew', NULL, 'published', 'public', 0, 2, 2, '2026-08-04 01:15:06', '2026-08-04 01:15:00', '2026-08-04 01:33:38', NULL),
(3, 1, 3, 21, 'sf', NULL, 'published', 'public', 0, 0, 0, '2026-08-04 01:16:48', '2026-08-04 01:16:39', '2026-08-04 01:16:48', NULL),
(4, 1, 3, 21, NULL, NULL, 'published', 'public', 0, 0, 0, '2026-08-04 01:54:19', '2026-08-04 01:53:42', '2026-08-04 01:54:19', NULL),
(5, 1, 3, 21, 'gre', NULL, 'published', 'public', 0, 0, 0, '2026-08-04 01:56:27', '2026-08-04 01:56:04', '2026-08-04 01:56:27', NULL),
(6, 1, 3, 21, 'sdfs', NULL, 'published', 'public', 0, 1, 0, '2026-08-04 01:57:56', '2026-08-04 01:57:46', '2026-08-04 02:16:25', NULL),
(7, 1, 3, 21, 'sfgds', NULL, 'published', 'public', 0, 1, 0, '2026-08-04 01:58:27', '2026-08-04 01:58:18', '2026-08-04 02:16:32', NULL),
(8, 1, 3, 21, 'sdf', NULL, 'published', 'public', 0, 0, 0, '2026-08-04 01:59:32', '2026-08-04 01:59:22', '2026-08-04 01:59:32', NULL),
(9, 1, 3, 21, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software like Aldus PageMaker and Microsoft Word including versions of Lorem Ipsum.', NULL, 'published', 'public', 0, 1, 0, '2026-08-04 02:02:27', '2026-08-04 02:00:44', '2026-08-04 02:16:35', NULL),
(10, 1, 3, 21, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets.', NULL, 'published', 'public', 0, 1, 1, '2026-08-04 02:02:36', '2026-08-04 02:02:21', '2026-08-04 02:10:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `community_post_likes`
--

CREATE TABLE `community_post_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_likes`
--

INSERT INTO `community_post_likes` (`id`, `post_id`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 1, 1, '2026-08-04 01:12:09', '2026-08-04 01:12:09'),
(3, 2, 1, '2026-08-04 01:31:28', '2026-08-04 01:31:28'),
(5, 10, 1, '2026-08-04 02:10:30', '2026-08-04 02:10:30'),
(6, 6, 1, '2026-08-04 02:16:25', '2026-08-04 02:16:25'),
(7, 7, 1, '2026-08-04 02:16:32', '2026-08-04 02:16:32'),
(8, 9, 1, '2026-08-04 02:16:35', '2026-08-04 02:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_media`
--

CREATE TABLE `community_post_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `media_type` varchar(20) NOT NULL DEFAULT 'image',
  `file_path` varchar(500) NOT NULL,
  `thumbnail_path` varchar(500) DEFAULT NULL,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_media`
--

INSERT INTO `community_post_media` (`id`, `post_id`, `media_type`, `file_path`, `thumbnail_path`, `width`, `height`, `alt_text`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'image', 'community/Vj2yjGJloxPZQV45BA4iGRsavG5tVSotom81TU45.jpg', NULL, 1080, 1350, NULL, 0, 'published', '2026-08-04 01:11:29', '2026-08-04 01:11:37'),
(2, 2, 'image', 'community/5zoyPUVusuRykchoj7gF7uvfvN4B2e5LQYL6qORg.jpg', NULL, 789, 789, NULL, 0, 'published', '2026-08-04 01:15:00', '2026-08-04 01:15:06'),
(3, 2, 'image', 'community/IAKHFgtsSZPgVpSKbxbefDP0PIz2oRYH9rOY98ap.jpg', NULL, 1080, 1350, NULL, 1, 'published', '2026-08-04 01:15:00', '2026-08-04 01:15:06'),
(4, 3, 'image', 'community/pdFSoLP9W9w32lfQ2q126kIxZYcv9Kz0A2E2X89k.png', NULL, 960, 960, NULL, 0, 'published', '2026-08-04 01:16:39', '2026-08-04 01:16:48'),
(5, 4, 'image', 'community/EAZE3trJgBdm1EpdKJjTh2357NnehqTnEPVz6q6W.png', NULL, 500, 500, NULL, 0, 'published', '2026-08-04 01:53:42', '2026-08-04 01:54:19'),
(6, 5, 'image', 'community/Oj3vyT8jhMM35u2cCHpOLPmkOkpGLbJEUkPoqRFQ.jpg', NULL, 1200, 1200, NULL, 0, 'published', '2026-08-04 01:56:04', '2026-08-04 01:56:27'),
(7, 6, 'image', 'community/25ks05f6h5wDmCevxm358DkbRF88G9yVYYsNcf2S.jpg', NULL, 768, 1150, NULL, 0, 'published', '2026-08-04 01:57:46', '2026-08-04 01:57:56'),
(8, 7, 'image', 'community/eA6p37C7SAFM0rTB3V5o1pRfx01FdIaroNDAXFZU.jpg', NULL, 1045, 1570, NULL, 0, 'published', '2026-08-04 01:58:18', '2026-08-04 01:58:27'),
(9, 8, 'image', 'community/ieaoEEkTQAU4c0hlxoNAofcqD8XQ9wz17dkvKTtj.jpg', NULL, 736, 1104, NULL, 0, 'published', '2026-08-04 01:59:22', '2026-08-04 01:59:32'),
(10, 9, 'image', 'community/nZnH1YyksplXbnnQejrUOpHJHXuXGyCkbT33m6Et.jpg', NULL, 1045, 1570, NULL, 0, 'published', '2026-08-04 02:00:44', '2026-08-04 02:02:27'),
(11, 10, 'image', 'community/OkjmCn2uVHiuW5WCLOnytBtGB80B6BJ9pTaPgjfT.jpg', NULL, 1045, 1570, NULL, 0, 'published', '2026-08-04 02:02:21', '2026-08-04 02:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `community_reports`
--

CREATE TABLE `community_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `reportable_type` varchar(50) NOT NULL,
  `reportable_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_enquiries`
--

CREATE TABLE `contact_enquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(40) NOT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('fixed','percentage') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `minimum_order_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `usage_limit` int(10) UNSIGNED DEFAULT NULL,
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `description`, `discount_type`, `discount_value`, `minimum_order_amount`, `start_date`, `end_date`, `usage_limit`, `used_count`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'KOKU101', '', 'fixed', 50.00, 1000.00, '2026-08-27', '2026-08-28', 10, 0, 1, '2026-08-16 21:28:56', '2026-08-16 21:28:56'),
(4, 'TEST101', '', 'fixed', 100.00, 1000.00, '2026-08-21', '2026-08-22', 10, 0, 1, '2026-08-18 21:29:24', '2026-08-18 21:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, '1cb8907a-6b14-425d-a8cf-e00547fa0c19', 'database', 'default', '{\"uuid\":\"1cb8907a-6b14-425d-a8cf-e00547fa0c19\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:21;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"items\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:25:\\\"phyoheinnaung29@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1785490168,\"delay\":null}', 'InvalidArgumentException: No hint path defined for [mail]. in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(89): Illuminate\\View\\FileViewFinder->parseNamespaceSegments(\'mail::message\')\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(75): Illuminate\\View\\FileViewFinder->findNamespacedView(\'mail::message\')\n#2 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(150): Illuminate\\View\\FileViewFinder->find(\'mail::message\')\n#3 D:\\ticks\\storage\\framework\\views\\1e40e04fbe4eff9562c7bc0b334fc761.php(3): Illuminate\\View\\Factory->make(\'mail::message\')\n#4 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(123): require(\'D:\\\\ticks\\\\storag...\')\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(57): Illuminate\\Filesystem\\Filesystem->getRequire(\'D:\\\\ticks\\\\storag...\', Array)\n#7 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#9 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#41 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#43 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#44 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#45 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#46 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#48 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#52 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#53 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#54 {main}\n\nNext Illuminate\\View\\ViewException: No hint path defined for [mail]. (View: D:\\ticks\\resources\\views\\emails\\order-confirmation.blade.php) in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(58): Illuminate\\View\\Engines\\CompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(59): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#2 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#3 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#4 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#7 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#9 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#49 {main}', '2026-07-31 02:59:29'),
(2, '1f2fd6fb-3462-43a0-b1b0-438417e2551c', 'database', 'default', '{\"uuid\":\"1f2fd6fb-3462-43a0-b1b0-438417e2551c\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:24;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"items\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:25:\\\"phyoheinnaung29@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1785824104,\"delay\":null}', 'InvalidArgumentException: No hint path defined for [mail]. in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(89): Illuminate\\View\\FileViewFinder->parseNamespaceSegments(\'mail::message\')\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(75): Illuminate\\View\\FileViewFinder->findNamespacedView(\'mail::message\')\n#2 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(150): Illuminate\\View\\FileViewFinder->find(\'mail::message\')\n#3 D:\\ticks\\storage\\framework\\views\\1e40e04fbe4eff9562c7bc0b334fc761.php(3): Illuminate\\View\\Factory->make(\'mail::message\')\n#4 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(123): require(\'D:\\\\ticks\\\\storag...\')\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(57): Illuminate\\Filesystem\\Filesystem->getRequire(\'D:\\\\ticks\\\\storag...\', Array)\n#7 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#9 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#41 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#43 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#44 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#45 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#46 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#48 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#52 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#53 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#54 {main}\n\nNext Illuminate\\View\\ViewException: No hint path defined for [mail]. (View: D:\\ticks\\resources\\views\\emails\\order-confirmation.blade.php) in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(58): Illuminate\\View\\Engines\\CompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(59): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#2 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#3 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#4 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#7 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#9 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#49 {main}', '2026-08-03 23:45:06');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(3, '7b062406-f8b2-453f-a81e-340905e5a963', 'database', 'default', '{\"uuid\":\"7b062406-f8b2-453f-a81e-340905e5a963\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"items\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:26:\\\"simplelifebyphyo@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1785832577,\"delay\":null}', 'InvalidArgumentException: No hint path defined for [mail]. in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(89): Illuminate\\View\\FileViewFinder->parseNamespaceSegments(\'mail::message\')\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(75): Illuminate\\View\\FileViewFinder->findNamespacedView(\'mail::message\')\n#2 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(150): Illuminate\\View\\FileViewFinder->find(\'mail::message\')\n#3 D:\\ticks\\storage\\framework\\views\\1e40e04fbe4eff9562c7bc0b334fc761.php(3): Illuminate\\View\\Factory->make(\'mail::message\')\n#4 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(123): require(\'D:\\\\ticks\\\\storag...\')\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(57): Illuminate\\Filesystem\\Filesystem->getRequire(\'D:\\\\ticks\\\\storag...\', Array)\n#7 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#9 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#41 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#43 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#44 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#45 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#46 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#48 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#52 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#53 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#54 {main}\n\nNext Illuminate\\View\\ViewException: No hint path defined for [mail]. (View: D:\\ticks\\resources\\views\\emails\\order-confirmation.blade.php) in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(58): Illuminate\\View\\Engines\\CompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(59): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#2 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#3 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#4 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#7 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#9 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#49 {main}', '2026-08-04 02:06:17'),
(4, 'b48831c7-a10a-4b23-934e-3b00e5e78b7f', 'database', 'default', '{\"uuid\":\"b48831c7-a10a-4b23-934e-3b00e5e78b7f\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:26;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"items\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:25:\\\"phyoheinnaung29@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1785833300,\"delay\":null}', 'InvalidArgumentException: No hint path defined for [mail]. in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(89): Illuminate\\View\\FileViewFinder->parseNamespaceSegments(\'mail::message\')\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(75): Illuminate\\View\\FileViewFinder->findNamespacedView(\'mail::message\')\n#2 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(150): Illuminate\\View\\FileViewFinder->find(\'mail::message\')\n#3 D:\\ticks\\storage\\framework\\views\\1e40e04fbe4eff9562c7bc0b334fc761.php(3): Illuminate\\View\\Factory->make(\'mail::message\')\n#4 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(123): require(\'D:\\\\ticks\\\\storag...\')\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(57): Illuminate\\Filesystem\\Filesystem->getRequire(\'D:\\\\ticks\\\\storag...\', Array)\n#7 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#9 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#41 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#43 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#44 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#45 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#46 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#48 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#52 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#53 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#54 {main}\n\nNext Illuminate\\View\\ViewException: No hint path defined for [mail]. (View: D:\\ticks\\resources\\views\\emails\\order-confirmation.blade.php) in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(58): Illuminate\\View\\Engines\\CompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(59): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#2 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#3 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#4 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#7 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#9 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#49 {main}', '2026-08-04 02:18:20');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(5, 'c839fe11-1407-42ac-a7c9-18d31a8a3551', 'database', 'default', '{\"uuid\":\"c839fe11-1407-42ac-a7c9-18d31a8a3551\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmationMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:30:\\\"App\\\\Mail\\\\OrderConfirmationMail\\\":3:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:27;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"items\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:25:\\\"phyoheinnaung29@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1785833602,\"delay\":null}', 'InvalidArgumentException: No hint path defined for [mail]. in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(89): Illuminate\\View\\FileViewFinder->parseNamespaceSegments(\'mail::message\')\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php(75): Illuminate\\View\\FileViewFinder->findNamespacedView(\'mail::message\')\n#2 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Factory.php(150): Illuminate\\View\\FileViewFinder->find(\'mail::message\')\n#3 D:\\ticks\\storage\\framework\\views\\1e40e04fbe4eff9562c7bc0b334fc761.php(3): Illuminate\\View\\Factory->make(\'mail::message\')\n#4 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(123): require(\'D:\\\\ticks\\\\storag...\')\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Filesystem\\Filesystem.php(124): Illuminate\\Filesystem\\Filesystem::Illuminate\\Filesystem\\{closure}()\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(57): Illuminate\\Filesystem\\Filesystem->getRequire(\'D:\\\\ticks\\\\storag...\', Array)\n#7 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#9 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#41 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#43 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#44 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#45 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#46 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#48 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#52 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#53 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#54 {main}\n\nNext Illuminate\\View\\ViewException: No hint path defined for [mail]. (View: D:\\ticks\\resources\\views\\emails\\order-confirmation.blade.php) in D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\FileViewFinder.php:111\nStack trace:\n#0 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(58): Illuminate\\View\\Engines\\CompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#1 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\PhpEngine.php(59): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->handleViewException(Object(InvalidArgumentException), 0)\n#2 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(22): Illuminate\\View\\Engines\\PhpEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#3 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\Engines\\CompilerEngine.php(76): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->evaluatePath(\'D:\\\\ticks\\\\storag...\', Array)\n#4 D:\\ticks\\vendor\\livewire\\livewire\\src\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine.php(10): Illuminate\\View\\Engines\\CompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#5 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(208): Livewire\\Mechanisms\\ExtendBlade\\ExtendedCompilerEngine->get(\'D:\\\\ticks\\\\resour...\', Array)\n#6 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(191): Illuminate\\View\\View->getContents()\n#7 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\View\\View.php(160): Illuminate\\View\\View->renderContents()\n#8 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(444): Illuminate\\View\\View->render()\n#9 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(419): Illuminate\\Mail\\Mailer->renderView(\'emails.order-co...\', Array)\n#10 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(312): Illuminate\\Mail\\Mailer->addContent(Object(Illuminate\\Mail\\Message), \'emails.order-co...\', NULL, NULL, Array)\n#11 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(207): Illuminate\\Mail\\Mailer->send(\'emails.order-co...\', Array, Object(Closure))\n#12 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Mail\\Mailable->Illuminate\\Mail\\{closure}()\n#13 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailable.php(200): Illuminate\\Mail\\Mailable->withLocale(NULL, Object(Closure))\n#14 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\SendQueuedMailable.php(82): Illuminate\\Mail\\Mailable->send(Object(Illuminate\\Mail\\MailManager))\n#15 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Mail\\SendQueuedMailable->handle(Object(Illuminate\\Mail\\MailManager))\n#16 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(129): Illuminate\\Container\\Container->call(Array)\n#21 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#22 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#23 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(136): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Mail\\SendQueuedMailable), false)\n#25 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#26 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Mail\\SendQueuedMailable))\n#27 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(129): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Mail\\SendQueuedMailable))\n#29 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(504): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(454): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(212): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 D:\\ticks\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 D:\\ticks\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\ticks\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\ticks\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 D:\\ticks\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 D:\\ticks\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#49 {main}', '2026-08-04 02:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_13_010412_create_brands_table', 1),
(5, '2026_07_13_012754_create_categories_table', 1),
(6, '2026_07_13_020457_create_products_table', 1),
(7, '2026_07_13_020507_create_product_specifications_table', 1),
(8, '2026_07_13_021932_create_product_variants_table', 1),
(9, '2026_07_13_021933_create_product_images_table', 1),
(10, '2026_07_16_074052_create_wishlist_items_table', 2),
(11, '2026_07_17_065801_create_carts_table', 3),
(12, '2026_07_17_065834_create_cart_items_table', 3),
(13, '2026_07_22_061314_create_shipping_zones_table', 4),
(14, '2026_07_22_061330_create_shipping_locations_table', 4),
(15, '2026_07_22_061438_create_coupons_table', 4),
(16, '2026_07_22_061512_create_addresses_table', 4),
(17, '2026_07_22_061545_create_orders_table', 4),
(18, '2026_07_22_061558_create_order_items_table', 4),
(19, '2026_07_22_061616_create_payments_table', 4),
(20, '2026_07_23_073319_add_email_to_orders_table', 5),
(21, '2026_07_27_000001_change_products_is_active_default_to_false', 6),
(22, '2026_07_28_000001_add_watch_type_to_products_table', 7),
(23, '2026_07_28_000002_create_product_variant_specifications_table', 7),
(24, '2026_07_30_073455_create_store_settings_table', 8),
(25, '2026_08_04_000001_create_reviews_table', 9),
(26, '2026_08_04_000002_change_review_status_defaults_to_pending', 10),
(27, '2026_08_04_000003_create_community_gallery_tables', 11),
(28, '2026_08_14_000001_create_contact_enquiries_table', 12),
(29, '2026_08_14_000002_add_tracking_fields_to_orders_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `shipping_location_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `shipping_full_name` varchar(255) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `shipping_country` varchar(255) NOT NULL,
  `shipping_state_region` varchar(255) DEFAULT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `shipping_district_area` varchar(255) DEFAULT NULL,
  `shipping_postal_code` varchar(20) DEFAULT NULL,
  `shipping_address_line1` varchar(255) NOT NULL,
  `shipping_address_line2` varchar(255) DEFAULT NULL,
  `billing_full_name` varchar(255) NOT NULL,
  `billing_phone` varchar(20) NOT NULL,
  `billing_country` varchar(255) NOT NULL,
  `billing_state_region` varchar(255) DEFAULT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_district_area` varchar(255) DEFAULT NULL,
  `billing_postal_code` varchar(20) DEFAULT NULL,
  `billing_address_line1` varchar(255) NOT NULL,
  `billing_address_line2` varchar(255) DEFAULT NULL,
  `shipping_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `billing_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `insurance_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `carrier` varchar(100) DEFAULT NULL,
  `tracking_number` varchar(120) DEFAULT NULL,
  `tracking_url` varchar(500) DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `estimated_delivery_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `coupon_id`, `shipping_location_id`, `order_number`, `email`, `shipping_full_name`, `shipping_phone`, `shipping_country`, `shipping_state_region`, `shipping_city`, `shipping_district_area`, `shipping_postal_code`, `shipping_address_line1`, `shipping_address_line2`, `billing_full_name`, `billing_phone`, `billing_country`, `billing_state_region`, `billing_city`, `billing_district_area`, `billing_postal_code`, `billing_address_line1`, `billing_address_line2`, `shipping_address_id`, `billing_address_id`, `subtotal`, `discount`, `tax`, `shipping_fee`, `insurance_fee`, `total`, `status`, `carrier`, `tracking_number`, `tracking_url`, `shipped_at`, `estimated_delivery_at`, `delivered_at`, `stripe_payment_intent_id`, `notes`, `created_at`, `updated_at`) VALUES
(21, 1, NULL, 1, 'TCK-1TL0BRA1', 'phyoheinnaung29@gmail.com', 'Phyo', '099492938', 'Myanmar', 'Yangon', 'Bahan', 'Shwgondaing', '2942', 'Room 212, Building 1, Diamond Condo', NULL, 'Phyo', '099492938', 'Myanmar', 'Yangon', 'Bahan', 'Shwgondaing', '2942', 'Room 212, Building 1, Diamond Condo', NULL, 1, NULL, 1500.00, 0.00, 0.00, 2.00, 0.00, 1502.00, 'delivered', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3TzCkPKAJwxJWsNi16pPzGwJ', NULL, '2026-07-31 02:58:55', '2026-08-03 20:56:28'),
(22, NULL, NULL, 14, 'TCK-O20ZFGZA', 'phein054@gmail.com', 'phyo heinnaung', '021949', 'Myanmar', 'Shan', 'sfdds', 'sd', NULL, 'R69P+FGJsfd', 'sfs', 'phyo heinnaung', '021949', 'Myanmar', 'Shan', 'sfdds', 'sd', NULL, 'R69P+FGJsfd', 'sfs', NULL, NULL, 1200.00, 0.00, 0.00, 7.00, 24.00, 1231.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3U06hzKAJwxJWsNi0CmgImqi', NULL, '2026-08-02 14:44:07', '2026-08-02 14:44:23'),
(23, 1, NULL, 1, 'TCK-T1PT0PBZ', 'phyoheinnaung29@gmail.com', 'Phyo', '099492938', 'Myanmar', 'Yangon', 'Bahan', 'Shwgondaing', '2942', 'Room 212, Building 1, Diamond Condo', NULL, 'Phyo', '099492938', 'Myanmar', 'Yangon', 'Bahan', 'Shwgondaing', '2942', 'Room 212, Building 1, Diamond Condo', NULL, 1, NULL, 1200.00, 0.00, 0.00, 2.00, 0.00, 1202.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3U0HtTKAJwxJWsNi1ZIPMHyQ', NULL, '2026-08-03 02:40:42', '2026-08-03 02:40:55'),
(24, 1, NULL, 1, 'TCK-YLRSK5F7', 'phyoheinnaung29@gmail.com', 'Phyo Hein Naung', '09421073207', 'Myanmar', 'Yangon', 'Thaketa', '10/North Ward ', NULL, '480/B, Tharlarwaddy Street', NULL, 'Phyo Hein Naung', '09421073207', 'Myanmar', 'Yangon', 'Thaketa', '10/North Ward ', NULL, '480/B, Tharlarwaddy Street', NULL, 2, NULL, 3000.00, 0.00, 0.00, 2.00, 60.00, 3062.00, 'delivered', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3U0bblKAJwxJWsNi1Gho2Knp', NULL, '2026-08-03 23:43:46', '2026-08-04 09:21:19'),
(25, NULL, NULL, 7, 'TCK-WV6BIWKE', 'simplelifebyphyo@gmail.com', 'Naung', '09967673183', 'Myanmar', 'Naypyidaw', 'NPW', '10 Ward', '11321', 'Nine Street, ', NULL, 'Naung', '09967673183', 'Myanmar', 'Naypyidaw', 'NPW', '10 Ward', '11321', 'Nine Street, ', NULL, NULL, NULL, 3000.00, 0.00, 0.00, 4.00, 60.00, 3064.00, 'delivered', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3U0dpRKAJwxJWsNi0jC3R8CR', NULL, '2026-08-04 02:06:03', '2026-08-04 02:08:28'),
(26, 1, NULL, 1, 'TCK-VYQGSCQI', 'phyoheinnaung29@gmail.com', 'Phyo Hein Naung', '09421073207', 'Myanmar', 'Yangon', 'Thaketa', '10/North Ward ', NULL, '480/B, Tharlarwaddy Street', NULL, 'Phyo Hein Naung', '09421073207', 'Myanmar', 'Yangon', 'Thaketa', '10/North Ward ', NULL, '480/B, Tharlarwaddy Street', NULL, 2, NULL, 1200.00, 0.00, 0.00, 2.00, 0.00, 1202.00, 'delivered', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3U0e0NKAJwxJWsNi0ZYp8dXP', NULL, '2026-08-04 02:17:21', '2026-08-04 02:18:51'),
(27, 1, NULL, 1, 'TCK-2DR1TJ3B', 'phyoheinnaung29@gmail.com', 'Phyo', '099492938', 'Myanmar', 'Yangon', 'Bahan', 'Shwgondaing', '2942', 'Room 212, Building 1, Diamond Condo', NULL, 'Phyo', '099492938', 'Myanmar', 'Yangon', 'Bahan', 'Shwgondaing', '2942', 'Room 212, Building 1, Diamond Condo', NULL, 1, NULL, 3000.00, 0.00, 0.00, 2.00, 60.00, 3062.00, 'delivered', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3U0e5rKAJwxJWsNi1gx5sEo6', NULL, '2026-08-04 02:23:01', '2026-08-04 09:21:19'),
(28, NULL, NULL, 1, 'TCK-TPEROKMS', 'phein054@gmail.com', 'phyo heinnaung', '09123456789', 'Myanmar', 'Yangon', 'Bahan', 'Bahan 2', '12334', 'Room 1, Builing 15, Myittar St', NULL, 'phyo heinnaung', '09123456789', 'Myanmar', 'Yangon', 'Bahan', 'Bahan 2', '12334', 'Room 1, Builing 15, Myittar St', NULL, NULL, NULL, 4200.00, 0.00, 0.00, 2.00, 84.00, 4286.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, 'pi_3U3tKAKAJwxJWsNi1TIsPPiw', NULL, '2026-08-13 01:15:11', '2026-08-13 01:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `variant_sku` varchar(100) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `variant_name` varchar(100) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `variant_id`, `variant_sku`, `product_name`, `variant_name`, `unit_price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(21, 21, 9, 'LG-MA-2204-WS', 'Longies Master ', 'White Dial / Steel Strap', 1500.00, 1, 1500.00, '2026-07-31 02:58:55', '2026-07-31 02:58:55'),
(22, 22, 7, 'LG-MA-2202-WL', 'Longies Master ', 'White Dial / Leather Strap ', 1200.00, 1, 1200.00, '2026-08-02 14:44:07', '2026-08-02 14:44:07'),
(23, 23, 7, 'LG-MA-2202-WL', 'Longies Master ', 'White Dial / Leather Strap ', 1200.00, 1, 1200.00, '2026-08-03 02:40:42', '2026-08-03 02:40:42'),
(24, 24, 23, 'LG-SP-BL-001', 'Longines Spirit ', 'Blue Dial', 3000.00, 1, 3000.00, '2026-08-03 23:43:46', '2026-08-03 23:43:46'),
(25, 25, 23, 'LG-SP-BL-001', 'Longines Spirit ', 'Blue Dial', 3000.00, 1, 3000.00, '2026-08-04 02:06:03', '2026-08-04 02:06:03'),
(26, 26, 7, 'LG-MA-2202-WL', 'Longies Master ', 'White Dial / Leather Strap ', 1200.00, 1, 1200.00, '2026-08-04 02:17:21', '2026-08-04 02:17:21'),
(27, 27, 23, 'LG-SP-BL-001', 'Longines Spirit ', 'Blue Dial', 3000.00, 1, 3000.00, '2026-08-04 02:23:01', '2026-08-04 02:23:01'),
(28, 28, 6, 'LG-MA-2201-BL', 'Longies Master ', 'Blue Dial / Leather Strap', 1200.00, 1, 1200.00, '2026-08-13 01:15:11', '2026-08-13 01:15:11'),
(29, 28, 23, 'LG-SP-BL-001', 'Longines Spirit ', 'Blue Dial', 3000.00, 1, 3000.00, '2026-08-13 01:15:11', '2026-08-13 01:15:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('name@gmail.com', '$2y$12$ykzhOqB7fqrldY3erJkQPuebdb14H2n6UoR9jowRQC53vww5.XGH6', '2026-08-17 00:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `method` enum('kbzpay','wavepay','bank_transfer','card') NOT NULL,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `method`, `status`, `transaction_id`, `amount`, `paid_at`, `created_at`, `updated_at`) VALUES
(9, 21, 'card', 'paid', 'pi_3TzCkPKAJwxJWsNi16pPzGwJ', 1502.00, '2026-07-31 02:59:22', '2026-07-31 02:59:22', '2026-07-31 02:59:22'),
(10, 24, 'card', 'paid', 'pi_3U0bblKAJwxJWsNi1Gho2Knp', 3062.00, '2026-08-03 23:44:58', '2026-08-03 23:44:58', '2026-08-03 23:44:58'),
(11, 25, 'card', 'paid', 'pi_3U0dpRKAJwxJWsNi0jC3R8CR', 3064.00, '2026-08-04 02:06:16', '2026-08-04 02:06:16', '2026-08-04 02:06:16'),
(12, 26, 'card', 'paid', 'pi_3U0e0NKAJwxJWsNi0ZYp8dXP', 1202.00, '2026-08-04 02:18:19', '2026-08-04 02:18:19', '2026-08-04 02:18:19'),
(13, 27, 'card', 'paid', 'pi_3U0e5rKAJwxJWsNi1gx5sEo6', 3062.00, '2026-08-04 02:23:19', '2026-08-04 02:23:19', '2026-08-04 02:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `gender` enum('men','women','unisex') NOT NULL,
  `watch_type` varchar(255) NOT NULL DEFAULT 'traditional',
  `movement` enum('automatic','quartz','mechanical','chronograph','smart') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand_id`, `category_id`, `name`, `slug`, `description`, `gender`, `watch_type`, `movement`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(3, 3, 2, 'Longies Master ', 'longies-master', 'Date, self-winding mechanical movement beating at 25\'200 vibrations per hour, with a monocrystalline silicon balance-spring power reserve up to 72 hours.\n\nWater-resistant to 3 bar, scratch-resistant sapphire crystal, with several layers of anti-reflective coating on both sides.', 'men', 'traditional', 'automatic', 1, 1, '2026-07-15 20:39:52', '2026-08-17 08:54:54'),
(7, 3, 2, 'Longines Spirit ', 'longines-spirit', 'Lorem', 'men', 'traditional', 'automatic', 1, 0, '2026-08-03 20:27:06', '2026-08-17 11:28:40'),
(78, 4, 2, 'Casio Tank', 'casio-tank', 'Timeless simplicity in a refined rectangular form.\n\nThe Casio LTP-V007L combines classic styling with an understated, elegant design. Its slim rectangular case, clean analog display and leather strap create a vintage-inspired look that transitions effortlessly from everyday wear to more formal occasions.\n\nPowered by a reliable quartz movement and protected by mineral glass, the LTP-V007L offers dependable timekeeping in a lightweight and comfortable design. Simple proportions and minimal detailing give the watch a distinctive character without unnecessary complexity.\n\nDesigned for those who appreciate quiet elegance, the LTP-V007L is a versatile expression of Casio\'s practical and timeless approach to watchmaking.', 'women', 'traditional', 'quartz', 1, 1, '2026-08-17 08:38:43', '2026-08-17 09:03:21'),
(79, 4, 8, 'Casio AQ-230', 'casio-aq-230', 'The Casio AQ-230 brings together retro styling and everyday functionality in a compact, distinctive design. Its rectangular case combines a classic analog dial with a digital display, creating the recognizable dual-display look that gives the AQ-230 its vintage character.\n\nDesigned for practical everyday wear, the watch offers useful functions including a daily alarm, stopwatch, calendar and dual time. A stainless-steel band complements its slim profile, while reliable quartz timekeeping makes it an easy and versatile choice for daily use.\n\nWith its clean proportions and unmistakable retro aesthetic, the AQ-230 blends classic Casio design with practical digital functionality.', 'unisex', 'traditional', 'quartz', 1, 0, '2026-08-17 09:39:59', '2026-08-17 09:46:38'),
(80, 6, 8, 'Citizen TSUYOSA', 'citizen-tsuyosa', 'The Citizen TSUYOSA brings contemporary sports styling together with the character of traditional mechanical watchmaking. Its 40 mm stainless-steel case flows into an integrated bracelet, creating a clean and modern profile designed for versatile everyday wear.\n\nBeneath the sapphire crystal, a minimalist three-hand dial features a date display at 3 o\'clock. The watch is powered by Citizen\'s automatic Caliber 8210, offering approximately 42 hours of power reserve along with a hacking function for precise time setting.\n\nWith 50 metres of water resistance, a comfortable stainless-steel bracelet and a transparent case back revealing the mechanical movement, the TSUYOSA balances distinctive design with practical everyday performance.', 'men', 'traditional', 'automatic', 1, 1, '2026-08-17 10:03:31', '2026-08-17 11:40:23'),
(81, 6, 8, 'Citizen Zenshin Three-Hand', 'citizen-zenshin-three-hand', 'The Citizen Zenshin Three-Hand combines modern Japanese design with lightweight, durable construction. Crafted from Citizen\'s Super Titanium™, its integrated case and bracelet create a streamlined contemporary profile while providing enhanced resistance to scratches and everyday wear.\n\nPowered by Citizen\'s Eco-Drive technology, the watch converts light into energy, eliminating the need for routine battery replacement. The textured dial features luminous hands and markers alongside a practical day-and-date display at 3 o\'clock.\n\nA sapphire crystal and 100 metres of water resistance complete the design, making the Zenshin Three-Hand a versatile everyday watch that balances refined styling with practical performance.', 'men', 'traditional', 'quartz', 1, 0, '2026-08-17 10:19:24', '2026-08-17 10:27:53'),
(82, 9, 9, 'Khaki Aviation Pilot Pioneer Mechanical Chrono', 'khaki-aviation-pilot-pioneer-mechanical-chrono', 'Inspired by Hamilton\'s military aviation heritage, the Khaki Aviation Pilot Pioneer Mechanical Chrono combines vintage pilot-watch character with modern mechanical performance. Its distinctive 40 mm stainless-steel case takes inspiration from chronographs Hamilton produced for British Royal Air Force pilots in the 1970s.\n\nAt its heart is Hamilton\'s hand-wound H-51-Si mechanical chronograph movement, equipped with a silicon balance spring and an extended 60-hour power reserve. The chronograph layout and tactile pushers provide traditional mechanical interaction while preserving the purposeful character of the original aviation instruments.\n\nProtected by sapphire crystal and offering 100 metres of water resistance, the Pilot Pioneer Mechanical Chrono balances historical design with the durability required for modern everyday wear.', 'men', 'traditional', 'chronograph', 1, 0, '2026-08-17 10:35:24', '2026-08-17 10:49:19'),
(83, 9, 2, 'Hamilton Jazzmaster Open Heart Auto', 'hamilton-jazzmaster-open-heart-auto', 'The Hamilton Jazzmaster Open Heart Auto 40 mm combines classic Swiss watchmaking with a contemporary open-dial design. Carefully positioned cut-outs reveal the automatic movement beneath the dial, bringing the mechanics of the watch into view while maintaining the refined character of the Jazzmaster collection.\n\nPowered by Hamilton\'s H-10 automatic caliber, the watch provides an impressive 80-hour power reserve and features a Nivachron™ balance spring for improved resistance to magnetic fields. A 40 mm stainless-steel case and sapphire crystal provide a durable foundation for its sophisticated design.\n\nWith its combination of traditional craftsmanship, visible mechanics and elegant proportions, the Jazzmaster Open Heart is equally suited to formal occasions and refined everyday wear.', 'men', 'traditional', 'automatic', 1, 1, '2026-08-17 10:52:54', '2026-08-17 11:12:31'),
(84, 8, 6, 'Tissot Seastar 1000 ', 'tissot-seastar-1000', 'The Tissot Seastar 1000 40 mm combines Swiss automatic watchmaking with the robust performance expected from a modern dive watch. Its compact 40 mm case offers a versatile profile while retaining the bold, functional character of the Seastar collection.\n\nPowered by an automatic movement with up to 80 hours of power reserve, the Seastar is designed for dependable performance both in and out of the water. A screw-down crown and case back contribute to an impressive 300 metres of water resistance, while the unidirectional rotating bezel and Super-LumiNova® details support visibility and functionality in demanding conditions.\n\nProtected by scratch-resistant sapphire crystal with an anti-reflective coating, the Seastar 1000 balances durability, contemporary styling and everyday versatility in a distinctly sporty Swiss timepiece.', 'men', 'traditional', 'automatic', 1, 0, '2026-08-17 11:18:15', '2026-08-17 11:27:41'),
(85, 8, 8, 'Tissot PRX Powermatic 80', 'tissot-prx-powermatic-80', 'The Tissot PRX Powermatic 80 40 mm combines the distinctive character of 1970s watch design with modern Swiss automatic performance. Its slim tonneau-shaped case and integrated stainless-steel bracelet create a clean, streamlined profile that gives the PRX its instantly recognizable appearance.\n\nInside, the Swiss automatic Powermatic 80 movement delivers up to 80 hours of power reserve and incorporates Tissot\'s Nivachron™ balance spring for improved resistance to magnetic fields. A sapphire crystal with anti-reflective coating protects the dial, while the transparent case back provides a view of the automatic movement.\n\nWith 100 metres of water resistance, an interchangeable bracelet and a versatile 40 mm case, the PRX Powermatic 80 balances contemporary everyday wear with the heritage-inspired styling of Tissot\'s original 1978 design.', 'men', 'traditional', 'automatic', 1, 0, '2026-08-17 11:31:23', '2026-08-17 11:37:32'),
(86, 5, 2, 'Seiko Presage Cocktail Time', 'seiko-presage-cocktail-time', 'The Seiko Presage Cocktail Time combines traditional mechanical watchmaking with an elegant design inspired by the atmosphere and artistry of cocktail bars. Its distinctive dial features a rich, reflective finish that changes character with the light, complemented by slender markers and delicately curved hands.\n\nPowered by Seiko\'s automatic Caliber 4R35, the watch offers approximately 41 hours of power reserve along with manual winding and stop-seconds functionality. A box-shaped Hardlex crystal enhances its classic appearance, while the see-through case back provides a view of the mechanical movement within.\n\nRefined proportions and expressive dial finishing give the Presage Cocktail Time a sophisticated character suited to formal occasions as well as elevated everyday wear. Seiko specifically describes the Cocktail Time collection as featuring vibrant, glossy dials inspired by colorful cocktails.', 'men', 'traditional', 'automatic', 1, 1, '2026-08-17 21:17:12', '2026-08-17 21:24:39'),
(87, 5, 6, 'Seiko 5 Sports SKX Series', 'seiko-5-sports-skx-series', 'The Seiko 5 Sports SKX Series combines the recognizable styling of Seiko\'s much-loved SKX design with the everyday versatility of the Seiko 5 Sports collection. Its bold 42.5 mm stainless-steel case, rotating bezel and highly legible dial create a distinctive sports-watch profile suited to casual and active wear.\n\nInside, Seiko\'s automatic Caliber 4R36 provides approximately 41 hours of power reserve and supports both manual winding and stop-seconds functionality. A practical day-and-date display adds everyday convenience, while LumiBrite on the hands and markers improves visibility in low-light conditions.\n\nWith a Hardlex crystal, 100 metres of water resistance and a see-through screw case back, the Seiko 5 Sports SKX Series combines mechanical character, durability and distinctive styling in an accessible automatic watch.', 'men', 'traditional', 'automatic', 1, 1, '2026-08-17 21:32:25', '2026-08-17 21:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `variant_id`, `image_url`, `alt_text`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
(23, 6, 'products/ywyI35bzukLbZHN6I1F6BCTDw7dm3pH0hhgKRWBZ.avif', NULL, 1, 1, '2026-07-15 21:24:26', '2026-07-16 00:10:59'),
(24, 7, 'products/56UpbESyvYxqtxEi5JEdaYNAcQSX7aPVKkMO7Scu.avif', NULL, 0, 5, '2026-07-15 21:26:46', '2026-07-27 03:31:56'),
(25, 7, 'products/2AljZy7kKF3AcBNvBsTZUrIlaMrsWSjtarUgn4Pj.avif', NULL, 0, 4, '2026-07-15 21:26:46', '2026-07-27 03:31:56'),
(26, 7, 'products/N9LLGCQ4iNWOW4TTuBdOQoQEyj284oWH0MNPo26M.avif', NULL, 0, 3, '2026-07-15 21:26:46', '2026-07-27 03:31:56'),
(27, 7, 'products/w0n57g3T5r42f3Pqsv94U2ZsXUkQNXvnlUiXKzVw.webp', NULL, 0, 2, '2026-07-15 21:26:46', '2026-07-27 03:31:56'),
(28, 7, 'products/UiXVylBc3GQeSWd68z8NdQk6hOYBxgG0G8SUU4iR.webp', NULL, 0, 1, '2026-07-15 21:26:46', '2026-07-27 03:31:56'),
(29, 7, 'products/7poDPPyyTxNk0ZrrtpIRgujyJMAyVpGFjzaVu0CK.avif', NULL, 1, 0, '2026-07-15 21:26:46', '2026-07-27 03:31:56'),
(30, 6, 'products/dD5FtVcX3NAPxJ6tnXjFtxiGnDTRbtTiGKCRHOt2.png', NULL, 0, 7, '2026-07-16 00:01:37', '2026-07-16 00:10:59'),
(31, 6, 'products/oEi1DIW9FX7ANozAXTi8AJwQbBuchLE3qSOz9fEU.png', NULL, 0, 8, '2026-07-16 00:03:20', '2026-07-16 00:10:59'),
(32, 6, 'products/Y7OJFs1PlckogCCgQQ5aZQYC8UqeZFD97kIKuwyT.png', NULL, 0, 9, '2026-07-16 00:05:26', '2026-07-16 00:10:59'),
(33, 6, 'products/hpSW8wctmcE6utanvu1xORfUB2JKt1Ms1iVdlHW3.png', NULL, 0, 4, '2026-07-16 00:05:26', '2026-07-16 00:10:59'),
(34, 6, 'products/Sp4yl13upVrBJTpoJX4Ww92rzWKnwpzqGKH4pay7.png', NULL, 0, 10, '2026-07-16 00:07:31', '2026-07-16 00:10:59'),
(36, 8, 'products/dFsuWy7ff9xw2NHCL5JiKggZ5MgeGdHk91ppzg8m.avif', NULL, 1, 0, '2026-07-16 00:26:44', '2026-07-16 00:26:44'),
(37, 9, 'products/aCEYldTX4Tzlq3lsoUZCNttRLJRP1XHj8DIRKbUi.avif', NULL, 1, 0, '2026-07-16 00:26:53', '2026-07-16 00:26:53'),
(38, 10, 'products/XgKj4o5Thny4DFueBWbOj9P9n7UTbTJYhSt3r0G7.avif', NULL, 1, 0, '2026-07-16 00:29:00', '2026-07-16 00:29:00'),
(46, 23, 'products/qCXe6RJrA1GON2bEwrV8mxYz63QNLIlontQasUIc.avif', NULL, 1, 0, '2026-08-03 20:28:12', '2026-08-03 20:28:12'),
(375, 166, 'products/K5FNkItpkcS0ckD11vVwrzRyIIEK2rLDKIrn6Wly.jpg', NULL, 0, 1, '2026-08-17 08:59:22', '2026-08-17 09:21:09'),
(377, 167, 'products/232uCOtZROvAg010MdSqDiD9KD1r6ton6CHpzcVc.jpg', NULL, 0, 1, '2026-08-17 09:06:32', '2026-08-17 09:06:57'),
(378, 167, 'products/wh35Q4orqwKCYIotkfNzQ2LDsmBRdv3gXR9dbigs.png', NULL, 1, 0, '2026-08-17 09:06:49', '2026-08-17 09:06:57'),
(379, 166, 'products/IlZPPkz8kiYnfFOnNdI3fMH0aYBilSR3Mrgpm87E.png', NULL, 1, 0, '2026-08-17 09:21:03', '2026-08-17 09:21:09'),
(380, 165, 'products/A7ZEB0yImU4GNvYDX4FUbKFXi7mgsXt0KRP6duAV.png', NULL, 1, 0, '2026-08-17 09:23:52', '2026-08-17 09:23:52'),
(381, 168, 'products/LFmkTdUguwoM6UvcDtwykgqVJej72FARZO25zIxu.avif', NULL, 1, 0, '2026-08-17 09:45:26', '2026-08-17 09:45:58'),
(382, 168, 'products/YJXLcjaYpsT3TJkJy3ZMRtRaTMYMRvWzqCUrwklG.avif', NULL, 0, 3, '2026-08-17 09:45:50', '2026-08-17 09:45:58'),
(383, 168, 'products/13BKWvSvAIHxCjFSNfkU6ARduJJHUfR7esYkSp9t.avif', NULL, 0, 2, '2026-08-17 09:45:50', '2026-08-17 09:45:58'),
(384, 168, 'products/kbVyuAErKgsdAhzbrvLYkoGQJWTRleKhcnS4Rv3W.avif', NULL, 0, 1, '2026-08-17 09:45:50', '2026-08-17 09:45:58'),
(385, 168, 'products/h71bSxwHddbE5pczYGuVnlapqrUXNA7qhazJWdOK.avif', NULL, 0, 4, '2026-08-17 09:46:10', '2026-08-17 09:46:10'),
(386, 168, 'products/24IKUdDBI0UHXVA4qbjMXLeV6vhfXd26aOa8npJQ.avif', NULL, 0, 5, '2026-08-17 09:46:17', '2026-08-17 09:46:17'),
(387, 169, 'products/SSueVfo96Y5G1EjmYJzB2VoAAduVbc5oFpeicr3x.webp', NULL, 1, 0, '2026-08-17 10:06:27', '2026-08-17 10:07:28'),
(388, 169, 'products/bFqsGJFvYMakX0apBDETuZuKvJS36fGdR8QWUtrd.webp', NULL, 0, 3, '2026-08-17 10:07:19', '2026-08-17 10:07:28'),
(389, 169, 'products/2JFm2bge1PbWFjMtcsj758ix118RMKPBl5Fahgy2.webp', NULL, 0, 4, '2026-08-17 10:07:19', '2026-08-17 10:07:28'),
(390, 169, 'products/nvbp5tX4UhzG12SKWdeQz5zRDTZfFGJBrO6saNu2.webp', NULL, 0, 2, '2026-08-17 10:07:19', '2026-08-17 10:07:28'),
(391, 169, 'products/WbZLghss9061gI6gK9bj2CHCVEUo7qgugF6tOdfw.webp', NULL, 0, 1, '2026-08-17 10:07:19', '2026-08-17 10:07:28'),
(392, 170, 'products/llimv8a0udGEUiCNNKTMmGagUixpRcH2ds0DWaZb.webp', NULL, 0, 3, '2026-08-17 10:10:30', '2026-08-17 10:15:05'),
(393, 170, 'products/JZq9fQI5YaGfCkCqBMMmtlbga9yUbKubITvpEUEG.webp', NULL, 0, 4, '2026-08-17 10:10:30', '2026-08-17 10:15:05'),
(394, 170, 'products/IR6N7p6MMtD3BPmE56JcQr89EAe8zuVrhuMO3JHQ.webp', NULL, 0, 5, '2026-08-17 10:10:30', '2026-08-17 10:15:05'),
(395, 170, 'products/pw7o39QLwPnqRkK5Z49xPl2I0oIkBMKLXp1F5RTh.webp', NULL, 0, 2, '2026-08-17 10:10:30', '2026-08-17 10:15:05'),
(396, 170, 'products/v3OXMvyD9Wv6f6NC1UFsbXcXfdW9VZoZI99SViry.webp', NULL, 0, 1, '2026-08-17 10:10:30', '2026-08-17 10:15:05'),
(397, 170, 'products/gURo6hzHbb714O9WPpzwjScQqQcMa8IxXVsX5bV1.webp', NULL, 1, 0, '2026-08-17 10:10:30', '2026-08-17 10:15:05'),
(404, 171, 'products/C79NJjSW1j9XTZxKF4TYyMF8BKAJ5qsuEulRfQXy.webp', NULL, 0, 3, '2026-08-17 10:13:49', '2026-08-17 10:14:02'),
(405, 171, 'products/vMTEUXZmfUIxJIQ22IIjVUt8gPw5wfkRvCk4wWl0.webp', NULL, 0, 4, '2026-08-17 10:13:49', '2026-08-17 10:14:02'),
(406, 171, 'products/QO6LnETDgIXEQZk4Rq2u7LqNDG9EKbTuOYvScwSr.webp', NULL, 0, 5, '2026-08-17 10:13:49', '2026-08-17 10:14:02'),
(407, 171, 'products/zakZ4RaU7Xz47SyM7cFoWV3hqBf7lrkRsWrKUWmG.webp', NULL, 0, 2, '2026-08-17 10:13:49', '2026-08-17 10:14:02'),
(408, 171, 'products/LAPr6sHepIZbuoig3pCKLYEhNgbUmX3KDgfkkovx.webp', NULL, 0, 1, '2026-08-17 10:13:49', '2026-08-17 10:14:02'),
(409, 171, 'products/DALy491McBO2VqKuu7TaPrsqz0Vbcfx6Bb19VseI.webp', NULL, 1, 0, '2026-08-17 10:13:49', '2026-08-17 10:14:02'),
(410, 174, 'products/VcPN4B97go5U65bGMXNtioR1ucUOclcN9BlFWL28.webp', NULL, 0, 3, '2026-08-17 10:25:12', '2026-08-17 10:25:24'),
(411, 174, 'products/dAi3MWYmQwBKvlsQCb4lcyP1LoZWXBYpBwyf88P4.webp', NULL, 0, 4, '2026-08-17 10:25:12', '2026-08-17 10:25:24'),
(412, 174, 'products/Msx9ZAovHLtWA8dCWQSj2gaOxwKGwpPYR7CaNIe9.webp', NULL, 0, 2, '2026-08-17 10:25:12', '2026-08-17 10:25:24'),
(413, 174, 'products/mUDsEkhOdpvLUc2CvCOYPjUAYHbW9ywuTnkYsumK.webp', NULL, 0, 1, '2026-08-17 10:25:12', '2026-08-17 10:25:24'),
(414, 174, 'products/1llO2EbJZ8kkDnPfQ5zqOTfzQp3PHv2brzK1iCJk.webp', NULL, 1, 0, '2026-08-17 10:25:12', '2026-08-17 10:25:24'),
(415, 173, 'products/ZdEFVnZ8J5DwB6F06IC0f2YolRUPZvLhuVezJ6wN.webp', NULL, 0, 3, '2026-08-17 10:25:44', '2026-08-17 10:25:58'),
(416, 173, 'products/UiLDVtYaOmCaVdxDclLDSQrr0bEvWYowR4Qhvtzr.webp', NULL, 0, 4, '2026-08-17 10:25:44', '2026-08-17 10:25:58'),
(417, 173, 'products/PhxEkYEA14b17s0i39hPN2ZHcjTAEkIKoclDQBso.webp', NULL, 0, 5, '2026-08-17 10:25:44', '2026-08-17 10:25:58'),
(418, 173, 'products/4yqGIs0n9chWQX6dZMojJNhQ1QkkDcBaeNNon4Bt.webp', NULL, 0, 2, '2026-08-17 10:25:44', '2026-08-17 10:25:58'),
(419, 173, 'products/eYbolYB5EGwF9RBn1F0Xs2txiORI8jR2Gwqw9ykU.webp', NULL, 0, 1, '2026-08-17 10:25:44', '2026-08-17 10:25:58'),
(420, 173, 'products/GwEV898faDWWS2pwMRx2I8bawHWo3zRYdA3O5ZEX.webp', NULL, 1, 0, '2026-08-17 10:25:44', '2026-08-17 10:25:58'),
(421, 174, 'products/HUkTTzK7kvAbscA18fqrH4s97WIbtq2S7JdL6J7o.webp', NULL, 0, 5, '2026-08-17 10:26:15', '2026-08-17 10:26:15'),
(422, 172, 'products/FajwmwTM6MzxhxZzXLT8Md7SimKJxb8rfQPXik5Q.webp', NULL, 0, 3, '2026-08-17 10:26:32', '2026-08-17 10:27:46'),
(423, 172, 'products/uuI1o3TU1zRgKRUEv8GEc6MkbznJQRhHPH3qTpiN.webp', NULL, 0, 4, '2026-08-17 10:26:32', '2026-08-17 10:27:46'),
(424, 172, 'products/e9Ns4zG2lZW9PD9pOEzHcJDnNSIIuMLocInBelm9.webp', NULL, 0, 2, '2026-08-17 10:26:32', '2026-08-17 10:27:46'),
(425, 172, 'products/b9v0Y5CO5XuE7J6W36zozFVBOH5UdBVfuj2Iz9b6.webp', NULL, 0, 1, '2026-08-17 10:26:32', '2026-08-17 10:27:46'),
(426, 172, 'products/ejWywCWRH8aUjMLXqetVM0r0RMlQoKRqoRXuNEuP.webp', NULL, 1, 0, '2026-08-17 10:26:32', '2026-08-17 10:27:46'),
(427, 172, 'products/oPiw7yKzydiFmKIIzCt8kq1UQBls2tZ80rGipbIS.webp', NULL, 0, 5, '2026-08-17 10:26:32', '2026-08-17 10:27:46'),
(428, 175, 'products/twQYrvVhjDZfgskDPqk5kpq2lPOLix4cwhl1ux1J.avif', NULL, 0, 4, '2026-08-17 10:40:27', '2026-08-17 10:40:55'),
(429, 175, 'products/4HQDLnFneOE5t7hqW0sXelAsnwbWe6YGGeXaMiCU.avif', NULL, 0, 1, '2026-08-17 10:40:27', '2026-08-17 10:40:55'),
(430, 175, 'products/X5hUUwOUihxvFOpq48zw40KPrhwrAXGwvSaClozl.avif', NULL, 0, 2, '2026-08-17 10:40:27', '2026-08-17 10:40:55'),
(431, 175, 'products/iR9aAlqSPZ1ufzFOjO2hjK1v3QDVLSkfgLW1vQ24.avif', NULL, 0, 5, '2026-08-17 10:40:27', '2026-08-17 10:40:55'),
(432, 175, 'products/zAdcfyauiwR5V4mfRU9Kfvgh2wrjcFpy0SuvRFsQ.avif', NULL, 0, 3, '2026-08-17 10:40:27', '2026-08-17 10:40:55'),
(433, 175, 'products/dXIFZL3ZZWFtipNFEGvgkqUD9O7SAp1e3rYkyn9z.avif', NULL, 1, 0, '2026-08-17 10:40:27', '2026-08-17 10:40:55'),
(434, 176, 'products/c1CxN9ezXX8nevY4r3MVdvDAjVcGaQ8fBVf47FWo.avif', NULL, 0, 4, '2026-08-17 10:46:34', '2026-08-17 10:47:01'),
(435, 176, 'products/maQwR9XcHCpsH47qMnhkiDibHyJ7Hm8WGisKaapp.jpg', NULL, 0, 3, '2026-08-17 10:46:34', '2026-08-17 10:47:01'),
(436, 176, 'products/KPtniGEtikT6EJkefEk3gMynOZ753Yx1RNjhvsOZ.avif', NULL, 0, 1, '2026-08-17 10:46:34', '2026-08-17 10:47:01'),
(437, 176, 'products/0Zf2E0thKLqoHzHbbbPtkNEoYAPDisSJQjkbvBQT.avif', NULL, 0, 2, '2026-08-17 10:46:34', '2026-08-17 10:47:01'),
(438, 176, 'products/WB0gqBd4EW1lKFShLkIUSECY3ETTOVuYprtiPkpp.avif', NULL, 1, 0, '2026-08-17 10:46:34', '2026-08-17 10:47:01'),
(439, 177, 'products/yl4NA77Ma1sfMlJXCVKn4i63vT3uAVbrkb5GTUeX.avif', NULL, 0, 4, '2026-08-17 10:48:55', '2026-08-17 10:49:11'),
(440, 177, 'products/9N14x81rHSUI9CStRoArxHX2nyHv1bHgoJk0dicc.avif', NULL, 0, 3, '2026-08-17 10:48:55', '2026-08-17 10:49:11'),
(441, 177, 'products/MdXuur6CfZoNAMdzGRr3gXM1oYQNPrUA1gQLH2s4.avif', NULL, 0, 2, '2026-08-17 10:48:55', '2026-08-17 10:49:11'),
(442, 177, 'products/ILLB8k8QssOt0uAPy6ZEnldvHNqeW26salZgkq92.avif', NULL, 0, 1, '2026-08-17 10:48:55', '2026-08-17 10:49:11'),
(443, 177, 'products/yeglUsxUrPNavBiRKdUiuCXKU3jmI1zYooMN4B3Z.avif', NULL, 0, 5, '2026-08-17 10:48:55', '2026-08-17 10:49:11'),
(444, 177, 'products/ez1wyLROIWgPNQlYfeOL0t29P71AmgH72oUWgqQc.avif', NULL, 1, 0, '2026-08-17 10:48:55', '2026-08-17 10:49:11'),
(445, 178, 'products/oLeBEKhHLi0i6oneJ9UE3oPk9fqenzLfymI5oc2s.avif', NULL, 0, 3, '2026-08-17 11:04:43', '2026-08-17 11:05:13'),
(446, 178, 'products/2Wl49gFkFaNg7veLSgb1jYQR7S5LKl23cvuyHEsJ.avif', NULL, 0, 4, '2026-08-17 11:04:43', '2026-08-17 11:05:13'),
(447, 178, 'products/ByZouDXUX8kqj2xh4iJOoRJHiOYOLFA4PLEEbTEt.webp', NULL, 0, 2, '2026-08-17 11:04:43', '2026-08-17 11:05:13'),
(448, 178, 'products/KOrUvYGrC7j5X985jjEUhv12NE4REtD3yqKMbjhF.avif', NULL, 0, 1, '2026-08-17 11:04:43', '2026-08-17 11:05:13'),
(449, 178, 'products/oa2w4rO8aIqY2Mi35zDGfFqcx50vBS759tYOJGuF.avif', NULL, 0, 5, '2026-08-17 11:04:43', '2026-08-17 11:05:13'),
(450, 178, 'products/CAyla7a9r44vNKi644Pa9SzkLHBEo7zTYvnLWQ6F.webp', NULL, 1, 0, '2026-08-17 11:04:43', '2026-08-17 11:05:13'),
(451, 179, 'products/HChNwIgmXAD57Bq3fi2LoKPmnq9JDNZjIDDW8SD7.avif', NULL, 0, 3, '2026-08-17 11:07:27', '2026-08-17 11:07:40'),
(452, 179, 'products/cO1ecnZjU0hLrCChMSdZO4v7o74SEsNDb7jBsyo4.avif', NULL, 0, 2, '2026-08-17 11:07:27', '2026-08-17 11:07:40'),
(453, 179, 'products/aR5cWIAyMNbbYONKzmQyZRSH1K5Tl1tTJZJvSKND.avif', NULL, 0, 5, '2026-08-17 11:07:27', '2026-08-17 11:07:40'),
(454, 179, 'products/qnq4SKQ1Dp24biDycBfv1qqVVh8O6SHbVmnRdHsL.avif', NULL, 0, 4, '2026-08-17 11:07:27', '2026-08-17 11:07:40'),
(455, 179, 'products/UVA6YkMWjCpjB1eriiKrSAeCX5Aewnrqj5Zx9Lmf.avif', NULL, 0, 1, '2026-08-17 11:07:27', '2026-08-17 11:07:40'),
(456, 179, 'products/hrkX0vuEOm8DdLhPYi5iA7s89FSB6XYMuY7VnRQS.avif', NULL, 1, 0, '2026-08-17 11:07:27', '2026-08-17 11:07:40'),
(457, 180, 'products/TtJPrMVB5LnSNVDJiwgpXl5HmvRtZ0pyqV0fcfZc.avif', NULL, 0, 4, '2026-08-17 11:12:08', '2026-08-17 11:12:20'),
(458, 180, 'products/P2gJwzkFiZLkjY1AutKNWxdoP2YyhF3WRY5xixrK.avif', NULL, 0, 3, '2026-08-17 11:12:08', '2026-08-17 11:12:20'),
(459, 180, 'products/EVm12zLjCLGKWwJ1U2EXV5LkoPPUTBo3ex9gxOPm.avif', NULL, 0, 1, '2026-08-17 11:12:08', '2026-08-17 11:12:20'),
(460, 180, 'products/yQ1bfaCv9mumsc0ni6XQqE6e3NmEhxXcShhUc1yO.avif', NULL, 0, 2, '2026-08-17 11:12:08', '2026-08-17 11:12:20'),
(461, 180, 'products/cvoOzOvPiM2Zm3m2DUzbUGEo11roDdwQEsdWM5PF.avif', NULL, 1, 0, '2026-08-17 11:12:08', '2026-08-17 11:12:20'),
(462, 181, 'products/wTIxtYiYHrTmWXneJBLKLPde5lbMPEqrFBulJ5mi.webp', NULL, 0, 3, '2026-08-17 11:23:14', '2026-08-17 11:23:49'),
(463, 181, 'products/VWbX5tAbijuKrTcWzZzF3oa4EEXivQsfG7zQOAuD.webp', NULL, 0, 4, '2026-08-17 11:23:14', '2026-08-17 11:23:49'),
(464, 181, 'products/Cez0Slg1zEoZs7eiPk9UdMaqQvuTTmCM5PvWlQNh.webp', NULL, 0, 2, '2026-08-17 11:23:14', '2026-08-17 11:23:49'),
(465, 181, 'products/N0oIa50J5E5JIXdJmG8j8vCAuZFw8rMNTWCSNPuQ.webp', NULL, 0, 1, '2026-08-17 11:23:14', '2026-08-17 11:23:49'),
(466, 181, 'products/mqRm2kwq7yd4lBf94WSSdU4UpaDAfBeXS7acCduv.webp', NULL, 1, 0, '2026-08-17 11:23:14', '2026-08-17 11:23:49'),
(467, 182, 'products/oRgzt3jW2T97ZhmcwUMWoQYn4xM19GQXVWSuBYNV.webp', NULL, 0, 3, '2026-08-17 11:27:17', '2026-08-17 11:27:36'),
(468, 182, 'products/qfjDrSNdlo1w3xKZlVZJqxeiGyShLDMMkJGqqHjm.webp', NULL, 0, 2, '2026-08-17 11:27:17', '2026-08-17 11:27:36'),
(469, 182, 'products/MjMtAUJm9LSCgVLnAD0jGOxTUkwPCHJRCwUrVsYk.webp', NULL, 0, 1, '2026-08-17 11:27:17', '2026-08-17 11:27:36'),
(470, 182, 'products/jIqY5fcqUEIg5QotzGywIZlPF2Pk8NsJkoL6r8jI.webp', NULL, 1, 0, '2026-08-17 11:27:17', '2026-08-17 11:27:36'),
(471, 184, 'products/mSt1bOUmlOjkyf8dfEiHRMpdRiWHI72X35p0wb52.webp', NULL, 0, 3, '2026-08-17 11:36:49', '2026-08-17 11:37:02'),
(472, 184, 'products/liE2drJT72m9LifQrhCxayullqqbKqt7j5PY3BqT.png', NULL, 0, 4, '2026-08-17 11:36:49', '2026-08-17 11:37:02'),
(473, 184, 'products/bCf4JUm5ZARMu7QYF0x9VJmktpfVTO70CW0k4WRN.webp', NULL, 0, 2, '2026-08-17 11:36:49', '2026-08-17 11:37:02'),
(474, 184, 'products/cZW3JNjsyBc6mHNo68qPg0d8acdMC9JLFA5r6pC2.webp', NULL, 0, 1, '2026-08-17 11:36:49', '2026-08-17 11:37:02'),
(475, 184, 'products/WZKfbN7tu8l5IWWdBV2lxRxPohjzfZO1F7KXQ5SB.png', NULL, 1, 0, '2026-08-17 11:36:49', '2026-08-17 11:37:02'),
(476, 183, 'products/u1DHYqjUHERCXxClr2xGVcbFKPygI2eeji682OaO.webp', NULL, 0, 4, '2026-08-17 11:37:14', '2026-08-17 11:37:26'),
(477, 183, 'products/bESB9XY4Fgj0PZdSdLag7BSxizG3X09kMhwrZ7ky.webp', NULL, 0, 3, '2026-08-17 11:37:14', '2026-08-17 11:37:26'),
(478, 183, 'products/qM22pZ1UMsmvldB5jGaSQqvIs8ZpX6d3JtQZym5Q.webp', NULL, 0, 2, '2026-08-17 11:37:14', '2026-08-17 11:37:26'),
(479, 183, 'products/wYSiodV2A3JELkVbZ6Y25PVb7ajTq38p4mw0rTpg.webp', NULL, 0, 1, '2026-08-17 11:37:14', '2026-08-17 11:37:26'),
(480, 183, 'products/z4prGBBVkUeMEswjcB6bNXqIg2q6IPUOaZkqlOqS.webp', NULL, 1, 0, '2026-08-17 11:37:14', '2026-08-17 11:37:26'),
(481, 185, 'products/sGZRxlxTn035cKnoYSWt28F0xUmlw5dMFmuleQaD.png', NULL, 0, 2, '2026-08-17 21:18:40', '2026-08-17 21:19:48'),
(482, 185, 'products/9ddZ7j0A7bG0I6EUZ6FAksaUGMINeAgNmQZCQwRN.png', NULL, 0, 1, '2026-08-17 21:18:40', '2026-08-17 21:19:48'),
(483, 185, 'products/eMLF1V5dh4WKJBobCozZDXYq1NdAtRPL6ej9vkR9.png', NULL, 1, 0, '2026-08-17 21:18:40', '2026-08-17 21:19:48'),
(484, 185, 'products/OgcXmtrTVPHhXmYFYyS9za0uXMfu6ckEMijymC3O.jpg', NULL, 0, 3, '2026-08-17 21:19:41', '2026-08-17 21:19:48'),
(485, 186, 'products/LRFFBpEYae4C7iXOoKtIK4VctMPR1sb1EUPgi8tt.webp', NULL, 0, 5, '2026-08-17 21:23:55', '2026-08-17 21:24:12'),
(486, 186, 'products/otGPR33948DPIXyqNuK4ELODKYAQV9WGNoFSd4KJ.webp', NULL, 0, 4, '2026-08-17 21:23:55', '2026-08-17 21:24:12'),
(487, 186, 'products/JB7gArxTQQ0JzmvYtuxxAjZfthggFT0RoLxoR6Bh.webp', NULL, 0, 2, '2026-08-17 21:23:55', '2026-08-17 21:24:12'),
(488, 186, 'products/FiLY5dxH85Rlo7c7kq6MbpXEcxb3uF11duUJ0Vhr.webp', NULL, 0, 1, '2026-08-17 21:23:55', '2026-08-17 21:24:12'),
(489, 186, 'products/4044qQmDAVsYsCZevvCN01YQpFL1cOdJDZIH04qy.png', NULL, 0, 3, '2026-08-17 21:23:55', '2026-08-17 21:24:12'),
(490, 186, 'products/j4T0pcQB6Ku2tJgF2sG9cMxO7TjwYo9rcndCwJEo.png', NULL, 1, 0, '2026-08-17 21:23:56', '2026-08-17 21:24:12'),
(491, 187, 'products/SvhZ2q9pnpUVpZF7uLSuNYFzZ06dYTFCocLR98XS.jpg', NULL, 0, 2, '2026-08-17 21:36:12', '2026-08-17 21:36:33'),
(492, 187, 'products/qAcy0Jj686If4vT3RdlS1hABfFWww0gjXzx5rDLk.jpg', NULL, 0, 1, '2026-08-17 21:36:12', '2026-08-17 21:36:33'),
(493, 187, 'products/inlvENpr1mbhihc6zmEf0ulUKia8lphCl0qWm4Cc.png', NULL, 1, 0, '2026-08-17 21:36:12', '2026-08-17 21:36:33'),
(494, 188, 'products/7xqC0B17IkMk5Y3GwRcYIc6L97j1SBFmcNqjxAl4.jpg', NULL, 0, 2, '2026-08-17 21:39:42', '2026-08-17 21:40:26'),
(495, 188, 'products/vE8Whco4cg28pcPKmaReNqJkwzBmBRBa1TW5vsY9.jpg', NULL, 0, 1, '2026-08-17 21:39:42', '2026-08-17 21:40:26'),
(496, 188, 'products/myhDldYU7uxbIixWhJL1PG57Lntu2wEe26PCJjXb.png', NULL, 1, 0, '2026-08-17 21:39:42', '2026-08-17 21:40:26');

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `case_size` varchar(255) DEFAULT NULL,
  `case_material` varchar(255) DEFAULT NULL,
  `case_thickness` varchar(255) DEFAULT NULL,
  `water_resistance` varchar(255) DEFAULT NULL,
  `glass_type` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `dial_color` varchar(255) DEFAULT NULL,
  `movement_caliber` varchar(255) DEFAULT NULL,
  `power_reserve` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `jewels` varchar(255) DEFAULT NULL,
  `functions` varchar(255) DEFAULT NULL,
  `strap_material` varchar(255) DEFAULT NULL,
  `clasp_type` varchar(255) DEFAULT NULL,
  `battery_life` varchar(255) DEFAULT NULL,
  `display_type` varchar(255) DEFAULT NULL,
  `connectivity` varchar(255) DEFAULT NULL,
  `compatibility` varchar(255) DEFAULT NULL,
  `country_of_origin` varchar(255) DEFAULT NULL,
  `custom_specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_specifications`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_specifications`
--

INSERT INTO `product_specifications` (`id`, `product_id`, `case_size`, `case_material`, `case_thickness`, `water_resistance`, `glass_type`, `weight`, `dial_color`, `movement_caliber`, `power_reserve`, `frequency`, `jewels`, `functions`, `strap_material`, `clasp_type`, `battery_life`, `display_type`, `connectivity`, `compatibility`, `country_of_origin`, `custom_specifications`, `created_at`, `updated_at`) VALUES
(3, 3, '41.00 mm', 'Stainless Steel', '9.50 mm', '3 ATM', 'Scratch-resistant sapphire crystal, with several layers of anti-reflective coating on both sides', '77.0 g', 'Blue \"barleycorn\"', 'L888.5', 'Up to 72 hours', '25\'200', '', 'Hours, minutes, seconds and date', 'Alligator leather', '', NULL, NULL, NULL, NULL, '', NULL, '2026-07-15 20:39:52', '2026-07-28 01:12:30'),
(7, 7, '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, '', NULL, '2026-08-03 20:27:06', '2026-08-03 20:27:06'),
(78, 78, '31 × 22 × 7.5 mm', 'Chrome-plated', '7.5 mm', 'Water Resistant', 'Mineral Glass', '22 g', '', 'Quartz', '', '', '', 'Analog: 3 hands (hour, minute, second)', '', 'Buckle', NULL, NULL, NULL, NULL, '', NULL, '2026-08-17 08:38:43', '2026-08-17 08:38:43'),
(79, 79, '38.8 × 29.8 × 8.1 mm', 'Resin / Chrome-plated', '8.1 mm', 'Water Resistant', 'Resin Glass', '47 g', '', '', '', '', '', 'Dual time, 1/100-second stopwatch, daily alarm, hourly time signal, auto-calendar', 'Stainless Steel', 'Adjustable Clasp', NULL, NULL, NULL, NULL, '', NULL, '2026-08-17 09:39:59', '2026-08-17 09:39:59'),
(80, 80, '40 mm', 'Stainless Steel', '11.7 mm', '50 m / 5 bar', 'Sapphire Crystal', '138 g', '', '8210', 'Approx. 42 hours', '21,600 vph', '21', '3-hand, Date, Hacking', 'Stainless Steel', 'Fold-over clasp with push buttons', NULL, NULL, NULL, NULL, '', NULL, '2026-08-17 10:03:31', '2026-08-17 10:03:31'),
(81, 81, '39 mm', 'Super Titanium™', '9.8 mm', '100 m / 10 bar', 'Sapphire Crystal', '91 g', '', 'J800', 'Approx. 8 months', '', '', '3-hand, Day/Date', 'Super Titanium™', 'Fold-over clasp with push buttons', NULL, NULL, NULL, NULL, '', NULL, '2026-08-17 10:19:24', '2026-08-17 10:19:24'),
(82, 82, '40 mm', 'Stainless Steel', '14.35 mm', '100 m / 10 bar', 'Sapphire Crystal', '', '', 'H-51-Si', '60 hours', '', '', 'Chronograph, Hours, Minutes, Small Seconds', '', '', NULL, NULL, NULL, NULL, 'Switzerland', NULL, '2026-08-17 10:35:24', '2026-08-17 10:35:24'),
(83, 83, '40 mm', 'Stainless Steel', '11.1 mm', '50 m / 5 bar', 'Sapphire Crystal', '', '', 'H-10', '80 hours', '', '', 'Hours, Minutes, Seconds', '', '', NULL, NULL, NULL, NULL, 'Switzerland', '{\"Case back\":\"Open Case Back\",\"Balance spring\":\"Nivachron\\u2122\",\"Lume\":\"Super-LumiNova\"}', '2026-08-17 10:52:54', '2026-08-17 10:54:26'),
(84, 84, '40 × 40 mm', '316L Stainless Steel', '12.48 mm', '300 m / 30 bar', 'Sapphire Crystal with Anti-Reflective Coating', '148 g', '', '', 'Up to 80 hours', '', '', 'Date', 'Stainless Steel', 'Folding Clasp with Safety Lock and Push-Buttons', NULL, NULL, NULL, NULL, 'Switzerland', '{\"Bezel type\":\"Unidirectional Rotating Bezel\",\"Case shape\":\"Round\",\"Balance spring\":\"Nivachron\\u2122\"}', '2026-08-17 11:18:16', '2026-08-17 11:19:37'),
(85, 85, '40 × 39.5 mm', '316L Stainless Steel', '10.93 mm', '100 m / 10 bar', 'Sapphire Crystal with Anti-Reflective Coating', '138 g', '', 'Powermatic 80', 'Up to 80 hours', '', '', 'Hours, Minutes, Seconds, Date', 'Stainless Steel', 'Butterfly Clasp with Push-Buttons', NULL, NULL, NULL, NULL, 'Switzerland', NULL, '2026-08-17 11:31:23', '2026-08-17 11:31:23'),
(86, 86, '40.5 mm', 'Stainless Steel', '11.8 mm', '50 m / 5 bar', 'Box-Shaped Hardlex', '', '', '4R35', 'Approx. 41 hours', '21,600 vph', '23', 'Hours, Minutes, Seconds, Date, Stop-Seconds', '', '', NULL, NULL, NULL, NULL, 'Japan', NULL, '2026-08-17 21:17:12', '2026-08-17 21:17:12'),
(87, 87, '', 'Stainless Steel', '', '100 m / 10 bar*', 'Hardlex', '', '', ' 4R36', '', '', '', '', '', '', NULL, NULL, NULL, NULL, '', NULL, '2026-08-17 21:32:25', '2026-08-17 21:32:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `sku`, `price`, `compare_price`, `stock_quantity`, `is_active`, `is_default`, `created_at`, `updated_at`) VALUES
(6, 3, 'Blue Dial / Leather Strap', 'LG-MA-2201-BL', 1200.00, NULL, 2, 1, 0, '2026-07-15 20:40:29', '2026-08-17 08:54:08'),
(7, 3, 'White Dial / Leather Strap ', 'LG-MA-2202-WL', 1200.00, NULL, 13, 1, 1, '2026-07-15 21:26:27', '2026-08-17 08:54:03'),
(8, 3, 'Blue Dial / Steel Strap', 'LG-MA-2203-BS', 1500.00, NULL, 23, 1, 0, '2026-07-16 00:19:29', '2026-08-17 08:54:18'),
(9, 3, 'White Dial / Steel Strap', 'LG-MA-2204-WS', 1500.00, NULL, 24, 1, 0, '2026-07-16 00:20:17', '2026-08-17 08:54:26'),
(10, 3, 'ARABIC DIAL', 'LG-MA-2205-AD', 1600.00, NULL, 25, 1, 0, '2026-07-16 00:28:47', '2026-08-17 08:54:32'),
(23, 7, 'Blue Dial', 'LG-SP-BL-001', 3000.00, NULL, 9, 1, 1, '2026-08-03 20:27:58', '2026-08-17 08:44:28'),
(165, 78, 'White Roman Dial / Gold-Tone Steel', 'LTP-V007G-9B', 60.00, NULL, 50, 1, 0, '2026-08-17 08:43:06', '2026-08-17 09:02:48'),
(166, 78, 'White Roman Dial / Black Leather', 'LTP-V007L-7B1', 60.00, NULL, 50, 1, 1, '2026-08-17 08:47:33', '2026-08-17 09:02:48'),
(167, 78, 'White Roman Dial / Brown Leather', 'LTPV007L-7B2', 60.00, NULL, 49, 1, 0, '2026-08-17 09:00:52', '2026-08-17 09:02:48'),
(168, 79, 'White Dial / Silver-Tone Steel', 'AQ-230A-7AMQY', 49.99, 59.99, 50, 1, 1, '2026-08-17 09:43:16', '2026-08-17 09:46:32'),
(169, 80, 'Sky Blue Dial / Silver-Tone Steel', 'NJ0151-53M', 495.00, NULL, 30, 1, 0, '2026-08-17 10:06:12', '2026-08-17 11:40:19'),
(170, 80, 'White Dial / Silver-Tone Steel', 'NJ0150-56A', 495.00, NULL, 25, 1, 1, '2026-08-17 10:10:16', '2026-08-17 11:40:19'),
(171, 80, 'Black Dial / Silver-Tone Steel', 'NJ0150-56E', 495.00, NULL, 32, 1, 0, '2026-08-17 10:13:09', '2026-08-17 11:40:19'),
(172, 81, 'White Dial / Silver-Tone Titanium', 'AW0130-85A', 550.00, NULL, 26, 1, 1, '2026-08-17 10:20:42', '2026-08-17 10:27:48'),
(173, 81, 'Blue Dial / Silver-Tone Titanium', 'AW0130-85L', 550.00, NULL, 45, 1, 0, '2026-08-17 10:22:09', '2026-08-17 10:27:48'),
(174, 81, 'Green Dial / Silver-Tone Titanium', 'AW0130-85X', 550.00, NULL, 35, 1, 0, '2026-08-17 10:22:37', '2026-08-17 10:27:48'),
(175, 82, 'Black Dial / Brown Leather', 'H76409530', 2395.00, NULL, 15, 1, 1, '2026-08-17 10:40:11', '2026-08-17 10:40:57'),
(176, 82, 'Aviation Blue / Leather', 'H76409540', 2395.00, NULL, 16, 1, 0, '2026-08-17 10:45:13', '2026-08-17 10:45:13'),
(177, 82, 'Aviation Blue Dial / Silver-Tone Steel', 'H76409140', 2395.00, NULL, 0, 1, 0, '2026-08-17 10:47:51', '2026-08-17 10:47:51'),
(178, 83, ' Burgundy Dial / Brown Leather', ' H32675570', 1228.00, NULL, 16, 1, 1, '2026-08-17 11:03:54', '2026-08-17 11:09:06'),
(179, 83, 'Silver Dial / Brown Leather', 'H32675551', 1228.00, NULL, 0, 1, 0, '2026-08-17 11:06:30', '2026-08-17 11:09:06'),
(180, 83, ' Blue Dial / Silver-Tone Steel', 'H32675141', 1326.00, NULL, 22, 1, 0, '2026-08-17 11:11:23', '2026-08-17 11:11:23'),
(181, 84, 'Black Dial / Silver-Tone Steel', 'T120.807.11.051.00', 975.00, NULL, 36, 1, 1, '2026-08-17 11:23:05', '2026-08-17 11:24:27'),
(182, 84, 'Black Dial / Black Rubber', 'T120.907.37.051.00', 1125.00, NULL, 0, 1, 0, '2026-08-17 11:27:06', '2026-08-17 11:27:06'),
(183, 85, 'Ice Blue Dial / Silver-Tone Steel', 'T137.407.11.351.00', 950.00, NULL, 37, 1, 1, '2026-08-17 11:35:57', '2026-08-17 11:37:27'),
(184, 85, 'Blue Dial / Silver-Tone Steel', 'T137.407.11.051.01', 950.00, NULL, 26, 1, 0, '2026-08-17 11:36:40', '2026-08-17 11:37:27'),
(185, 86, 'Blue Dial / Silver-Tone Steel', 'SRPB41J1', 475.00, NULL, 33, 1, 1, '2026-08-17 21:18:26', '2026-08-17 21:19:50'),
(186, 86, 'Light Blue Dial / Brown Leather', 'SRPB43J1', 450.00, NULL, 25, 1, 0, '2026-08-17 21:21:25', '2026-08-17 21:21:25'),
(187, 87, 'SRPE53', 'SRPE53', 350.00, NULL, 26, 1, 1, '2026-08-17 21:35:38', '2026-08-17 21:40:27'),
(188, 87, 'SRPE55', 'SRPE55', 350.00, NULL, 36, 1, 0, '2026-08-17 21:39:32', '2026-08-17 21:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_specifications`
--

CREATE TABLE `product_variant_specifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `overrides` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`overrides`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_specifications`
--

INSERT INTO `product_variant_specifications` (`id`, `product_variant_id`, `overrides`, `created_at`, `updated_at`) VALUES
(1, 165, '{\"dial_color\":\"White\",\"strap_material\":\"Stainless Steel\",\"custom_specifications\":{\"Hourmarker\":\"Roman Numerals\"}}', '2026-08-17 08:43:06', '2026-08-17 08:43:06'),
(2, 166, '{\"dial_color\":\"White\",\"strap_material\":\"Leather\",\"custom_specifications\":{\"Hourmarker\":\"Roman Numerals\"}}', '2026-08-17 08:47:33', '2026-08-17 08:47:33'),
(3, 167, '{\"dial_color\":\"White\",\"strap_material\":\"Leather\",\"custom_specifications\":{\"Hourmarker\":\"Roman Numerals\"}}', '2026-08-17 09:00:52', '2026-08-17 09:00:52'),
(4, 168, '{\"dial_color\":\"White\"}', '2026-08-17 09:43:16', '2026-08-17 09:43:16'),
(5, 169, '{\"dial_color\":\"Sky Blue\"}', '2026-08-17 10:06:12', '2026-08-17 10:06:12'),
(6, 170, '{\"dial_color\":\"White\"}', '2026-08-17 10:10:16', '2026-08-17 10:10:16'),
(7, 171, '{\"dial_color\":\"Black\"}', '2026-08-17 10:13:09', '2026-08-17 10:13:09'),
(8, 172, '{\"dial_color\":\"White\"}', '2026-08-17 10:20:42', '2026-08-17 10:20:42'),
(9, 173, '{\"dial_color\":\"Blue\"}', '2026-08-17 10:22:09', '2026-08-17 10:22:09'),
(10, 174, '{\"dial_color\":\"Green\"}', '2026-08-17 10:22:37', '2026-08-17 10:22:37'),
(11, 175, '{\"dial_color\":\"Black\",\"strap_material\":\"Cow Leather\",\"clasp_type\":\"H-Buckle\"}', '2026-08-17 10:40:11', '2026-08-17 10:40:11'),
(12, 176, '{\"dial_color\":\"Aviation Blue\",\"strap_material\":\"Cow Leather\",\"clasp_type\":\"H-Buckle\"}', '2026-08-17 10:45:13', '2026-08-17 10:45:13'),
(13, 177, '{\"dial_color\":\"Aviation Blue\",\"strap_material\":\"Stainless Steel\",\"clasp_type\":\"Folding Clasp\"}', '2026-08-17 10:47:51', '2026-08-17 10:47:51'),
(14, 178, '{\"dial_color\":\"Burgundy\",\"strap_material\":\"Calf leather\",\"clasp_type\":\"Pin buckle\"}', '2026-08-17 11:03:54', '2026-08-17 11:03:54'),
(15, 179, '{\"strap_material\":\"Calf leather\",\"clasp_type\":\"Pin buckle\",\"dial_color\":\"Silver\"}', '2026-08-17 11:06:30', '2026-08-17 11:06:30'),
(16, 180, '{\"dial_color\":\"Blue\",\"strap_material\":\"Stainless steel\",\"clasp_type\":\"Butterfly\"}', '2026-08-17 11:11:23', '2026-08-17 11:11:23'),
(17, 181, '{\"dial_color\":\"Black\"}', '2026-08-17 11:23:05', '2026-08-17 11:23:05'),
(18, 182, '{\"strap_material\":\"Nitrile Rubber\",\"dial_color\":\"Grey gradient\",\"case_size\":\"44.00 mm\",\"case_material\":\"316L stainless steel & PVD coating\",\"weight\":\"130.00\"}', '2026-08-17 11:27:06', '2026-08-17 11:27:06'),
(19, 183, '{\"dial_color\":\"Ice Blue\"}', '2026-08-17 11:35:57', '2026-08-17 11:35:57'),
(20, 184, '{\"dial_color\":\"Blue\"}', '2026-08-17 11:36:41', '2026-08-17 11:36:41'),
(21, 185, '{\"dial_color\":\"Blue\",\"strap_material\":\"Stainless Steel\",\"clasp_type\":\"Deployment Clasp with Push-Button Release\",\"weight\":\"135 g\"}', '2026-08-17 21:18:26', '2026-08-17 21:18:26'),
(22, 186, '{\"dial_color\":\"Light Blue\",\"strap_material\":\"Cow Leather\",\"clasp_type\":\"Three-fold clasp with push button release\",\"weight\":\"70.0g\"}', '2026-08-17 21:21:25', '2026-08-17 21:21:25'),
(23, 187, '{\"case_size\":\"40.0mm\",\"movement_caliber\":\"4R36\",\"case_thickness\":\"11.5 mm\",\"weight\":\"142.0g\",\"dial_color\":\"Blue\",\"power_reserve\":\"Approx. 41 hours\",\"jewels\":\"24\",\"functions\":\"Stop second hand function and Day\\/Date display\",\"clasp_type\":\"Three-fold clasp with push button release\",\"strap_material\":\"Stainless Steel\"}', '2026-08-17 21:35:38', '2026-08-17 21:35:38'),
(24, 188, '{\"case_size\":\"40.0 mm\",\"case_thickness\":\"11.5 mm\",\"weight\":\"142.0g\",\"dial_color\":\"Black\",\"power_reserve\":\"Approx. 41 hours\",\"jewels\":\"24\",\"functions\":\"Stop second hand function and Day\\/Date display\",\"strap_material\":\"Stainless Steel\",\"clasp_type\":\"Three-fold clasp with push button release\"}', '2026-08-17 21:39:32', '2026-08-17 21:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `verified_purchase` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `order_item_id`, `rating`, `comment`, `status`, `verified_purchase`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 1, 3, 26, 3, 'thr', 'pending', 1, '2026-08-04 00:17:17', '2026-08-17 08:55:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `review_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2jl5kHCnu6BKXVqeaiYADlRQrTp85zRIPJVAK9di', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3JiS0REOFVPVkZpa0cySnZ5YmJIaHV4QTlrZ0pNVFlWbGdPQjVCSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1787114255),
('F9roJpDPWyKtHBWdz6bu2srS7IwX5HRrnUFBTM4K', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXdiaVBFYW1yU2xEQ0U0aWFxWVJkcXFQODhwaDFBanZ2Z29XOExpVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb21tdW5pdHkvMTEiO3M6NToicm91dGUiO3M6MTQ6ImNvbW11bml0eS5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1787112487),
('tahSLyPlmizuYfWWlU8xTgcwAC8yijfbsLnKxWvi', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVThvdHk4MjduN0NFSFl4czhNWWxJV0hTYm9rc1ExT1daYTlEa0xFRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zaG9wIjtzOjU6InJvdXRlIjtzOjEwOiJzaG9wLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1787115891),
('vVqMPKJwxbRWRPl564o5Lf9piuDvfufGqRZ4VrGA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHd1dmNQMkFxQmtrRlM2Vlg2cGQ4TjB3YkxtcEIwTUxyOUtkakZlNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1787034817),
('WlsrJkLQj1P5l44HCAvRI13Oip8C9YCVuYf9r9hb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkRIMWcxbDJIUmx6bjgzMVBRQlc3dmhtUkpxQTRoT3hqZ3BLYlEwcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cy9zZWlrby01LXNwb3J0cy1za3gtc2VyaWVzIjtzOjU6InJvdXRlIjtzOjEyOiJzaG9wLnByb2R1Y3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1787036869),
('ynYVQOIEpWKKbMBN537rv392Mo4lgdr0u9UgQyrk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkMzbGY4SjlCcVUyM2tUVk1abWJHeDdaUERUSERSYW9FTk8yNzRqaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cy9zZWlrby01LXNwb3J0cy1za3gtc2VyaWVzIjtzOjU6InJvdXRlIjtzOjEyOiJzaG9wLnByb2R1Y3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1787036866);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_locations`
--

CREATE TABLE `shipping_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipping_zone_id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(255) NOT NULL,
  `state_region` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `district_area` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_locations`
--

INSERT INTO `shipping_locations` (`id`, `shipping_zone_id`, `country`, `state_region`, `city`, `district_area`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Myanmar', 'Yangon', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(2, 2, 'Myanmar', 'Mandalay', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(3, 2, 'Myanmar', 'Bago', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(4, 2, 'Myanmar', 'Ayeyarwady', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(5, 2, 'Myanmar', 'Magway', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(6, 2, 'Myanmar', 'Sagaing', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(7, 2, 'Myanmar', 'Naypyidaw', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(8, 3, 'Myanmar', 'Chin', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(9, 3, 'Myanmar', 'Kachin', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(10, 3, 'Myanmar', 'Kayah', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(11, 3, 'Myanmar', 'Kayin', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(12, 3, 'Myanmar', 'Mon', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(13, 3, 'Myanmar', 'Rakhine', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(14, 3, 'Myanmar', 'Shan', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(15, 3, 'Myanmar', 'Tanintharyi', 'All', NULL, 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_zones`
--

CREATE TABLE `shipping_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `estimated_days` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_zones`
--

INSERT INTO `shipping_zones` (`id`, `name`, `fee`, `estimated_days`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Yangon', 2.00, '1-2 days', 'Yangon Region', 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(2, 'Central Myanmar', 4.00, '2-4 days', 'Mandalay, Bago, Ayeyarwady, Magway, Sagaing, Naypyidaw', 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44'),
(3, 'Other States', 7.00, '4-7 days', 'Chin, Kachin, Kayah, Kayin, Mon, Rakhine, Shan, Tanintharyi', 1, '2026-07-21 23:50:44', '2026-07-21 23:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_name` varchar(255) NOT NULL DEFAULT 'TICKS',
  `legal_name` varchar(255) DEFAULT NULL,
  `support_email` varchar(255) DEFAULT NULL,
  `support_phone` varchar(40) DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `default_country` varchar(100) NOT NULL DEFAULT 'Myanmar',
  `order_prefix` varchar(8) NOT NULL DEFAULT 'TCK',
  `low_stock_threshold` smallint(5) UNSIGNED NOT NULL DEFAULT 5,
  `insurance_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `insurance_rate` decimal(6,4) NOT NULL DEFAULT 0.0200,
  `guest_checkout_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `store_name`, `legal_name`, `support_email`, `support_phone`, `business_address`, `default_country`, `order_prefix`, `low_stock_threshold`, `insurance_enabled`, `insurance_rate`, `guest_checkout_enabled`, `created_at`, `updated_at`) VALUES
(1, 'TICKS', NULL, NULL, NULL, NULL, 'Myanmar', 'TCK', 5, 1, 0.0200, 1, '2026-07-30 01:35:28', '2026-07-30 01:36:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `status` enum('pending','active','banned') NOT NULL DEFAULT 'pending',
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `status`, `name`, `phone`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'phyoheinnaung29@gmail.com', '2026-07-14 21:32:53', '$2y$12$Njqiv0ShnSqg1zsWhqtKIuAcy60YUo8qr4QM9jX6LHt8pD6FhC3yK', NULL, 'admin', 'active', 'Phyo Hein Naung', NULL, 'avatars/rI4Yabk2uWDFJIhx3mnxXYSHxdxO9687rwvumFsj.png', '2026-07-14 21:29:36', '2026-08-03 03:20:44'),
(10, 'admin@koku.test', '2026-08-13 04:11:07', '$2y$12$cHFEnKUwOEB1wYufHhOHReuE.eUUmM.LlM2gFEWDc23nEWJPhAXmO', NULL, 'admin', 'active', 'Admin', NULL, NULL, '2026-08-13 01:38:54', '2026-08-13 04:11:07'),
(11, 'customer@koku.test', '2026-08-12 22:20:40', '$2y$12$LK3tYF5lj43v1Qm17qUtXekHWHiQZBquikI8w0lH9wmVhfEC61XBu', NULL, 'user', 'active', 'Customer', NULL, 'avatars/4Xmv9KlUhXJNKnSAWn7puf7DjJgMdVfgEmwOB1uI.jpg', '2026-08-12 21:43:40', '2026-08-18 21:46:50');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist_items`
--

INSERT INTO `wishlist_items` (`id`, `user_id`, `session_id`, `product_id`, `created_at`, `updated_at`) VALUES
(16, NULL, 'fhRnakgh7uv4Xq6ZGppAFqFwN4iXYLEyZD3TaEhx', 3, '2026-07-22 00:05:49', '2026-07-22 00:05:49'),
(28, NULL, 'JI0317XF0ZRxnMkubqjtCuJpNJ2mkbzCpHv5Djql', 3, '2026-08-03 03:19:00', '2026-08-03 03:19:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_name_unique` (`name`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_cart_id_variant_id_unique` (`cart_id`,`variant_id`),
  ADD KEY `cart_items_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community_comments_user_id_foreign` (`user_id`),
  ADD KEY `community_comments_parent_id_foreign` (`parent_id`),
  ADD KEY `community_comments_post_id_status_created_at_index` (`post_id`,`status`,`created_at`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community_posts_order_item_id_foreign` (`order_item_id`),
  ADD KEY `community_posts_status_published_at_index` (`status`,`published_at`),
  ADD KEY `community_posts_product_id_status_published_at_index` (`product_id`,`status`,`published_at`),
  ADD KEY `community_posts_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `community_post_likes_user_id_post_id_unique` (`user_id`,`post_id`),
  ADD KEY `community_post_likes_post_id_created_at_index` (`post_id`,`created_at`);

--
-- Indexes for table `community_post_media`
--
ALTER TABLE `community_post_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `community_post_media_post_id_sort_order_index` (`post_id`,`sort_order`);

--
-- Indexes for table `community_reports`
--
ALTER TABLE `community_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `community_reports_unique` (`reporter_id`,`reportable_type`,`reportable_id`),
  ADD KEY `community_reports_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `community_reports_reportable_type_reportable_id_index` (`reportable_type`,`reportable_id`);

--
-- Indexes for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_enquiries_user_id_foreign` (`user_id`),
  ADD KEY `contact_enquiries_order_number_index` (`order_number`),
  ADD KEY `contact_enquiries_status_index` (`status`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD UNIQUE KEY `orders_stripe_payment_intent_id_unique` (`stripe_payment_intent_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_coupon_id_foreign` (`coupon_id`),
  ADD KEY `orders_shipping_location_id_foreign` (`shipping_location_id`),
  ADD KEY `orders_shipping_address_id_foreign` (`shipping_address_id`),
  ADD KEY `orders_billing_address_id_foreign` (`billing_address_id`),
  ADD KEY `orders_tracking_number_index` (`tracking_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_watch_type_index` (`watch_type`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_specifications_product_id_unique` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variant_specifications`
--
ALTER TABLE `product_variant_specifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variant_specifications_product_variant_id_unique` (`product_variant_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `reviews_order_item_id_foreign` (`order_item_id`),
  ADD KEY `reviews_product_id_status_created_at_index` (`product_id`,`status`,`created_at`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_images_review_id_sort_order_index` (`review_id`,`sort_order`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipping_locations`
--
ALTER TABLE `shipping_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_locations_shipping_zone_id_foreign` (`shipping_zone_id`);

--
-- Indexes for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipping_zones_name_unique` (`name`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlist_items_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD UNIQUE KEY `wishlist_items_session_id_product_id_unique` (`session_id`,`product_id`),
  ADD KEY `wishlist_items_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `community_comments`
--
ALTER TABLE `community_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `community_post_media`
--
ALTER TABLE `community_post_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `community_reports`
--
ALTER TABLE `community_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=497;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `product_variant_specifications`
--
ALTER TABLE `product_variant_specifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipping_locations`
--
ALTER TABLE `shipping_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `shipping_zones`
--
ALTER TABLE `shipping_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `community_comments`
--
ALTER TABLE `community_comments`
  ADD CONSTRAINT `community_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `community_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`),
  ADD CONSTRAINT `community_posts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  ADD CONSTRAINT `community_post_likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_post_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_post_media`
--
ALTER TABLE `community_post_media`
  ADD CONSTRAINT `community_post_media_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_reports`
--
ALTER TABLE `community_reports`
  ADD CONSTRAINT `community_reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_reports_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  ADD CONSTRAINT `contact_enquiries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_billing_address_id_foreign` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_shipping_address_id_foreign` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_shipping_location_id_foreign` FOREIGN KEY (`shipping_location_id`) REFERENCES `shipping_locations` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_specifications`
--
ALTER TABLE `product_variant_specifications`
  ADD CONSTRAINT `product_variant_specifications_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `review_images_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_locations`
--
ALTER TABLE `shipping_locations`
  ADD CONSTRAINT `shipping_locations_shipping_zone_id_foreign` FOREIGN KEY (`shipping_zone_id`) REFERENCES `shipping_zones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `wishlist_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
