-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2025 at 07:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myshopify`
--

-- --------------------------------------------------------

--
-- Table structure for table `back_in_stock`
--

CREATE TABLE `back_in_stock` (
  `id` int(11) NOT NULL,
  `main_image` varchar(255) NOT NULL,
  `side_image_1` varchar(255) NOT NULL,
  `side_image_2` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `back_in_stock`
--

INSERT INTO `back_in_stock` (`id`, `main_image`, `side_image_1`, `side_image_2`, `created_at`) VALUES
(1, '1750081348_Mlouye_-_Bags_collection.webp', '1750081348_mlouye-mini-eddy-off-white-1_1c48b857-644c-44b6-825f-b87bc84a9ab6.webp', '1750081348_Mlouye_-_Shoe_collection.webp', '2025-06-16 13:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `size`, `quantity`, `added_at`) VALUES
(3, 1, 7, 'S', 1, '2025-09-11 17:20:45');

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

CREATE TABLE `footer` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `text` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer`
--

INSERT INTO `footer` (`id`, `type`, `text`, `link`, `image`, `created_at`, `updated_at`) VALUES
(8, 'quick_link', 'Bags', '#', NULL, '2025-06-20 13:10:46', '2025-06-20 13:10:46'),
(9, 'quick_link', 'Shoes', '#', NULL, '2025-06-20 13:10:46', '2025-06-20 13:10:46'),
(10, 'quick_link', 'Lookbook', '#', NULL, '2025-06-20 13:10:46', '2025-06-20 13:10:46'),
(11, 'info_link', 'About', '#', NULL, '2025-06-20 13:11:57', '2025-06-20 13:11:57'),
(12, 'info_link', 'Contect Us', '#', NULL, '2025-06-20 13:11:57', '2025-06-20 13:11:57'),
(13, 'info_link', 'Shipping Policy', '#', NULL, '2025-06-20 13:11:57', '2025-06-20 13:11:57'),
(14, 'info_link', 'Blog', '#', NULL, '2025-06-20 13:11:57', '2025-06-20 13:11:57'),
(15, 'mission', 'Quality materials, good designs, craftsmanship and sustainability.', NULL, NULL, '2025-06-20 13:12:18', '2025-06-20 13:12:18'),
(16, 'social', 'fa-brands fa-facebook', ' https://www.facebook.com/shopify/', NULL, '2025-06-20 13:13:47', '2025-06-20 13:13:47'),
(18, 'social', 'fa-brands fa-instagram', 'https://www.instagram.com/shopify/', NULL, '2025-06-20 13:16:27', '2025-06-20 13:16:27'),
(19, 'social', 'fa-brands fa-youtube', 'https://www.youtube.com/@shopify', NULL, '2025-06-20 13:17:06', '2025-06-20 13:17:06'),
(20, 'social', 'fa-brands fa-x-twitter', 'https://twitter.com/shopify', NULL, '2025-06-20 13:17:56', '2025-06-20 13:17:56'),
(21, 'template1', '\"The leather is sourced from environmentally friendly tanneries in Italy, France, and Turkey, where Rure is based and everything is assembled by hand.\"', NULL, 'Mlouye_Refinery_logo.webp', '2025-06-20 13:41:34', '2025-06-20 13:41:34'),
(22, 'template2', '\"The leather is sourced from environmentally friendly tanneries in Italy, France, and Turkey, where Rure is based and everything is assembled by hand.\"', NULL, 'the-cut-logo2_180x_eb657194-39db-4604-852b-1c97f84255ce.webp', '2025-06-20 13:41:59', '2025-06-20 13:41:59');

-- --------------------------------------------------------

--
-- Table structure for table `hero`
--

