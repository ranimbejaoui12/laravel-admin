-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 13 mai 2026 à 12:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `laravel`
--

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `motivation` text NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `hospital_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `attestations`
--

CREATE TABLE `attestations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL DEFAULT 'attestation_travail',
  `status` enum('pending','accepted','refused') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `specialty` varchar(191) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `specialty_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctor_patient`
--

CREATE TABLE `doctor_patient` (
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `address` varchar(191) NOT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `address`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'public hospital beja', 'beja', '1778429443.jpg', '2026-05-10 15:10:44', '2026-05-10 15:10:44');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Accepted','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_10_17_182010_create_patients_table', 1),
(6, '2022_10_19_120120_create_scans_table', 1),
(7, '2022_10_20_142615_create_orientation_letters_table', 1),
(8, '2022_10_20_143728_add_patient_id_to_orientation_letters_table', 1),
(9, '2022_10_21_165843_create_appointments_table', 1),
(10, '2022_10_22_224954_create_prescriptions_table', 1),
(11, '2022_10_28_143456_create_doctor_patient_table', 1),
(12, '2022_11_01_175122_add_doctor_id_to_prescriptions_table', 1),
(13, '2022_11_01_184522_add_doctor_id_to__orientation_letter_table', 1),
(14, '2022_11_01_185750_add_doctor_id_to_scans_table', 1),
(15, '2026_02_18_050635_create_doctors_table', 1),
(16, '2026_02_23_050036_add_image_to_users_table', 1),
(17, '2026_02_23_053954_add_specialty_and_phone_to_users_table', 1),
(18, '2026_02_25_044513_create_hospitals_table', 1),
(19, '2026_02_27_050520_add_visit_fields_to_patients_table', 1),
(20, '2026_03_06_214104_add_password_to_doctors_table', 1),
(21, '2026_03_12_035034_add_no_social_to_patients_table', 1),
(22, '2026_03_12_040711_add_dob_to_patients_table', 1),
(23, '2026_03_14_124059_add_user_id_to_doctors_table', 1),
(24, '2026_03_14_140907_add_doctor_id_to_patients_table', 1),
(25, '2026_03_18_205455_add_status_to_users_table', 1),
(26, '2026_03_26_182139_add_notes_to_users_table', 1),
(27, '2026_03_27_161610_create_leave_requests_table', 1),
(28, '2026_03_27_202702_update_appointments_table', 1),
(29, '2026_03_27_210840_add_is_new_to_appointments_table', 1),
(30, '2026_03_28_001937_make_doctor_id_nullable_in_appointments_table', 1),
(31, '2026_04_03_121419_create_attestations_table', 1),
(32, '2026_04_06_175015_modify_hospital_id_nullable', 1),
(33, '2026_04_07_080313_add_description_to_attestations_table', 1),
(40, '2026_04_13_163059_add_code_to_password_resets_table', 1),
(41, '2026_04_14_110543_remove_token_from_password_resets', 1),
(43, '2026_04_07_082045_fix_attestations_doctor_fk', 2),
(44, '2026_04_07_194054_drop_description_from_attestations_table', 3),
(45, '2026_04_08_130906_create_jobs_table', 3),
(46, '2026_04_08_205249_create_notifications_table', 3),
(47, '2026_04_08_234338_create_specialties_table', 3),
(48, '2026_04_09_013228_add_appointment_id_to_notifications_table', 3),
(49, '2026_04_14_122549_recreate_status_in_appointments_table', 4),
(50, '2026_05_08_170015_create_password_resets_table', 4),
(51, '2026_05_08_173100_add_code_to_password_resets_table', 5),
(52, '2026_05_09_160411_add_token_to_password_resets_table', 6);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `appointment_id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('58575ea8-bc9f-44a5-80aa-095c49fb8760', NULL, 'App\\Notifications\\NewRequestForAdmin', 'App\\Models\\User', 2, '{\"type\":\"doctor_register\",\"title\":\"New Doctor Registration\",\"message\":\"ranim requested to join the platform\",\"doctor_id\":7,\"url\":\"http:\\/\\/192.168.1.148:8000\\/users\"}', NULL, '2026-05-09 14:46:15', '2026-05-09 14:46:15'),
('f7c2641b-a76d-44ec-b20c-69646aa1d780', NULL, 'App\\Notifications\\NewRequestForAdmin', 'App\\Models\\User', 2, '{\"type\":\"doctor_register\",\"title\":\"New Doctor Registration\",\"message\":\"ranim requested to join the platform\",\"doctor_id\":6,\"url\":\"http:\\/\\/192.168.1.148:8000\\/users\"}', NULL, '2026-05-09 14:45:08', '2026-05-09 14:45:08');

-- --------------------------------------------------------

--
-- Structure de la table `orientation_letters`
--

CREATE TABLE `orientation_letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `content` text NOT NULL,
  `patient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) DEFAULT NULL,
  `otp` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `code` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `otp`, `created_at`, `code`) VALUES
('ranimbejaou@gmail.com', 'VA5yrR8dmGVOvN79n29ebbnBYhEq3MiAbyspQqsQsrXzS00vFFZjHWwcFryK', '433362', '2026-05-09 15:07:53', '433362'),
('ranimbejaoui74@gmail.com', 'htvwRnlYAXuSy2AaUAjqCaRyg5YbRJkDVN5oGUVC4nDKRGuNnEO0XEAUq97R', '692880', '2026-05-09 15:22:39', '692880');

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `lastname` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `noSSocial` int(11) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `diseases` varchar(191) DEFAULT NULL,
  `allergies` varchar(191) DEFAULT NULL,
  `antecedents` varchar(191) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_visit` date DEFAULT NULL,
  `next_appointment` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`id`, `doctor_id`, `user_id`, `name`, `lastname`, `username`, `noSSocial`, `dob`, `phone`, `email`, `diseases`, `allergies`, `antecedents`, `comments`, `created_at`, `updated_at`, `last_visit`, `next_appointment`) VALUES
