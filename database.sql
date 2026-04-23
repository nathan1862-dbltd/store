-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 23, 2026 at 03:53 AM
-- Server version: 11.4.10-MariaDB-cll-lve
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `riusfnxmti_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `attribute_id` int(11) NOT NULL,
  `attribute_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`attribute_id`, `attribute_name`) VALUES
(1, 'Size'),
(2, 'Color'),
(3, 'Type'),
(4, 'Pack');

-- --------------------------------------------------------

--
-- Table structure for table `birthday_gifts`
--

CREATE TABLE `birthday_gifts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `birthday_gifts`
--

INSERT INTO `birthday_gifts` (`id`, `product_id`) VALUES
(1, 4),
(2, 9),
(3, 14);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `created_at`) VALUES
(1, 'Beauty of Joseon', 'beauty-of-joseon', 'Hanbang-inspired Korean skincare blending traditional ingredients with modern science.', NULL, '2026-01-30 10:43:44'),
(2, 'Laneige', 'laneige', 'Premium hydration-focused Korean skincare brand known for Water Sleeping Mask.', NULL, '2026-01-30 10:43:44'),
(3, 'Innisfree', 'innisfree', 'Natural skincare brand inspired by Jeju Island ingredients.', NULL, '2026-01-30 10:43:44'),
(5, 'Round Lab', 'round-lab', 'Dermatologist-loved Korean brand famous for Dokdo mineral skincare line.', NULL, '2026-01-30 10:43:44'),
(6, 'Etude', 'etude', 'Playful Korean cosmetics brand specializing in lip tints and makeup essentials.', NULL, '2026-01-30 10:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, NULL, '247bno1mso9v4e6nkgomejref3', '2026-02-01 12:09:58', '2026-02-01 12:09:58'),
(3, NULL, '3n03j9fqeccg9o909mllsqft87', '2026-02-01 22:25:31', '2026-02-01 22:25:31'),
(4, NULL, '1scikpci0mb5i3bh221b5p43jc', '2026-02-07 21:45:06', '2026-02-07 21:45:06'),
(9, NULL, 'a07tub1tgfdlec0isaoi7ve7m4', '2026-02-13 14:27:35', '2026-02-13 14:27:35'),
(10, 3, '8vemgn9s2r0jb8kjc7m7beso5j', '2026-02-14 07:04:16', '2026-02-14 07:04:16'),
(11, 8, 'ueere9pkbqp55otj97ehdb5kat', '2026-02-16 19:46:29', '2026-02-16 19:46:29'),
(12, 9, 'r63dq5dnhq7jsmsmiarccjfatq', '2026-02-16 22:09:37', '2026-02-16 22:09:37'),
(13, 2, 'd2qj31rgkmlvaqtsld7uru5745', '2026-02-17 02:24:24', '2026-02-17 02:24:24'),
(14, 10, 's8v8ivsj4s754jd45setqp3653', '2026-02-22 21:25:42', '2026-02-22 21:25:42'),
(15, NULL, '48hqb2rdqh8ojmtn6ts9o9lu75', '2026-04-16 01:24:53', '2026-04-16 01:24:53');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `variant_id`, `quantity`, `unit_price`, `created_at`) VALUES
(1, 1, 1, 1, 1, 10000.00, '2026-02-01 12:09:58'),
(6, 3, 5, 9, 1, 20000.00, '2026-02-01 22:25:31'),
(8, 4, 2, 3, 3, 16000.00, '2026-02-07 21:54:35'),
(16, 9, 169, 41, 1, 22000.00, '2026-02-13 14:27:35'),
(17, 9, 196, 53, 1, 53000.00, '2026-02-13 22:53:13'),
(21, 10, 196, 53, 1, 53000.00, '2026-02-14 10:21:19'),
(22, 11, 190, 50, 1, 44000.00, '2026-02-16 19:46:29'),
(23, 12, 196, 53, 1, 53000.00, '2026-02-16 22:09:37'),
(25, 13, 197, 55, 1, 5500.00, '2026-02-22 20:35:59'),
(27, 15, 5, 43, 1, 29000.00, '2026-04-16 01:24:53'),
(28, 9, 3, 6, 2, 22000.00, '2026-04-16 13:55:43'),
(29, 9, 3, 5, 1, 15000.00, '2026-04-16 14:00:13'),
(30, 9, 1, 2, 1, 18000.00, '2026-04-20 08:41:46');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item_attributes`
--