CREATE TABLE `hero` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `position` enum('left','right') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero`
--

INSERT INTO `hero` (`id`, `image`, `position`, `created_at`) VALUES
(1, 'hero_6856b5527203c.webp', 'left', '2025-06-16 13:38:14'),
(2, 'hero_68501e9ccd67c.webp', 'right', '2025-06-16 13:38:29');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'heyimtannu@gmail.com', '$2y$10$BJMECsiUEx4ERxbJI88yneJUvfPM/ckga5arcZ2CrXASf1SwMmjb.', '2025-09-08 17:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `navbar`
--

CREATE TABLE `navbar` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `nav_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`nav_links`)),
  `header_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `navbar`
--

INSERT INTO `navbar` (`id`, `logo`, `nav_links`, `header_text`) VALUES
(1, 'logo_1750080231.png', '[]', 'Free shipping available on all orders!');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `product_title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `hover_image` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `sizes` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('shoes','cloth') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `sale_price`, `image`, `hover_image`, `status`, `sizes`, `description`, `type`) VALUES
(7, 'Women Washed A-line Green Skirt', 864.00, 799.00, 'SKIRT1.webp', 'SKIRT2.webp', 'in_stock', 'XS,S,M,L,XL,XXL,Free', '<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Brand :&nbsp; SASSAFRAS</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Type :&nbsp; A-line</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Brand Color :&nbsp; Green</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Style Code :&nbsp; SFSKRT30392</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Color :&nbsp; Green</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Ideal For :&nbsp; Women</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Suitable For :&nbsp; Western Wear</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fabric :&nbsp; Denim</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fabric Care :&nbsp; Machine Wash</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sales Package :&nbsp; 1 Skirt</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Net Quantity :&nbsp; 1</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fashion Core :&nbsp; Fashion</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Occasion :&nbsp; Casual</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Pattern :&nbsp;Washed</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Length :&nbsp; Below Knee/Midi</div>\r\n</div>', 'cloth'),
(8, 'Casuals Shoes For Women,men', 945.00, 910.00, 'shoe.webp', 'shoe2.webp', 'in_stock', '5,6,9,10,11,12', '<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Color :&nbsp; White</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Inner material :&nbsp; Mesh</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Outer material :&nbsp; Synthetic</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Model name :&nbsp; Casuals Shoes</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Ideal for :&nbsp; Women</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Occasion :&nbsp; Casual</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Net Quantity :&nbsp;1</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sole material :&nbsp; Airmix/PVC</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Closure :&nbsp; Lace-Ups</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Weight :&nbsp; 500 g (per single Shoe) - Weight of the product may vary depending on size.</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Upper Pttern :&nbsp; Solid</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sales package :&nbsp; 1 pair</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">pack of :&nbsp; 1</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Technology used :&nbsp; Lightweight</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Care instructions :&nbsp; Wipe with a clean, dry cloth to remove the dust</div>\r\n<div class=\"col col-3-12 _9NUIO9\">&nbsp;</div>\r\n<div class=\"col col-3-12 _9NUIO9\">&nbsp;</div>\r\n</div>\r\n<div class=\"_4aGEkW\">Experience great comfort walking in this elegant pair of sneakers shoes from Roadster Brand. This sneakers shoe is perfect for your everyday look. The specially designed and engineered sneakers shoes deliver the comfort your feet deserve along with the performance you seek from long-lasting footwear. The vibrant colors of Roadster sneakers shoes speak your personality and the sturdy style features match your fashion statement</div>', 'shoes'),
(9, 'Men Regular Fit Checkered Spread Collar Casual Shirt', NULL, 428.00, 'shirt.webp', 'shirt2.webp', 'out_of_stock', 'XS,S,M,L,XL', '<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">\r\n<p>Pack of : &nbsp;1<br>Style Code : &nbsp;Waffle Shirt<br>Secondary Color : &nbsp;Beige<br>Closure : &nbsp;Button<br>Fit : &nbsp;Regular<br>Fabric : &nbsp;Cotton Blend<br>Sleeve : &nbsp;Full Sleeve<br>Pattern : &nbsp;Checkered<br>Reversible : &nbsp;No<br>Collar : &nbsp;Spread<br>Color : &nbsp;Beige<br>Fabric Care : &nbsp;Gentle Machine Wash, Do not bleach<br>Suitable For : &nbsp;Western Wear<br>Hem : &nbsp;Curved<br>Other Details : &nbsp;Color may slightly vary due to Graphics Lighting or your system glow As Well as Display Resolution of your Mobile or computer Displays. Order According to your chest size or according to your any branded product to avoid returns.(Please Check the Size Chart Properly for your best fitting )</p>\r\n<p>Other Dimensions : &nbsp;Product Detail: This Full sleeves shirts for men includes a men\'s casual shirt in comfortable cotton blend fabric.</p>\r\n<p>Net Quantity : &nbsp;1</p>\r\n</div>\r\n</div>', 'cloth'),
(10, 'Apple iPhone 16 (Teal, 128 GB)', NULL, 1129.00, 'i.webp', 'i2.webp', 'in_stock', '', '<div class=\"GNDEQ-\">\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">In The Box</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Handset, USB C Charge Cable (1m), Documentation</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Model Number</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">MYED3HN/A</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Model Name</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">iPhone 16</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Color</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Teal</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Browse Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Smartphones</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">SIM Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Dual Sim(Nano + eSIM)</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Hybrid Sim Slot</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">No</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Touchscreen</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">OTG Compatible</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">No</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Sound Enhancements</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Built-in Stereo Speaker</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Display Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Display Size</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">15.49 cm (6.1 inch)</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Resolution</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">2556 x 1179 Pixels</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Resolution Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Super Retina XDR Display</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">GPU</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">New 5 Core</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Display Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">All Screen OLED Display</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Other Display Features</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Dynamic Island, HDR Display, True Tone, Wide Colour (P3), Haptic Touch, Contrast Ratio: 2,000,000:1 (Typical), 1,000 nits Max Brightness (Typical), 1,600 nits Peak Brightness (HDR), 2,000 nits Peak Brightness (Outdoor), 1 nits Minimum Brightness, Fingerprint Resistant Oleophobic Coating, Support for Display of Multiple Languages and Characters Simultaneously</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Os &amp; Processor Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Operating System</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">iOS 18</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Processor Brand</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Apple</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Processor Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">A18 Chip, 6 Core Processor</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Processor Core</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Hexa Core</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Operating Frequency</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">5G FDD NR (Bands n1, n2, n3, n5, n7, n8, n12, n14, n20, n25, n26, n28, n29, n30, n66, n70, n71, n75, n76), 5G TDD NR (Bands n38, n40, n41, n48, n53, n77, n78, n79), 5G NR mmWave (Bands n258, n260, n261), 4G LTE FDD (B1, B2, B3, B4, B5, B7, B8, B12, B13, B14, B17, B18, B19, B20, B25, B26, B28, B29, B30, B32, B66, B71), 4G LTE TDD (B34, B38, B39, B40, B41, B42, B48, B53), 3G UMTS/HSPA+/DC?HSDPA (850 MHz, 900 MHz, 1700 MHz/2100 MHz, 1900 MHz, 2100 MHz), 2G GSM/EDGE (850 MHz , 900 MHz, 1800 MHz, 1900 MHz)</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Memory &amp; Storage Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Internal Storage</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">128 GB</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Camera Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Primary Camera Available</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Primary Camera</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">48MP + 12MP</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Primary Camera Features</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Dual Camera Setup: 48MP Fusion Camera (Focal Length: 26mm, f/1.6 Aperture, Sensor Shift Optical Image Stabilisation, 100% Focus Pixels, Support for Super-High-Resolution Photos (24MP and 48MP)) + 12MP Ultra Wide Camera (Focal Length: 13mm, f/2.4 Aperture, FOV: 120 Degree, 100% Focus Pixels), 2x Optical Zoom-in, 2x Optical Zoom Out, 4x Optical Zoom Range, Camera Control, Sapphire Crystal Lens Cover, Camera Features: Photonic Engine, Deep Fusion, Smart HDR 5, Next Generation Portraits with Focus and Depth Control, Portrait Lighting with Six Effects, Night Mode, Panorama (Upto 63MP), Latest Generation Photographic Styles, Spatial Photos, Macro Photography, Wide Color Capture for Photos and Live Photos, Lens Correction (Ultra Wide), Advanced Red Eye Correction, Auto Image Stabilization, Burst Mode, Photo Geotagging, Image Formats Captured: HEIF and JPEG</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Optical Zoom</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Secondary Camera Available</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Secondary Camera</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">12MP Front Camera</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Secondary Camera Features</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">12MP TrueDepth Camera Setup: (f/1.9 Aperture), Camera Feature: Autofocus with Focus Pixels, Photonic Engine, Deep Fusion, Smart HDR 5, Next Generation Portraits with Focus and Depth Control, Portrait Lighting with Six Effects, Animoji and Memoji, Night Mode, Photographic Styles, Wide Colour Capture for Photos and Live Photos, Lens Correction, Auto Image Stabilisation, Burst Mode, 4K Dolby Video Recording at 24 fps, 25 fps, 30 fps or 60 fps, 1080p Dolby Vision Video Recording at 25 fps, 30 fps or 60 fps, Cinematic Mode Upto 4K Dolby Vision at 30 fps, Slo Mo Video Support for 1080p at 120 fps, Timelapse Video With Stabilization, Night Mode Timelapse, QuickTake Video Upto 4K at 60 fps in Dolby Vision, Cinematic Video Stabilization (4K, 1080p, and 720p), Spatial Audio and Stereo Recording</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Flash</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Rear: True Tone Flash | Front: Retina Flash</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">HD Recording</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Full HD Recording</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Video Recording</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Video Recording Resolution</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Rear Camera: 4K (at 24 fps/ 25 fps/ 30 fps/ 60 fps), 1080P (at 240p /120 fps/60 fps/30 fps/ 25 fps), 720P (at 30 fps) | Front Camera: 4K (at 24 fps/ 25 fps/ 30 fps/ 60 fps), 1080P (at 120 fps/60 fps/30 fps/ 25 fps)</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Digital Zoom</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">10X</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Frame Rate</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">240 fps, 120 fps, 60 fps, 30 fps, 25 fps, 24 fps</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Dual Camera Lens</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Primary Camera</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Call Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Video Call Support</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Speaker Phone</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Connectivity Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Network Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">5G, 4G VOLTE, 4G, 3G, 2G</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Supported Networks</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">5G, 4G VoLTE, 4G LTE, UMTS, GSM</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Internet Connectivity</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">5G, 4G, 3G, Wi-Fi, EDGE</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Bluetooth Support</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Bluetooth Version</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">v5.3</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Wi-Fi</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Wi-Fi Version</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Wi?Fi 7 (802.11be)</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">NFC</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Map Support</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">GPS Support</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Other Details</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Smartphone</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">SIM Size</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Nano Sim + eSIM</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Graphics PPI</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">460 PPI</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Sensors</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Face ID, Barometer, High Dynamic Range Gyro, High-G Accelerometer, Proximity Sensor, Dual Ambient Light Sensors</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Supported Languages</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">English (Australia, UK, US), Chinese (Simplified, Traditional, Traditional - Hong Kong), French (Canada, France), German, Italian, Japanese, Korean, Spanish (Latin America, Spain), Arabic, Bulgarian, Catalan, Croatian, Czech, Danish, Dutch, Finnish, Greek, Hebrew, Hindi, Hungarian, Indonesian, Kazakh, Malay, Norwegian, Polish, Portuguese (Brazil, Portugal), Romanian, Russian, Slovak, Swedish, Thai, Turkish, Ukrainian, Vietnamese</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Browser</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Safari</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Other Features</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Aluminium Design, Latest Generation Ceramic Shield Front, Colour Infused Glass Back, Rated IP68 (Maximum Depth of 6 Metres Upto 30 Minutes) Under IEC Standard 60529, New 16 Core Neural Engine, Enabled by TrueDepth Camera for Facial Recognition, Apple Pay, Apple Card, Emergency SOS via Satellite, Crash Detection, Roadside Assistance via Satellite, Messages via Satellite, Find My via Satellite, 5G (sub 6 GHz and mmWave) with 4x4 MIMO, Gigabit LTE with 4x4 MIMO and LAA, 2x2 MIMO, Second Generation Ultra Wideband Chip, Thread Networking Technology, NFC with Reader Mode, Express Cards with Power Reserve, FaceTime Video Calling Over Cellular or Wi?Fi, FaceTime HD (1080p) Video Calling Over 5G or Wi Fi, Share Experiences like Movies, TV, Music and Other Apps in a FaceTime Call with SharePlay, Screen Sharing, Portrait Mode in FaceTime Video, Spatial Audio, Voice Isolation and Wide Spectrum Microphone Modes, Zoom with Rear Facing Camera, FaceTime Audio, Voice Over LTE (VoLTE), Wi Fi Calling, Voice Isolation and Wide Spectrum Microphone Modes, Spatial Audio Playback, User Configurable Maximum Volume Limit, Supported Formats Include HEVC, H.264 and AV1, HDR with Dolby Vision, HDR10+/HDR10 and HLG, Upto 4K HDR AirPlay for Mirroring, Photos, and Video Out to Apple TV (2nd Generation or Later) or AirPlay Enabled Smart TV, Video Mirroring and Video Out Support: Upto 4K HDR Through Native DisplayPort Output Over USB-C or USB-C Digital AV Adapter (Model A2119, Adapter Sold Separately), USB-C Connector with Support For: Charging, DisplayPort, USB 2 (Upto 480Mb/s), Built-in Rechargeable Lithium-ion Battery, MagSafe Wireless Charging Upto 25W with 30W Adapter or Higher, Qi2 Wireless Charging Upto 15W, Qi Wireless Charging Upto 7.5W, Fast Charge Capable: Upto 50% Charge in Around 30 minutes with 20W Adapter or Higher Paired with USB?C Charging Cable, or 30W Adapter or Higher Paired with MagSafe Charger (All Available Separately), MagSafe: Wireless Charging Upto 25W with 30W Adapter or Higher, Magnet Array, Alignment Magnet, Accessory Identification NFC, Magnetometer, Accessibility: VoiceOver, Zoom, Magnifier, Voice Control, Switch Control, AssistiveTouch, Eye Tracking, RTT and TTY Support, Closed Captions, Live Captions, Personal Voice, Live Speech, Type to Siri, Vocal Shortcuts, Spoken Content, Rating for Hearing Aids: Hearing Aid Compatible, System Requirements: Apple ID (Required for Some Features), Internet Access, Syncing to a Mac or PC Requires: macOS Catalina 10.15 or Later Using the Finder, macOS High Sierra 10.13 to macOS Mojave 10.14.6 Using iTunes 12.9 or Later, Windows 10 or Later Using iTunes 12.12.10 or Later (Free Download From itunes.com/uk)</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Important Apps</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Store, App Store, Books, Calculator, Calendar, Camera, Clips, Clock, Compass, Contacts, FaceTime, Files, Find My, Fitness, Freeform, GarageBand, Health, Home, iMovie, iTunes Store, Keynote, Magnifier, Mail, Maps, Measure, Messages, Music, News, Notes, Numbers, Pages, Passwords, Phone, Photos, Podcasts, Reminders, Safari, Settings, Shortcuts, Stocks, Tips, Translate, TV, Voice Memos, Wallet, Watch, Weather</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">GPS Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">GPS, GLONASS, GALILEO, QZSS, BEIDOU, Digital Compass, Wi?Fi, Cellular, iBeacon Micro Location</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Multimedia Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Audio Formats</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">AAC, APAC, MP3, Lossless, FLAC, Dolby Digital, Dolby Digital Plus, and Dolby Atmos</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Video Formats</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">HEVC, H.264</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Dimensions</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Width</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">71.6 mm</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Height</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">147.6 mm</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Depth</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">7.8 mm</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Weight</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">170 g</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Warranty</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Warranty Summary</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">1 year warranty for phone and 1 year warranty for in Box Accessories.</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Domestic Warranty</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">1 Year</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>', ''),
(12, 'MAYBELLINE NEW YORK Superstay Vinyl Ink Sauce - Barbecue  (Barbecue, 4.2 g)', 11.25, 10.31, 'l.webp', 'l2.webp', 'out_of_stock', '', '<div class=\"DOjaWF YJG4Cf\">\r\n<div class=\"cPHDOP col-6-12\">\r\n<div class=\"U+9u4y\">\r\n<div class=\"xFVion\">\r\n<ul>\r\n<li class=\"_7eSDEz\">Gives a Gloss finish look</li>\r\n<li class=\"_7eSDEz\">Texture is: Liquid</li>\r\n<li class=\"_7eSDEz\">Quantity: 4.2 g</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"DOjaWF gdgoEp\">\r\n<div class=\"cPHDOP col-12-12\">\r\n<div class=\"cvCpHS\">\r\n<div class=\"wXHdFe\">Seller :&nbsp; SURICYBCOMBazaar</div>\r\n<div>\r\n<div class=\"jHlbt-\">\r\n<ul class=\"fke1mx\">\r\n<li class=\"_1u+DIo\">\r\n<div class=\"YhUgfO\">No Returns Applicable</div>\r\n</li>\r\n</ul>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"cPHDOP col-12-12\">\r\n<div class=\"_3Fm-hO\">\r\n<div class=\"_8tSq3v\"><em><strong>Specifications</strong></em></div>\r\n<div class=\"_8tSq3v\">&nbsp;</div>\r\n<div>\r\n<div class=\"GNDEQ-\">\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Form</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Liquid</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Skin Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">All Skin Types</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Finish</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Gloss</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Duration</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Above 15 hrs</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Color</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Red</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Net Quantity</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">4.2 g</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', ''),
(14, 'Simple Mini Tiny Heart Charm Choker Pendant', 11.25, 10.29, 'p1.webp', 'p2.webp', 'in_stock', '', '<ul>\r\n<li class=\"_7eSDEz\">For Girls, Women</li>\r\n<li class=\"_7eSDEz\">Material: Stainless Steel</li>\r\n<li class=\"_7eSDEz\">Chain Included</li>\r\n<li class=\"_7eSDEz\">Collection: Ethnic</li>\r\n</ul>\r\n<p>Simple and Trendy for Dress up or down:You can wear them in date, on an evening party. You are the gorgeous lady. The layering necklaces make great gifts for women,daughter, girlfriend etc.Made with high-quality material, Safe and Durable. Simple, elegant and good. It would be the most eye-catching decoration which fit well with all hairstyles and any style of clothes. These choker Pendant will light up your daily outfit, make you stand out of the crowd. Truly elegant and breathtaking design looks gorgeous and amazing. Short style shows your beautiful clavicle better, charming. Delicate in appearance, you can wear it in any occasions and it makes you eye catching. Great in detail and good for your personal jewelry collection.</p>\r\n<p>&nbsp;</p>\r\n<div class=\"_8tSq3v\"><span style=\"text-decoration: underline;\"><em><strong>Specifications</strong></em></span></div>\r\n<div class=\"_8tSq3v\">&nbsp;</div>\r\n<div>\r\n<div class=\"_1OjC5I\">\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\"><em><strong>General</strong></em></div>\r\n<table class=\"_0ZhAN9\" style=\"height: 1679px; width: 99.8022%;\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Brand</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">vs unique collections</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Base Material</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Stainless Steel</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Plating</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Gold-plated</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Color</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Gold</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Model Number</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Golden Sterling Love Heart Choker Pendant Simple Mini Small Heart Necklace, Tiny Heart Charm Pendant Locket Necklace With Chain for Girl\'s And Women\'s</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Model Name</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Golden Simple Mini Tiny Heart Charm Choker Pendant Locket Necklace With Chain</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Occasion</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Everyday, Love, Party, Workwear</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Chain Included</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Yes</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Collection</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Ethnic</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Sales Package</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">1 X Mini Heart Pendant Locket With Chain</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Net Quantity</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">1</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Brand Color</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Golden</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Chain Length Type</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Short</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Ideal For</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Girls, Women</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Pendant Shape</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Heart</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\" style=\"width: 13.7663%;\">Trend</td>\r\n<td class=\"Izz52n col col-9-12\" style=\"width: 86.2569%;\">\r\n<ul>\r\n<li class=\"HPETK2\">Hearts</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Product Details</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Pack of</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">1</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Type</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Pendant</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Finish</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Glossy</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Other Features</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Keep Jewellery Away from Direct Heat, Water, Perfumes, Deodorants and Other Strong Chemicals as They May React with the Metal or Plating., Occasion &amp; Gift: Suits to Wear in Any Occasion Like Daily, Party , Events and Festival. A Well Researched Mini Heart Chain Pendant, Which Looks Stylish, Elegant and Fashionable, All at Same Time., Skin Friendly - Nickel and Lead Less as Per Standards That Makes It Very Skin Friendly. the Plating Is Non-Allergic and Safe for All Environments., A versatile design which can be paired with both casual and formal outfits, in both western and indian attires.</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<div class=\"GNDEQ-\">\r\n<div class=\"_4BJ2V+\">Chain Features</div>\r\n<table class=\"_0ZhAN9\">\r\n<tbody>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Chain Material</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Stainless Steel</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Chain Finish</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Golden</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr class=\"WJdYP6 row\">\r\n<td class=\"+fFi1w col col-3-12\">Chain Plating</td>\r\n<td class=\"Izz52n col col-9-12\">\r\n<ul>\r\n<li class=\"HPETK2\">Golden</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n</div>\r\n</div>', ''),
(15, 'Solid/Plain Bollywood Silk Blend Saree  (Brown)', 28.25, 25.91, 'sa1.webp', 'sa2.webp', 'in_stock', '', '<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Brand :&nbsp; Vichitra</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Style Code :&nbsp; BHAGWAN_ARUNA_001_COFFEE_750</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Pattern :&nbsp; Solid/Plain</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Pack of :&nbsp; 1</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Occasion :&nbsp; Casual, Party &amp; Festive, Wedding, Wedding &amp; Festive</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Decorative Material :&nbsp; Lace</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Construction Type :&nbsp; Machine</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fabric Care : c&nbsp;Dry Clean Only</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fabric :&nbsp; Silk Blend</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Type :&nbsp; Bollywood</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Blouse Piece :&nbsp; Unstitched</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sari Style :&nbsp; Regular Sari</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Net Quantity :&nbsp; 1</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Blouse Pattern :&nbsp; Embroidered</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Blouse Fabric :&nbsp; Silk</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Border Details :&nbsp; Lace</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Border Length :&nbsp; Thin/Small</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Color :&nbsp; Brown</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Brand Color :&nbsp; Coffee</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Handloom Product :&nbsp; No</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Ideal For :&nbsp; Women</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Print Type :&nbsp; Solid</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sales Package :&nbsp; 1 Saree, 1 Blouse</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sari Purity :&nbsp; Blend</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Trend :&nbsp; Day Festive, For Daywear</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Uniform :&nbsp; No</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sari Length :&nbsp; 5.5m</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Blouse Piece Length :&nbsp; 0.8 m</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Weight :&nbsp; 0.3 kg</div>\r\n<div class=\"col col-3-12 _9NUIO9\">&nbsp;</div>\r\n<div class=\"col col-3-12 _9NUIO9\">&nbsp;</div>\r\n</div>\r\n<div class=\"_4aGEkW\">Crafted from a premium silk blend fabric, it offers a luxurious feel with a subtle sheen that enhances its elegance. The saree is beautifully highlighted with a minimal golden zari border and tasteful tassels at the pallu, adding charm and finishing touches to your ensemble.Paired with a contrast floral blouse, as pictured, or stitched as per your preference, this saree is perfect for weddings, festive functions, parties, and formal events. The smooth fall and lightweight texture make it a pleasure to wear for long hours.</div>', ''),
(16, 'Men 3 piece suit for men Solid Suit', NULL, 89.68, 'su1.webp', 'su2.webp', 'in_stock', 'M,L,XXL', '<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Ideal For :&nbsp; Men</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Occasion :&nbsp; Wedding ,Meeting</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Pattern :&nbsp; Solid</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Color :&nbsp; Black</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Type :&nbsp; 3 piece suit for men</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fabric :&nbsp; Polycotton</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fit :&nbsp; Regular</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Brand :&nbsp; METRONAUT</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Brand Color :&nbsp; Black</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Size :&nbsp; 36</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Trouser Fit :&nbsp; Regular Fit</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Trouser Rise :&nbsp; Mid Rise</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Trouser Closure :&nbsp; Button</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Belt Loops :&nbsp; Yes</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Fabric Care :&nbsp; Machine wash as per tag</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Style Code :&nbsp; MT-3PCS SUIT-BLACK</div>\r\n</div>\r\n<div class=\"row\">\r\n<div class=\"col col-3-12 _9NUIO9\">Sales Package :&nbsp; 1 Blazer, 1 Trouser, 1 Waistcoat</div>\r\n</div>', 'cloth');

