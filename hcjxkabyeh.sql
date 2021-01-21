-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `general_settings`;
CREATE TABLE `general_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_subtitle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_footer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `general_settings` (`id`, `site_name`, `site_title`, `site_subtitle`, `site_desc`, `site_footer`, `created_at`, `updated_at`) VALUES
(1,	'Blue Care Hub',	'Mambo Dubai Multivendor Marketplace',	'Your Awesome Marketplace',	'Buy . Sell . Admin',	'© Copyright 2020 - City of UAE Dubai. All rights reserved.',	'2021-01-17 05:24:39',	'2021-01-17 05:24:39');

DROP TABLE IF EXISTS `localization_settings`;
CREATE TABLE `localization_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `localization_settings` (`id`, `language`, `currency`, `created_at`, `updated_at`) VALUES
(1,	'aed',	'AED',	'2021-01-17 05:24:40',	'2021-01-17 05:24:40');

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'2014_10_12_000000_create_users_table',	1),
(2,	'2014_10_12_100000_create_password_resets_table',	1),
(3,	'2018_09_17_111127_create_roles_table',	1),
(4,	'2018_09_17_111825_create_role_user_table',	1),
(5,	'2018_09_22_021222_create_general_settings_table',	1),
(6,	'2018_10_08_113434_create_localization_settings_table',	1),
(7,	'2021_01_17_142044_create_verify_emails_table',	1);

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1,	'admin',	'2021-01-17 05:24:39',	'2021-01-17 05:24:39'),
(2,	'careowner',	'2021-01-17 05:24:39',	'2021-01-17 05:24:39'),
(3,	'caregiver',	'2021-01-17 05:24:39',	'2021-01-17 05:24:39');

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1,	1,	3,	'2021-01-17 20:57:47',	'2021-01-17 20:57:47'),
(2,	2,	2,	'2021-01-17 21:03:53',	'2021-01-17 21:03:53'),
(3,	3,	3,	'2021-01-18 05:36:44',	'2021-01-18 05:36:44'),
(4,	4,	3,	'2021-01-18 14:21:32',	'2021-01-18 14:21:32');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middlename` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `street1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_logo` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` int(11) DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sign_date` datetime NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `email`, `gender`, `birthday`, `street1`, `street2`, `city`, `zip_code`, `state`, `profile_logo`, `email_verified_at`, `password`, `phone_number`, `sign_date`, `remember_token`, `created_at`, `updated_at`) VALUES
(1,	'Nemanja',	NULL,	'Jovanovic',	'nemanja1029',	'kingfullstack@yandex.com',	0,	'2020-12-29',	NULL,	NULL,	NULL,	NULL,	NULL,	'z30D9igaEzM27PhHPPqOCv9RdYGb9rn7QWypo8bW.png',	NULL,	'$2y$10$rRwai.9fJbS/m8UQ.p1noOEEtGaw.1L2koBIwnGv4Y/L9kcmGZ722',	'123123123',	'2021-01-17 08:57:47',	'hCz4z0qT2Ei8hB7hMEcg2HkGk83AAvDoeFp6kpEbw4vEzStcUT6f8GeLOygI',	'2021-01-17 20:57:47',	'2021-01-17 20:57:47'),
(2,	'Anastasia',	NULL,	'Owera',	'anastasia',	'king.fullstack.727@yandex.com',	0,	'2020-12-27',	NULL,	NULL,	NULL,	NULL,	NULL,	'FGbDIy7928pYpwtYssY82eMu7KkJXfyjrWMo0Hxd.jpeg',	NULL,	'$2y$10$TGf2mqhQRKFOKf8rmhxqM.eGY5zNKYSRZyZdi9gjxCAPebp4mLgeC',	'123129842',	'2021-01-17 09:03:53',	'zZMeUvrSZdQicGhxqLvKC36Bqfg6UwwqxeGTYyMhjdAKygDKS8tBZaOcgvyk',	'2021-01-17 21:03:53',	'2021-01-17 21:03:53'),
(3,	'Nebiyu',	NULL,	'Mehari',	'nem2020',	'nebiyu@gmail.com',	0,	'1979-10-19',	NULL,	NULL,	NULL,	NULL,	NULL,	'yBpISiG9s9uQfR2sTIvZZdFQpt95W4MZkLuk8cBn.png',	NULL,	'$2y$10$fCVCN0etRl22kfY7moCCr.WOjjgRCss84D37C4CYA/eR.BbY.EhBm',	'09090909',	'2021-01-18 05:36:44',	NULL,	'2021-01-18 05:36:44',	'2021-01-18 05:36:44'),
(4,	'Aman',	NULL,	'Kidane',	'Amankidane',	'amanelsa@yahoo.com',	0,	'1982-01-18',	NULL,	NULL,	NULL,	NULL,	NULL,	'R0v6X5U61LYhfTYEYXBrv9Sn4v49UDdqYOTeHTHO.jpeg',	NULL,	'$2y$10$uFVMCSKsidOrKY3IQQBV7OCSVuD1jfaNld8FN1csGPf3mMOwhlxJ.',	'2068329506',	'2021-01-18 02:21:32',	NULL,	'2021-01-18 14:21:32',	'2021-01-18 14:21:32');

DROP TABLE IF EXISTS `verify_emails`;
CREATE TABLE `verify_emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verify_code` int(11) NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `verify_emails` (`id`, `email`, `verify_code`, `password`, `created_at`, `updated_at`) VALUES
(1,	'kingfullstack@yandex.com',	802312,	'111111',	'2021-01-17 20:29:54',	'2021-01-17 20:38:15'),
(2,	'king.fullstack.727@yandex.com',	752195,	'111111',	'2021-01-17 21:01:15',	'2021-01-17 21:01:15'),
(3,	'nebiyu@gmail.com',	813208,	'dubai2020',	'2021-01-18 05:34:24',	'2021-01-18 05:34:24'),
(4,	'amanelsa@yahoo.com',	731897,	'111111',	'2021-01-18 14:16:53',	'2021-01-18 14:16:53'),
(5,	'jovanovic.nemanja.1029@gmail.com',	133104,	'111111',	'2021-01-19 05:40:18',	'2021-01-19 05:40:18');

-- 2021-01-21 11:42:15