CREATE TABLE `cart_item_attributes` (
  `id` int(11) NOT NULL,
  `cart_item_id` int(11) NOT NULL,
  `attribute_name` varchar(100) DEFAULT NULL,
  `attribute_value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_path`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cleanser', 'cleanser', NULL, NULL, NULL, 1, 1, '2026-02-13 10:26:49', NULL),
(2, 'Toner', 'toner', NULL, NULL, NULL, 2, 1, '2026-02-13 10:26:49', NULL),
(3, 'Serum', 'serum', NULL, NULL, NULL, 3, 1, '2026-02-13 10:26:49', NULL),
(4, 'Moisturizer', 'moisturizer', NULL, NULL, NULL, 4, 1, '2026-02-13 10:26:49', NULL),
(5, 'Sunscreen', 'sunscreen', NULL, NULL, NULL, 5, 1, '2026-02-13 10:26:49', NULL),
(6, 'Mask', 'mask', NULL, NULL, NULL, 6, 1, '2026-02-13 10:26:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('fixed','percent') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_order` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order`, `max_discount`, `expires_at`, `is_active`, `created_at`) VALUES
(1, 'SAVE5000', 'fixed', 5000.00, 30000.00, NULL, NULL, 1, '2026-02-02 00:01:20'),
(2, 'OFF10', 'percent', 10.00, 0.00, NULL, NULL, 1, '2026-02-02 00:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_redemptions`
--

CREATE TABLE `coupon_redemptions` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `redeemed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupon_redemptions`
--

INSERT INTO `coupon_redemptions` (`id`, `coupon_id`, `user_id`, `order_id`, `redeemed_at`) VALUES
(1, 1, 3, 10, '2025-10-28 08:56:27'),
(2, 1, 1, 12, '2025-10-31 22:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slider`
--

CREATE TABLE `hero_slider` (
  `id` int(11) NOT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `hero_slider`
--

INSERT INTO `hero_slider` (`id`, `image1`, `image2`, `image3`, `image4`, `updated_at`) VALUES
(1, 'assets/uploads/hero/hero_image1_20260125_020637_fe2904f6.png', NULL, NULL, NULL, '2026-01-24 17:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `highlights`
--

CREATE TABLE `highlights` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `svg_icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `highlights`
--

INSERT INTO `highlights` (`id`, `title`, `svg_icon`) VALUES
(1, 'Cruelty Free', 'assets/uploads/highlights/1769710687_noun-cruelty-free-7612074.svg'),
(2, 'Alcohol Free', 'assets/uploads/highlights/1769710705_noun-alcohol-free-3339164.svg'),
(3, 'Fragrance Free', 'assets/uploads/highlights/1769710719_noun-unscented-7722212.svg'),
(4, 'Physical Sunscreen', 'assets/uploads/highlights/1769711182_noun-skin-8237761.svg'),
(5, 'Chemical Sunscreen', 'assets/uploads/highlights/1769711202_noun-skin-7864828.svg'),
(6, 'Fast Selling', 'assets/uploads/highlights/1769711437_noun-best-selling-product-7843919.svg');

-- --------------------------------------------------------

--
-- Table structure for table `highlight_icons`
--

CREATE TABLE `highlight_icons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `svg_file` varchar(150) NOT NULL,
  `color` varchar(20) DEFAULT '#111111',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `highlight_icons`
--

INSERT INTO `highlight_icons` (`id`, `code`, `label`, `svg_file`, `color`, `created_at`) VALUES
(1, 'skin', 'Skin Friendly', 'skin.svg', '#2196F3', '2026-02-07 19:18:34'),
(2, 'skin_variant', 'Skin Variant', 'skin-variant.svg', '#111111', '2026-02-07 19:18:34'),
(3, 'acne', 'Acne Care', 'acne.svg', '#111111', '2026-02-07 19:18:34'),
(4, 'sensitive_skin', 'Sensitive Skin', 'sensitive-skin.svg', '#111111', '2026-02-07 19:18:34'),
(5, 'fragrance_free', 'Fragrance Free', 'fragrance-free.svg', '#E91E63', '2026-02-07 19:18:34'),
(6, 'unscented', 'Unscented', 'unscented.svg', '#111111', '2026-02-07 19:18:34'),
(7, 'cruelty_free', 'Cruelty Free', 'cruelty-free.svg', '#4CAF50', '2026-02-07 19:18:34'),
(8, 'cruelty_free_variant', 'Cruelty Free (Variant)', 'cruelty-free-variant.svg', '#111111', '2026-02-07 19:18:34'),
(9, 'alcohol_free', 'Alcohol Free', 'alcohol-free.svg', '#FF9800', '2026-02-07 19:18:34'),
(10, 'alcohol_free_variant', 'Alcohol Free (Variant)', 'alcohol-free-variant.svg', '#111111', '2026-02-07 19:18:34'),
(11, 'best_selling', 'Best Selling', 'best-selling.svg', '#111111', '2026-02-07 19:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_ledger`
--

CREATE TABLE `loyalty_ledger` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `points` int(11) NOT NULL,
  `type` enum('earn','redeem') NOT NULL,
  `status` enum('pending','approved','expired') DEFAULT 'approved',
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `loyalty_ledger`
--

INSERT INTO `loyalty_ledger` (`id`, `user_id`, `order_id`, `points`, `type`, `status`, `expires_at`, `created_at`) VALUES
(1, 2, 1, 0, 'earn', 'approved', '2026-08-17 13:34:26', '2026-02-16 23:34:26'),
(2, 10, 2, 88, 'earn', 'approved', '2026-08-23 06:26:57', '2026-02-22 16:26:57');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'cod',
  `payment_status` enum('unpaid','paid','failed','refunded') DEFAULT 'unpaid',
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text NOT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `township_id` int(10) UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(100) DEFAULT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tracking_number` varchar(100) DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `subtotal`, `shipping`, `discount`, `total`, `status`, `payment_method`, `payment_status`, `full_name`, `phone`, `email`, `address`, `state_id`, `township_id`, `coupon_code`, `order_number`, `notes`, `created_at`, `updated_at`, `tracking_number`, `shipped_at`, `delivered_at`, `cancelled_at`) VALUES
(1, 2, 35000.00, 3000.00, 5000.00, 33000.00, 'delivered', 'cod', 'paid', 'Kaung Sett Win', '9955497743', NULL, '0', 1, 2, 'SAVE5000', 'ORD-20260217124049-425', NULL, '2026-02-16 22:40:49', '2026-02-22 15:18:26', NULL, NULL, NULL, NULL),
(2, 10, 90000.00, 3000.00, 5000.00, 88000.00, 'delivered', 'cod', 'paid', 'Kaung Sett Win', '9764397743', NULL, '0', 1, 1, 'SAVE5000', 'ORD-20260223062634-339', NULL, '2026-02-22 16:26:34', '2026-02-22 16:44:45', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `variant_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `variant_name` varchar(255) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name`, `variant_name`, `sku`, `image_path`, `quantity`, `unit_price`, `line_total`, `created_at`) VALUES
(1, 1, 167, 31, 'Relief Sun: Rice + Probiotics SPF50+', '50ml', NULL, NULL, 1, 35000.00, 35000.00, '2026-02-16 22:40:49'),
(2, 2, 197, 54, 'Dark Spot Correcting Glow Serum', '50 ml', NULL, NULL, 2, 45000.00, 90000.00, '2026-02-22 16:26:34');

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `otp_requests`
--

INSERT INTO `otp_requests` (`id`, `phone`, `otp`, `created_at`) VALUES
(1, '+959764397743', '295219', '2025-10-27 21:24:19'),
(2, '+959955497743', '845095', '2025-11-28 07:10:12'),
(3, '09955497743', '973983', '2026-01-14 08:52:47'),
(4, '+959955497743', '361736', '2026-01-14 08:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `points_ledger`
--

CREATE TABLE `points_ledger` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `change_amount` int(11) NOT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `how_to_use` text DEFAULT NULL,
  `has_variants` tinyint(1) NOT NULL DEFAULT 0,
  `brand_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `image_path2` varchar(255) DEFAULT NULL,
  `image_path3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sold_count` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_on_sale` tinyint(1) NOT NULL DEFAULT 0,
  `from_price` decimal(10,2) DEFAULT NULL,
  `discount_price` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `is_active`, `description`, `ingredients`, `how_to_use`, `has_variants`, `brand_id`, `image_path`, `image_path2`, `image_path3`, `created_at`, `updated_at`, `sold_count`, `is_featured`, `is_on_sale`, `from_price`, `discount_price`) VALUES
(1, 'Hydrating Facial Cleanser', 'hydrating-facial-cleanser', 1, 'Gentle daily cleanser suitable for all skin types.', 'Glycerin, Hyaluronic Acid, Chamomile Extract', 'Apply to damp skin, massage gently, rinse with water.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 1, 1, 12000.00, 0),
(2, 'Vitamin C Brightening Serum', 'vitamin-c-brightening-serum', 1, 'Boosts radiance and evens skin tone.', 'Vitamin C, Ferulic Acid, Aloe Vera', 'Apply 2–3 drops to clean skin before moisturizer.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 0, 1, 18000.00, 0),
(3, 'Daily Moisturizing Cream', 'daily-moisturizing-cream', 1, 'Lightweight moisturizer for everyday hydration.', 'Shea Butter, Ceramides, Niacinamide', 'Apply evenly to face and neck morning and night.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 1, 1, 15000.00, 0),
(4, 'Soothing Aloe Gel', 'soothing-aloe-gel', 1, 'Calms irritated and sun-exposed skin.', 'Aloe Vera, Green Tea Extract', 'Apply a thin layer to affected areas as needed.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 0, 1, 9000.00, 0),
(5, 'Anti-Aging Night Cream', 'anti-aging-night-cream', 1, 'Deeply nourishes skin overnight.', 'Retinol, Peptides, Vitamin E', 'Apply nightly to clean skin before sleep.', 1, 1, 'assets/images/product_1761930663_9782.png', 'assets/images/product_1761930663_2244.jpeg', 'assets/images/product_1761930663_7152.jpeg', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 1, 1, 22000.00, 0),
(6, 'Oil Control Face Wash', 'oil-control-face-wash', 1, 'Removes excess oil without over-drying.', 'Salicylic Acid, Tea Tree Oil', 'Use twice daily, massage onto wet face and rinse.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 0, 1, 11000.00, 0),
(7, 'Hydrating Sheet Mask', 'hydrating-sheet-mask', 1, 'Instant hydration and glow boost.', 'Hyaluronic Acid, Collagen', 'Apply mask for 15–20 minutes, remove and massage serum.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 0, 1, 5000.00, 0),
(8, 'Gentle Exfoliating Scrub', 'gentle-exfoliating-scrub', 1, 'Removes dead skin cells and smooths texture.', 'Walnut Shell Powder, Lactic Acid', 'Massage gently onto damp skin 2–3 times a week.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 1, 1, 14000.00, 0),
(9, 'SPF 50 Sunscreen Lotion', 'spf-50-sunscreen-lotion', 1, 'Broad-spectrum protection against UV rays.', 'Zinc Oxide, Vitamin E', 'Apply generously 15 minutes before sun exposure.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 0, 1, 16000.00, 0),
(10, 'Repairing Eye Cream', 'repairing-eye-cream', 1, 'Reduces puffiness and fine lines.', 'Caffeine, Peptides, Hyaluronic Acid', 'Gently tap around eye area morning and night.', 1, 1, 'assets/images/no-image.png', 'assets/images/no-image.png', 'assets/images/no-image.png', '2026-02-01 11:47:29', '2026-02-14 01:48:20', 0, 0, 1, 13000.00, 0),
(167, 'Relief Sun: Rice + Probiotics SPF50+', 'relief-sun-rice-probiotics', 1, 'Lightweight sunscreen with rice extract and probiotics for daily UV protection.', NULL, NULL, 0, 3, '/V3/uploads/products/relief-sunscreen-1-front.png', '/V3/uploads/products/relief-sunscreen-1-front.png', '/V3/uploads/products/relief-sunscreen-1-front.png', '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 38000.00, 0),
(168, 'Glow Serum: Propolis + Niacinamide', 'glow-serum-propolis-niacinamide', 1, 'Brightening serum formulated with propolis and niacinamide.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 42000.00, 0),
(169, 'Revive Serum: Ginseng + Snail Mucin', 'revive-serum-ginseng-snail', 1, 'Anti-aging serum with ginseng and snail mucin for skin repair.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 24000.00, 0),
(170, 'Calming Serum: Green Tea + Panthenol', 'calming-serum-green-tea', 1, 'Soothing serum with green tea and panthenol.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 42000.00, 0),
(171, 'Glow Deep Serum: Rice + Alpha-Arbutin', 'glow-deep-serum-rice-arbutin', 1, 'Brightening serum targeting dark spots.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 40000.00, 0),
(172, 'Dynasty Cream', 'dynasty-cream', 1, 'Moisturizing cream with ginseng and rice bran water.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 48000.00, 0),
(173, 'Ginseng Essence Water', 'ginseng-essence-water', 1, 'Hydrating essence with 80% ginseng root water.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 39000.00, 0),
(174, 'Radiance Cleansing Balm', 'radiance-cleansing-balm', 1, 'Oil-based cleansing balm for makeup removal.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 42000.00, 0),
(175, 'Apricot Blossom Peeling Gel', 'apricot-blossom-peeling-gel', 1, 'Gentle exfoliating peeling gel with apricot blossom.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 30000.00, 0),
(176, 'Green Plum Refreshing Cleanser', 'green-plum-refreshing-cleanser', 1, 'Low pH gel cleanser with green plum extract.', NULL, NULL, 0, 3, NULL, NULL, NULL, '2026-02-13 13:33:00', '2026-02-14 01:48:20', 0, 0, 0, 32000.00, 0),
(190, 'Day Dew Sunscreen Lightweight SPF 50', '', 1, 'Description\r\nWhat it is: A skincare-first sunscreen formula with a barely-there texture that melts in fast, leaves zero white cast, and delivers a fresh, dewy finish.\r\n\r\nSkin Type: Normal, Dry, Combination, and Oily\r\n\r\nSkincare Concerns: Dryness, Loss of Firmness and', 'Active Ingredients : Avobenzone 3.0%, Homosalate 7.0%, Octisalate 5.0%, Octocrylene 5.0% Inactive Ingredients : Water (Aqua), Butyloctyl Salicylate, Glycerin, Dimethicone, Poly C10-30 Alkyl Acrylate, VP/Eicosene Copolymer, Cetearyl Alcohol, Niacinamide, Sodium Stearoyl Glutamate, Ammonium Polyacryloyldimethyl Taurate, Hydroxyacetophenone, Phenoxyethanol, Tocopheryl Acetate, Panthenol, Pentaerythrityl Tetra-di-t-butyl Hydroxyhydrocinnamate, Xanthan Gum, Propanediol, Oryza Sativa (Rice) Extract, 1,2-Hexanediol, Sodium Hyaluronate', '\r\nSuggested Usage:\r\n-As the final step of your skincare routine, apply a generous amount evenly over areas exposed to the sun.\r\n-Reapply every two hours to maintain UV protection and refresh your glow', 0, 1, 'uploads/products/1771019818_IMG_8535.jpeg', 'uploads/products/1771019818_IMG_8536.jpeg', 'uploads/products/1771019818_IMG_8538.jpeg', '2026-02-13 21:56:58', '2026-02-14 01:48:20', 0, 0, 0, 44000.00, 0),
(196, 'Mighty Bamboo Panthenol Cream', 'mighty-bamboo-panthenol-cream', 1, 'Description\r\n* The product is free of artificial fragrances and essential oils, but you may notice a mild scent from natural ingredients.\r\nSkin Type\r\nAll skin types\r\nScent\r\nFragrance-free\r\nFeel\r\nMoisturizing gel-type cream that smoothly melts into the skin\r\nFinish\r\nDewy, radiant finish', 'Water, Panthenol (10%), Squalane, Butylene Glycol, 1,2-Hexanediol, Niacinamide, Bambusa Vulgaris Extract (3,497 ppm), Hydrolyzed Jojoba Esters, Acrylates/C10-30 Alkyl Acrylate Crosspolymer, Ammonium Acryloyldimethyltaurate/VP Copolymer, Hydroxyacetophenone, Tromethamine, Dipotassium Glycyrrhizate, Ethylhexylglycerin, Xanthan Gum, Disodium EDTA, Sodium Hyaluronate, Madecassoside, Asiaticoside, Asiatic Acid, Madecassic Acid, Copper Tripeptide-1', '0', 1, 2, 'uploads/products/1771022146_IMG_8540.png', 'uploads/products/1771022146_IMG_8539.webp', 'uploads/products/1771022146_IMG_8541.webp', '2026-02-13 22:35:46', '2026-02-14 01:48:20', 0, 0, 0, 56000.00, 0),
(197, 'Dark Spot Correcting Glow Serum', 'dark-spot-correcting-glow-serum', 1, 'A targeted solution for dark spots, and dull, uneven skin.\r\n\r\nThis multi-functional serum is designed to visibly correct dark spots, and brighten the skin—without irritation. Powered by 5% Niacinamide, plant-based Squalane, and a curated blend of natural brightening extracts, this serum helps balance an uneven tone while strengthening the skin moisture barrier for long-term clarity.\r\n\r\nWhether you\'re dealing with dark spots, sun damage, or overall dullness, this is a proven solution for achieving clearer, smoother, and more radiant skin.', 'Water, Glycerin, Niacinamide, Sodium Hyaluronate, Propanediol, Erythritol, Butylene Glycol, Squalane, Oryza Sativa (Rice) Bran Extract, Calendula Officinalis Flower Extract, Carica Papaya (Papaya) Fruit Extract, Hippophae Rhamnoides Fruit Extract, Malpighia Glabra (Acerola)Fruit Extract, Polyglyceryl-10 Laurate, Chlorphenesin, Arginine, Ethylhexylglycerin, Carbomer, Glutathione, 1,2-Hexanediol, Hydroxypropyl Cyclodextrin, Disodium EDTA, Hydroxyethylcellulose, Allantoin, Rosmarinus Officinalis (Rosemary) Leaf Oil', '0', 0, 6, 'uploads/products/1771792406_IMG_8655.png', 'uploads/products/1771792406_IMG_8656.png', 'uploads/products/1771792406_IMG_8657.png', '2026-02-22 20:33:26', '2026-02-22 20:33:26', 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`, `created_at`) VALUES
(1, 5, 2, '2026-02-13 07:59:58'),
(5, 167, 5, '2026-02-13 08:33:01'),
(6, 170, 3, '2026-02-13 08:33:01'),
(7, 171, 3, '2026-02-13 08:33:01'),
(8, 168, 3, '2026-02-13 08:33:01'),
(9, 169, 3, '2026-02-13 08:33:01'),
(13, 172, 4, '2026-02-13 08:33:01'),
(14, 173, 6, '2026-02-13 08:33:01'),
(15, 176, 2, '2026-02-13 08:33:01'),
(16, 174, 2, '2026-02-13 08:33:01'),
(31, 190, 5, '2026-02-13 16:56:58'),
(34, 196, 3, '2026-02-13 17:35:46'),
(35, 197, 3, '2026-02-22 15:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `product_highlights`
--

CREATE TABLE `product_highlights` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `icon_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(150) DEFAULT NULL,
  `svg_icon` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_highlights`
--

INSERT INTO `product_highlights` (`id`, `product_id`, `icon_id`, `title`, `subtitle`, `svg_icon`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 1, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(2, 1, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(3, 1, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(4, 1, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(5, 2, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(6, 2, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(7, 2, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(8, 2, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(9, 3, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(10, 3, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(11, 3, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(12, 3, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(13, 4, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(14, 4, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(15, 4, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(16, 4, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(17, 5, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(18, 5, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(19, 5, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(20, 5, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(21, 6, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(22, 6, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(23, 6, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(24, 6, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(25, 7, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(26, 7, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(27, 7, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(28, 7, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(29, 8, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(30, 8, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(31, 8, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(32, 8, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(33, 9, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(34, 9, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(35, 9, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(36, 9, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(37, 10, 9, '', NULL, '', 3, 1, '2026-02-07 19:37:40'),
(38, 10, 7, '', NULL, '', 4, 1, '2026-02-07 19:37:40'),
(39, 10, 5, '', NULL, '', 2, 1, '2026-02-07 19:37:40'),
(40, 10, 1, '', NULL, '', 1, 1, '2026-02-07 19:37:40'),
(41, 167, 1, '', NULL, '', 1, 1, '2026-02-13 14:00:52'),
(42, 167, 3, '', NULL, '', 2, 1, '2026-02-13 14:00:52'),
(43, 167, 5, '', NULL, '', 3, 1, '2026-02-13 14:00:52'),
(44, 167, 6, '', NULL, '', 4, 1, '2026-02-13 14:00:52'),
(45, 168, 2, '', NULL, '', 1, 1, '2026-02-13 14:00:52'),
(46, 168, 3, '', NULL, '', 2, 1, '2026-02-13 14:00:52'),
(47, 168, 4, '', NULL, '', 3, 1, '2026-02-13 14:00:52'),
(48, 168, 6, '', NULL, '', 4, 1, '2026-02-13 14:00:52'),
(49, 169, 1, '', NULL, '', 1, 1, '2026-02-13 14:00:52'),
(50, 169, 2, '', NULL, '', 2, 1, '2026-02-13 14:00:52'),
(51, 169, 3, '', NULL, '', 3, 1, '2026-02-13 14:00:52'),
(52, 169, 4, '', NULL, '', 4, 1, '2026-02-13 14:00:52'),
(53, 190, 2, '', NULL, '', 0, 1, '2026-02-13 21:56:58'),
(54, 190, 5, '', NULL, '', 0, 1, '2026-02-13 21:56:58'),
(55, 190, 1, '', NULL, '', 0, 1, '2026-02-13 21:56:58'),
(56, 190, 3, '', NULL, '', 0, 1, '2026-02-13 21:56:58'),
(57, 194, 2, '', NULL, '', 0, 1, '2026-02-13 22:13:36'),
(58, 194, 1, '', NULL, '', 0, 1, '2026-02-13 22:13:36'),
(59, 194, 6, '', NULL, '', 0, 1, '2026-02-13 22:13:36'),
(60, 194, 3, '', NULL, '', 0, 1, '2026-02-13 22:13:36'),
(65, 196, 2, '', NULL, '', 0, 1, '2026-02-13 22:35:46'),
(66, 196, 1, '', NULL, '', 0, 1, '2026-02-13 22:35:46'),
(67, 196, 6, '', NULL, '', 0, 1, '2026-02-13 22:35:46'),
(68, 196, 3, '', NULL, '', 0, 1, '2026-02-13 22:35:46'),
(69, 197, 2, '', NULL, '', 0, 1, '2026-02-22 20:33:26'),
(70, 197, 1, '', NULL, '', 0, 1, '2026-02-22 20:33:26'),
(71, 197, 6, '', NULL, '', 0, 1, '2026-02-22 20:33:26'),
(72, 197, 3, '', NULL, '', 0, 1, '2026-02-22 20:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_name` varchar(150) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `on_sale` tinyint(1) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_name`, `sku`, `barcode`, `image_path`, `price`, `discount_price`, `on_sale`, `stock`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, '50ml', 'SKU-1-50', NULL, 'assets/images/no-image.png', 12000.00, 10000.00, 1, 47, 1, '2026-02-01 11:49:01', '2026-02-11 14:50:36'),
(2, 1, '100ml', 'SKU-1-100', NULL, 'assets/images/no-image.png', 20000.00, 18000.00, 1, 30, 0, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(3, 2, '30ml', 'SKU-2-30', NULL, 'assets/images/no-image.png', 18000.00, 16000.00, 1, 38, 1, '2026-02-01 11:49:01', '2026-02-08 06:23:43'),
(4, 2, '60ml', 'SKU-2-60', NULL, 'assets/images/no-image.png', 28000.00, 25000.00, 1, 25, 0, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(5, 3, '50ml', 'SKU-3-50', NULL, 'assets/images/no-image.png', 15000.00, NULL, 0, 59, 1, '2026-02-01 11:49:01', '2026-02-11 11:30:06'),
(6, 3, '100ml', 'SKU-3-100', NULL, 'assets/images/no-image.png', 24000.00, 22000.00, 1, 35, 0, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(7, 4, '100ml', 'SKU-4-100', NULL, 'assets/images/no-image.png', 9000.00, 8000.00, 1, 70, 1, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(8, 4, '200ml', 'SKU-4-200', NULL, 'assets/images/no-image.png', 15000.00, 14000.00, 1, 40, 0, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(9, 5, '30g', 'SKU-5-30', NULL, 'assets/images/product_1761930663_9782.png', 22000.00, NULL, 0, 30, 1, '2026-02-01 11:49:01', '2026-02-07 16:12:38'),
(10, 5, '60g', 'SKU-5-60', NULL, 'assets/images/IMG_8386.jpeg', 35000.00, NULL, 0, 19, 0, '2026-02-01 11:49:01', '2026-02-08 22:22:56'),
(11, 6, '100ml', 'SKU-6-100', NULL, 'assets/images/no-image.png', 11000.00, 10000.00, 1, 80, 1, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(12, 6, '200ml', 'SKU-6-200', NULL, 'assets/images/no-image.png', 18000.00, NULL, 0, 50, 0, '2026-02-01 11:49:01', '2026-02-07 16:10:28'),
(13, 7, 'Single', 'SKU-7-1', NULL, 'assets/images/no-image.png', 5000.00, 4500.00, 1, 100, 1, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(14, 7, 'Pack of 5', 'SKU-7-5', NULL, 'assets/images/no-image.png', 22000.00, 20000.00, 1, 40, 0, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(15, 8, '50ml', 'SKU-8-50', NULL, 'assets/images/no-image.png', 14000.00, 12500.00, 1, 0, 1, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(16, 8, '100ml', 'SKU-8-100', NULL, 'assets/images/no-image.png', 23000.00, 21000.00, 1, 30, 0, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(17, 9, '50ml', 'SKU-9-50', NULL, 'assets/images/no-image.png', 16000.00, 14500.00, 1, 55, 1, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(18, 9, '100ml', 'SKU-9-100', NULL, 'assets/images/no-image.png', 26000.00, NULL, 0, 35, 0, '2026-02-01 11:49:01', '2026-02-07 16:10:28'),
(19, 10, '15ml', 'SKU-10-15', NULL, 'assets/images/no-image.png', 13000.00, 12000.00, 1, 50, 1, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(20, 10, '30ml', 'SKU-10-30', NULL, 'assets/images/no-image.png', 22000.00, 20000.00, 1, 30, 0, '2026-02-01 11:49:01', '2026-02-07 16:19:52'),
(21, 167, '50ml', NULL, NULL, NULL, 38000.00, 35000.00, 1, 0, 1, '2026-02-13 13:33:00', '2026-02-13 20:42:05'),
(22, 168, '30ml', NULL, NULL, NULL, 42000.00, 39000.00, 1, 40, 1, '2026-02-13 13:33:00', '2026-02-13 13:33:00'),
(23, 169, '30ml', NULL, NULL, NULL, 45000.00, 42000.00, 1, 40, 1, '2026-02-13 13:33:00', '2026-02-13 13:33:00'),
(24, 170, '30ml', NULL, NULL, NULL, 42000.00, NULL, 0, 35, 1, '2026-02-13 13:33:00', '2026-02-13 13:33:00'),
(25, 171, '30ml', NULL, NULL, NULL, 40000.00, 37000.00, 1, 35, 1, '2026-02-13 13:33:00', '2026-02-13 13:33:00'),
(26, 172, '50ml', NULL, NULL, NULL, 48000.00, NULL, 0, 30, 1, '2026-02-13 13:33:00', '2026-02-13 13:33:00'),
(27, 173, '150ml', NULL, NULL, NULL, 39000.00, NULL, 0, 40, 1, '2026-02-13 13:33:00', '2026-02-13 13:33:00'),
(28, 174, '100ml', NULL, NULL, NULL, 42000.00, 39000.00, 1, 30, 1, '2026-02-13 13:33:01', '2026-02-13 13:33:01'),
(29, 175, '100ml', NULL, NULL, NULL, 30000.00, NULL, 0, 35, 1, '2026-02-13 13:33:01', '2026-02-13 13:33:01'),
(30, 176, '100ml', NULL, NULL, NULL, 32000.00, NULL, 0, 40, 1, '2026-02-13 13:33:01', '2026-02-13 13:33:01'),
(31, 167, '50ml', NULL, NULL, NULL, 38000.00, 35000.00, 1, 48, 1, '2026-02-13 13:34:52', '2026-02-17 03:40:49'),
(32, 168, '30ml', NULL, NULL, NULL, 42000.00, 39000.00, 1, 40, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(33, 169, '30ml', NULL, NULL, NULL, 45000.00, 42000.00, 1, 40, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(34, 170, '30ml', NULL, NULL, NULL, 42000.00, NULL, 0, 35, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(35, 171, '30ml', NULL, NULL, NULL, 40000.00, 37000.00, 1, 35, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(36, 172, '50ml', NULL, NULL, NULL, 48000.00, NULL, 0, 30, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(37, 173, '150ml', NULL, NULL, NULL, 39000.00, NULL, 0, 40, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(38, 174, '100ml', NULL, NULL, NULL, 42000.00, 39000.00, 1, 30, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(39, 175, '100ml', NULL, NULL, NULL, 30000.00, NULL, 0, 35, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(40, 176, '100ml', NULL, NULL, NULL, 32000.00, NULL, 0, 40, 1, '2026-02-13 13:34:52', '2026-02-13 13:34:52'),
(41, 169, '30ml', NULL, NULL, NULL, 24000.00, 22000.00, 1, 50, 0, '2026-02-13 13:41:35', '2026-02-13 13:41:35'),
(42, 169, '50ml', NULL, NULL, NULL, 35000.00, NULL, 0, 30, 0, '2026-02-13 13:41:35', '2026-02-13 13:41:35'),
(43, 5, '50ml', NULL, NULL, NULL, 32000.00, 29000.00, 1, 25, 1, '2026-02-13 13:46:51', '2026-02-13 13:46:51'),
(44, 5, '100ml', NULL, NULL, NULL, 48000.00, NULL, 0, 15, 0, '2026-02-13 13:46:51', '2026-02-13 13:46:51'),
(50, 190, '50 ml', NULL, NULL, NULL, 44000.00, 0.00, 0, 30, 0, '2026-02-13 21:56:58', '2026-02-13 21:56:58'),
(53, 196, '100 ml', NULL, NULL, NULL, 56000.00, 53000.00, 1, 4, 0, '2026-02-13 22:35:46', '2026-02-14 10:09:55'),
(54, 197, '50 ml', NULL, NULL, NULL, 45000.00, 0.00, 0, 8, 0, '2026-02-22 20:33:26', '2026-02-22 21:26:34'),
(55, 197, '5 ml', NULL, NULL, NULL, 5500.00, 0.00, 0, 30, 0, '2026-02-22 20:33:26', '2026-02-22 20:33:26');

--
-- Triggers `product_variants`
--
DELIMITER $$
CREATE TRIGGER `trg_variant_on_sale_insert` BEFORE INSERT ON `product_variants` FOR EACH ROW BEGIN
    IF NEW.discount_price IS NOT NULL
       AND NEW.discount_price > 0 THEN
        SET NEW.on_sale = 1;
    ELSE
        SET NEW.on_sale = 0;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_variant_on_sale_update` BEFORE UPDATE ON `product_variants` FOR EACH ROW BEGIN
    IF NEW.discount_price IS NOT NULL
       AND NEW.discount_price > 0 THEN
        SET NEW.on_sale = 1;
    ELSE
        SET NEW.on_sale = 0;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, 'Yangon'),
(2, 'Mandalay'),
(3, 'Ayawaddy');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `points_required` int(11) NOT NULL,
  `type` enum('coupon','product') NOT NULL,
  `reward_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `name`, `description`, `points_required`, `type`, `reward_value`) VALUES
(1, '50% off your next purchase', 'Get half off a single future order.', 500, 'coupon', 'HALFOFF'),
(2, 'Free Yoga Mat', 'Redeem this reward to receive a free yoga mat.', 300, 'product', '4'),
(3, 'Ks 10,000 voucher', 'Take Ks 10,000 off your next order.', 200, 'coupon', 'TENKOFF');

-- --------------------------------------------------------

--
-- Table structure for table `reward_claims`
--

CREATE TABLE `reward_claims` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `points_used` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `claimed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_items`
--

CREATE TABLE `reward_items` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `points_required` int(11) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_states`
--

CREATE TABLE `shipping_states` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `shipping_states`
--

INSERT INTO `shipping_states` (`id`, `name`, `is_active`, `created_at`) VALUES
(1, 'Yangon', 1, '2026-02-07 20:07:50'),
(2, 'Mandalay', 1, '2026-02-07 20:07:50'),
(3, 'Naypyitaw', 1, '2026-02-07 20:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_townships`
--

CREATE TABLE `shipping_townships` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `shipping_townships`
--

INSERT INTO `shipping_townships` (`id`, `state_id`, `name`, `shipping_fee`, `is_active`, `created_at`) VALUES
(1, 1, 'Hlaing', 3000.00, 1, '2026-02-16 11:00:08'),
(2, 1, 'Kamayut', 3000.00, 1, '2026-02-16 11:00:08'),
(3, 1, 'Bahan', 4000.00, 1, '2026-02-16 11:00:08'),
(4, 2, 'Aungmyethazan', 5000.00, 1, '2026-02-16 11:00:08'),
(5, 2, 'Chanayethazan', 5000.00, 1, '2026-02-16 11:00:08'),
(6, 3, 'Zabuthiri', 6000.00, 1, '2026-02-16 11:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `townships`
--

CREATE TABLE `townships` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `townships`
--

INSERT INTO `townships` (`id`, `region_id`, `name`, `shipping_fee`) VALUES
(1, 1, 'Hlaing', 2000.00),
(2, 1, 'Sanchaung', 2500.00),
(3, 1, 'Kamayut', 3000.00),
(4, 2, 'Chanmyathazi', 2500.00),
(5, 2, 'Aungmyethazan', 3000.00),
(6, 3, 'Pathein', 4500.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  `loyalty_points` int(11) NOT NULL DEFAULT 0,
  `last_gift_claim_year` int(11) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `welcome_discount_used` tinyint(1) DEFAULT 0,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `points` int(11) NOT NULL DEFAULT 0,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `phone`, `birthday`, `loyalty_points`, `last_gift_claim_year`, `is_admin`, `created_at`, `welcome_discount_used`, `otp_code`, `otp_expires_at`, `is_verified`, `points`, `reset_token`, `reset_expires`) VALUES
(1, 'Kgkg', '$2y$10$San3Tc3QNVvnzLFrSQM00.GCeQd51vlWyJNWBo09sf2U4K5pfb3n.', '9955497743', '2025-10-15', 91, NULL, 0, '2025-10-27 18:41:13', 0, NULL, NULL, 0, 0, NULL, NULL),
(2, 'admin2', '$2y$10$7XsoDzZpTj4HfLty.34nB.ONFWzwd5qx6iRMviGPYUCntomoUmUqq', '9955497743', '2025-07-24', 0, NULL, 1, '2025-10-27 22:32:37', 0, NULL, NULL, 0, 0, NULL, NULL),
(3, 'nathan', '$2y$10$oHOIOuhgZp1u7zPLK.bD.O5AhpKNcG0BTdypWaSVg7uGARBAzqB.q', '09965460512', '1986-08-02', 200, NULL, 0, '2025-10-28 05:08:15', 0, NULL, NULL, 0, 45, NULL, NULL),
(4, 'kiki', '$2y$10$mPG3ARSxiqMcF1/4smNnqO3Mjmrk8qbaLS09u38VGgVxpr4yW2UZ.', '09965460512', '2001-01-24', 0, NULL, 0, '2026-01-24 07:13:11', 0, '438182', '2026-01-24 07:18:11', 0, 0, NULL, NULL),
(5, 'nathank', '$2y$10$mHRpuKbZKpiOoV4DmA719uUfCMpkXwX1UmeznHxol0IipZv3E5TrS', '9955497743', '2006-01-24', 0, NULL, 0, '2026-01-24 07:35:00', 0, '901204', '2026-01-24 07:40:00', 0, 0, NULL, NULL),
(6, 'kiki2001', '$2y$10$JtA8nb5PIv832OPFQyARHeVxIGMw5xsyXEmyAUwNcp2ER3mW3NC7O', '09955497743', '2000-01-24', 0, NULL, 0, '2026-01-24 10:15:57', 0, '123456', '2026-01-25 00:20:57', 0, 0, NULL, NULL),
(7, 'chothar', '$2y$10$N3/Gin4rM/FQLMAefJIleOPjnwXV7tz.riX6FlVdzMcvAzA2SL0UW', '09965460512', '2004-01-04', 0, NULL, 0, '2026-01-24 10:25:52', 0, '123456', '2026-01-25 00:30:52', 0, 0, NULL, NULL),
(8, 'kaungsett', '$2y$10$z3jVOXjfTaNfVXZKk1DI/e21Dl7M83mrm4vE4ofNGBvIwPkqHUAlm', '09764397743', '2001-06-18', 0, NULL, 0, '2026-02-16 14:46:13', 0, '123456', '2026-02-17 04:51:13', 0, 0, NULL, NULL),
(9, 'nathansett', '$2y$10$ItLw39H3lKD1MvbDUZsQ8uv1/7Fj/bJ8krz5GTAh8OYjvTQBHdmzG', '09764397743', '2001-08-17', 0, NULL, 0, '2026-02-16 17:08:58', 0, '123456', '2026-02-17 07:13:58', 0, 0, NULL, NULL),
(10, 'sett', '$2y$10$JF2clGMiug1ZbqMpwupA7eFfeGaSaPLdI5mCvAdCmPlxWXCQaYWvu', '09955497743', '1999-02-17', 0, NULL, 0, '2026-02-22 16:25:13', 0, '123456', '2026-02-23 06:30:13', 0, 0, NULL, NULL),
(11, 'thelthel', '$2y$10$L9VML6bnp8TQ1VgF8b3Nret8hYLDRTHaDQwPTcTXkEsI00/I4YxQe', '09764397743', '2008-02-23', 0, NULL, 0, '2026-02-22 17:16:43', 0, '123456', '2026-02-23 07:21:43', 0, 0, NULL, NULL),
(12, 'kgkg111', '$2y$10$xNtNLMjoXiaEEUZUreQ8LuD6RHVcBe6eOphNmUMEgvr9LQ/IgvjHS', '09964332212', '2026-04-15', 0, NULL, 0, '2026-04-20 08:44:20', 0, '123456', '2026-04-20 21:49:20', 0, 0, NULL, NULL),
(13, 'kiki1279', '$2y$10$PqAX9RDWgVWjZSn9y9kECOcdH1mrEpPZiB3hi0A5I.eJxpLZ5alla', '09444332212', '1995-07-12', 0, NULL, 0, '2026-04-20 15:35:40', 0, '123456', '2026-04-21 04:40:40', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_points`
--

CREATE TABLE `user_points` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('earn','redeem','refund','adjust') NOT NULL,
  `points` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `order_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_points`
--

INSERT INTO `user_points` (`id`, `user_id`, `type`, `points`, `status`, `order_id`, `admin_id`, `description`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'adjust', 330, 'approved', NULL, NULL, 'Manual bonus points', NULL, '2026-02-22 16:03:26', '2026-02-22 16:03:26'),
(2, 10, 'earn', 88, 'approved', 2, NULL, 'Points from Order #2', '2027-02-22 16:44:45', '2026-02-22 16:44:45', '2026-02-22 16:44:45');

-- --------------------------------------------------------

--
-- Table structure for table `user_rewards`
--

CREATE TABLE `user_rewards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `claimed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_rewards`
--

INSERT INTO `user_rewards` (`id`, `user_id`, `reward_id`, `claimed_at`) VALUES
(1, 3, 3, '2025-10-28 09:15:08');

-- --------------------------------------------------------

--
-- Table structure for table `variant_attributes`
--

CREATE TABLE `variant_attributes` (
  `id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `attribute_name` varchar(100) NOT NULL,
  `attribute_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `variant_attributes`
--

INSERT INTO `variant_attributes` (`id`, `variant_id`, `attribute_name`, `attribute_value`) VALUES
(1, 1, 'Size', '50ml'),
(2, 2, 'Size', '100ml'),
(3, 3, 'Size', '30ml'),
(4, 4, 'Size', '60ml'),
(5, 5, 'Size', '50ml'),
(6, 6, 'Size', '100ml'),
(7, 7, 'Size', '100ml'),
(8, 8, 'Size', '200ml'),
(9, 9, 'Size', '30g'),
(10, 10, 'Size', '60g'),
(11, 11, 'Size', '100ml'),
(12, 12, 'Size', '200ml'),
(13, 13, 'Size', 'Single'),
(14, 14, 'Size', 'Pack of 5'),
(15, 15, 'Size', '50ml'),
(16, 16, 'Size', '100ml'),
(17, 17, 'Size', '50ml'),
(18, 18, 'Size', '100ml'),
(19, 19, 'Size', '15ml'),
(20, 20, 'Size', '30ml'),
(21, 1, 'Size', '50ml'),
(22, 2, 'Size', '100ml');

-- --------------------------------------------------------

--
-- Table structure for table `variant_images`
--

CREATE TABLE `variant_images` (
  `id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`attribute_id`);

--
-- Indexes for table `birthday_gifts`
--
ALTER TABLE `birthday_gifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `cart_item_attributes`
--
ALTER TABLE `cart_item_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_item_id` (`cart_item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `slug_2` (`slug`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `coupon_redemptions`
--
ALTER TABLE `coupon_redemptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `hero_slider`
--
ALTER TABLE `hero_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `highlight_icons`
--
ALTER TABLE `highlight_icons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `loyalty_ledger`
--
ALTER TABLE `loyalty_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `payment_status` (`payment_status`),
  ADD KEY `order_number_2` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `points_ledger`
--
ALTER TABLE `points_ledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ledger_user` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_slug` (`slug`),
  ADD KEY `fk_products_brand` (`brand_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_product_category` (`product_id`,`category_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_highlights`
--
ALTER TABLE `product_highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reward_claims`
--
ALTER TABLE `reward_claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_claim_user` (`user_id`),
  ADD KEY `fk_claim_reward` (`reward_id`),
  ADD KEY `idx_claim_status` (`status`);

--
-- Indexes for table `reward_items`
--
ALTER TABLE `reward_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reward_active` (`is_active`);

--
-- Indexes for table `shipping_states`
--
ALTER TABLE `shipping_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_townships`
--
ALTER TABLE `shipping_townships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `townships`
--
ALTER TABLE `townships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_points`
--
ALTER TABLE `user_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `user_rewards`
--
ALTER TABLE `user_rewards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reward_id` (`reward_id`);

--
-- Indexes for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_variant_images` (`variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `birthday_gifts`
--
ALTER TABLE `birthday_gifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `cart_item_attributes`
--
ALTER TABLE `cart_item_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupon_redemptions`
--
ALTER TABLE `coupon_redemptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hero_slider`
--
ALTER TABLE `hero_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `highlights`
--
ALTER TABLE `highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `highlight_icons`
--
ALTER TABLE `highlight_icons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `loyalty_ledger`
--
ALTER TABLE `loyalty_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `points_ledger`
--
ALTER TABLE `points_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `product_highlights`
--
ALTER TABLE `product_highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reward_claims`
--
ALTER TABLE `reward_claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_items`
--
ALTER TABLE `reward_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_states`
--
ALTER TABLE `shipping_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipping_townships`
--
ALTER TABLE `shipping_townships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `townships`
--
ALTER TABLE `townships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_points`
--
ALTER TABLE `user_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_rewards`
--
ALTER TABLE `user_rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `variant_images`
--
ALTER TABLE `variant_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_item_attributes`
--
ALTER TABLE `cart_item_attributes`
  ADD CONSTRAINT `cart_item_attributes_ibfk_1` FOREIGN KEY (`cart_item_id`) REFERENCES `cart_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `points_ledger`
--
ALTER TABLE `points_ledger`
  ADD CONSTRAINT `fk_ledger_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `fk_pc_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pc_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reward_claims`
--
ALTER TABLE `reward_claims`
  ADD CONSTRAINT `fk_claim_reward` FOREIGN KEY (`reward_id`) REFERENCES `reward_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_claim_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_townships`
--
ALTER TABLE `shipping_townships`
  ADD CONSTRAINT `fk_township_state` FOREIGN KEY (`state_id`) REFERENCES `shipping_states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `townships`
--
ALTER TABLE `townships`
  ADD CONSTRAINT `fk_townships_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_points`
--
ALTER TABLE `user_points`
  ADD CONSTRAINT `fk_user_points_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_attributes`
--
ALTER TABLE `variant_attributes`
  ADD CONSTRAINT `variant_attributes_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;