-- MariaDB dump 10.19-11.3.2-MariaDB, for osx10.18 (x86_64)
--
-- Host: localhost    Database: hofesh
-- ------------------------------------------------------
-- Server version	8.0.33

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_customer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES
(1,'Joseph','Etta','email@gmail.com','678749474938',1,'2024-05-28 15:20:19','2024-06-06 15:37:59',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demands`
--

DROP TABLE IF EXISTS `demands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT '0',
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reception_start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_people` int DEFAULT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `event_date` datetime DEFAULT NULL,
  `demand_date` datetime DEFAULT NULL,
  `status` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demands`
--

LOCK TABLES `demands` WRITE;
/*!40000 ALTER TABLE `demands` DISABLE KEYS */;
INSERT INTO `demands` VALUES
(1,1,'Mariage','Mariage',NULL,NULL,NULL,NULL,NULL,'2024-05-28 16:20:19',-1,'2024-05-28 15:20:19','2024-05-29 12:50:49',NULL),
(2,1,'Birthday','Mariage',NULL,NULL,NULL,NULL,NULL,'2024-05-28 16:20:19',1,'2024-05-28 15:27:48','2024-06-03 13:34:37',NULL);
/*!40000 ALTER TABLE `demands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_types`
--

DROP TABLE IF EXISTS `event_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_types`
--

LOCK TABLES `event_types` WRITE;
/*!40000 ALTER TABLE `event_types` DISABLE KEYS */;
INSERT INTO `event_types` VALUES
(1,'Mariage','2024-06-03 15:35:25','2024-06-03 15:35:25');
/*!40000 ALTER TABLE `event_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2024_05_20_105157_add_two_factor_columns_to_users_table',1),
(5,'2024_05_20_105224_create_personal_access_tokens_table',1),
(6,'2024_05_20_105224_create_teams_table',1),
(7,'2024_05_20_105225_create_team_user_table',1),
(8,'2024_05_20_105226_create_team_invitations_table',1),
(9,'2024_05_27_162247_create_customers_table',2),
(10,'2024_05_27_164025_create_demands_table',2),
(11,'2024_05_28_131143_create_event_types_table',2),
(12,'2024_06_03_150214_create_prestations_table',3),
(13,'2024_06_03_160928_create_services_table',4),
(14,'2024_06_05_160256_add_column_price_to_services_table',5),
(15,'2024_06_05_162242_create_prestation_services_table',6),
(16,'2024_06_05_162252_create_prestation_payments_table',6),
(17,'2024_06_05_163002_create_payments_table',7),
(18,'2024_06_05_173532_add_column_name_to_prestation_services_table',8),
(19,'2024_06_06_171114_create_payment_methods_table',9),
(20,'2024_06_06_221958_add_column_cancel_comment_to_prestations_table',10),
(21,'2024_06_06_222231_add_column_dates_to_prestations_table',11),
(22,'2024_06_07_125857_add_column_roles_to_users_table',12);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES
(1,'Carde','2024-06-06 16:16:26','2024-06-06 16:16:26',NULL),
(2,'Bank','2024-06-06 16:25:13','2024-06-06 16:25:13',NULL);
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prestation_id` bigint unsigned NOT NULL,
  `payment_method_id` bigint unsigned DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES
(1,6,NULL,'Bank',2000,'2024-06-06 16:42:59','2024-06-06 16:42:59',NULL),
(2,6,NULL,'Bank',2000,'2024-06-06 16:45:07','2024-06-06 16:45:07',NULL),
(3,6,NULL,NULL,5000,'2024-06-06 17:10:49','2024-06-06 17:10:49',NULL),
(4,1,2,'Bank',27000,'2024-06-07 01:39:54','2024-06-07 01:39:54',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES
(1,'App\\Models\\User',1,'authToken','c25482ef9a1dc93b152f44b62dc886c25ca99865a9af4b2e736f38250b08a271','[\"*\"]',NULL,NULL,'2024-05-24 03:44:14','2024-05-24 03:44:14'),
(2,'App\\Models\\User',1,'authToken','2962588f879350179a40695e46b7949915ca9f51f062222619dfc035d8ecf5f1','[\"*\"]',NULL,NULL,'2024-05-24 12:34:40','2024-05-24 12:34:40'),
(3,'App\\Models\\User',1,'authToken','b7212c9e472afbfa8fdaaf4bcc4da9072add62d68820a8f6a06af6d03b5602ec','[\"*\"]',NULL,NULL,'2024-05-24 12:35:38','2024-05-24 12:35:38'),
(4,'App\\Models\\User',1,'authToken','5ddb9f3cee8072967481231446893895ba916086dfc290e01d20d84575b5847b','[\"*\"]',NULL,NULL,'2024-05-24 12:38:57','2024-05-24 12:38:57'),
(5,'App\\Models\\User',1,'authToken','6249b7fce50fd135856289b77f2e672fe2ff88389c56d1d769857353458b9bd1','[\"*\"]',NULL,NULL,'2024-05-24 12:54:52','2024-05-24 12:54:52'),
(6,'App\\Models\\User',1,'authToken','ac5024c865ff68e8f540c33c16aa8345742c5af02180ea6fabd8af8b80db06ea','[\"*\"]','2024-05-27 12:47:51',NULL,'2024-05-24 13:09:35','2024-05-27 12:47:51'),
(7,'App\\Models\\User',13,'authToken','94ff5c154b03884662b6217a35001922b7a23bb252a5de8fde03454b42172653','[\"*\"]',NULL,NULL,'2024-05-27 13:03:26','2024-05-27 13:03:26'),
(8,'App\\Models\\User',13,'authToken','2da015566ad11137afd0f7a55152ebf175acb5173d4d2346f12e4147012061e3','[\"*\"]','2024-05-27 13:05:48',NULL,'2024-05-27 13:04:04','2024-05-27 13:05:48'),
(9,'App\\Models\\User',1,'authToken','6cd4366bf3e77756dbbb76a4c1e53ce906f71cd561b96878e7c94808c08bbdfb','[\"*\"]','2024-05-27 13:09:02',NULL,'2024-05-27 13:08:37','2024-05-27 13:09:02'),
(10,'App\\Models\\User',1,'authToken','0704caa958264e11c8b9761c4d6a16d7aac0c36a1f97b1747e80077d7dd0a48c','[\"*\"]',NULL,NULL,'2024-05-27 13:40:38','2024-05-27 13:40:38'),
(11,'App\\Models\\User',1,'authToken','28244276cace161ca780f475408caff5827e1a16264785f28a0ffabf803a89c3','[\"*\"]',NULL,NULL,'2024-05-27 13:41:34','2024-05-27 13:41:34'),
(12,'App\\Models\\User',1,'authToken','97deeb2f25fb58551cbac6428899e55c348b42f64f7a05de80809022ef3185df','[\"*\"]',NULL,NULL,'2024-05-27 13:42:59','2024-05-27 13:42:59'),
(13,'App\\Models\\User',1,'authToken','20047d2418cdd4e8636b06177aae4eb6e434468457092e4ad452c97d030e8d66','[\"*\"]',NULL,NULL,'2024-05-27 13:43:49','2024-05-27 13:43:49'),
(14,'App\\Models\\User',1,'authToken','d1265221916fbaddee3a360509822ffe5c7b469a7321629cfef2d6fe63bdd52b','[\"*\"]',NULL,NULL,'2024-05-27 13:45:09','2024-05-27 13:45:09'),
(15,'App\\Models\\User',1,'authToken','31c4802a4ca3885d9fa97f1e8a1053eab16d33a98b1d1b63f2f575bcf400c29e','[\"*\"]',NULL,NULL,'2024-05-27 13:46:03','2024-05-27 13:46:03'),
(16,'App\\Models\\User',1,'authToken','a1d196a294f73b1e9258ba6ba258cf8a3ce678c5876effd4c62e4fc9d6e41a31','[\"*\"]',NULL,NULL,'2024-05-27 13:47:48','2024-05-27 13:47:48'),
(17,'App\\Models\\User',1,'authToken','de1be11dfceab1391a10afbd325e6e5c7ad0769f82b5d3d099562d57e6b57a46','[\"*\"]',NULL,NULL,'2024-05-27 14:05:12','2024-05-27 14:05:12'),
(18,'App\\Models\\User',1,'authToken','e70c0b3b6f22884db47e313f0f14bc6c4ed1b17e18484ec7dc7703e28854d905','[\"*\"]','2024-05-27 14:40:02',NULL,'2024-05-27 14:14:02','2024-05-27 14:40:02'),
(19,'App\\Models\\User',1,'authToken','653842bc5452a857532b6419efbe1471da630bc2e67e4a10eed90df097a81431','[\"*\"]','2024-06-07 12:31:26',NULL,'2024-05-29 10:57:51','2024-06-07 12:31:26'),
(20,'App\\Models\\User',1,'authToken','382b8e9408e4e367e48568cc2268caedb2dbcd90da0bebb8e85d7f7c64ce664a','[\"*\"]','2024-06-07 12:34:31',NULL,'2024-06-07 12:33:38','2024-06-07 12:34:31'),
(21,'App\\Models\\User',1,'authToken','b0563de19dba081be9e1cb85998964c0157fb2e459817d4964d5589ad79b806c','[\"*\"]',NULL,NULL,'2024-06-07 12:34:43','2024-06-07 12:34:43'),
(22,'App\\Models\\User',1,'authToken','9ef6bc410c6a7a94b9f766003f26afd7cee1a335c9f62b9a2bb39f7a5241879d','[\"*\"]','2024-06-11 12:41:52',NULL,'2024-06-11 11:49:40','2024-06-11 12:41:52'),
(23,'App\\Models\\User',1,'authToken','1a45d0504c3209a82c195a0be8dfcd8ba234bde2ef9f5c5e1db2b7dfb1dcbf9a','[\"*\"]','2024-06-11 12:42:33',NULL,'2024-06-11 12:42:05','2024-06-11 12:42:33');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestation_payments`
--

DROP TABLE IF EXISTS `prestation_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestation_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prestation_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `amount` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestation_payments`
--

LOCK TABLES `prestation_payments` WRITE;
/*!40000 ALTER TABLE `prestation_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `prestation_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestation_services`
--

DROP TABLE IF EXISTS `prestation_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestation_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prestation_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `quantity` bigint unsigned DEFAULT '0',
  `price` double DEFAULT '0',
  `total` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestation_services`
--

LOCK TABLES `prestation_services` WRITE;
/*!40000 ALTER TABLE `prestation_services` DISABLE KEYS */;
INSERT INTO `prestation_services` VALUES
(4,6,2,6,5000,30000,'2024-06-06 13:39:21','2024-06-06 14:44:44',NULL,'Service traiteur'),
(7,6,1,4,3000,12000,'2024-06-06 14:42:38','2024-06-06 14:42:38',NULL,'Chaises'),
(8,1,2,3,5000,15000,'2024-06-06 15:14:02','2024-06-06 15:14:02',NULL,'Service traiteur'),
(9,1,1,3,3000,9000,'2024-06-06 15:14:10','2024-06-06 15:14:10',NULL,'Chaises'),
(10,1,1,1,3000,3000,'2024-06-07 00:20:22','2024-06-07 00:20:22',NULL,'Chaises');
/*!40000 ALTER TABLE `prestation_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestations`
--

DROP TABLE IF EXISTS `prestations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT '0',
  `demand_id` int DEFAULT '0',
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reception_start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_people` int DEFAULT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `event_date` datetime DEFAULT NULL,
  `demand_date` datetime DEFAULT NULL,
  `status` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `cancel_comment` longtext COLLATE utf8mb4_unicode_ci,
  `validated_date` datetime DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `closed_date` datetime DEFAULT NULL,
  `cancelled_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestations`
--

LOCK TABLES `prestations` WRITE;
/*!40000 ALTER TABLE `prestations` DISABLE KEYS */;
INSERT INTO `prestations` VALUES
(1,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,'2024-06-03 14:09:23','2024-06-07 01:40:05',NULL,NULL,NULL,'2024-06-07 02:40:05',NULL,NULL),
(2,1,2,NULL,NULL,NULL,NULL,NULL,'sdjfalskdfj asdf kasdklfajklsdf klasdfjkladf asdfkljasdflkj sldfjkalsdfkjlasdfj sdfkljasdlfksdf',NULL,NULL,1,'2024-06-03 14:10:48','2024-06-06 23:18:01',NULL,NULL,'2024-06-07 00:18:01',NULL,NULL,'2024-06-06 23:49:38'),
(3,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,-1,'2024-06-03 14:12:16','2024-06-06 23:12:09',NULL,'A comment',NULL,NULL,NULL,'2024-06-07 00:12:09'),
(4,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2024-06-03 14:13:04','2024-06-03 14:13:04',NULL,NULL,NULL,NULL,NULL,NULL),
(5,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2024-06-03 14:13:30','2024-06-03 14:13:30',NULL,NULL,NULL,NULL,NULL,NULL),
(6,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-05-28 16:20:19',3,'2024-06-03 14:14:45','2024-06-06 17:16:37',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `prestations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `price` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,'Chaises','Chaises','2024-06-03 15:19:26','2024-06-05 15:06:30',NULL,3000),
(2,'Service traiteur','','2024-06-05 15:06:45','2024-06-05 15:08:54',NULL,5000);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('wNMdSKqq6jevZvavoXbNsBHuLahoHTWnS5E3OAra',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibTI0enZEMDlCcUFxdk5RMGU2YlkweGJVVXg4M3pCbFNzek42Q1hZRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zYW5jdHVtL2NzcmYtY29va2llIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1716522936);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_invitations`
--

DROP TABLE IF EXISTS `team_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_invitations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_invitations_team_id_email_unique` (`team_id`,`email`),
  CONSTRAINT `team_invitations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_invitations`
--

LOCK TABLES `team_invitations` WRITE;
/*!40000 ALTER TABLE `team_invitations` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_invitations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_user`
--

DROP TABLE IF EXISTS `team_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_user`
--

LOCK TABLES `team_user` WRITE;
/*!40000 ALTER TABLE `team_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_team` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `roles` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Etta','ettaegbe40@gmail.com',NULL,'$2y$12$c3RTuwElSi/jwrY3AE6hkePwxFoF302vgHWayt4g/dQFPCs/UHn8.',NULL,NULL,NULL,NULL,NULL,NULL,'2024-05-24 03:44:14','2024-06-11 12:41:39','[\"/demands\", \"/customers\", \"/prestations/list/started\", \"/prestations/list/validated\", \"/prestations/list/processing\", \"/prestations/list/closed\", \"/prestations/list/cancelled\", \"/services\", \"/payments\", \"/event_types\", \"/payment_methods\", \"/admins\", \"Prestations\", \"/prestations\"]'),
(13,'Admin','admin@gmail.com',NULL,'$2y$12$cuxedrOgG5KOprn6C6vKeeuqlnoSxHYr8TyXgPqLNqETwv/EI7jt2',NULL,NULL,NULL,NULL,NULL,NULL,'2024-05-27 11:15:47','2024-06-07 12:18:23','[\"/prestations/list/started\", \"/customers\", \"/demands\", \"/prestations/list/validated\", \"/prestations/list/processing\", \"/prestations/list/closed\", \"/prestations/list/cancelled\", \"/payments\", \"/services\", \"/event_types\", \"/payment_methods\", \"/admins\"]'),
(14,'Joseph','josehp@engie.com',NULL,'$2y$12$U0YxHqQle.qf.W17ucOIduTYpRWdXRAfzeFUnP0OsptscUw4lCe3C',NULL,NULL,NULL,NULL,NULL,NULL,'2024-06-07 11:41:46','2024-06-07 11:41:46',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'hofesh'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-11 15:37:08
