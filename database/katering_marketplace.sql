-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: katering_marketplace
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `katering_marketplace`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `katering_marketplace` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `katering_marketplace`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `customer_profiles`
--

DROP TABLE IF EXISTS `customer_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `office_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pic_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `customer_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_profiles`
--

LOCK TABLES `customer_profiles` WRITE;
/*!40000 ALTER TABLE `customer_profiles` DISABLE KEYS */;
INSERT INTO `customer_profiles` VALUES (1,5,'PT Maju Jaya Sentosa','Kota Jambi','Jl. Sultan Agung No. 45, Kota Jambi','Andi Rahman','2026-10-15 04:56:40','2026-10-15 04:56:40'),(2,6,'CV Batam Trade Office','Kota Batam','Komplek Batam Business Park Blok B2, Batam','Siska Melati','2026-10-15 04:56:40','2026-10-15 04:56:40');
/*!40000 ALTER TABLE `customer_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` date NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `payment_status` enum('belum_lunas','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_lunas',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,1,'INV-20261012-0001','2026-10-12',900000.00,'lunas','2026-10-13 04:56:40','2026-10-12 04:56:40','2026-10-12 04:56:40'),(2,2,'INV-20261015-0002','2026-10-15',750000.00,'belum_lunas',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(3,3,'INV-20261015-0003','2026-10-15',600000.00,'belum_lunas',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(4,4,'INV-20261014-0004','2026-10-14',450000.00,'belum_lunas',NULL,'2026-10-14 04:56:40','2026-10-14 04:56:40');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `merchant_profile_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('Makanan Berat','Makanan Ringan','Minuman','Paket') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Makanan Berat',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_merchant_profile_id_is_available_index` (`merchant_profile_id`,`is_available`),
  CONSTRAINT `menus_merchant_profile_id_foreign` FOREIGN KEY (`merchant_profile_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,1,'Nasi Kotak Ayam Bakar','Nasi putih, ayam bakar madu, tempe orek, tumis buncis, sambal, kerupuk.',25000.00,'menus/nasi-kotak-ayam-bakar.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(2,1,'Nasi Kotak Ikan Goreng','Nasi putih, ikan nila goreng, tahu, tumis kangkung, sambal terasi.',23000.00,'menus/nasi-kotak-ikan-goreng.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(3,1,'Soto Ayam Spesial','Soto ayam kuah kuning dengan nasi terpisah, telur, perkedel, dan emping.',18000.00,'menus/soto-ayam-spesial.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(4,1,'Ayam Geprek Sambal Bawang','Ayam krispi digeprek sambal bawang (level 0-5), nasi putih, lalapan segar.',20000.00,'menus/ayam-geprek-sambal-bawang.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(5,1,'Es Teh Manis','Teh manis dingin kemasan cup 400 ml, diseduh segar setiap pagi.',5000.00,'menus/es-teh-manis.jpg','Minuman',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(6,1,'Snack Box 3 Item','Tiga kue pilihan: risoles, lemper, dan pastel. Cocok untuk rapat pagi.',12000.00,'menus/snack-box-3-item.jpg','Makanan Ringan',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(7,2,'Nasi Padang Rendang','Rendang daging sapi empuk, sayur nangka, gulai rebung, sambal lado merah.',30000.00,'menus/nasi-padang-rendang.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(8,2,'Nasi Padang Ayam Pop','Ayam pop gurih lembut, sambal tomat segar, daun singkong rebus.',27000.00,'menus/nasi-padang-ayam-pop.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(9,2,'Gulai Tunjang','Gulai tunjang sapi kuah kental berbumbu rempah, disajikan dengan nasi putih.',32000.00,'menus/gulai-tunjang.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(10,2,'Paket Hemat Anak Kantor','Nasi, ayam gulai, sayur nangka, sambal. Khusus pemesanan minimal 10 porsi.',22000.00,'menus/paket-hemat-anak-kantor.jpg','Paket',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(11,2,'Teh Talua','Teh talua khas Minang: teh, telur, dan gula aren dikocok berbusa.',8000.00,'menus/teh-talua.jpg','Minuman',0,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(12,3,'Chicken Salad Bowl','Dada ayam panggang, mixed lettuce, tomat ceri, alpukat, dressing lemon madu.',35000.00,'menus/chicken-salad-bowl.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(13,3,'Berry Smoothie Bowl','Smoothie stroberi dan blueberry, topping granola, chia seed, dan pisang.',30000.00,'menus/berry-smoothie-bowl.jpg','Makanan Ringan',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(14,3,'Set Nasi Merah Ayam Panggang','Nasi merah, ayam panggang herb, brokoli kukus, telur rebus. Sekitar 520 kkal.',38000.00,'menus/set-nasi-merah-ayam-panggang.jpg','Paket',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(15,3,'Green Juice 500ml','Campuran bayam, apel hijau, timun, dan jeruk nipis tanpa gula tambahan.',15000.00,'menus/green-juice-500ml.jpg','Minuman',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(16,3,'Overnight Oats Cokelat','Oat direndam susu almond, kakao, madu, dan potongan pisang.',20000.00,'menus/overnight-oats-cokelat.jpg','Makanan Ringan',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(17,4,'Chicken Teriyaki Bento','Ayam teriyaki, nasi tabur wijen, salad kol, dan tamagoyaki.',40000.00,'menus/chicken-teriyaki-bento.jpg','Paket',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(18,4,'Beef Gyudon','Irisan daging sapi manis-gurih di atas nasi hangat, taburan nori.',45000.00,'menus/beef-gyudon.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(19,4,'Sushi Roll Set 8 pcs','Delapan roll sushi isi crab stick dan timun, lengkap saus shoyu dan wasabi.',42000.00,'menus/sushi-roll-set-8-pcs.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(20,4,'Ramen Tori Paitan','Ramen kuah kaldu ayam kental, chashu ayam, telur ramen, dan menma.',38000.00,'menus/ramen-tori-paitan.jpg','Makanan Berat',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(21,4,'Ocha Dingin','Teh hijau Jepang tanpa gula, botol 350 ml.',7000.00,'menus/ocha-dingin.jpg','Minuman',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(22,4,'Takoyaki 6 pcs','Takoyaki isi gurita dengan saus takoyaki, mayones, dan katsuobushi.',18000.00,'menus/takoyaki-6-pcs.jpg','Makanan Ringan',0,'2026-10-15 04:56:40','2026-10-15 04:56:40');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `merchant_profiles`
--

DROP TABLE IF EXISTS `merchant_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `merchant_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cuisine_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `merchant_profiles_user_id_foreign` (`user_id`),
  KEY `merchant_profiles_city_index` (`city`),
  KEY `merchant_profiles_cuisine_type_index` (`cuisine_type`),
  CONSTRAINT `merchant_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `merchant_profiles`
--

LOCK TABLES `merchant_profiles` WRITE;
/*!40000 ALTER TABLE `merchant_profiles` DISABLE KEYS */;
INSERT INTO `merchant_profiles` VALUES (1,1,'Katering Sedap Nusantara','Kota Jambi','Jl. Hayam Wuruk No. 12, Jelutung, Kota Jambi','Indonesia','Katering rumahan sejak 2015, spesialis nasi kotak dan prasmanan untuk kantor serta acara keluarga. Bahan segar setiap hari, halal, dan tanpa MSG berlebihan.',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(2,2,'Dapur Umi Masakan Padang','Kota Jambi','Jl. Gatot Subroto No. 88, Pasar Jambi, Kota Jambi','Padang','Masakan Padang otentik dengan bumbu warisan keluarga. Melayani porsi perorangan sampai ratusan porsi untuk instansi, diantar tepat waktu.',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(3,3,'Green Bowl Healthy Catering','Kota Batam','Komplek Nagoya Business Centre Blok C3, Batam','Sehat','Katering sehat untuk gaya hidup aktif: rendah minyak, tanpa pengawet, dan informasi kalori tercantum per porsi. Cocok untuk program makan siang kantor.',1,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(4,4,'Sakura Bento Batam','Kota Batam','Jl. Raja Ali Haji No. 21, Batam Centre, Batam','Jepang','Bento dan masakan Jepang halal-friendly untuk makan siang kantor. Dikemas rapi, porsi mengenyangkan, dan selalu tepat waktu.',1,'2026-10-15 04:56:40','2026-10-15 04:56:40');
/*!40000 ALTER TABLE `merchant_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_15_000001_add_role_and_phone_to_users_table',1),(5,'2026_10_15_000002_create_merchant_profiles_table',1),(6,'2026_10_15_000003_create_customer_profiles_table',1),(7,'2026_10_15_000004_create_menus_table',1),(8,'2026_10_15_000005_create_orders_table',1),(9,'2026_10_15_000006_create_order_items_table',1),(10,'2026_10_15_000007_create_invoices_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `menu_id` bigint unsigned NOT NULL,
  `qty` int unsigned NOT NULL,
  `price_snapshot` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,30,25000.00,750000.00,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(2,1,5,30,5000.00,150000.00,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(3,2,12,15,35000.00,525000.00,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(4,2,15,15,15000.00,225000.00,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(5,3,7,20,30000.00,600000.00,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(6,4,18,10,45000.00,450000.00,'2026-10-15 04:56:40','2026-10-15 04:56:40');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merchant_profile_id` bigint unsigned NOT NULL,
  `customer_profile_id` bigint unsigned NOT NULL,
  `delivery_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(12,2) NOT NULL,
  `status` enum('pending','dikonfirmasi','dikirim','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_profile_id_foreign` (`customer_profile_id`),
  KEY `orders_merchant_profile_id_status_index` (`merchant_profile_id`,`status`),
  KEY `orders_delivery_date_index` (`delivery_date`),
  CONSTRAINT `orders_customer_profile_id_foreign` FOREIGN KEY (`customer_profile_id`) REFERENCES `customer_profiles` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `orders_merchant_profile_id_foreign` FOREIGN KEY (`merchant_profile_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-20261012-0001',1,1,'2026-10-14','Diantar ke resepsionis lantai 1.',900000.00,'selesai','2026-10-12 04:56:40','2026-10-15 04:56:40'),(2,'ORD-20261015-0002',3,2,'2026-10-17',NULL,750000.00,'dikonfirmasi','2026-10-15 04:56:40','2026-10-15 04:56:40'),(3,'ORD-20261015-0003',2,1,'2026-10-16','Mohon sambal dipisah.',600000.00,'pending','2026-10-15 04:56:40','2026-10-15 04:56:40'),(4,'ORD-20261014-0004',4,2,'2026-10-15',NULL,450000.00,'dibatalkan','2026-10-14 04:56:40','2026-10-15 04:56:40');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('merchant','customer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Sri Wahyuni','sedap@katering.test',NULL,'$2y$12$..jrHRC/rFYy8tdX49JPi.4ugHe9Jc45Ws6ZxhTrux5EZzOXt7oOG','merchant','081234567001',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(2,'Yeti Wulandari','dapurumi@katering.test',NULL,'$2y$12$..jrHRC/rFYy8tdX49JPi.4ugHe9Jc45Ws6ZxhTrux5EZzOXt7oOG','merchant','081234567002',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(3,'Maya Kusuma','greenbowl@katering.test',NULL,'$2y$12$..jrHRC/rFYy8tdX49JPi.4ugHe9Jc45Ws6ZxhTrux5EZzOXt7oOG','merchant','081234567003',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(4,'Rina Tanaka','sakura@katering.test',NULL,'$2y$12$..jrHRC/rFYy8tdX49JPi.4ugHe9Jc45Ws6ZxhTrux5EZzOXt7oOG','merchant','081234567004',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(5,'Andi Rahman','kantor@majujaya.test',NULL,'$2y$12$..jrHRC/rFYy8tdX49JPi.4ugHe9Jc45Ws6ZxhTrux5EZzOXt7oOG','customer','081345678001',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40'),(6,'Siska Melati','kantor@batamtrade.test',NULL,'$2y$12$..jrHRC/rFYy8tdX49JPi.4ugHe9Jc45Ws6ZxhTrux5EZzOXt7oOG','customer','081345678002',NULL,'2026-10-15 04:56:40','2026-10-15 04:56:40');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-15 11:56:46