(1, NULL, 1, 'ranim', 'bejaoui', 'ranimbejaoui', 12345678, '2000-01-01', '25741147', 'ranim@gmail.com', NULL, NULL, NULL, NULL, '2026-05-07 08:51:52', '2026-05-07 08:51:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '49837ede8b0ed4ae957117e1a38b2febea02391121806a8898d2784a5f79f6fb', '[\"*\"]', NULL, NULL, '2026-05-07 08:52:00', '2026-05-07 08:52:00'),
(2, 'App\\Models\\User', 1, 'auth_token', '294df083664afed1c482147d6028f4b090d150eba570459b31cabb6ba293779a', '[\"*\"]', NULL, NULL, '2026-05-07 08:52:32', '2026-05-07 08:52:32'),
(3, 'App\\Models\\User', 1, 'auth_token', '9fccffa0528b4ad08273a1c131590b31933f7696e609e6d55c6242f64b886bf7', '[\"*\"]', NULL, NULL, '2026-05-07 09:16:22', '2026-05-07 09:16:22'),
(4, 'App\\Models\\User', 1, 'auth_token', '4155eceb5bcb6c108f0d28a3abfd238211af7a1dca5be0b2708e0b460a4b5b12', '[\"*\"]', NULL, NULL, '2026-05-07 09:16:25', '2026-05-07 09:16:25'),
(5, 'App\\Models\\User', 1, 'auth_token', '27a4ac51922dcc64e94b8ad3b184ab57f709353a5b84156030afa30fec304707', '[\"*\"]', NULL, NULL, '2026-05-07 09:16:43', '2026-05-07 09:16:43'),
(6, 'App\\Models\\User', 1, 'auth_token', 'c51ddd9876adf61cb88c5643750e58674dbdb251b16a617658a9db69963249f9', '[\"*\"]', NULL, NULL, '2026-05-07 09:32:10', '2026-05-07 09:32:10'),
(7, 'App\\Models\\User', 1, 'auth_token', 'fca50ddc1e949df91d32647ba04c5b82902a5de5360b44dc7c87bcd99c80078a', '[\"*\"]', NULL, NULL, '2026-05-07 09:32:26', '2026-05-07 09:32:26'),
(8, 'App\\Models\\User', 1, 'auth_token', 'ae4a4315d4bf13f834845c2ae9e13a3dfdabdc38684ea6299a342dc6fe773ebe', '[\"*\"]', NULL, NULL, '2026-05-07 09:32:35', '2026-05-07 09:32:35'),
(9, 'App\\Models\\User', 1, 'auth_token', '157e4ec8297c64d722f08caff20bab18d00b8dc7e55dbab49b638884cd49f5a6', '[\"*\"]', NULL, NULL, '2026-05-07 09:41:26', '2026-05-07 09:41:26'),
(10, 'App\\Models\\User', 1, 'auth_token', 'df21d3fa11ccfa72c9fadf691fb91bf92ec448cce7641617aeb64a9951c09c0b', '[\"*\"]', NULL, NULL, '2026-05-07 09:41:35', '2026-05-07 09:41:35'),
(11, 'App\\Models\\User', 1, 'auth_token', '86a4a77bc484ccbc37a5b73bb2f1e7640a4b69957538156dbc73e344b70f0227', '[\"*\"]', NULL, NULL, '2026-05-07 09:47:16', '2026-05-07 09:47:16'),
(12, 'App\\Models\\User', 1, 'auth_token', 'de53e1a7b82206174ebcb6a67320b927ab4dcc1512794bf355e38dda440d5f86', '[\"*\"]', NULL, NULL, '2026-05-07 09:47:20', '2026-05-07 09:47:20'),
(13, 'App\\Models\\User', 1, 'auth_token', '449cec5e19d394de7f9b107d273148374751151b1263ac54fe7c3516f93c4e3b', '[\"*\"]', NULL, NULL, '2026-05-07 09:47:31', '2026-05-07 09:47:31'),
(14, 'App\\Models\\User', 1, 'auth_token', 'cb5ae1e1519d7d2ce7da6242d6d0cca17270f23c5d39b07a809f13babeedc4e5', '[\"*\"]', NULL, NULL, '2026-05-07 09:47:44', '2026-05-07 09:47:44'),
(15, 'App\\Models\\User', 1, 'auth_token', 'ef2f4f9212e2534e17b01fa2176126961e4adf41656ac7bb4d44b1f61a8cfeb8', '[\"*\"]', NULL, NULL, '2026-05-07 09:48:24', '2026-05-07 09:48:24'),
(16, 'App\\Models\\User', 1, 'auth_token', '8e561bbd07c3725848775e8c295caa7142f2e42fc25918d832d25ff4f94356ba', '[\"*\"]', NULL, NULL, '2026-05-07 09:48:37', '2026-05-07 09:48:37'),
(17, 'App\\Models\\User', 1, 'auth_token', '4239067977de20bd0bc201f9c5addfbfd1f6d024b85ce44afe768ddd1c23f969', '[\"*\"]', NULL, NULL, '2026-05-07 09:48:39', '2026-05-07 09:48:39'),
(18, 'App\\Models\\User', 1, 'auth_token', 'ad8ac33890b1acc48d7146e23ee84f3dbf60fbe228c445a175885507de3e9e67', '[\"*\"]', NULL, NULL, '2026-05-07 09:48:40', '2026-05-07 09:48:40'),
(19, 'App\\Models\\User', 1, 'auth_token', 'fd7bb129f587f62875e8382d84a29b2e826e612c0477ef18f6e31bfd439cddbc', '[\"*\"]', NULL, NULL, '2026-05-07 09:49:10', '2026-05-07 09:49:10'),
(20, 'App\\Models\\User', 1, 'auth_token', '4a84c6a4136bc2f90d27c466d1359483c1cb675122a1db48d54cec41fd8d707a', '[\"*\"]', NULL, NULL, '2026-05-07 09:49:20', '2026-05-07 09:49:20'),
(21, 'App\\Models\\User', 1, 'auth_token', '51f41792f63acde1ed0db1813a8652b4f17c8e45f67766a19e4fef65254ba3c4', '[\"*\"]', NULL, NULL, '2026-05-07 09:57:40', '2026-05-07 09:57:40'),
(22, 'App\\Models\\User', 1, 'auth_token', '485c9d230b508589e21c98d3b0cad3a771b528774d10761f41da5a9bd980fe1e', '[\"*\"]', NULL, NULL, '2026-05-07 09:57:48', '2026-05-07 09:57:48'),
(23, 'App\\Models\\User', 1, 'auth_token', '66c88fa91aa284f6b589820a954f47ddb2370ae7175cd617d658036bb4178320', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:25', '2026-05-07 09:58:25'),
(24, 'App\\Models\\User', 1, 'auth_token', '7222c2d0e26d64eab39274aac321caf69d1fa388104f5366bba89f55b39a7a08', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:28', '2026-05-07 09:58:28'),
(25, 'App\\Models\\User', 1, 'auth_token', '233023a9c41f3d377e1f41e8f1121eadfcb7cb901243274ab0d66983b693c110', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:31', '2026-05-07 09:58:31'),
(26, 'App\\Models\\User', 1, 'auth_token', '90228f8f93e744e1e5fedc2584fdb3da2be230773575529bd55f07e412bd57a6', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:32', '2026-05-07 09:58:32'),
(27, 'App\\Models\\User', 1, 'auth_token', '0c2d922eafe7d701de23831598c457f9d4c33fadb712c1e76388d6c4fae1bac0', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:33', '2026-05-07 09:58:33'),
(28, 'App\\Models\\User', 1, 'auth_token', '1d38a9c90bc46c0a66e5e6ccaf65552113ccd4d0313ab82856cb362447bae2b6', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:34', '2026-05-07 09:58:34'),
(29, 'App\\Models\\User', 1, 'auth_token', '8a18ca9ae05f271bbf4c887ecb97d137298a51bb1dd66fa5ddff49fa155c37aa', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:35', '2026-05-07 09:58:35'),
(30, 'App\\Models\\User', 1, 'auth_token', 'eff5df354a858b1dc065387fcefce730bad215d84f6350fb07f2294852dc7577', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:35', '2026-05-07 09:58:35'),
(31, 'App\\Models\\User', 1, 'auth_token', '9c385651022dd95857d9fed8cc8b224c0d410f46999b3c78f300c9f3db14b507', '[\"*\"]', NULL, NULL, '2026-05-07 09:58:48', '2026-05-07 09:58:48'),
(32, 'App\\Models\\User', 1, 'auth_token', '900eddd48f4bcb6d18fb42448aaec2bfd227a06329aaff874e408d040ecbf736', '[\"*\"]', NULL, NULL, '2026-05-07 10:17:06', '2026-05-07 10:17:06'),
(33, 'App\\Models\\User', 1, 'auth_token', 'b032bcdc6cfa18c99453c2fb86b069c91af267eb579c61381f3a31339d6cfa1b', '[\"*\"]', NULL, NULL, '2026-05-07 10:17:13', '2026-05-07 10:17:13'),
(34, 'App\\Models\\User', 4, 'auth_token', '745e43b253b653135d57860d7bea522e8efb541c6382391187c09804a9a1c106', '[\"*\"]', '2026-05-08 14:32:21', NULL, '2026-05-08 14:29:24', '2026-05-08 14:32:21'),
(35, 'App\\Models\\User', 5, 'auth_token', '00ac572a4d5b0c1f4dba791546cef2db5196a9f2d501789a39bcd7482c44a8b7', '[\"*\"]', '2026-05-09 14:01:39', NULL, '2026-05-09 14:01:36', '2026-05-09 14:01:39'),
(36, 'App\\Models\\User', 7, 'auth_token', 'b8e3f27eb97011f47256f34c9957e88cb5f54d7afc5ab8b58f4c935ccb0ff45c', '[\"*\"]', '2026-05-09 14:47:16', NULL, '2026-05-09 14:47:13', '2026-05-09 14:47:16'),
(37, 'App\\Models\\User', 1, 'auth_token', 'fce0991e45e223f8fabec41ebf77523b1de050bb1f5cceecb35098ccf26ff954', '[\"*\"]', NULL, NULL, '2026-05-10 15:08:19', '2026-05-10 15:08:19'),
(38, 'App\\Models\\User', 5, 'auth_token', '3deda933f9d8c6f4b918e6ee5c860e7ae9a64c90bf2846a5e27418cdefa265f1', '[\"*\"]', '2026-05-10 15:28:20', NULL, '2026-05-10 15:09:01', '2026-05-10 15:28:20'),
(39, 'App\\Models\\User', 1, 'auth_token', '11c2a12eefed6ca7f5cf9257cb9ad5f694eeddc721b971004285c183d4045060', '[\"*\"]', NULL, NULL, '2026-05-10 15:13:19', '2026-05-10 15:13:19'),
(40, 'App\\Models\\User', 1, 'auth_token', '6fe643758dbed8d580730cfb4d1607b74ceb4244c30f5d1a64d424c4e5f408ee', '[\"*\"]', NULL, NULL, '2026-05-10 15:13:39', '2026-05-10 15:13:39'),
(41, 'App\\Models\\User', 1, 'auth_token', '1f0aa65ef8b97bd49b5af466a0d12f40fe36fd4fca4b3fff385bf165148122bd', '[\"*\"]', NULL, NULL, '2026-05-10 15:13:51', '2026-05-10 15:13:51'),
(42, 'App\\Models\\User', 1, 'auth_token', 'f6bd0aacf8a4f8c31e344bcf9ad05062e202992e441c90e0a21287dedb179bd8', '[\"*\"]', NULL, NULL, '2026-05-10 15:13:55', '2026-05-10 15:13:55'),
(43, 'App\\Models\\User', 1, 'auth_token', '9b93f89b5646ec09753ab590a00ea5b81d123a88d2ebf7c7907c9c0773eeeb59', '[\"*\"]', '2026-05-10 15:26:27', NULL, '2026-05-10 15:14:32', '2026-05-10 15:26:27'),
(44, 'App\\Models\\User', 1, 'auth_token', '3488a99bf0da6447fa94acf255149f56b0bf9b5a9cfb02aa9a1c60387660e85b', '[\"*\"]', NULL, NULL, '2026-05-10 15:35:07', '2026-05-10 15:35:07'),
(45, 'App\\Models\\User', 1, 'auth_token', 'b21585d418e3aa1477689b37aa3a9c6ad79c8f41adb31acf7d6d122e453e9809', '[\"*\"]', NULL, NULL, '2026-05-10 15:36:04', '2026-05-10 15:36:04'),
(46, 'App\\Models\\User', 1, 'auth_token', '07163f1b2f2d204ef57ce6f43a698a544d0da4ce0531040f89d9bb7c86ef00db', '[\"*\"]', NULL, NULL, '2026-05-10 15:36:20', '2026-05-10 15:36:20'),
(47, 'App\\Models\\User', 1, 'auth_token', '51a17351220bbe6c182470cfaa3b295570f4156b9e276746524ed087462be416', '[\"*\"]', NULL, NULL, '2026-05-10 15:36:32', '2026-05-10 15:36:32'),
(48, 'App\\Models\\User', 1, 'auth_token', '78a08135f872e81d5b3192c17e92b9e3ffc7577719877049a3814028d58a467b', '[\"*\"]', NULL, NULL, '2026-05-10 15:36:45', '2026-05-10 15:36:45'),
(49, 'App\\Models\\User', 1, 'auth_token', 'f7a261385ed8eb51305f661d49c0e37babd6259ee9da933fbc6aeb1f74074091', '[\"*\"]', NULL, NULL, '2026-05-10 15:36:48', '2026-05-10 15:36:48'),
(50, 'App\\Models\\User', 1, 'auth_token', '33bd7c200252f9ecd3e763db448b21a3c8e8d646005b7744dbe8140704a3f334', '[\"*\"]', NULL, NULL, '2026-05-10 15:36:51', '2026-05-10 15:36:51'),
(51, 'App\\Models\\User', 1, 'auth_token', '759019476c01dcf87d1b835205db81b5dadfcc850eeb2933d8017db23f02a3d0', '[\"*\"]', NULL, NULL, '2026-05-10 15:37:15', '2026-05-10 15:37:15'),
(52, 'App\\Models\\User', 1, 'auth_token', '565af2720d1d06a8c7670d47504c8ce9d3e670fdc2fe4b35046d760e06bd5bd1', '[\"*\"]', NULL, NULL, '2026-05-10 15:37:53', '2026-05-10 15:37:53'),
(53, 'App\\Models\\User', 1, 'auth_token', '34f9fe5335d2a9e1be7ef855560e10e2a17a49336ca81f0ac6475743ca5614fc', '[\"*\"]', NULL, NULL, '2026-05-10 15:38:39', '2026-05-10 15:38:39'),
(54, 'App\\Models\\User', 1, 'auth_token', '528c42fba871ce68ec024b687943acd441cbb4edec087f942689cfa26103aeb0', '[\"*\"]', NULL, NULL, '2026-05-10 15:39:05', '2026-05-10 15:39:05'),
(55, 'App\\Models\\User', 1, 'auth_token', 'd6ea94f860273b80e06abe65de748e2011b19760dd62f6ccda095355316d90ab', '[\"*\"]', NULL, NULL, '2026-05-10 15:39:15', '2026-05-10 15:39:15'),
(56, 'App\\Models\\User', 1, 'auth_token', 'a399a4b97991960399790339ef8c8e65dc464b4a5e55042b6c74d8337a3c886b', '[\"*\"]', NULL, NULL, '2026-05-10 15:39:19', '2026-05-10 15:39:19'),
(57, 'App\\Models\\User', 1, 'auth_token', '487d6d8cf766ca22023b7cef2237ca9a894b3d7a82fa8b2d292538683d8f9a78', '[\"*\"]', NULL, NULL, '2026-05-10 15:39:28', '2026-05-10 15:39:28'),
(58, 'App\\Models\\User', 1, 'auth_token', '82ac0d2d24a431e82d8175b946198d62fc2c49567d4670990682c1727f2880fa', '[\"*\"]', NULL, NULL, '2026-05-10 15:39:32', '2026-05-10 15:39:32');

-- --------------------------------------------------------

--
-- Structure de la table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `medication` varchar(191) NOT NULL,
  `dosage` varchar(191) NOT NULL,
  `instructions` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `file` varchar(191) DEFAULT NULL,
  `prescribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scans`
--

CREATE TABLE `scans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(191) NOT NULL,
  `scan_path` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `specialties`
--

CREATE TABLE `specialties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `lastname` varchar(191) DEFAULT NULL,
  `username` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `specialty` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `image`, `specialty`, `phone`, `status`, `notes`) VALUES
(1, 'ranim', 'bejaoui', 'ranimbejaoui', 'ranim@gmail.com', NULL, '$2y$10$3qRIGigb7sWZ2SXkRJLpQegRhiX0r06bLxMYDjivuibM78K0EbLha', 1, NULL, '2026-05-07 08:51:52', '2026-05-07 08:51:52', NULL, NULL, '25741147', 'pending', NULL),
(2, 'Ranim', 'Bejaoui', 'Ranimbejaoui', 'ranimbejaoui50@gmail.com', NULL, '$2y$10$rp7iKTamec51sdYlR2si7eyFThADzRBVqjjcO2FjNwFsqHtOulqqS', 2, NULL, '2026-05-08 14:08:30', '2026-05-08 14:28:09', 'profiles/ELjrojUfxHF0s6PecCAqixhiO0VIQYNAYfUghCL0.jpg', NULL, NULL, 'pending', NULL),
(4, 'islem', 'ben salem', 'bensalemislem', 'islem@gmail.com', NULL, '$2y$10$3EfN.QTc4KbzSnZ15e6oM.6wPvSaRq5UiVsFW9gBLjPGVc0lCSEju', 0, NULL, '2026-05-08 14:10:54', '2026-05-08 14:32:21', 'doctors/CvIt6WULpIalpNgYPRWHb2lxRKnWzn6Z9WcWWF4r.png', 'Orthopedics', '25741254', 'accepted', NULL),
(5, 'eanim', NULL, NULL, 'ranimbejaoui74@gmail.com', NULL, '$2y$10$s61Z6iHu6L8P2s2oKyAjGOn67aTLhEqBaLRDZJ3wjidh.OH2BML02', 0, NULL, '2026-05-08 15:30:50', '2026-05-08 15:31:16', NULL, 'Orthopedics', '63258741', 'accepted', NULL),
(6, 'ranim', NULL, NULL, 'ranimbejaoui5@gmail.com', NULL, '$2y$10$dzWV1jcNG8ZIfSAT56G/Ve2tHXok0Rkg6r5XSO6U1Og.U9SbJS2US', 0, NULL, '2026-05-09 14:45:08', '2026-05-09 14:45:08', NULL, 'Orthopedics', '25784541', 'pending', NULL),
(7, 'ranim', NULL, NULL, 'ranimbejaou@gmail.com', NULL, '$2y$10$ZBo3ruzyJm1IRuBj8T02VO2U1kh164TJgXmmVmSQLvifPfRuwGk8S', 0, NULL, '2026-05-09 14:46:15', '2026-05-09 14:46:58', NULL, 'Neurology', '26536365', 'accepted', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `appointments_patient_id_foreign` (`patient_id`),
  ADD KEY `appointments_user_id_foreign` (`user_id`);

--
-- Index pour la table `attestations`
--
ALTER TABLE `attestations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attestations_doctor_id_foreign` (`doctor_id`);

--
-- Index pour la table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctors_email_unique` (`email`),
  ADD KEY `doctors_specialty_id_foreign` (`specialty_id`);

--
-- Index pour la table `doctor_patient`
--
ALTER TABLE `doctor_patient`
  ADD KEY `doctor_patient_patient_id_foreign` (`patient_id`),
  ADD KEY `doctor_patient_user_id_foreign` (`user_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_doctor_id_foreign` (`doctor_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  ADD KEY `notifications_appointment_id_foreign` (`appointment_id`);

--
-- Index pour la table `orientation_letters`
--
ALTER TABLE `orientation_letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orientation_letters_patient_id_foreign` (`patient_id`),
  ADD KEY `orientation_letters_user_id_foreign` (`user_id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_username_unique` (`username`),
  ADD KEY `patients_user_id_foreign` (`user_id`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions_patient_id_foreign` (`patient_id`),
  ADD KEY `prescriptions_user_id_foreign` (`user_id`);

--
-- Index pour la table `scans`
--
ALTER TABLE `scans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scans_patient_id_foreign` (`patient_id`),
  ADD KEY `scans_user_id_foreign` (`user_id`);

--
-- Index pour la table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `specialties_name_unique` (`name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `attestations`
--
ALTER TABLE `attestations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `orientation_letters`
--
ALTER TABLE `orientation_letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `scans`
--
ALTER TABLE `scans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `attestations`
--
ALTER TABLE `attestations`
  ADD CONSTRAINT `attestations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_specialty_id_foreign` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `doctor_patient`
--
ALTER TABLE `doctor_patient`
  ADD CONSTRAINT `doctor_patient_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_patient_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orientation_letters`
--
ALTER TABLE `orientation_letters`
  ADD CONSTRAINT `orientation_letters_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orientation_letters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `scans`
--
ALTER TABLE `scans`
  ADD CONSTRAINT `scans_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