-- --------------------------------------------------------

--
-- Table structure for table `products_video`
--

CREATE TABLE `products_video` (
  `id` int(11) NOT NULL,
  `video_title` varchar(255) NOT NULL,
  `youtube_id` varchar(20) NOT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products_video`
--

INSERT INTO `products_video` (`id`, `video_title`, `youtube_id`, `thumbnail_image`) VALUES
(1, 'Bags', 'yXWXFzjVnt8', '1750254222_Mlouye_video.webp');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`) VALUES
(10, 7, '1751029926_SKIRT4.webp'),
(11, 7, '1751029926_SKIRT3.webp'),
(12, 7, '1751029926_SKIRT5.webp'),
(13, 8, '1751032453_shoe3.webp'),
(14, 8, '1751032453_shoe4.webp'),
(15, 8, '1751032453_shoes5.webp'),
(16, 8, '1751032453_shoe6.webp'),
(17, 8, '1751032453_shoes7.webp'),
(18, 9, '1751033311_shirt4.webp'),
(19, 9, '1751033311_shirt3.webp'),
(20, 10, '1751114445_i3.webp'),
(21, 10, '1751114445_i4.webp'),
(22, 10, '1751114445_i5.webp'),
(23, 10, '1751114445_i6.webp'),
(27, 12, '1751115982_l4.webp'),
(28, 12, '1751115982_l3.webp'),
(29, 12, '1751115983_l5.webp'),
(30, 12, '1751115983_l6.webp'),
(31, 12, '1751115984_l7.webp'),
(35, 14, '1751116949_p3.webp'),
(36, 14, '1751116949_p5.webp'),
(37, 14, '1751116949_p4.webp'),
(38, 14, '1751116949_p6.webp'),
(39, 15, '1751286177_sa3.webp'),
(40, 15, '1751286177_sa4.webp'),
(41, 15, '1751286177_sa6.webp'),
(42, 15, '1751286177_sa7.webp'),
(43, 15, '1751286177_sa8.webp'),
(44, 15, '1751286177_sa9.webp'),
(45, 16, '1751286541_su3.webp'),
(46, 16, '1751286541_su4.webp');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `created_at`, `phone`, `address`, `city`, `state`, `country`) VALUES
(1, 'tannu', 'heyimtannu@gmail.com', '$2y$10$YhfFQTnitXPIxn2ALoroJeOJeVpEbZMLj9T/WE7JzJgqTlk3WvHt2', '2025-09-08 17:47:25', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `back_in_stock`
--
ALTER TABLE `back_in_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero`
--
ALTER TABLE `hero`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `position` (`position`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `navbar`
--
ALTER TABLE `navbar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_video`
--
ALTER TABLE `products_video`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `back_in_stock`
--
ALTER TABLE `back_in_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `footer`
--
ALTER TABLE `footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `hero`
--
ALTER TABLE `hero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products_video`
--
ALTER TABLE `products_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
