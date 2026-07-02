-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bank_code` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_holder_name` varchar(255) NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banks_bank_code_unique` (`bank_code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES (1,'BOC','Bank of Ceylon','Colombo Fort','1234567890','Nexus ERP Solutions','2026-06-26','2026-06-26 00:20:30','2026-06-26 00:20:30',5000000.00),(2,'COM','Commercial Bank','Kandy City','2234567891','Nexus ERP Solutions','2026-06-26','2026-06-26 00:20:30','2026-06-26 00:20:30',3500000.00),(3,'HNB','Hatton National Bank','Galle Fort','3234567892','Nexus ERP Solutions','2026-06-26','2026-06-26 00:20:30','2026-06-26 00:20:30',2500000.00),(4,'SAMP','Sampath Bank','Negombo Office','4234567893','Nexus ERP Solutions','2026-06-26','2026-06-26 00:20:30','2026-06-26 00:20:30',1800000.00),(5,'NDB','NDB Bank','Jaffna Office','5234567894','Nexus ERP Solutions','2026-06-26','2026-06-26 00:20:30','2026-06-26 00:20:30',1200000.00);
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branches` (
  `branch_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone_num1` varchar(255) DEFAULT NULL,
  `phone_num2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `opening_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`branch_id`),
  UNIQUE KEY `branches_email_unique` (`email`),
  KEY `branches_manager_id_foreign` (`manager_id`),
  CONSTRAINT `branches_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,'BRH001','Colombo Head Office','Colombo 03','+94112222221',NULL,'colombo@nexuserp.com',1,'2020-01-01',1,'2026-06-26 00:20:26','2026-06-26 00:20:28'),(2,'BRH002','Kandy Branch','Kandy Town','+94812222222',NULL,'kandy@nexuserp.com',2,'2021-06-15',1,'2026-06-26 00:20:26','2026-06-26 00:20:29'),(3,'BRH003','Galle Branch','Galle Fort','+94912222223',NULL,'galle@nexuserp.com',3,'2022-03-20',1,'2026-06-26 00:20:26','2026-06-26 00:20:29'),(4,'BRH004','Jaffna Branch','Jaffna Town','+94212222224',NULL,'jaffna@nexuserp.com',4,'2023-11-01',1,'2026-06-26 00:20:27','2026-06-26 00:20:29'),(5,'BRH005','Negombo Branch','Negombo Beach Road','+94312222225',NULL,'negombo@nexuserp.com',5,'2024-05-10',1,'2026-06-26 00:20:27','2026-06-26 00:20:29');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `brand_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_code` varchar(20) DEFAULT NULL,
  `brand_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`brand_id`),
  UNIQUE KEY `brands_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'BRD001','Samsung','Lee Kun','+94771234560','samsung@corp.com','samsung.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(2,'BRD002','Apple','Tim Cook','+94771234561','apple@corp.com','apple.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(3,'BRD003','Sony','Ken Yoshida','+94771234562','sony@corp.com','sony.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(4,'BRD004','Nike','Phil Knight','+94771234563','nike@corp.com','nike.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(5,'BRD005','Adidas','Bjorn Gulden','+94771234564','adidas@corp.com','adidas.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(6,'BRD006','Dell','Michael Dell','+94771234565','dell@corp.com','dell.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(7,'BRD007','HP','Enrique Lores','+94771234566','hp@corp.com','hp.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(8,'BRD008','Logitech','Bracken Darrell','+94771234567','logitech@corp.com','logitech.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(9,'BRD009','Ninja','Mark Barrocas','+94771234568','ninja@corp.com','ninjakitchen.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(10,'BRD010','Breville','Jim Clayton','+94771234569','breville@corp.com','breville.com','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),(11,'BRD013','Google','Contact Google','+94771234567','google@corp.com','google.com','2026-06-26','2026-06-26 00:20:32','2026-06-26 00:20:32'),(12,'BRD014','OnePlus','Contact OnePlus','+94771234567','oneplus@corp.com','oneplus.com','2026-06-26','2026-06-26 00:20:33','2026-06-26 00:20:33'),(13,'BRD017','Lenovo','Contact Lenovo','+94771234567','lenovo@corp.com','lenovo.com','2026-06-26','2026-06-26 00:20:33','2026-06-26 00:20:33'),(14,'BRD019','Bose','Contact Bose','+94771234567','bose@corp.com','bose.com','2026-06-26','2026-06-26 00:20:33','2026-06-26 00:20:33'),(15,'BRD020','Sennheiser','Contact Sennheiser','+94771234567','sennheiser@corp.com','sennheiser.com','2026-06-26','2026-06-26 00:20:34','2026-06-26 00:20:34'),(16,'BRD021','Anker','Contact Anker','+94771234567','anker@corp.com','anker.com','2026-06-26','2026-06-26 00:20:34','2026-06-26 00:20:34'),(17,'BRD023','Amazon','Contact Amazon','+94771234567','amazon@corp.com','amazon.com','2026-06-26','2026-06-26 00:20:34','2026-06-26 00:20:34'),(18,'BRD024','Philips','Contact Philips','+94771234567','philips@corp.com','philips.com','2026-06-26','2026-06-26 00:20:34','2026-06-26 00:20:34'),(19,'BRD025','Ring','Contact Ring','+94771234567','ring@corp.com','ring.com','2026-06-26','2026-06-26 00:20:34','2026-06-26 00:20:34'),(20,'BRD028','Puma','Contact Puma','+94771234567','puma@corp.com','puma.com','2026-06-26','2026-06-26 00:20:34','2026-06-26 00:20:34'),(21,'BRD029','Under Armour','Contact Under Armour','+94771234567','underarmour@corp.com','underarmour.com','2026-06-26','2026-06-26 00:20:35','2026-06-26 00:20:35'),(22,'BRD030','Levi\'s','Contact Levi\'s','+94771234567','levi\'s@corp.com','levi\'s.com','2026-06-26','2026-06-26 00:20:35','2026-06-26 00:20:35'),(23,'BRD031','Wrangler','Contact Wrangler','+94771234567','wrangler@corp.com','wrangler.com','2026-06-26','2026-06-26 00:20:35','2026-06-26 00:20:35'),(24,'BRD032','Lee','Contact Lee','+94771234567','lee@corp.com','lee.com','2026-06-26','2026-06-26 00:20:35','2026-06-26 00:20:35'),(25,'BRD033','Diesel','Contact Diesel','+94771234567','diesel@corp.com','diesel.com','2026-06-26','2026-06-26 00:20:35','2026-06-26 00:20:35'),(26,'BRD034','Patagonia','Contact Patagonia','+94771234567','patagonia@corp.com','patagonia.com','2026-06-26','2026-06-26 00:20:35','2026-06-26 00:20:35'),(27,'BRD035','The North Face','Contact The North Face','+94771234567','thenorthface@corp.com','thenorthface.com','2026-06-26','2026-06-26 00:20:36','2026-06-26 00:20:36'),(28,'BRD036','Columbia','Contact Columbia','+94771234567','columbia@corp.com','columbia.com','2026-06-26','2026-06-26 00:20:36','2026-06-26 00:20:36'),(29,'BRD037','Arc\'teryx','Contact Arc\'teryx','+94771234567','arc\'teryx@corp.com','arc\'teryx.com','2026-06-26','2026-06-26 00:20:36','2026-06-26 00:20:36'),(30,'BRD038','Darn Tough','Contact Darn Tough','+94771234567','darntough@corp.com','darntough.com','2026-06-26','2026-06-26 00:20:36','2026-06-26 00:20:36'),(31,'BRD039','Smartwool','Contact Smartwool','+94771234567','smartwool@corp.com','smartwool.com','2026-06-26','2026-06-26 00:20:36','2026-06-26 00:20:36'),(32,'BRD040','New Era','Contact New Era','+94771234567','newera@corp.com','newera.com','2026-06-26','2026-06-26 00:20:37','2026-06-26 00:20:37'),(33,'BRD041','Carhartt','Contact Carhartt','+94771234567','carhartt@corp.com','carhartt.com','2026-06-26','2026-06-26 00:20:37','2026-06-26 00:20:37'),(34,'BRD042','Keurig','Contact Keurig','+94771234567','keurig@corp.com','keurig.com','2026-06-26','2026-06-26 00:20:37','2026-06-26 00:20:37'),(35,'BRD043','Nespresso','Contact Nespresso','+94771234567','nespresso@corp.com','nespresso.com','2026-06-26','2026-06-26 00:20:37','2026-06-26 00:20:37'),(36,'BRD045','Hamilton Beach','Contact Hamilton Beach','+94771234567','hamiltonbeach@corp.com','hamiltonbeach.com','2026-06-26','2026-06-26 00:20:37','2026-06-26 00:20:37'),(37,'BRD046','Toshiba','Contact Toshiba','+94771234567','toshiba@corp.com','toshiba.com','2026-06-26','2026-06-26 00:20:38','2026-06-26 00:20:38'),(38,'BRD047','Panasonic','Contact Panasonic','+94771234567','panasonic@corp.com','panasonic.com','2026-06-26','2026-06-26 00:20:38','2026-06-26 00:20:38'),(39,'BRD048','Black+Decker','Contact Black+Decker','+94771234567','black+decker@corp.com','black+decker.com','2026-06-26','2026-06-26 00:20:38','2026-06-26 00:20:38'),(40,'BRD049','Vitamix','Contact Vitamix','+94771234567','vitamix@corp.com','vitamix.com','2026-06-26','2026-06-26 00:20:38','2026-06-26 00:20:38'),(41,'BRD051','NutriBullet','Contact NutriBullet','+94771234567','nutribullet@corp.com','nutribullet.com','2026-06-26','2026-06-26 00:20:38','2026-06-26 00:20:38'),(42,'BRD052','Magic Bullet','Contact Magic Bullet','+94771234567','magicbullet@corp.com','magicbullet.com','2026-06-26','2026-06-26 00:20:38','2026-06-26 00:20:38'),(43,'BRD053','Cuisinart','Contact Cuisinart','+94771234567','cuisinart@corp.com','cuisinart.com','2026-06-26','2026-06-26 00:20:39','2026-06-26 00:20:39'),(44,'BRD054','KitchenAid','Contact KitchenAid','+94771234567','kitchenaid@corp.com','kitchenaid.com','2026-06-26','2026-06-26 00:20:39','2026-06-26 00:20:39'),(45,'BRD055','Oster','Contact Oster','+94771234567','oster@corp.com','oster.com','2026-06-26','2026-06-26 00:20:39','2026-06-26 00:20:39'),(46,'BRD056','Cosori','Contact Cosori','+94771234567','cosori@corp.com','cosori.com','2026-06-26','2026-06-26 00:20:39','2026-06-26 00:20:39'),(47,'BRD057','Instant Pot','Contact Instant Pot','+94771234567','instantpot@corp.com','instantpot.com','2026-06-26','2026-06-26 00:20:39','2026-06-26 00:20:39'),(48,'BRD058','Wilson','Contact Wilson','+94771234567','wilson@corp.com','wilson.com','2026-06-26','2026-06-26 00:20:40','2026-06-26 00:20:40'),(49,'BRD059','Spalding','Contact Spalding','+94771234567','spalding@corp.com','spalding.com','2026-06-26','2026-06-26 00:20:40','2026-06-26 00:20:40'),(50,'BRD060','Bowflex','Contact Bowflex','+94771234567','bowflex@corp.com','bowflex.com','2026-06-26','2026-06-26 00:20:40','2026-06-26 00:20:40'),(51,'BRD061','CAP','Contact CAP','+94771234567','cap@corp.com','cap.com','2026-06-26','2026-06-26 00:20:40','2026-06-26 00:20:40'),(52,'BRD062','Amazon Basics','Contact Amazon Basics','+94771234567','amazonbasics@corp.com','amazonbasics.com','2026-06-26','2026-06-26 00:20:40','2026-06-26 00:20:40'),(53,'BRD063','Rogue','Contact Rogue','+94771234567','rogue@corp.com','rogue.com','2026-06-26','2026-06-26 00:20:41','2026-06-26 00:20:41'),(54,'BRD064','Lululemon','Contact Lululemon','+94771234567','lululemon@corp.com','lululemon.com','2026-06-26','2026-06-26 00:20:41','2026-06-26 00:20:41'),(55,'BRD065','Manduka','Contact Manduka','+94771234567','manduka@corp.com','manduka.com','2026-06-26','2026-06-26 00:20:41','2026-06-26 00:20:41'),(56,'BRD066','Gaiam','Contact Gaiam','+94771234567','gaiam@corp.com','gaiam.com','2026-06-26','2026-06-26 00:20:41','2026-06-26 00:20:41'),(57,'BRD067','Jade','Contact Jade','+94771234567','jade@corp.com','jade.com','2026-06-26','2026-06-26 00:20:41','2026-06-26 00:20:41'),(58,'BRD068','Hydro Flask','Contact Hydro Flask','+94771234567','hydroflask@corp.com','hydroflask.com','2026-06-26','2026-06-26 00:20:41','2026-06-26 00:20:41'),(59,'BRD069','Yeti','Contact Yeti','+94771234567','yeti@corp.com','yeti.com','2026-06-26','2026-06-26 00:20:42','2026-06-26 00:20:42'),(60,'BRD070','CamelBak','Contact CamelBak','+94771234567','camelbak@corp.com','camelbak.com','2026-06-26','2026-06-26 00:20:42','2026-06-26 00:20:42'),(61,'BRD071','Nalgene','Contact Nalgene','+94771234567','nalgene@corp.com','nalgene.com','2026-06-26','2026-06-26 00:20:42','2026-06-26 00:20:42'),(62,'BRD072','Brooks','Contact Brooks','+94771234567','brooks@corp.com','brooks.com','2026-06-26','2026-06-26 00:20:42','2026-06-26 00:20:42'),(63,'BRD073','Asics','Contact Asics','+94771234567','asics@corp.com','asics.com','2026-06-26','2026-06-26 00:20:42','2026-06-26 00:20:42'),(64,'BRD074','Moleskine','Contact Moleskine','+94771234567','moleskine@corp.com','moleskine.com','2026-06-26','2026-06-26 00:20:42','2026-06-26 00:20:42'),(65,'BRD075','Rhodia','Contact Rhodia','+94771234567','rhodia@corp.com','rhodia.com','2026-06-26','2026-06-26 00:20:43','2026-06-26 00:20:43'),(66,'BRD076','Leuchtturm1917','Contact Leuchtturm1917','+94771234567','leuchtturm1917@corp.com','leuchtturm1917.com','2026-06-26','2026-06-26 00:20:43','2026-06-26 00:20:43'),(67,'BRD077','Field Notes','Contact Field Notes','+94771234567','fieldnotes@corp.com','fieldnotes.com','2026-06-26','2026-06-26 00:20:43','2026-06-26 00:20:43'),(68,'BRD078','Pilot','Contact Pilot','+94771234567','pilot@corp.com','pilot.com','2026-06-26','2026-06-26 00:20:43','2026-06-26 00:20:43'),(69,'BRD079','Uni-ball','Contact Uni-ball','+94771234567','uni-ball@corp.com','uni-ball.com','2026-06-26','2026-06-26 00:20:44','2026-06-26 00:20:44'),(70,'BRD080','Parker','Contact Parker','+94771234567','parker@corp.com','parker.com','2026-06-26','2026-06-26 00:20:44','2026-06-26 00:20:44'),(71,'BRD081','Sharpie','Contact Sharpie','+94771234567','sharpie@corp.com','sharpie.com','2026-06-26','2026-06-26 00:20:44','2026-06-26 00:20:44'),(72,'BRD082','Mesh','Contact Mesh','+94771234567','mesh@corp.com','mesh.com','2026-06-26','2026-06-26 00:20:44','2026-06-26 00:20:44'),(73,'BRD083','Wooden','Contact Wooden','+94771234567','wooden@corp.com','wooden.com','2026-06-26','2026-06-26 00:20:44','2026-06-26 00:20:44'),(74,'BRD084','Leather','Contact Leather','+94771234567','leather@corp.com','leather.com','2026-06-26','2026-06-26 00:20:44','2026-06-26 00:20:44'),(75,'BRD085','Monitor Stand','Contact Monitor Stand','+94771234567','monitorstand@corp.com','monitorstand.com','2026-06-26','2026-06-26 00:20:44','2026-06-26 00:20:44'),(76,'BRD086','Swingline','Contact Swingline','+94771234567','swingline@corp.com','swingline.com','2026-06-26','2026-06-26 00:20:45','2026-06-26 00:20:45'),(77,'BRD087','Bostitch','Contact Bostitch','+94771234567','bostitch@corp.com','bostitch.com','2026-06-26','2026-06-26 00:20:45','2026-06-26 00:20:45'),(78,'BRD088','PaperPro','Contact PaperPro','+94771234567','paperpro@corp.com','paperpro.com','2026-06-26','2026-06-26 00:20:45','2026-06-26 00:20:45'),(79,'BRD089','Rapid','Contact Rapid','+94771234567','rapid@corp.com','rapid.com','2026-06-26','2026-06-26 00:20:45','2026-06-26 00:20:45'),(80,'BRD090','Fellowes','Contact Fellowes','+94771234567','fellowes@corp.com','fellowes.com','2026-06-26','2026-06-26 00:20:45','2026-06-26 00:20:45'),(81,'BRD091','Aurora','Contact Aurora','+94771234567','aurora@corp.com','aurora.com','2026-06-26','2026-06-26 00:20:45','2026-06-26 00:20:45'),(82,'BRD092','Royal Sovereign','Contact Royal Sovereign','+94771234567','royalsovereign@corp.com','royalsovereign.com','2026-06-26','2026-06-26 00:20:46','2026-06-26 00:20:46');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_code` varchar(20) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'CAT001','Electronics','Gadgets, devices and accessories','2026-06-26',1,'2026-06-26 00:20:23','2026-06-26 00:20:23'),(2,'CAT002','Apparel','Clothing, footwear and wearable gear','2026-06-26',1,'2026-06-26 00:20:23','2026-06-26 00:20:23'),(3,'CAT003','Home & Kitchen','Appliances, cooking tools and smart home devices','2026-06-26',1,'2026-06-26 00:20:23','2026-06-26 00:20:23'),(4,'CAT004','Sports & Outdoors','Fitness equipment, sporting goods and outdoor gear','2026-06-26',1,'2026-06-26 00:20:23','2026-06-26 00:20:23'),(5,'CAT005','Office Supplies','Stationery, desk organizers and office electronics','2026-06-26',1,'2026-06-26 00:20:23','2026-06-26 00:20:23');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `customer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(50) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `customer_type` varchar(255) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `customers_email_unique` (`email`),
  UNIQUE KEY `customers_customer_code_unique` (`customer_code`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'CUS001','Customer_First_1','Customer_Last_1',NULL,'customer1@gmail.com','+94723000001','No. 1, Galle Road, Colombo','Retail','2025-07-26',0.00,'2026-06-26 00:20:30','2026-06-26 00:20:30'),(2,'CUS002','Customer_First_2','Customer_Last_2',NULL,'customer2@gmail.com','+94723000002','No. 2, Galle Road, Colombo','Retail','2025-08-26',0.00,'2026-06-26 00:20:30','2026-06-26 00:20:30'),(3,'CUS003','Customer_First_3','Customer_Last_3','Corporate Customer 3 PLC','customer3@gmail.com','+94723000003','No. 3, Galle Road, Colombo','Wholesale','2025-09-26',500000.00,'2026-06-26 00:20:30','2026-06-26 00:20:30'),(4,'CUS004','Customer_First_4','Customer_Last_4',NULL,'customer4@gmail.com','+94723000004','No. 4, Galle Road, Colombo','Retail','2025-10-26',0.00,'2026-06-26 00:20:30','2026-06-26 00:20:30'),(5,'CUS005','Customer_First_5','Customer_Last_5',NULL,'customer5@gmail.com','+94723000005','No. 5, Galle Road, Colombo','Retail','2025-11-26',0.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(6,'CUS006','Customer_First_6','Customer_Last_6','Corporate Customer 6 PLC','customer6@gmail.com','+94723000006','No. 6, Galle Road, Colombo','Wholesale','2025-12-26',500000.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(7,'CUS007','Customer_First_7','Customer_Last_7',NULL,'customer7@gmail.com','+94723000007','No. 7, Galle Road, Colombo','Retail','2026-01-26',0.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(8,'CUS008','Customer_First_8','Customer_Last_8',NULL,'customer8@gmail.com','+94723000008','No. 8, Galle Road, Colombo','Retail','2026-02-26',0.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(9,'CUS009','Customer_First_9','Customer_Last_9','Corporate Customer 9 PLC','customer9@gmail.com','+94723000009','No. 9, Galle Road, Colombo','Wholesale','2026-03-26',500000.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(10,'CUS010','Customer_First_10','Customer_Last_10',NULL,'customer10@gmail.com','+94723000010','No. 10, Galle Road, Colombo','Retail','2026-04-26',0.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(11,'CUS011','Customer_First_11','Customer_Last_11',NULL,'customer11@gmail.com','+94723000011','No. 11, Galle Road, Colombo','Retail','2026-05-26',0.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(12,'CUS012','Customer_First_12','Customer_Last_12','Corporate Customer 12 PLC','customer12@gmail.com','+94723000012','No. 12, Galle Road, Colombo','Wholesale','2026-06-26',500000.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(13,'CUS013','Customer_First_13','Customer_Last_13',NULL,'customer13@gmail.com','+94723000013','No. 13, Galle Road, Colombo','Retail','2026-07-26',0.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(14,'CUS014','Customer_First_14','Customer_Last_14',NULL,'customer14@gmail.com','+94723000014','No. 14, Galle Road, Colombo','Retail','2026-08-26',0.00,'2026-06-26 00:20:31','2026-06-26 00:20:31'),(15,'CUS015','Customer_First_15','Customer_Last_15','Corporate Customer 15 PLC','customer15@gmail.com','+94723000015','No. 15, Galle Road, Colombo','Wholesale','2026-09-26',500000.00,'2026-06-26 00:20:31','2026-06-26 00:20:31');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `department_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_code` varchar(255) DEFAULT NULL,
  `department_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  KEY `departments_branch_id_foreign` (`branch_id`),
  KEY `departments_manager_id_foreign` (`manager_id`),
  CONSTRAINT `departments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE,
  CONSTRAINT `departments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'DEP001','Sales & Marketing','Deals with sales, client relations and marketing campaigns',6,1,'2026-06-26','2026-06-26 00:20:27','2026-06-26 00:20:29'),(2,'DEP002','Procurement & Logistics','Deals with supplier sourcing, ordering and GRNs',7,1,'2026-06-26','2026-06-26 00:20:27','2026-06-26 00:20:29'),(3,'DEP003','Inventory Control','Manages stocks, transactions and warehousing',8,1,'2026-06-26','2026-06-26 00:20:27','2026-06-26 00:20:29'),(4,'DEP004','Finance & Accounting','Manages payroll, invoices, expenses and bank records',9,1,'2026-06-26','2026-06-26 00:20:27','2026-06-26 00:20:29'),(5,'DEP005','Human Resources','Manages employee hiring, performance and details',10,1,'2026-06-26','2026-06-26 00:20:27','2026-06-26 00:20:29');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `employee_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(20) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_num1` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `department_id` bigint(20) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_num2` varchar(255) DEFAULT NULL,
  `nic_num` varchar(255) DEFAULT NULL,
  `nic_front` varchar(255) DEFAULT NULL,
  `nic_back` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `employees_email_unique` (`email`),
  KEY `employees_department_id_foreign` (`department_id`),
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'EMP001','John','Smith','john.smith@nexuserp.com','+94772000000','2024-06-26','Sales Executive',45000.00,'No. 10, Main Road, Colombo','2004-06-26',1,1,'2026-06-26 00:20:27','2026-06-26 00:20:27','+94773000000','199000000001V',NULL,NULL),(2,'EMP002','Jane','Doe','jane.doe@nexuserp.com','+94772000001','2024-07-26','Procurement Officer',50000.00,'No. 11, Main Road, Colombo','2003-06-26',2,1,'2026-06-26 00:20:27','2026-06-26 00:20:27','+94773000001','199000000002V',NULL,NULL),(3,'EMP003','David','Johnson','david.johnson@nexuserp.com','+94772000002','2024-08-26','Inventory Clerk',55000.00,'No. 12, Main Road, Colombo','2002-06-26',3,1,'2026-06-26 00:20:27','2026-06-26 00:20:27','+94773000002','199000000003V',NULL,NULL),(4,'EMP004','Sarah','Williams','sarah.williams@nexuserp.com','+94772000003','2024-09-26','Accountant',60000.00,'No. 13, Main Road, Colombo','2001-06-26',4,1,'2026-06-26 00:20:27','2026-06-26 00:20:27','+94773000003','199000000004V',NULL,NULL),(5,'EMP005','Michael','Brown','michael.brown@nexuserp.com','+94772000004','2024-10-26','HR Specialist',65000.00,'No. 14, Main Road, Colombo','2000-06-26',5,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000004','199000000005V',NULL,NULL),(6,'EMP006','Emily','Jones','emily.jones@nexuserp.com','+94772000005','2024-11-26','Branch Assistant',70000.00,'No. 15, Main Road, Colombo','1999-06-26',1,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000005','199000000006V',NULL,NULL),(7,'EMP007','Robert','Miller','robert.miller@nexuserp.com','+94772000006','2024-12-26','Sales Manager',75000.00,'No. 16, Main Road, Colombo','1998-06-26',2,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000006','199000000007V',NULL,NULL),(8,'EMP008','Jessica','Davis','jessica.davis@nexuserp.com','+94772000007','2025-01-26','Operations Lead',80000.00,'No. 17, Main Road, Colombo','1997-06-26',3,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000007','199000000008V',NULL,NULL),(9,'EMP009','William','Wilson','william.wilson@nexuserp.com','+94772000008','2025-02-26','Office Supervisor',85000.00,'No. 18, Main Road, Colombo','1996-06-26',4,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000008','199000000009V',NULL,NULL),(10,'EMP010','Ashley','Anderson','ashley.anderson@nexuserp.com','+94772000009','2025-03-26','Store Manager',90000.00,'No. 19, Main Road, Colombo','1995-06-26',5,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000009','199000000010V',NULL,NULL),(11,'EMP011','James','Taylor','james.taylor@nexuserp.com','+94772000010','2025-04-26','Sales Executive',95000.00,'No. 20, Main Road, Colombo','1994-06-26',1,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000010','199000000011V',NULL,NULL),(12,'EMP012','Amanda','Thomas','amanda.thomas@nexuserp.com','+94772000011','2025-05-26','Procurement Officer',100000.00,'No. 21, Main Road, Colombo','1993-06-26',2,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000011','199000000012V',NULL,NULL),(13,'EMP013','Charles','Moore','charles.moore@nexuserp.com','+94772000012','2025-06-26','Inventory Clerk',105000.00,'No. 22, Main Road, Colombo','1992-06-26',3,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000012','199000000013V',NULL,NULL),(14,'EMP014','Mary','Martin','mary.martin@nexuserp.com','+94772000013','2025-07-26','Accountant',110000.00,'No. 23, Main Road, Colombo','1991-06-26',4,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000013','199000000014V',NULL,NULL),(15,'EMP015','Richard','Jackson','richard.jackson@nexuserp.com','+94772000014','2025-08-26','HR Specialist',115000.00,'No. 24, Main Road, Colombo','1990-06-26',5,1,'2026-06-26 00:20:28','2026-06-26 00:20:28','+94773000014','199000000015V',NULL,NULL);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `inv_paras`
--

DROP TABLE IF EXISTS `inv_paras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inv_paras` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_flag` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_no_01` varchar(255) NOT NULL,
  `phone_no_02` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `dashboard_logo_size` int(11) NOT NULL DEFAULT 80,
  `auto_print_grn` tinyint(1) NOT NULL DEFAULT 0,
  `show_print_preview` tinyint(1) NOT NULL DEFAULT 1,
  `print_footer_text` varchar(255) DEFAULT NULL,
  `grn_print_size` varchar(255) NOT NULL DEFAULT 'A4',
  `product_code` int(11) NOT NULL DEFAULT 1,
  `brand_code` int(11) NOT NULL DEFAULT 1,
  `category_code` int(11) NOT NULL DEFAULT 1,
  `invoice_no` int(11) NOT NULL DEFAULT 1,
  `grn_no` int(11) NOT NULL DEFAULT 1,
  `branch_code` int(11) NOT NULL DEFAULT 1,
  `bank_code` int(11) NOT NULL DEFAULT 1,
  `customer_code` int(11) NOT NULL DEFAULT 1,
  `employee_code` int(11) NOT NULL DEFAULT 1,
  `supplier_code` int(11) NOT NULL DEFAULT 1,
  `department_code` int(11) NOT NULL DEFAULT 1,
  `expenses_code` int(11) NOT NULL DEFAULT 1,
  `store_code` int(11) NOT NULL DEFAULT 1,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `package` varchar(255) NOT NULL DEFAULT 'basic',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inv_paras`
--

LOCK TABLES `inv_paras` WRITE;
/*!40000 ALTER TABLE `inv_paras` DISABLE KEYS */;
INSERT INTO `inv_paras` VALUES (1,'1','Nexus ERP Solutions','456 Innovation Drive, Colombo, Sri Lanka','support@nexuserp.com','+94112233445','+94112233446','www.nexuserp.com',NULL,80,0,1,NULL,'A4',100,10,5,1000,2000,5,5,15,10,10,5,1,5,'active','premium','2026-06-26 00:20:23','2026-06-26 00:20:23');
/*!40000 ALTER TABLE `inv_paras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_items` (
  `item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_product_id_foreign` (`product_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (3,4,1,'iPhone 15 Pro','PROD001',10000.00,5,50000.00,'2026-02-22 13:21:32','2026-02-22 13:21:32'),(4,4,2,'Samsung Galaxy S24','PROD002',10000.00,5,50000.00,'2026-02-22 13:21:32','2026-02-22 13:21:32'),(5,4,3,'Google Pixel 8','PROD003',10000.00,1,10000.00,'2026-02-22 13:21:32','2026-02-22 13:21:32'),(6,4,4,'OnePlus 12','PROD004',10000.00,5,50000.00,'2026-02-22 13:21:32','2026-02-22 13:21:32'),(7,6,3,'Google Pixel 8','PROD003',10000.00,4,40000.00,'2026-02-06 20:21:33','2026-02-06 20:21:33'),(8,6,4,'OnePlus 12','PROD004',10000.00,5,50000.00,'2026-02-06 20:21:33','2026-02-06 20:21:33'),(9,7,5,'MacBook Air M3','PROD005',10000.00,1,10000.00,'2026-02-12 14:21:33','2026-02-12 14:21:33'),(10,7,6,'Dell XPS 13','PROD006',10000.00,3,30000.00,'2026-02-12 14:21:33','2026-02-12 14:21:33'),(11,7,7,'HP Spectre x360','PROD007',10000.00,5,50000.00,'2026-02-12 14:21:33','2026-02-12 14:21:33'),(13,9,7,'HP Spectre x360','PROD007',10000.00,2,20000.00,'2026-01-28 12:21:34','2026-01-28 12:21:34'),(15,9,8,'Lenovo ThinkPad X1','PROD008',10000.00,5,50000.00,'2026-01-28 12:21:34','2026-01-28 12:21:34'),(17,12,9,'Sony WH-1000XM5','PROD009',10000.00,5,50000.00,'2026-01-31 13:21:34','2026-01-31 13:21:34'),(18,12,10,'Bose QuietComfort Ultra','PROD010',10000.00,3,30000.00,'2026-01-31 13:21:34','2026-01-31 13:21:34'),(19,12,11,'Apple AirPods Pro','PROD011',10000.00,1,10000.00,'2026-01-31 13:21:34','2026-01-31 13:21:34'),(20,12,12,'Sennheiser Momentum 4','PROD012',10000.00,5,50000.00,'2026-01-31 13:21:34','2026-01-31 13:21:34'),(21,13,11,'Apple AirPods Pro','PROD011',10000.00,2,20000.00,'2026-02-08 19:21:34','2026-02-08 19:21:34'),(22,13,12,'Sennheiser Momentum 4','PROD012',10000.00,4,40000.00,'2026-02-08 19:21:34','2026-02-08 19:21:34'),(23,13,13,'Anker PowerCore 24K','PROD013',1000.00,3,3000.00,'2026-02-08 19:21:34','2026-02-08 19:21:34'),(24,14,13,'Anker PowerCore 24K','PROD013',1000.00,2,2000.00,'2026-02-21 15:21:35','2026-02-21 15:21:35'),(25,14,14,'Logitech MX Master 3S','PROD014',1000.00,4,4000.00,'2026-02-21 15:21:35','2026-02-21 15:21:35'),(26,14,15,'Apple Magic Keyboard','PROD015',1000.00,5,5000.00,'2026-02-21 15:21:35','2026-02-21 15:21:35'),(27,15,15,'Apple Magic Keyboard','PROD015',1000.00,3,3000.00,'2026-01-31 12:21:36','2026-01-31 12:21:36'),(28,15,16,'Samsung T7 Shield SSD','PROD016',1000.00,2,2000.00,'2026-01-31 12:21:36','2026-01-31 12:21:36'),(29,15,17,'Amazon Echo Dot','PROD017',1000.00,1,1000.00,'2026-01-31 12:21:36','2026-01-31 12:21:36'),(30,16,17,'Amazon Echo Dot','PROD017',1000.00,3,3000.00,'2026-02-13 16:21:37','2026-02-13 16:21:37'),(31,16,18,'Google Nest Hub','PROD018',1000.00,2,2000.00,'2026-02-13 16:21:37','2026-02-13 16:21:37'),(32,16,19,'Philips Hue Starter Kit','PROD019',1000.00,4,4000.00,'2026-02-13 16:21:37','2026-02-13 16:21:37'),(33,17,19,'Philips Hue Starter Kit','PROD019',1000.00,1,1000.00,'2026-02-05 14:21:37','2026-02-05 14:21:37'),(34,17,20,'Ring Video Doorbell','PROD020',10000.00,5,50000.00,'2026-02-05 14:21:37','2026-02-05 14:21:37'),(35,18,21,'Nike Dri-FIT Tee','PROD021',1000.00,5,5000.00,'2026-02-05 23:21:39','2026-02-05 23:21:39'),(36,18,22,'Adidas Originals Tee','PROD022',1000.00,4,4000.00,'2026-02-05 23:21:39','2026-02-05 23:21:39'),(37,18,23,'Puma Classic Tee','PROD023',1000.00,1,1000.00,'2026-02-05 23:21:39','2026-02-05 23:21:39'),(38,18,24,'Under Armour Sport Tee','PROD024',1000.00,1,1000.00,'2026-02-05 23:21:39','2026-02-05 23:21:39'),(39,19,23,'Puma Classic Tee','PROD023',1000.00,1,1000.00,'2026-02-02 21:21:39','2026-02-02 21:21:39'),(40,19,24,'Under Armour Sport Tee','PROD024',1000.00,3,3000.00,'2026-02-02 21:21:39','2026-02-02 21:21:39'),(41,19,25,'Levis 501 Original','PROD025',1000.00,5,5000.00,'2026-02-02 21:21:39','2026-02-02 21:21:39'),(42,19,26,'Wrangler Authentic Jeans','PROD026',1000.00,1,1000.00,'2026-02-02 21:21:39','2026-02-02 21:21:39'),(43,20,25,'Levis 501 Original','PROD025',1000.00,3,3000.00,'2026-02-17 19:21:40','2026-02-17 19:21:40'),(44,20,26,'Wrangler Authentic Jeans','PROD026',1000.00,5,5000.00,'2026-02-17 19:21:40','2026-02-17 19:21:40'),(45,20,27,'Lee Rider Slim Jeans','PROD027',1000.00,5,5000.00,'2026-02-17 19:21:40','2026-02-17 19:21:40'),(46,21,27,'Lee Rider Slim Jeans','PROD027',1000.00,2,2000.00,'2026-01-30 14:21:40','2026-01-30 14:21:40'),(47,21,28,'Diesel D-Strukt Jeans','PROD028',1000.00,5,5000.00,'2026-01-30 14:21:40','2026-01-30 14:21:40'),(48,22,29,'Patagonia Torrentshell','PROD029',1000.00,4,4000.00,'2026-01-31 18:21:41','2026-01-31 18:21:41'),(49,22,30,'The North Face Resolve','PROD030',1000.00,2,2000.00,'2026-01-31 18:21:41','2026-01-31 18:21:41'),(50,22,31,'Columbia Watertight','PROD031',1000.00,4,4000.00,'2026-01-31 18:21:41','2026-01-31 18:21:41'),(51,23,31,'Columbia Watertight','PROD031',1000.00,3,3000.00,'2026-02-05 22:21:41','2026-02-05 22:21:41'),(52,23,32,'Arc`teryx Beta LT','PROD032',10000.00,3,30000.00,'2026-02-05 22:21:41','2026-02-05 22:21:41'),(53,23,33,'Darn Tough Cushion','PROD033',1000.00,5,5000.00,'2026-02-05 22:21:41','2026-02-05 22:21:41'),(54,23,34,'Smartwool Run Socks','PROD034',100.00,3,300.00,'2026-02-05 22:21:41','2026-02-05 22:21:41'),(55,24,33,'Darn Tough Cushion','PROD033',1000.00,1,1000.00,'2026-03-19 13:21:41','2026-03-19 13:21:41'),(56,24,34,'Smartwool Run Socks','PROD034',100.00,5,500.00,'2026-03-19 13:21:41','2026-03-19 13:21:41'),(57,25,35,'Nike Everyday Cushion','PROD035',100.00,5,500.00,'2026-03-06 23:21:41','2026-03-06 23:21:41'),(58,25,36,'Adidas Athletic Socks','PROD036',100.00,1,100.00,'2026-03-06 23:21:41','2026-03-06 23:21:41'),(59,25,37,'New Era 59FIFTY Cap','PROD037',1000.00,4,4000.00,'2026-03-06 23:21:41','2026-03-06 23:21:41'),(60,26,37,'New Era 59FIFTY Cap','PROD037',1000.00,4,4000.00,'2026-03-22 12:21:42','2026-03-22 12:21:42'),(61,26,38,'Nike Legacy91 Cap','PROD038',1000.00,3,3000.00,'2026-03-22 12:21:42','2026-03-22 12:21:42'),(62,26,39,'Adidas Superlite Cap','PROD039',1000.00,5,5000.00,'2026-03-22 12:21:42','2026-03-22 12:21:42'),(63,27,39,'Adidas Superlite Cap','PROD039',1000.00,5,5000.00,'2026-02-26 12:21:42','2026-02-26 12:21:42'),(64,27,40,'Carhartt Beanie','PROD040',1000.00,5,5000.00,'2026-02-26 12:21:42','2026-02-26 12:21:42'),(65,28,41,'Keurig K-Elite','PROD041',10000.00,4,40000.00,'2026-03-19 14:21:43','2026-03-19 14:21:43'),(66,28,42,'Nespresso Vertuo Next','PROD042',10000.00,2,20000.00,'2026-03-19 14:21:43','2026-03-19 14:21:43'),(67,29,43,'Breville Barista','PROD043',10000.00,2,20000.00,'2026-03-16 14:21:44','2026-03-16 14:21:44'),(68,29,44,'Hamilton FlexBrew','PROD044',1000.00,1,1000.00,'2026-03-16 14:21:44','2026-03-16 14:21:44'),(69,29,45,'Toshiba EM131A5C','PROD045',1000.00,2,2000.00,'2026-03-16 14:21:44','2026-03-16 14:21:44'),(70,29,46,'Panasonic NN-SN686S','PROD046',10000.00,4,40000.00,'2026-03-16 14:21:44','2026-03-16 14:21:44'),(71,30,45,'Toshiba EM131A5C','PROD045',1000.00,3,3000.00,'2026-03-07 23:21:44','2026-03-07 23:21:44'),(72,30,46,'Panasonic NN-SN686S','PROD046',10000.00,4,40000.00,'2026-03-07 23:21:44','2026-03-07 23:21:44'),(73,30,47,'Black+Decker EM720','PROD047',1000.00,1,1000.00,'2026-03-07 23:21:44','2026-03-07 23:21:44'),(74,31,47,'Black+Decker EM720','PROD047',1000.00,5,5000.00,'2026-02-25 15:21:44','2026-02-25 15:21:44'),(75,31,48,'Samsung MS19M','PROD048',10000.00,1,10000.00,'2026-02-25 15:21:44','2026-02-25 15:21:44'),(76,31,49,'Vitamix 5200','PROD049',10000.00,4,40000.00,'2026-02-25 15:21:44','2026-02-25 15:21:44'),(77,31,50,'Ninja Professional','PROD050',10000.00,5,50000.00,'2026-02-25 15:21:44','2026-02-25 15:21:44'),(78,32,49,'Vitamix 5200','PROD049',10000.00,3,30000.00,'2026-02-25 16:21:45','2026-02-25 16:21:45'),(79,32,50,'Ninja Professional','PROD050',10000.00,2,20000.00,'2026-02-25 16:21:45','2026-02-25 16:21:45'),(80,32,51,'NutriBullet Pro','PROD051',1000.00,2,2000.00,'2026-02-25 16:21:45','2026-02-25 16:21:45'),(81,33,51,'NutriBullet Pro','PROD051',1000.00,3,3000.00,'2026-03-24 17:21:45','2026-03-24 17:21:45'),(82,33,52,'Magic Bullet Blender','PROD052',1000.00,1,1000.00,'2026-03-24 17:21:45','2026-03-24 17:21:45'),(83,34,53,'Breville Bit More','PROD053',1000.00,1,1000.00,'2026-03-18 22:21:45','2026-03-18 22:21:45'),(84,34,54,'Cuisinart CPT-180','PROD054',1000.00,4,4000.00,'2026-03-18 22:21:45','2026-03-18 22:21:45'),(85,35,55,'KitchenAid 2-Slice','PROD055',1000.00,5,5000.00,'2026-03-06 18:21:46','2026-03-06 18:21:46'),(86,35,56,'Oster 4-Slice Toaster','PROD056',1000.00,3,3000.00,'2026-03-06 18:21:46','2026-03-06 18:21:46'),(87,35,57,'Cosori Pro II','PROD057',1000.00,4,4000.00,'2026-03-06 18:21:46','2026-03-06 18:21:46'),(88,36,57,'Cosori Pro II','PROD057',1000.00,1,1000.00,'2026-03-21 22:21:46','2026-03-21 22:21:46'),(89,36,58,'Ninja AF101','PROD058',10000.00,5,50000.00,'2026-03-21 22:21:46','2026-03-21 22:21:46'),(90,36,59,'Instant Vortex Plus','PROD059',10000.00,4,40000.00,'2026-03-21 22:21:46','2026-03-21 22:21:46'),(91,37,59,'Instant Vortex Plus','PROD059',10000.00,3,30000.00,'2026-03-16 20:21:46','2026-03-16 20:21:46'),(92,37,60,'Philips Essential Air','PROD060',1000.00,4,4000.00,'2026-03-16 20:21:46','2026-03-16 20:21:46'),(93,37,61,'Wilson NFL Duke','PROD061',1000.00,5,5000.00,'2026-03-16 20:21:46','2026-03-16 20:21:46'),(94,38,61,'Wilson NFL Duke','PROD061',1000.00,5,5000.00,'2026-03-16 21:21:47','2026-03-16 21:21:47'),(95,38,62,'Adidas Al Rihla','PROD062',1000.00,5,5000.00,'2026-03-16 21:21:47','2026-03-16 21:21:47'),(96,39,63,'Nike Strike Ball','PROD063',1000.00,4,4000.00,'2026-03-03 18:21:47','2026-03-03 18:21:47'),(97,39,64,'Spalding TF-1000 Ball','PROD064',1000.00,2,2000.00,'2026-03-03 18:21:47','2026-03-03 18:21:47'),(98,39,65,'Bowflex SelectTech','PROD065',10000.00,4,40000.00,'2026-03-03 18:21:47','2026-03-03 18:21:47'),(99,39,66,'CAP Cast Iron Dumbbell','PROD066',1000.00,4,4000.00,'2026-03-03 18:21:47','2026-03-03 18:21:47'),(100,40,65,'Bowflex SelectTech','PROD065',10000.00,5,50000.00,'2026-04-17 14:21:48','2026-04-17 14:21:48'),(101,40,66,'CAP Cast Iron Dumbbell','PROD066',1000.00,1,1000.00,'2026-04-17 14:21:48','2026-04-17 14:21:48'),(102,41,67,'Amazon Basics Neoprene','PROD067',1000.00,5,5000.00,'2026-04-10 12:21:48','2026-04-10 12:21:48'),(103,41,68,'Rogue Rubber Hex','PROD068',1000.00,2,2000.00,'2026-04-10 12:21:48','2026-04-10 12:21:48'),(104,41,69,'Lululemon 5mm Mat','PROD069',1000.00,3,3000.00,'2026-04-10 12:21:48','2026-04-10 12:21:48'),(105,41,70,'Manduka PRO Mat','PROD070',1000.00,3,3000.00,'2026-04-10 12:21:48','2026-04-10 12:21:48'),(106,42,69,'Lululemon 5mm Mat','PROD069',1000.00,2,2000.00,'2026-04-16 23:21:48','2026-04-16 23:21:48'),(107,42,70,'Manduka PRO Mat','PROD070',1000.00,5,5000.00,'2026-04-16 23:21:48','2026-04-16 23:21:48'),(108,42,71,'Gaiam Essentials Mat','PROD071',1000.00,1,1000.00,'2026-04-16 23:21:48','2026-04-16 23:21:48'),(109,42,72,'Jade Harmony Mat','PROD072',1000.00,2,2000.00,'2026-04-16 23:21:48','2026-04-16 23:21:48'),(110,43,71,'Gaiam Essentials Mat','PROD071',1000.00,3,3000.00,'2026-04-05 22:21:49','2026-04-05 22:21:49'),(111,43,72,'Jade Harmony Mat','PROD072',1000.00,5,5000.00,'2026-04-05 22:21:49','2026-04-05 22:21:49'),(112,44,73,'Hydro Flask 32oz','PROD073',1000.00,5,5000.00,'2026-04-01 18:21:49','2026-04-01 18:21:49'),(113,44,74,'Yeti Rambler 26oz','PROD074',1000.00,4,4000.00,'2026-04-01 18:21:49','2026-04-01 18:21:49'),(114,44,75,'CamelBak Chute Mag','PROD075',1000.00,3,3000.00,'2026-04-01 18:21:49','2026-04-01 18:21:49'),(115,44,76,'Nalgene Wide Mouth','PROD076',1000.00,4,4000.00,'2026-04-01 18:21:49','2026-04-01 18:21:49'),(116,45,75,'CamelBak Chute Mag','PROD075',1000.00,4,4000.00,'2026-04-03 13:21:49','2026-04-03 13:21:49'),(117,45,76,'Nalgene Wide Mouth','PROD076',1000.00,1,1000.00,'2026-04-03 13:21:49','2026-04-03 13:21:49'),(118,45,77,'Nike Air Zoom Pegasus','PROD077',1000.00,2,2000.00,'2026-04-03 13:21:49','2026-04-03 13:21:49'),(119,46,77,'Nike Air Zoom Pegasus','PROD077',1000.00,2,2000.00,'2026-04-06 18:21:50','2026-04-06 18:21:50'),(120,46,78,'Adidas Ultraboost','PROD078',10000.00,5,50000.00,'2026-04-06 18:21:50','2026-04-06 18:21:50'),(121,47,79,'Brooks Ghost 15','PROD079',1000.00,5,5000.00,'2026-04-15 23:21:50','2026-04-15 23:21:50'),(122,47,80,'Asics Gel-Kayano 30','PROD080',10000.00,3,30000.00,'2026-04-15 23:21:50','2026-04-15 23:21:50'),(123,47,81,'Moleskine Classic','PROD081',1000.00,1,1000.00,'2026-04-15 23:21:50','2026-04-15 23:21:50'),(124,48,81,'Moleskine Classic','PROD081',1000.00,3,3000.00,'2026-04-03 17:21:51','2026-04-03 17:21:51'),(125,48,82,'Rhodia Webnotebook','PROD082',1000.00,2,2000.00,'2026-04-03 17:21:51','2026-04-03 17:21:51'),(126,48,83,'Leuchtturm1917 Medium','PROD083',1000.00,5,5000.00,'2026-04-03 17:21:51','2026-04-03 17:21:51'),(127,49,83,'Leuchtturm1917 Medium','PROD083',1000.00,5,5000.00,'2026-04-11 13:21:51','2026-04-11 13:21:51'),(128,49,84,'Field Notes 3-Pack','PROD084',100.00,5,500.00,'2026-04-11 13:21:51','2026-04-11 13:21:51'),(129,49,85,'Pilot G2 Gel Pens','PROD085',100.00,4,400.00,'2026-04-11 13:21:51','2026-04-11 13:21:51'),(130,50,85,'Pilot G2 Gel Pens','PROD085',100.00,1,100.00,'2026-04-20 19:21:51','2026-04-20 19:21:51'),(131,50,86,'Uni-ball Onyx Pens','PROD086',100.00,5,500.00,'2026-04-20 19:21:51','2026-04-20 19:21:51'),(132,50,87,'Parker Jotter Classic','PROD087',100.00,2,200.00,'2026-04-20 19:21:51','2026-04-20 19:21:51'),(133,50,88,'Sharpie S-Gel Pens','PROD088',100.00,5,500.00,'2026-04-20 19:21:51','2026-04-20 19:21:51'),(134,51,87,'Parker Jotter Classic','PROD087',100.00,2,200.00,'2026-04-21 12:21:52','2026-04-21 12:21:52'),(135,51,88,'Sharpie S-Gel Pens','PROD088',100.00,2,200.00,'2026-04-21 12:21:52','2026-04-21 12:21:52'),(136,51,89,'Mesh Desk Organizer','PROD089',100.00,5,500.00,'2026-04-21 12:21:52','2026-04-21 12:21:52'),(137,52,89,'Mesh Desk Organizer','PROD089',100.00,2,200.00,'2026-04-07 19:21:52','2026-04-07 19:21:52'),(138,52,90,'Wooden Desk Caddy','PROD090',1000.00,5,5000.00,'2026-04-07 19:21:52','2026-04-07 19:21:52'),(139,52,91,'Leather Desk Pad','PROD091',1000.00,1,1000.00,'2026-04-07 19:21:52','2026-04-07 19:21:52'),(140,53,91,'Leather Desk Pad','PROD091',1000.00,2,2000.00,'2026-04-18 12:21:52','2026-04-18 12:21:52'),(141,53,92,'Monitor Stand Riser','PROD092',1000.00,1,1000.00,'2026-04-18 12:21:52','2026-04-18 12:21:52'),(142,54,93,'Swingline 747 Classic','PROD093',1000.00,4,4000.00,'2026-04-06 23:21:53','2026-04-06 23:21:53'),(143,54,94,'Bostitch Office Staple','PROD094',100.00,1,100.00,'2026-04-06 23:21:53','2026-04-06 23:21:53'),(144,54,95,'PaperPro InSpire','PROD095',100.00,5,500.00,'2026-04-06 23:21:53','2026-04-06 23:21:53'),(145,55,95,'PaperPro InSpire','PROD095',100.00,3,300.00,'2026-04-03 23:21:53','2026-04-03 23:21:53'),(146,55,96,'Rapid Supreme Press','PROD096',1000.00,3,3000.00,'2026-04-03 23:21:53','2026-04-03 23:21:53'),(147,56,1,'iPhone 15 Pro','PROD001',10000.00,4,40000.00,'2026-05-03 21:21:53','2026-05-03 21:21:53'),(148,56,2,'Samsung Galaxy S24','PROD002',10000.00,3,30000.00,'2026-05-03 21:21:53','2026-05-03 21:21:53'),(149,56,3,'Google Pixel 8','PROD003',10000.00,2,20000.00,'2026-05-03 21:21:53','2026-05-03 21:21:53'),(150,56,4,'OnePlus 12','PROD004',10000.00,3,30000.00,'2026-05-03 21:21:53','2026-05-03 21:21:53'),(151,57,3,'Google Pixel 8','PROD003',10000.00,4,40000.00,'2026-04-27 17:21:54','2026-04-27 17:21:54'),(152,57,4,'OnePlus 12','PROD004',10000.00,5,50000.00,'2026-04-27 17:21:54','2026-04-27 17:21:54'),(153,57,5,'MacBook Air M3','PROD005',10000.00,1,10000.00,'2026-04-27 17:21:54','2026-04-27 17:21:54'),(154,58,5,'MacBook Air M3','PROD005',10000.00,1,10000.00,'2026-05-06 12:21:54','2026-05-06 12:21:54'),(155,58,6,'Dell XPS 13','PROD006',10000.00,1,10000.00,'2026-05-06 12:21:54','2026-05-06 12:21:54'),(156,58,7,'HP Spectre x360','PROD007',10000.00,5,50000.00,'2026-05-06 12:21:54','2026-05-06 12:21:54'),(157,58,8,'Lenovo ThinkPad X1','PROD008',10000.00,3,30000.00,'2026-05-06 12:21:54','2026-05-06 12:21:54'),(158,59,7,'HP Spectre x360','PROD007',10000.00,1,10000.00,'2026-05-16 20:21:55','2026-05-16 20:21:55'),(159,59,8,'Lenovo ThinkPad X1','PROD008',10000.00,3,30000.00,'2026-05-16 20:21:55','2026-05-16 20:21:55'),(160,59,9,'Sony WH-1000XM5','PROD009',10000.00,3,30000.00,'2026-05-16 20:21:55','2026-05-16 20:21:55'),(161,59,10,'Bose QuietComfort Ultra','PROD010',10000.00,2,20000.00,'2026-05-16 20:21:55','2026-05-16 20:21:55'),(162,60,9,'Sony WH-1000XM5','PROD009',10000.00,2,20000.00,'2026-05-21 12:21:55','2026-05-21 12:21:55'),(163,60,10,'Bose QuietComfort Ultra','PROD010',10000.00,1,10000.00,'2026-05-21 12:21:55','2026-05-21 12:21:55'),(164,60,11,'Apple AirPods Pro','PROD011',10000.00,5,50000.00,'2026-05-21 12:21:55','2026-05-21 12:21:55'),(165,60,12,'Sennheiser Momentum 4','PROD012',10000.00,1,10000.00,'2026-05-21 12:21:55','2026-05-21 12:21:55'),(166,61,11,'Apple AirPods Pro','PROD011',10000.00,3,30000.00,'2026-05-24 12:21:56','2026-05-24 12:21:56'),(167,61,12,'Sennheiser Momentum 4','PROD012',10000.00,1,10000.00,'2026-05-24 12:21:56','2026-05-24 12:21:56'),(168,61,13,'Anker PowerCore 24K','PROD013',1000.00,4,4000.00,'2026-05-24 12:21:56','2026-05-24 12:21:56'),(169,62,13,'Anker PowerCore 24K','PROD013',1000.00,4,4000.00,'2026-05-12 12:21:56','2026-05-12 12:21:56'),(170,62,14,'Logitech MX Master 3S','PROD014',1000.00,5,5000.00,'2026-05-12 12:21:56','2026-05-12 12:21:56'),(171,62,15,'Apple Magic Keyboard','PROD015',1000.00,4,4000.00,'2026-05-12 12:21:56','2026-05-12 12:21:56'),(172,63,15,'Apple Magic Keyboard','PROD015',1000.00,1,1000.00,'2026-05-10 16:21:57','2026-05-10 16:21:57'),(173,63,16,'Samsung T7 Shield SSD','PROD016',1000.00,4,4000.00,'2026-05-10 16:21:57','2026-05-10 16:21:57'),(174,63,17,'Amazon Echo Dot','PROD017',1000.00,4,4000.00,'2026-05-10 16:21:57','2026-05-10 16:21:57'),(175,64,17,'Amazon Echo Dot','PROD017',1000.00,1,1000.00,'2026-05-02 21:21:57','2026-05-02 21:21:57'),(176,64,18,'Google Nest Hub','PROD018',1000.00,5,5000.00,'2026-05-02 21:21:57','2026-05-02 21:21:57'),(177,64,19,'Philips Hue Starter Kit','PROD019',1000.00,3,3000.00,'2026-05-02 21:21:57','2026-05-02 21:21:57'),(178,64,20,'Ring Video Doorbell','PROD020',10000.00,4,40000.00,'2026-05-02 21:21:57','2026-05-02 21:21:57'),(179,65,19,'Philips Hue Starter Kit','PROD019',1000.00,1,1000.00,'2026-05-07 22:21:58','2026-05-07 22:21:58'),(180,65,20,'Ring Video Doorbell','PROD020',10000.00,3,30000.00,'2026-05-07 22:21:58','2026-05-07 22:21:58'),(181,66,21,'Nike Dri-FIT Tee','PROD021',1000.00,3,3000.00,'2026-04-29 20:21:58','2026-04-29 20:21:58'),(182,66,22,'Adidas Originals Tee','PROD022',1000.00,1,1000.00,'2026-04-29 20:21:58','2026-04-29 20:21:58'),(183,67,23,'Puma Classic Tee','PROD023',1000.00,5,5000.00,'2026-05-03 16:21:59','2026-05-03 16:21:59'),(184,67,24,'Under Armour Sport Tee','PROD024',1000.00,3,3000.00,'2026-05-03 16:21:59','2026-05-03 16:21:59'),(185,68,25,'Levis 501 Original','PROD025',1000.00,4,4000.00,'2026-05-05 15:21:59','2026-05-05 15:21:59'),(186,68,26,'Wrangler Authentic Jeans','PROD026',1000.00,5,5000.00,'2026-05-05 15:21:59','2026-05-05 15:21:59'),(187,68,27,'Lee Rider Slim Jeans','PROD027',1000.00,3,3000.00,'2026-05-05 15:21:59','2026-05-05 15:21:59'),(188,69,27,'Lee Rider Slim Jeans','PROD027',1000.00,2,2000.00,'2026-05-14 21:22:00','2026-05-14 21:22:00'),(189,69,28,'Diesel D-Strukt Jeans','PROD028',1000.00,3,3000.00,'2026-05-14 21:22:00','2026-05-14 21:22:00'),(190,69,29,'Patagonia Torrentshell','PROD029',1000.00,5,5000.00,'2026-05-14 21:22:00','2026-05-14 21:22:00'),(191,70,29,'Patagonia Torrentshell','PROD029',1000.00,3,3000.00,'2026-05-19 19:22:00','2026-05-19 19:22:00'),(192,70,30,'The North Face Resolve','PROD030',1000.00,1,1000.00,'2026-05-19 19:22:00','2026-05-19 19:22:00'),(193,70,31,'Columbia Watertight','PROD031',1000.00,3,3000.00,'2026-05-19 19:22:00','2026-05-19 19:22:00'),(194,71,31,'Columbia Watertight','PROD031',1000.00,4,4000.00,'2026-05-21 19:22:01','2026-05-21 19:22:01'),(195,71,32,'Arc`teryx Beta LT','PROD032',10000.00,5,50000.00,'2026-05-21 19:22:01','2026-05-21 19:22:01'),(196,71,33,'Darn Tough Cushion','PROD033',1000.00,4,4000.00,'2026-05-21 19:22:01','2026-05-21 19:22:01'),(197,71,34,'Smartwool Run Socks','PROD034',100.00,1,100.00,'2026-05-21 19:22:01','2026-05-21 19:22:01'),(198,72,33,'Darn Tough Cushion','PROD033',1000.00,3,3000.00,'2026-06-05 12:22:01','2026-06-05 12:22:01'),(199,72,34,'Smartwool Run Socks','PROD034',100.00,2,200.00,'2026-06-05 12:22:01','2026-06-05 12:22:01'),(200,72,35,'Nike Everyday Cushion','PROD035',100.00,2,200.00,'2026-06-05 12:22:01','2026-06-05 12:22:01'),(201,72,36,'Adidas Athletic Socks','PROD036',100.00,1,100.00,'2026-06-05 12:22:01','2026-06-05 12:22:01'),(202,73,35,'Nike Everyday Cushion','PROD035',100.00,5,500.00,'2026-06-10 17:22:01','2026-06-10 17:22:01'),(203,73,36,'Adidas Athletic Socks','PROD036',100.00,3,300.00,'2026-06-10 17:22:01','2026-06-10 17:22:01'),(204,73,37,'New Era 59FIFTY Cap','PROD037',1000.00,1,1000.00,'2026-06-10 17:22:01','2026-06-10 17:22:01'),(205,74,37,'New Era 59FIFTY Cap','PROD037',1000.00,2,2000.00,'2026-06-09 17:22:02','2026-06-09 17:22:02'),(206,74,38,'Nike Legacy91 Cap','PROD038',1000.00,2,2000.00,'2026-06-09 17:22:02','2026-06-09 17:22:02'),(207,74,39,'Adidas Superlite Cap','PROD039',1000.00,3,3000.00,'2026-06-09 17:22:02','2026-06-09 17:22:02'),(208,74,40,'Carhartt Beanie','PROD040',1000.00,3,3000.00,'2026-06-09 17:22:02','2026-06-09 17:22:02'),(209,75,39,'Adidas Superlite Cap','PROD039',1000.00,2,2000.00,'2026-06-13 22:22:02','2026-06-13 22:22:02'),(210,75,40,'Carhartt Beanie','PROD040',1000.00,4,4000.00,'2026-06-13 22:22:02','2026-06-13 22:22:02'),(211,76,41,'Keurig K-Elite','PROD041',10000.00,2,20000.00,'2026-06-15 15:22:03','2026-06-15 15:22:03'),(212,76,42,'Nespresso Vertuo Next','PROD042',10000.00,5,50000.00,'2026-06-15 15:22:03','2026-06-15 15:22:03'),(213,77,43,'Breville Barista','PROD043',10000.00,1,10000.00,'2026-06-22 21:22:03','2026-06-22 21:22:03'),(214,77,44,'Hamilton FlexBrew','PROD044',1000.00,2,2000.00,'2026-06-22 21:22:03','2026-06-22 21:22:03'),(215,77,45,'Toshiba EM131A5C','PROD045',1000.00,1,1000.00,'2026-06-22 21:22:03','2026-06-22 21:22:03'),(216,78,45,'Toshiba EM131A5C','PROD045',1000.00,3,3000.00,'2026-06-01 21:22:04','2026-06-01 21:22:04'),(217,78,46,'Panasonic NN-SN686S','PROD046',10000.00,3,30000.00,'2026-06-01 21:22:04','2026-06-01 21:22:04'),(218,78,47,'Black+Decker EM720','PROD047',1000.00,4,4000.00,'2026-06-01 21:22:04','2026-06-01 21:22:04'),(219,79,47,'Black+Decker EM720','PROD047',1000.00,2,2000.00,'2026-06-05 16:22:04','2026-06-05 16:22:04'),(220,79,48,'Samsung MS19M','PROD048',10000.00,2,20000.00,'2026-06-05 16:22:04','2026-06-05 16:22:04'),(221,79,49,'Vitamix 5200','PROD049',10000.00,2,20000.00,'2026-06-05 16:22:04','2026-06-05 16:22:04'),(222,80,49,'Vitamix 5200','PROD049',10000.00,1,10000.00,'2026-06-14 17:22:05','2026-06-14 17:22:05'),(223,80,50,'Ninja Professional','PROD050',10000.00,2,20000.00,'2026-06-14 17:22:05','2026-06-14 17:22:05'),(224,80,51,'NutriBullet Pro','PROD051',1000.00,2,2000.00,'2026-06-14 17:22:05','2026-06-14 17:22:05'),(225,81,51,'NutriBullet Pro','PROD051',1000.00,3,3000.00,'2026-06-08 12:22:05','2026-06-08 12:22:05'),(226,81,52,'Magic Bullet Blender','PROD052',1000.00,4,4000.00,'2026-06-08 12:22:05','2026-06-08 12:22:05'),(227,81,53,'Breville Bit More','PROD053',1000.00,1,1000.00,'2026-06-08 12:22:05','2026-06-08 12:22:05'),(228,81,54,'Cuisinart CPT-180','PROD054',1000.00,2,2000.00,'2026-06-08 12:22:05','2026-06-08 12:22:05'),(229,82,53,'Breville Bit More','PROD053',1000.00,1,1000.00,'2026-06-03 20:22:06','2026-06-03 20:22:06'),(230,82,54,'Cuisinart CPT-180','PROD054',1000.00,3,3000.00,'2026-06-03 20:22:06','2026-06-03 20:22:06'),(231,82,55,'KitchenAid 2-Slice','PROD055',1000.00,1,1000.00,'2026-06-03 20:22:06','2026-06-03 20:22:06'),(232,83,55,'KitchenAid 2-Slice','PROD055',1000.00,3,3000.00,'2026-06-15 16:22:06','2026-06-15 16:22:06'),(233,83,56,'Oster 4-Slice Toaster','PROD056',1000.00,2,2000.00,'2026-06-15 16:22:06','2026-06-15 16:22:06'),(234,84,57,'Cosori Pro II','PROD057',1000.00,1,1000.00,'2026-06-02 17:22:06','2026-06-02 17:22:06'),(235,84,58,'Ninja AF101','PROD058',10000.00,5,50000.00,'2026-06-02 17:22:06','2026-06-02 17:22:06'),(236,85,59,'Instant Vortex Plus','PROD059',10000.00,1,10000.00,'2026-06-11 18:22:06','2026-06-11 18:22:06'),(237,85,60,'Philips Essential Air','PROD060',1000.00,5,5000.00,'2026-06-11 18:22:06','2026-06-11 18:22:06'),(238,85,61,'Wilson NFL Duke','PROD061',1000.00,2,2000.00,'2026-06-11 18:22:06','2026-06-11 18:22:06'),(239,85,62,'Adidas Al Rihla','PROD062',1000.00,1,1000.00,'2026-06-11 18:22:06','2026-06-11 18:22:06'),(240,86,61,'Wilson NFL Duke','PROD061',1000.00,1,1000.00,'2026-06-14 21:22:07','2026-06-14 21:22:07'),(241,86,62,'Adidas Al Rihla','PROD062',1000.00,2,2000.00,'2026-06-14 21:22:07','2026-06-14 21:22:07'),(242,86,63,'Nike Strike Ball','PROD063',1000.00,1,1000.00,'2026-06-14 21:22:07','2026-06-14 21:22:07'),(243,87,63,'Nike Strike Ball','PROD063',1000.00,5,5000.00,'2026-06-03 12:22:07','2026-06-03 12:22:07'),(244,87,64,'Spalding TF-1000 Ball','PROD064',1000.00,3,3000.00,'2026-06-03 12:22:07','2026-06-03 12:22:07'),(245,87,65,'Bowflex SelectTech','PROD065',10000.00,4,40000.00,'2026-06-03 12:22:07','2026-06-03 12:22:07');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `invoice_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `net_total` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card') NOT NULL,
  `credit_note_code` varchar(255) DEFAULT NULL,
  `credit_amount_used` decimal(12,2) DEFAULT NULL,
  `cash_given` decimal(10,2) DEFAULT NULL,
  `balance_returned` decimal(10,2) DEFAULT NULL,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`invoice_id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_branch_id_foreign` (`branch_id`),
  CONSTRAINT `invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (4,'INV-2026-0001',2,160000.00,'cash',NULL,NULL,160000.00,0.00,'paid','2026-02-22 13:21:32','2026-06-26 00:21:33'),(6,'INV-2026-0002',3,90000.00,'cash',NULL,NULL,90000.00,0.00,'paid','2026-02-06 20:21:33','2026-06-26 00:21:33'),(7,'INV-2026-0003',1,90000.00,'card',NULL,NULL,90000.00,0.00,'paid','2026-02-12 14:21:33','2026-06-26 00:21:34'),(9,'INV-2026-0004',2,70000.00,'cash',NULL,NULL,70000.00,0.00,'paid','2026-01-28 12:21:34','2026-06-26 00:21:34'),(12,'INV-2026-0005',3,140000.00,'cash',NULL,NULL,140000.00,0.00,'paid','2026-01-31 13:21:34','2026-06-26 00:21:34'),(13,'INV-2026-0006',1,63000.00,'card',NULL,NULL,63000.00,0.00,'paid','2026-02-08 19:21:34','2026-06-26 00:21:35'),(14,'INV-2026-0007',2,11000.00,'cash',NULL,NULL,11000.00,0.00,'paid','2026-02-21 15:21:35','2026-06-26 00:21:36'),(15,'INV-2026-0008',3,6000.00,'cash',NULL,NULL,6000.00,0.00,'paid','2026-01-31 12:21:36','2026-06-26 00:21:37'),(16,'INV-2026-0009',1,9000.00,'card',NULL,NULL,9000.00,0.00,'paid','2026-02-13 16:21:37','2026-06-26 00:21:37'),(17,'INV-2026-0010',2,51000.00,'cash',NULL,NULL,51000.00,0.00,'paid','2026-02-05 14:21:37','2026-06-26 00:21:38'),(18,'INV-2026-0011',3,11000.00,'cash',NULL,NULL,11000.00,0.00,'paid','2026-02-05 23:21:39','2026-06-26 00:21:39'),(19,'INV-2026-0012',1,10000.00,'card',NULL,NULL,10000.00,0.00,'paid','2026-02-02 21:21:39','2026-06-26 00:21:40'),(20,'INV-2026-0013',2,13000.00,'cash',NULL,NULL,13000.00,0.00,'paid','2026-02-17 19:21:40','2026-06-26 00:21:40'),(21,'INV-2026-0014',3,7000.00,'cash',NULL,NULL,7000.00,0.00,'paid','2026-01-30 14:21:40','2026-06-26 00:21:41'),(22,'INV-2026-0015',1,10000.00,'card',NULL,NULL,10000.00,0.00,'paid','2026-01-31 18:21:41','2026-06-26 00:21:41'),(23,'INV-2026-0016',2,38300.00,'cash',NULL,NULL,38300.00,0.00,'paid','2026-02-05 22:21:41','2026-06-26 00:21:41'),(24,'INV-2026-0017',3,1500.00,'cash',NULL,NULL,1500.00,0.00,'paid','2026-03-19 13:21:41','2026-06-26 00:21:41'),(25,'INV-2026-0018',1,4600.00,'card',NULL,NULL,4600.00,0.00,'paid','2026-03-06 23:21:41','2026-06-26 00:21:42'),(26,'INV-2026-0019',2,12000.00,'cash',NULL,NULL,12000.00,0.00,'paid','2026-03-22 12:21:42','2026-06-26 00:21:42'),(27,'INV-2026-0020',3,10000.00,'cash',NULL,NULL,10000.00,0.00,'paid','2026-02-26 12:21:42','2026-06-26 00:21:42'),(28,'INV-2026-0021',1,60000.00,'card',NULL,NULL,60000.00,0.00,'paid','2026-03-19 14:21:43','2026-06-26 00:21:44'),(29,'INV-2026-0022',2,63000.00,'cash',NULL,NULL,63000.00,0.00,'paid','2026-03-16 14:21:44','2026-06-26 00:21:44'),(30,'INV-2026-0023',3,44000.00,'cash',NULL,NULL,44000.00,0.00,'paid','2026-03-07 23:21:44','2026-06-26 00:21:44'),(31,'INV-2026-0024',1,105000.00,'card',NULL,NULL,105000.00,0.00,'paid','2026-02-25 15:21:44','2026-06-26 00:21:45'),(32,'INV-2026-0025',2,52000.00,'cash',NULL,NULL,52000.00,0.00,'paid','2026-02-25 16:21:45','2026-06-26 00:21:45'),(33,'INV-2026-0026',3,4000.00,'cash',NULL,NULL,4000.00,0.00,'paid','2026-03-24 17:21:45','2026-06-26 00:21:45'),(34,'INV-2026-0027',1,5000.00,'card',NULL,NULL,5000.00,0.00,'paid','2026-03-18 22:21:45','2026-06-26 00:21:46'),(35,'INV-2026-0028',2,12000.00,'cash',NULL,NULL,12000.00,0.00,'paid','2026-03-06 18:21:46','2026-06-26 00:21:46'),(36,'INV-2026-0029',3,91000.00,'cash',NULL,NULL,91000.00,0.00,'paid','2026-03-21 22:21:46','2026-06-26 00:21:46'),(37,'INV-2026-0030',1,39000.00,'card',NULL,NULL,39000.00,0.00,'paid','2026-03-16 20:21:46','2026-06-26 00:21:47'),(38,'INV-2026-0031',2,10000.00,'cash',NULL,NULL,10000.00,0.00,'paid','2026-03-16 21:21:47','2026-06-26 00:21:47'),(39,'INV-2026-0032',3,50000.00,'cash',NULL,NULL,50000.00,0.00,'paid','2026-03-03 18:21:47','2026-06-26 00:21:48'),(40,'INV-2026-0033',1,51000.00,'card',NULL,NULL,51000.00,0.00,'paid','2026-04-17 14:21:48','2026-06-26 00:21:48'),(41,'INV-2026-0034',2,13000.00,'cash',NULL,NULL,13000.00,0.00,'paid','2026-04-10 12:21:48','2026-06-26 00:21:48'),(42,'INV-2026-0035',3,10000.00,'cash',NULL,NULL,10000.00,0.00,'paid','2026-04-16 23:21:48','2026-06-26 00:21:49'),(43,'INV-2026-0036',1,8000.00,'card',NULL,NULL,8000.00,0.00,'paid','2026-04-05 22:21:49','2026-06-26 00:21:49'),(44,'INV-2026-0037',2,16000.00,'cash',NULL,NULL,16000.00,0.00,'paid','2026-04-01 18:21:49','2026-06-26 00:21:49'),(45,'INV-2026-0038',3,7000.00,'cash',NULL,NULL,7000.00,0.00,'paid','2026-04-03 13:21:49','2026-06-26 00:21:50'),(46,'INV-2026-0039',1,52000.00,'card',NULL,NULL,52000.00,0.00,'paid','2026-04-06 18:21:50','2026-06-26 00:21:50'),(47,'INV-2026-0040',2,36000.00,'cash',NULL,NULL,36000.00,0.00,'paid','2026-04-15 23:21:50','2026-06-26 00:21:51'),(48,'INV-2026-0041',3,10000.00,'cash',NULL,NULL,10000.00,0.00,'paid','2026-04-03 17:21:51','2026-06-26 00:21:51'),(49,'INV-2026-0042',1,5900.00,'card',NULL,NULL,5900.00,0.00,'paid','2026-04-11 13:21:51','2026-06-26 00:21:51'),(50,'INV-2026-0043',2,1300.00,'cash',NULL,NULL,1300.00,0.00,'paid','2026-04-20 19:21:51','2026-06-26 00:21:52'),(51,'INV-2026-0044',3,900.00,'cash',NULL,NULL,900.00,0.00,'paid','2026-04-21 12:21:52','2026-06-26 00:21:52'),(52,'INV-2026-0045',1,6200.00,'card',NULL,NULL,6200.00,0.00,'paid','2026-04-07 19:21:52','2026-06-26 00:21:52'),(53,'INV-2026-0046',2,3000.00,'cash',NULL,NULL,3000.00,0.00,'paid','2026-04-18 12:21:52','2026-06-26 00:21:53'),(54,'INV-2026-0047',3,4600.00,'cash',NULL,NULL,4600.00,0.00,'paid','2026-04-06 23:21:53','2026-06-26 00:21:53'),(55,'INV-2026-0048',1,3300.00,'card',NULL,NULL,3300.00,0.00,'paid','2026-04-03 23:21:53','2026-06-26 00:21:53'),(56,'INV-2026-0049',2,120000.00,'cash',NULL,NULL,120000.00,0.00,'paid','2026-05-03 21:21:53','2026-06-26 00:21:54'),(57,'INV-2026-0050',3,100000.00,'cash',NULL,NULL,100000.00,0.00,'paid','2026-04-27 17:21:54','2026-06-26 00:21:54'),(58,'INV-2026-0051',1,100000.00,'card',NULL,NULL,100000.00,0.00,'paid','2026-05-06 12:21:54','2026-06-26 00:21:54'),(59,'INV-2026-0052',2,90000.00,'cash',NULL,NULL,90000.00,0.00,'paid','2026-05-16 20:21:55','2026-06-26 00:21:55'),(60,'INV-2026-0053',3,90000.00,'cash',NULL,NULL,90000.00,0.00,'paid','2026-05-21 12:21:55','2026-06-26 00:21:55'),(61,'INV-2026-0054',1,44000.00,'card',NULL,NULL,44000.00,0.00,'paid','2026-05-24 12:21:56','2026-06-26 00:21:56'),(62,'INV-2026-0055',2,13000.00,'cash',NULL,NULL,13000.00,0.00,'paid','2026-05-12 12:21:56','2026-06-26 00:21:57'),(63,'INV-2026-0056',3,9000.00,'cash',NULL,NULL,9000.00,0.00,'paid','2026-05-10 16:21:57','2026-06-26 00:21:57'),(64,'INV-2026-0057',1,49000.00,'card',NULL,NULL,49000.00,0.00,'paid','2026-05-02 21:21:57','2026-06-26 00:21:58'),(65,'INV-2026-0058',2,31000.00,'cash',NULL,NULL,31000.00,0.00,'paid','2026-05-07 22:21:58','2026-06-26 00:21:58'),(66,'INV-2026-0059',3,4000.00,'cash',NULL,NULL,4000.00,0.00,'paid','2026-04-29 20:21:58','2026-06-26 00:21:59'),(67,'INV-2026-0060',1,8000.00,'card',NULL,NULL,8000.00,0.00,'paid','2026-05-03 16:21:59','2026-06-26 00:21:59'),(68,'INV-2026-0061',2,12000.00,'cash',NULL,NULL,12000.00,0.00,'paid','2026-05-05 15:21:59','2026-06-26 00:21:59'),(69,'INV-2026-0062',3,10000.00,'cash',NULL,NULL,10000.00,0.00,'paid','2026-05-14 21:22:00','2026-06-26 00:22:00'),(70,'INV-2026-0063',1,7000.00,'card',NULL,NULL,7000.00,0.00,'paid','2026-05-19 19:22:00','2026-06-26 00:22:01'),(71,'INV-2026-0064',2,58100.00,'cash',NULL,NULL,58100.00,0.00,'paid','2026-05-21 19:22:01','2026-06-26 00:22:01'),(72,'INV-2026-0065',3,3500.00,'cash',NULL,NULL,3500.00,0.00,'paid','2026-06-05 12:22:01','2026-06-26 00:22:01'),(73,'INV-2026-0066',1,1800.00,'card',NULL,NULL,1800.00,0.00,'paid','2026-06-10 17:22:01','2026-06-26 00:22:02'),(74,'INV-2026-0067',2,10000.00,'cash',NULL,NULL,10000.00,0.00,'paid','2026-06-09 17:22:02','2026-06-26 00:22:02'),(75,'INV-2026-0068',3,6000.00,'cash',NULL,NULL,6000.00,0.00,'paid','2026-06-13 22:22:02','2026-06-26 00:22:03'),(76,'INV-2026-0069',1,70000.00,'card',NULL,NULL,70000.00,0.00,'paid','2026-06-15 15:22:03','2026-06-26 00:22:03'),(77,'INV-2026-0070',2,13000.00,'cash',NULL,NULL,13000.00,0.00,'paid','2026-06-22 21:22:03','2026-06-26 00:22:03'),(78,'INV-2026-0071',3,37000.00,'cash',NULL,NULL,37000.00,0.00,'paid','2026-06-01 21:22:04','2026-06-26 00:22:04'),(79,'INV-2026-0072',1,42000.00,'card',NULL,NULL,42000.00,0.00,'paid','2026-06-05 16:22:04','2026-06-26 00:22:04'),(80,'INV-2026-0073',2,32000.00,'cash',NULL,NULL,32000.00,0.00,'paid','2026-06-14 17:22:05','2026-06-26 00:22:05'),(81,'INV-2026-0074',3,10000.00,'cash',NULL,NULL,10000.00,0.00,'paid','2026-06-08 12:22:05','2026-06-26 00:22:06'),(82,'INV-2026-0075',1,5000.00,'card',NULL,NULL,5000.00,0.00,'paid','2026-06-03 20:22:06','2026-06-26 00:22:06'),(83,'INV-2026-0076',2,5000.00,'cash',NULL,NULL,5000.00,0.00,'paid','2026-06-15 16:22:06','2026-06-26 00:22:06'),(84,'INV-2026-0077',3,51000.00,'cash',NULL,NULL,51000.00,0.00,'paid','2026-06-02 17:22:06','2026-06-26 00:22:06'),(85,'INV-2026-0078',1,18000.00,'card',NULL,NULL,18000.00,0.00,'paid','2026-06-11 18:22:06','2026-06-26 00:22:07'),(86,'INV-2026-0079',2,4000.00,'cash',NULL,NULL,4000.00,0.00,'paid','2026-06-14 21:22:07','2026-06-26 00:22:07'),(87,'INV-2026-0080',3,48000.00,'cash',NULL,NULL,48000.00,0.00,'paid','2026-06-03 12:22:07','2026-06-26 00:22:08');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (95,'2019_12_14_000001_create_personal_access_tokens_table',1),(96,'2025_01_01_063951_2024_01_01_000003_create_brands_table',1),(97,'2025_12_15_063950_2024_01_01_000001_create_users_table',1),(98,'2025_12_15_063951_2024_01_01_000002_create_inv_paras_table',1),(99,'2025_12_15_063951_2024_01_01_000004_create_categories_table',1),(100,'2025_12_15_063951_2024_01_01_000005_create_sub_categories_table',1),(101,'2025_12_15_063952_2024_01_01_000006_create_customers_table',1),(102,'2025_12_15_063952_2024_01_01_000007_create_suppliers_table',1),(103,'2025_12_15_063952_2024_01_01_000008_create_branches_table',1),(104,'2025_12_15_063953_2024_01_01_000009_create_employees_table',1),(105,'2025_12_15_063953_2024_01_01_000010_create_departments_table',1),(106,'2025_12_15_063953_2024_01_01_000011_create_products_table',1),(107,'2025_12_15_063953_2024_01_01_000012_create_stocks_table',1),(108,'2025_12_15_063954_2024_01_01_000013_create_stock_transactions_table',1),(109,'2025_12_15_063954_2024_01_01_000014_create_orders_table',1),(110,'2025_12_15_063954_2024_01_01_000015_create_password_reset_tokens_table',1),(111,'2025_12_15_063954_2024_01_01_000016_create_failed_jobs_table',1),(112,'2025_12_15_063955_2024_01_01_000017_add_manager_id_foreign_to_branches_table',1),(113,'2025_12_15_063955_2024_01_01_000018_add_foreign_keys_to_employees_table',1),(114,'2025_12_15_063955_2024_01_01_000019_add_manager_id_foreign_to_departments_table',1),(115,'2026_02_04_212341_change_supplier_id_to_string_primary_key',1),(116,'2026_02_17_043956_add_category_code_to_categories_table',1),(117,'2026_02_17_115851_add_brand_code_to_brands',1),(118,'2026_02_18_063509_add_employee_code_to_employees',1),(119,'2026_02_20_043655_add_employe_coloums_to_employees',1),(120,'2026_02_20_083011_add_subcategory_code_to_sub_categories_table',1),(121,'2026_04_01_171000_add_user_id_to_products_table',1),(122,'2026_04_02_000001_add_branch_code_to_branches_table',1),(123,'2026_04_02_000001_add_department_code_to_departments_table',1),(124,'2026_04_02_000002_add_customer_code_to_customers_table',1),(125,'2026_04_27_063813_create_stores_table',1),(126,'2026_04_29_012239_create_banks_table',1),(127,'2026_04_30_015159_add_amount_to_banks_table',1),(128,'2026_05_04_000000_create_purchases_table',1),(129,'2026_05_05_161451_add_printing_settings_to_inv_paras',1),(130,'2026_05_06_000001_add_grn_print_size_to_inv_paras',1),(131,'2026_05_06_035609_create_invoices_table',1),(132,'2026_05_06_040045_create_invoice_items_table',1),(133,'2026_05_07_000000_add_dashboard_logo_size_to_inv_paras',1),(134,'2026_06_15_100719_create_sales_returns_table',1),(135,'2026_06_15_100721_create_sales_return_items_table',1),(136,'2026_06_15_204443_add_store_credit_fields',1),(137,'2026_06_16_104949_add_current_stock_qty_to_purchase_items_table',1),(138,'2026_06_19_193000_create_purchase_returns_table',1),(139,'2026_06_22_090000_convert_existing_grn_ids_to_sequential',1),(140,'2026_06_22_101134_add_branch_id_to_invoices_table',1),(141,'2026_06_23_103250_add_minimum_stock_level_to_products_table',1),(142,'2026_12_14_000001_create_personal_access_tokens_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `order_date` date DEFAULT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `total_amount` double(8,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_branch_id_foreign` (`branch_id`),
  KEY `orders_employee_id_foreign` (`employee_id`),
  CONSTRAINT `orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  CONSTRAINT `orders_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-2026-0001','2026-06-07',1,1,1,3942.00,'Completed','Paid','No. 1, Galle Road, Colombo','Sample order 1 notes','2026-06-06 21:21:28','2026-06-06 21:21:28'),(2,'ORD-2026-0002','2026-06-08',2,2,2,11192.00,'Completed','Paid','No. 2, Galle Road, Colombo','Sample order 2 notes','2026-06-07 22:21:29','2026-06-07 22:21:29'),(3,'ORD-2026-0003','2026-06-09',3,3,3,11841.00,'Completed','Paid','No. 3, Galle Road, Colombo','Sample order 3 notes','2026-06-08 23:21:29','2026-06-08 23:21:29'),(4,'ORD-2026-0004','2026-06-09',4,4,4,6390.00,'Completed','Paid','No. 4, Galle Road, Colombo','Sample order 4 notes','2026-06-09 16:21:29','2026-06-09 16:21:29'),(5,'ORD-2026-0005','2026-06-10',5,5,5,12703.00,'Completed','Paid','No. 5, Galle Road, Colombo','Sample order 5 notes','2026-06-10 13:21:29','2026-06-10 13:21:29'),(6,'ORD-2026-0006','2026-06-12',6,1,6,13676.00,'Pending','Unpaid','No. 6, Galle Road, Colombo','Sample order 6 notes','2026-06-11 21:21:29','2026-06-11 21:21:29'),(7,'ORD-2026-0007','2026-06-13',7,2,7,6081.00,'Cancelled','Paid','No. 7, Galle Road, Colombo','Sample order 7 notes','2026-06-12 19:21:30','2026-06-12 19:21:30'),(8,'ORD-2026-0008','2026-06-13',8,3,8,5552.00,'Completed','Paid','No. 8, Galle Road, Colombo','Sample order 8 notes','2026-06-13 16:21:30','2026-06-13 16:21:30'),(9,'ORD-2026-0009','2026-06-15',9,4,9,10684.00,'Completed','Paid','No. 9, Galle Road, Colombo','Sample order 9 notes','2026-06-14 21:21:30','2026-06-14 21:21:30'),(10,'ORD-2026-0010','2026-06-15',10,5,10,6343.00,'Completed','Paid','No. 10, Galle Road, Colombo','Sample order 10 notes','2026-06-15 15:21:30','2026-06-15 15:21:30'),(11,'ORD-2026-0011','2026-06-16',11,1,11,11601.00,'Completed','Paid','No. 11, Galle Road, Colombo','Sample order 11 notes','2026-06-16 15:21:30','2026-06-16 15:21:30'),(12,'ORD-2026-0012','2026-06-17',12,2,12,12674.00,'Pending','Unpaid','No. 12, Galle Road, Colombo','Sample order 12 notes','2026-06-17 14:21:30','2026-06-17 14:21:30'),(13,'ORD-2026-0013','2026-06-18',13,3,13,11756.00,'Completed','Paid','No. 13, Galle Road, Colombo','Sample order 13 notes','2026-06-18 17:21:30','2026-06-18 17:21:30'),(14,'ORD-2026-0014','2026-06-20',14,4,14,3303.00,'Cancelled','Paid','No. 14, Galle Road, Colombo','Sample order 14 notes','2026-06-19 21:21:30','2026-06-19 21:21:30'),(15,'ORD-2026-0015','2026-06-20',15,5,15,13269.00,'Completed','Paid','No. 15, Galle Road, Colombo','Sample order 15 notes','2026-06-20 18:21:31','2026-06-20 18:21:31'),(16,'ORD-2026-0016','2026-06-22',1,1,1,5407.00,'Completed','Paid','No. 1, Galle Road, Colombo','Sample order 16 notes','2026-06-21 23:21:31','2026-06-21 23:21:31'),(17,'ORD-2026-0017','2026-06-23',2,2,2,3269.00,'Completed','Paid','No. 2, Galle Road, Colombo','Sample order 17 notes','2026-06-22 20:21:31','2026-06-22 20:21:31'),(18,'ORD-2026-0018','2026-06-23',3,3,3,11508.00,'Pending','Unpaid','No. 3, Galle Road, Colombo','Sample order 18 notes','2026-06-23 14:21:31','2026-06-23 14:21:31'),(19,'ORD-2026-0019','2026-06-24',4,4,4,1722.00,'Completed','Paid','No. 4, Galle Road, Colombo','Sample order 19 notes','2026-06-24 18:21:31','2026-06-24 18:21:31'),(20,'ORD-2026-0020','2026-06-25',5,5,5,12442.00,'Completed','Paid','No. 5, Galle Road, Colombo','Sample order 20 notes','2026-06-25 12:21:31','2026-06-25 12:21:31'),(21,'ORD-2026-0021','2026-06-27',6,1,6,7205.00,'Cancelled','Paid','No. 6, Galle Road, Colombo','Sample order 21 notes','2026-06-26 21:21:31','2026-06-26 21:21:31'),(22,'ORD-2026-0022','2026-06-27',7,2,7,3692.00,'Completed','Paid','No. 7, Galle Road, Colombo','Sample order 22 notes','2026-06-27 17:21:31','2026-06-27 17:21:31'),(23,'ORD-2026-0023','2026-06-28',8,3,8,3445.00,'Completed','Paid','No. 8, Galle Road, Colombo','Sample order 23 notes','2026-06-28 13:21:31','2026-06-28 13:21:31'),(24,'ORD-2026-0024','2026-06-29',9,4,9,3314.00,'Pending','Unpaid','No. 9, Galle Road, Colombo','Sample order 24 notes','2026-06-29 15:21:31','2026-06-29 15:21:31'),(25,'ORD-2026-0025','2026-06-30',10,5,10,7922.00,'Completed','Paid','No. 10, Galle Road, Colombo','Sample order 25 notes','2026-06-30 14:21:32','2026-06-30 14:21:32');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',1,'auth_token','3fb2a94b72264860e9134bca42aef41896b6072cf8d75a47188d8f552e6335eb','[\"*\"]','2026-06-26 01:45:42',NULL,'2026-06-26 00:33:52','2026-06-26 01:45:42'),(2,'App\\Models\\User',1,'auth_token','f40d29d00d0e0db7d99c99d464700ff60ddbaa23a83545354d39fa726b8876d4','[\"*\"]','2026-06-26 01:46:27',NULL,'2026-06-26 01:46:16','2026-06-26 01:46:27');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_code` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `brand_id` bigint(20) unsigned NOT NULL,
  `subcategory_id` bigint(20) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `status` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `minimum_stock_level` int(11) NOT NULL DEFAULT 10,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `supplier_id` varchar(32) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `products_product_code_unique` (`product_code`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_subcategory_id_foreign` (`subcategory_id`),
  KEY `products_user_id_foreign` (`user_id`),
  KEY `products_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE,
  CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `sub_categories` (`subcategory_id`) ON DELETE CASCADE,
  CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE SET NULL,
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'PROD001','iPhone 15 Pro',2,1,10000.00,'pcs','active','Premium quality iPhone 15 Pro Smartphones from Apple.',10,1,'890100000001',7000.00,'6 kg','18x10x43 cm','SUP001','2026-06-26 00:20:31','2026-06-26 00:20:31'),(2,'PROD002','Samsung Galaxy S24',1,1,10000.00,'pcs','active','Premium quality Samsung Galaxy S24 Smartphones from Samsung.',10,1,'890100000002',7000.00,'6 kg','26x32x16 cm','SUP002','2026-06-26 00:20:32','2026-06-26 00:20:32'),(3,'PROD003','Google Pixel 8',11,1,10000.00,'pcs','active','Premium quality Google Pixel 8 Smartphones from Google.',10,1,'890100000003',7000.00,'2 kg','22x15x17 cm','SUP003','2026-06-26 00:20:33','2026-06-26 00:20:33'),(4,'PROD004','OnePlus 12',12,1,10000.00,'pcs','active','Premium quality OnePlus 12 Smartphones from OnePlus.',10,1,'890100000004',7000.00,'6 kg','27x45x16 cm','SUP004','2026-06-26 00:20:33','2026-06-26 00:20:33'),(5,'PROD005','MacBook Air M3',2,2,10000.00,'pcs','active','Premium quality MacBook Air M3 Laptops from Apple.',10,1,'890100000005',7000.00,'5 kg','8x45x50 cm','SUP005','2026-06-26 00:20:33','2026-06-26 00:20:33'),(6,'PROD006','Dell XPS 13',6,2,10000.00,'pcs','active','Premium quality Dell XPS 13 Laptops from Dell.',10,1,'890100000006',7000.00,'8 kg','17x7x24 cm','SUP006','2026-06-26 00:20:33','2026-06-26 00:20:33'),(7,'PROD007','HP Spectre x360',7,2,10000.00,'pcs','active','Premium quality HP Spectre x360 Laptops from HP.',10,1,'890100000007',7000.00,'5 kg','42x44x13 cm','SUP007','2026-06-26 00:20:33','2026-06-26 00:20:33'),(8,'PROD008','Lenovo ThinkPad X1',13,2,10000.00,'pcs','active','Premium quality Lenovo ThinkPad X1 Laptops from Lenovo.',10,1,'890100000008',7000.00,'7 kg','49x23x31 cm','SUP008','2026-06-26 00:20:33','2026-06-26 00:20:33'),(9,'PROD009','Sony WH-1000XM5',3,3,10000.00,'pcs','active','Premium quality Sony WH-1000XM5 Audio from Sony.',10,1,'890100000009',7000.00,'6 kg','37x24x28 cm','SUP009','2026-06-26 00:20:33','2026-06-26 00:20:33'),(10,'PROD010','Bose QuietComfort Ultra',14,3,10000.00,'box','active','Premium quality Bose QuietComfort Ultra Audio from Bose.',10,1,'890100000010',7000.00,'10 kg','29x31x33 cm','SUP010','2026-06-26 00:20:33','2026-06-26 00:20:33'),(11,'PROD011','Apple AirPods Pro',2,3,10000.00,'pcs','active','Premium quality Apple AirPods Pro Audio from Apple.',10,1,'890100000011',7000.00,'8 kg','46x22x10 cm','SUP001','2026-06-26 00:20:33','2026-06-26 00:20:33'),(12,'PROD012','Sennheiser Momentum 4',15,3,10000.00,'pcs','active','Premium quality Sennheiser Momentum 4 Audio from Sennheiser.',10,1,'890100000012',7000.00,'9 kg','41x30x5 cm','SUP002','2026-06-26 00:20:34','2026-06-26 00:20:34'),(13,'PROD013','Anker PowerCore 24K',16,4,1000.00,'pcs','active','Premium quality Anker PowerCore 24K Accessories from Anker.',10,1,'890100000013',700.00,'9 kg','6x19x49 cm','SUP003','2026-06-26 00:20:34','2026-06-26 00:20:34'),(14,'PROD014','Logitech MX Master 3S',8,4,1000.00,'pcs','active','Premium quality Logitech MX Master 3S Accessories from Logitech.',10,1,'890100000014',700.00,'7 kg','45x27x25 cm','SUP004','2026-06-26 00:20:34','2026-06-26 00:20:34'),(15,'PROD015','Apple Magic Keyboard',2,4,1000.00,'kg','active','Premium quality Apple Magic Keyboard Accessories from Apple.',10,1,'890100000015',700.00,'7 kg','16x18x16 cm','SUP005','2026-06-26 00:20:34','2026-06-26 00:20:34'),(16,'PROD016','Samsung T7 Shield SSD',1,4,1000.00,'pcs','active','Premium quality Samsung T7 Shield SSD Accessories from Samsung.',10,1,'890100000016',700.00,'5 kg','27x9x35 cm','SUP006','2026-06-26 00:20:34','2026-06-26 00:20:34'),(17,'PROD017','Amazon Echo Dot',17,5,1000.00,'pcs','active','Premium quality Amazon Echo Dot Smart Home from Amazon.',10,1,'890100000017',700.00,'7 kg','50x22x35 cm','SUP007','2026-06-26 00:20:34','2026-06-26 00:20:34'),(18,'PROD018','Google Nest Hub',11,5,1000.00,'pcs','active','Premium quality Google Nest Hub Smart Home from Google.',10,1,'890100000018',700.00,'10 kg','36x19x30 cm','SUP008','2026-06-26 00:20:34','2026-06-26 00:20:34'),(19,'PROD019','Philips Hue Starter Kit',18,5,1000.00,'pcs','active','Premium quality Philips Hue Starter Kit Smart Home from Philips.',10,1,'890100000019',700.00,'6 kg','31x47x42 cm','SUP009','2026-06-26 00:20:34','2026-06-26 00:20:34'),(20,'PROD020','Ring Video Doorbell',19,5,10000.00,'box','active','Premium quality Ring Video Doorbell Smart Home from Ring.',10,1,'890100000020',7000.00,'10 kg','34x24x38 cm','SUP010','2026-06-26 00:20:34','2026-06-26 00:20:34'),(21,'PROD021','Nike Dri-FIT Tee',4,6,1000.00,'pcs','active','Premium quality Nike Dri-FIT Tee T-Shirts from Nike.',10,1,'890100000021',700.00,'3 kg','12x15x40 cm','SUP001','2026-06-26 00:20:34','2026-06-26 00:20:34'),(22,'PROD022','Adidas Originals Tee',5,6,1000.00,'pcs','active','Premium quality Adidas Originals Tee T-Shirts from Adidas.',10,1,'890100000022',700.00,'7 kg','49x32x22 cm','SUP002','2026-06-26 00:20:34','2026-06-26 00:20:34'),(23,'PROD023','Puma Classic Tee',20,6,1000.00,'pcs','active','Premium quality Puma Classic Tee T-Shirts from Puma.',10,1,'890100000023',700.00,'8 kg','34x41x39 cm','SUP003','2026-06-26 00:20:35','2026-06-26 00:20:35'),(24,'PROD024','Under Armour Sport Tee',21,6,1000.00,'pcs','active','Premium quality Under Armour Sport Tee T-Shirts from Under Armour.',10,1,'890100000024',700.00,'9 kg','34x10x14 cm','SUP004','2026-06-26 00:20:35','2026-06-26 00:20:35'),(25,'PROD025','Levis 501 Original',22,7,1000.00,'pcs','active','Premium quality Levis 501 Original Jeans from Levi\'s.',10,1,'890100000025',700.00,'3 kg','20x36x31 cm','SUP005','2026-06-26 00:20:35','2026-06-26 00:20:35'),(26,'PROD026','Wrangler Authentic Jeans',23,7,1000.00,'pcs','active','Premium quality Wrangler Authentic Jeans Jeans from Wrangler.',10,1,'890100000026',700.00,'8 kg','35x28x13 cm','SUP006','2026-06-26 00:20:35','2026-06-26 00:20:35'),(27,'PROD027','Lee Rider Slim Jeans',24,7,1000.00,'pcs','active','Premium quality Lee Rider Slim Jeans Jeans from Lee.',10,1,'890100000027',700.00,'9 kg','21x9x42 cm','SUP007','2026-06-26 00:20:35','2026-06-26 00:20:35'),(28,'PROD028','Diesel D-Strukt Jeans',25,7,1000.00,'pcs','active','Premium quality Diesel D-Strukt Jeans Jeans from Diesel.',10,1,'890100000028',700.00,'6 kg','13x25x38 cm','SUP008','2026-06-26 00:20:35','2026-06-26 00:20:35'),(29,'PROD029','Patagonia Torrentshell',26,8,1000.00,'pcs','active','Premium quality Patagonia Torrentshell Jackets from Patagonia.',10,1,'890100000029',700.00,'8 kg','39x24x19 cm','SUP009','2026-06-26 00:20:36','2026-06-26 00:20:36'),(30,'PROD030','The North Face Resolve',27,8,1000.00,'box','active','Premium quality The North Face Resolve Jackets from The North Face.',10,1,'890100000030',700.00,'6 kg','50x9x23 cm','SUP010','2026-06-26 00:20:36','2026-06-26 00:20:36'),(31,'PROD031','Columbia Watertight',28,8,1000.00,'pcs','active','Premium quality Columbia Watertight Jackets from Columbia.',10,1,'890100000031',700.00,'2 kg','34x16x14 cm','SUP001','2026-06-26 00:20:36','2026-06-26 00:20:36'),(32,'PROD032','Arc`teryx Beta LT',29,8,10000.00,'pcs','active','Premium quality Arc`teryx Beta LT Jackets from Arc\'teryx.',10,1,'890100000032',7000.00,'2 kg','11x7x48 cm','SUP002','2026-06-26 00:20:36','2026-06-26 00:20:36'),(33,'PROD033','Darn Tough Cushion',30,9,1000.00,'pcs','active','Premium quality Darn Tough Cushion Socks from Darn Tough.',10,1,'890100000033',700.00,'6 kg','41x13x13 cm','SUP003','2026-06-26 00:20:36','2026-06-26 00:20:36'),(34,'PROD034','Smartwool Run Socks',31,9,100.00,'pcs','active','Premium quality Smartwool Run Socks Socks from Smartwool.',10,1,'890100000034',70.00,'9 kg','19x46x30 cm','SUP004','2026-06-26 00:20:36','2026-06-26 00:20:36'),(35,'PROD035','Nike Everyday Cushion',4,9,100.00,'pcs','active','Premium quality Nike Everyday Cushion Socks from Nike.',10,1,'890100000035',70.00,'5 kg','30x34x47 cm','SUP005','2026-06-26 00:20:37','2026-06-26 00:20:37'),(36,'PROD036','Adidas Athletic Socks',5,9,100.00,'pcs','active','Premium quality Adidas Athletic Socks Socks from Adidas.',10,1,'890100000036',70.00,'5 kg','24x14x25 cm','SUP006','2026-06-26 00:20:37','2026-06-26 00:20:37'),(37,'PROD037','New Era 59FIFTY Cap',32,10,1000.00,'pcs','active','Premium quality New Era 59FIFTY Cap Caps from New Era.',10,1,'890100000037',700.00,'3 kg','46x39x22 cm','SUP007','2026-06-26 00:20:37','2026-06-26 00:20:37'),(38,'PROD038','Nike Legacy91 Cap',4,10,1000.00,'pcs','active','Premium quality Nike Legacy91 Cap Caps from Nike.',10,1,'890100000038',700.00,'6 kg','24x28x31 cm','SUP008','2026-06-26 00:20:37','2026-06-26 00:20:37'),(39,'PROD039','Adidas Superlite Cap',5,10,1000.00,'pcs','active','Premium quality Adidas Superlite Cap Caps from Adidas.',10,1,'890100000039',700.00,'5 kg','28x13x26 cm','SUP009','2026-06-26 00:20:37','2026-06-26 00:20:37'),(40,'PROD040','Carhartt Beanie',33,10,1000.00,'box','active','Premium quality Carhartt Beanie Caps from Carhartt.',10,1,'890100000040',700.00,'4 kg','11x45x50 cm','SUP010','2026-06-26 00:20:37','2026-06-26 00:20:37'),(41,'PROD041','Keurig K-Elite',34,11,10000.00,'pcs','active','Premium quality Keurig K-Elite Coffee Makers from Keurig.',10,1,'890100000041',7000.00,'8 kg','24x37x17 cm','SUP001','2026-06-26 00:20:37','2026-06-26 00:20:37'),(42,'PROD042','Nespresso Vertuo Next',35,11,10000.00,'pcs','active','Premium quality Nespresso Vertuo Next Coffee Makers from Nespresso.',10,1,'890100000042',7000.00,'5 kg','48x34x26 cm','SUP002','2026-06-26 00:20:37','2026-06-26 00:20:37'),(43,'PROD043','Breville Barista',10,11,10000.00,'pcs','active','Premium quality Breville Barista Coffee Makers from Breville.',10,1,'890100000043',7000.00,'8 kg','10x33x39 cm','SUP003','2026-06-26 00:20:37','2026-06-26 00:20:37'),(44,'PROD044','Hamilton FlexBrew',36,11,1000.00,'pcs','active','Premium quality Hamilton FlexBrew Coffee Makers from Hamilton Beach.',10,1,'890100000044',700.00,'10 kg','24x21x45 cm','SUP004','2026-06-26 00:20:37','2026-06-26 00:20:37'),(45,'PROD045','Toshiba EM131A5C',37,12,1000.00,'kg','active','Premium quality Toshiba EM131A5C Microwaves from Toshiba.',10,1,'890100000045',700.00,'9 kg','43x36x19 cm','SUP005','2026-06-26 00:20:38','2026-06-26 00:20:38'),(46,'PROD046','Panasonic NN-SN686S',38,12,10000.00,'pcs','active','Premium quality Panasonic NN-SN686S Microwaves from Panasonic.',10,1,'890100000046',7000.00,'10 kg','10x15x11 cm','SUP006','2026-06-26 00:20:38','2026-06-26 00:20:38'),(47,'PROD047','Black+Decker EM720',39,12,1000.00,'pcs','active','Premium quality Black+Decker EM720 Microwaves from Black+Decker.',10,1,'890100000047',700.00,'1 kg','49x6x32 cm','SUP007','2026-06-26 00:20:38','2026-06-26 00:20:38'),(48,'PROD048','Samsung MS19M',1,12,10000.00,'pcs','active','Premium quality Samsung MS19M Microwaves from Samsung.',10,1,'890100000048',7000.00,'5 kg','25x24x34 cm','SUP008','2026-06-26 00:20:38','2026-06-26 00:20:38'),(49,'PROD049','Vitamix 5200',40,13,10000.00,'pcs','active','Premium quality Vitamix 5200 Blenders from Vitamix.',10,1,'890100000049',7000.00,'4 kg','44x22x14 cm','SUP009','2026-06-26 00:20:38','2026-06-26 00:20:38'),(50,'PROD050','Ninja Professional',9,13,10000.00,'box','active','Premium quality Ninja Professional Blenders from Ninja.',10,1,'890100000050',7000.00,'6 kg','6x11x19 cm','SUP010','2026-06-26 00:20:38','2026-06-26 00:20:38'),(51,'PROD051','NutriBullet Pro',41,13,1000.00,'pcs','active','Premium quality NutriBullet Pro Blenders from NutriBullet.',10,1,'890100000051',700.00,'3 kg','9x19x50 cm','SUP001','2026-06-26 00:20:38','2026-06-26 00:20:38'),(52,'PROD052','Magic Bullet Blender',42,13,1000.00,'pcs','active','Premium quality Magic Bullet Blender Blenders from Magic Bullet.',10,1,'890100000052',700.00,'9 kg','35x20x19 cm','SUP002','2026-06-26 00:20:38','2026-06-26 00:20:38'),(53,'PROD053','Breville Bit More',10,14,1000.00,'pcs','active','Premium quality Breville Bit More Toasters from Breville.',10,1,'890100000053',700.00,'5 kg','40x16x33 cm','SUP003','2026-06-26 00:20:38','2026-06-26 00:20:38'),(54,'PROD054','Cuisinart CPT-180',43,14,1000.00,'pcs','active','Premium quality Cuisinart CPT-180 Toasters from Cuisinart.',10,1,'890100000054',700.00,'9 kg','23x10x20 cm','SUP004','2026-06-26 00:20:39','2026-06-26 00:20:39'),(55,'PROD055','KitchenAid 2-Slice',44,14,1000.00,'pcs','active','Premium quality KitchenAid 2-Slice Toasters from KitchenAid.',10,1,'890100000055',700.00,'6 kg','28x28x41 cm','SUP005','2026-06-26 00:20:39','2026-06-26 00:20:39'),(56,'PROD056','Oster 4-Slice Toaster',45,14,1000.00,'pcs','active','Premium quality Oster 4-Slice Toaster Toasters from Oster.',10,1,'890100000056',700.00,'3 kg','38x18x23 cm','SUP006','2026-06-26 00:20:39','2026-06-26 00:20:39'),(57,'PROD057','Cosori Pro II',46,15,1000.00,'pcs','active','Premium quality Cosori Pro II Air Fryers from Cosori.',10,1,'890100000057',700.00,'4 kg','13x17x6 cm','SUP007','2026-06-26 00:20:39','2026-06-26 00:20:39'),(58,'PROD058','Ninja AF101',9,15,10000.00,'pcs','active','Premium quality Ninja AF101 Air Fryers from Ninja.',10,1,'890100000058',7000.00,'4 kg','18x24x13 cm','SUP008','2026-06-26 00:20:39','2026-06-26 00:20:39'),(59,'PROD059','Instant Vortex Plus',47,15,10000.00,'pcs','active','Premium quality Instant Vortex Plus Air Fryers from Instant Pot.',10,1,'890100000059',7000.00,'4 kg','50x12x7 cm','SUP009','2026-06-26 00:20:39','2026-06-26 00:20:39'),(60,'PROD060','Philips Essential Air',18,15,1000.00,'box','active','Premium quality Philips Essential Air Air Fryers from Philips.',10,1,'890100000060',700.00,'1 kg','15x22x35 cm','SUP010','2026-06-26 00:20:40','2026-06-26 00:20:40'),(61,'PROD061','Wilson NFL Duke',48,16,1000.00,'pcs','active','Premium quality Wilson NFL Duke Footballs from Wilson.',10,1,'890100000061',700.00,'6 kg','47x7x8 cm','SUP001','2026-06-26 00:20:40','2026-06-26 00:20:40'),(62,'PROD062','Adidas Al Rihla',5,16,1000.00,'pcs','active','Premium quality Adidas Al Rihla Footballs from Adidas.',10,1,'890100000062',700.00,'5 kg','22x27x26 cm','SUP002','2026-06-26 00:20:40','2026-06-26 00:20:40'),(63,'PROD063','Nike Strike Ball',4,16,1000.00,'pcs','active','Premium quality Nike Strike Ball Footballs from Nike.',10,1,'890100000063',700.00,'4 kg','40x22x36 cm','SUP003','2026-06-26 00:20:40','2026-06-26 00:20:40'),(64,'PROD064','Spalding TF-1000 Ball',49,16,1000.00,'pcs','active','Premium quality Spalding TF-1000 Ball Footballs from Spalding.',10,1,'890100000064',700.00,'5 kg','36x44x34 cm','SUP004','2026-06-26 00:20:40','2026-06-26 00:20:40'),(65,'PROD065','Bowflex SelectTech',50,17,10000.00,'pcs','active','Premium quality Bowflex SelectTech Dumbbells from Bowflex.',10,1,'890100000065',7000.00,'9 kg','19x6x31 cm','SUP005','2026-06-26 00:20:40','2026-06-26 00:20:40'),(66,'PROD066','CAP Cast Iron Dumbbell',51,17,1000.00,'pcs','active','Premium quality CAP Cast Iron Dumbbell Dumbbells from CAP.',10,1,'890100000066',700.00,'2 kg','13x45x34 cm','SUP006','2026-06-26 00:20:40','2026-06-26 00:20:40'),(67,'PROD067','Amazon Basics Neoprene',52,17,1000.00,'pcs','active','Premium quality Amazon Basics Neoprene Dumbbells from Amazon Basics.',10,1,'890100000067',700.00,'4 kg','30x5x5 cm','SUP007','2026-06-26 00:20:40','2026-06-26 00:20:40'),(68,'PROD068','Rogue Rubber Hex',53,17,1000.00,'pcs','active','Premium quality Rogue Rubber Hex Dumbbells from Rogue.',10,1,'890100000068',700.00,'5 kg','30x47x18 cm','SUP008','2026-06-26 00:20:41','2026-06-26 00:20:41'),(69,'PROD069','Lululemon 5mm Mat',54,18,1000.00,'pcs','active','Premium quality Lululemon 5mm Mat Yoga Mats from Lululemon.',10,1,'890100000069',700.00,'2 kg','49x35x15 cm','SUP009','2026-06-26 00:20:41','2026-06-26 00:20:41'),(70,'PROD070','Manduka PRO Mat',55,18,1000.00,'box','active','Premium quality Manduka PRO Mat Yoga Mats from Manduka.',10,1,'890100000070',700.00,'6 kg','22x38x6 cm','SUP010','2026-06-26 00:20:41','2026-06-26 00:20:41'),(71,'PROD071','Gaiam Essentials Mat',56,18,1000.00,'pcs','active','Premium quality Gaiam Essentials Mat Yoga Mats from Gaiam.',10,1,'890100000071',700.00,'2 kg','14x46x28 cm','SUP001','2026-06-26 00:20:41','2026-06-26 00:20:41'),(72,'PROD072','Jade Harmony Mat',57,18,1000.00,'pcs','active','Premium quality Jade Harmony Mat Yoga Mats from Jade.',10,1,'890100000072',700.00,'9 kg','16x30x36 cm','SUP002','2026-06-26 00:20:41','2026-06-26 00:20:41'),(73,'PROD073','Hydro Flask 32oz',58,19,1000.00,'pcs','active','Premium quality Hydro Flask 32oz Water Bottles from Hydro Flask.',10,1,'890100000073',700.00,'6 kg','5x43x9 cm','SUP003','2026-06-26 00:20:42','2026-06-26 00:20:42'),(74,'PROD074','Yeti Rambler 26oz',59,19,1000.00,'pcs','active','Premium quality Yeti Rambler 26oz Water Bottles from Yeti.',10,1,'890100000074',700.00,'9 kg','29x33x20 cm','SUP004','2026-06-26 00:20:42','2026-06-26 00:20:42'),(75,'PROD075','CamelBak Chute Mag',60,19,1000.00,'kg','active','Premium quality CamelBak Chute Mag Water Bottles from CamelBak.',10,1,'890100000075',700.00,'1 kg','14x32x48 cm','SUP005','2026-06-26 00:20:42','2026-06-26 00:20:42'),(76,'PROD076','Nalgene Wide Mouth',61,19,1000.00,'pcs','active','Premium quality Nalgene Wide Mouth Water Bottles from Nalgene.',10,1,'890100000076',700.00,'6 kg','42x42x38 cm','SUP006','2026-06-26 00:20:42','2026-06-26 00:20:42'),(77,'PROD077','Nike Air Zoom Pegasus',4,20,1000.00,'pcs','active','Premium quality Nike Air Zoom Pegasus Running Shoes from Nike.',10,1,'890100000077',700.00,'2 kg','28x11x11 cm','SUP007','2026-06-26 00:20:42','2026-06-26 00:20:42'),(78,'PROD078','Adidas Ultraboost',5,20,10000.00,'pcs','active','Premium quality Adidas Ultraboost Running Shoes from Adidas.',10,1,'890100000078',7000.00,'7 kg','5x50x34 cm','SUP008','2026-06-26 00:20:42','2026-06-26 00:20:42'),(79,'PROD079','Brooks Ghost 15',62,20,1000.00,'pcs','active','Premium quality Brooks Ghost 15 Running Shoes from Brooks.',10,1,'890100000079',700.00,'7 kg','44x34x22 cm','SUP009','2026-06-26 00:20:42','2026-06-26 00:20:42'),(80,'PROD080','Asics Gel-Kayano 30',63,20,10000.00,'box','active','Premium quality Asics Gel-Kayano 30 Running Shoes from Asics.',10,1,'890100000080',7000.00,'1 kg','38x11x18 cm','SUP010','2026-06-26 00:20:42','2026-06-26 00:20:42'),(81,'PROD081','Moleskine Classic',64,21,1000.00,'pcs','active','Premium quality Moleskine Classic Notebooks from Moleskine.',10,1,'890100000081',700.00,'8 kg','37x5x22 cm','SUP001','2026-06-26 00:20:43','2026-06-26 00:20:43'),(82,'PROD082','Rhodia Webnotebook',65,21,1000.00,'pcs','active','Premium quality Rhodia Webnotebook Notebooks from Rhodia.',10,1,'890100000082',700.00,'10 kg','36x48x12 cm','SUP002','2026-06-26 00:20:43','2026-06-26 00:20:43'),(83,'PROD083','Leuchtturm1917 Medium',66,21,1000.00,'pcs','active','Premium quality Leuchtturm1917 Medium Notebooks from Leuchtturm1917.',10,1,'890100000083',700.00,'9 kg','37x40x16 cm','SUP003','2026-06-26 00:20:43','2026-06-26 00:20:43'),(84,'PROD084','Field Notes 3-Pack',67,21,100.00,'pcs','active','Premium quality Field Notes 3-Pack Notebooks from Field Notes.',10,1,'890100000084',70.00,'10 kg','20x10x49 cm','SUP004','2026-06-26 00:20:43','2026-06-26 00:20:43'),(85,'PROD085','Pilot G2 Gel Pens',68,22,100.00,'pcs','active','Premium quality Pilot G2 Gel Pens Pens from Pilot.',10,1,'890100000085',70.00,'8 kg','30x6x18 cm','SUP005','2026-06-26 00:20:43','2026-06-26 00:20:43'),(86,'PROD086','Uni-ball Onyx Pens',69,22,100.00,'pcs','active','Premium quality Uni-ball Onyx Pens Pens from Uni-ball.',10,1,'890100000086',70.00,'2 kg','26x30x44 cm','SUP006','2026-06-26 00:20:44','2026-06-26 00:20:44'),(87,'PROD087','Parker Jotter Classic',70,22,100.00,'pcs','active','Premium quality Parker Jotter Classic Pens from Parker.',10,1,'890100000087',70.00,'3 kg','15x40x15 cm','SUP007','2026-06-26 00:20:44','2026-06-26 00:20:44'),(88,'PROD088','Sharpie S-Gel Pens',71,22,100.00,'pcs','active','Premium quality Sharpie S-Gel Pens Pens from Sharpie.',10,1,'890100000088',70.00,'8 kg','50x23x24 cm','SUP008','2026-06-26 00:20:44','2026-06-26 00:20:44'),(89,'PROD089','Mesh Desk Organizer',72,23,100.00,'pcs','active','Premium quality Mesh Desk Organizer Desk Organizers from Mesh.',10,1,'890100000089',70.00,'3 kg','6x23x50 cm','SUP009','2026-06-26 00:20:44','2026-06-26 00:20:44'),(90,'PROD090','Wooden Desk Caddy',73,23,1000.00,'box','active','Premium quality Wooden Desk Caddy Desk Organizers from Wooden.',10,1,'890100000090',700.00,'4 kg','22x25x10 cm','SUP010','2026-06-26 00:20:44','2026-06-26 00:20:44'),(91,'PROD091','Leather Desk Pad',74,23,1000.00,'pcs','active','Premium quality Leather Desk Pad Desk Organizers from Leather.',10,1,'890100000091',700.00,'3 kg','21x26x7 cm','SUP001','2026-06-26 00:20:44','2026-06-26 00:20:44'),(92,'PROD092','Monitor Stand Riser',75,23,1000.00,'pcs','active','Premium quality Monitor Stand Riser Desk Organizers from Monitor Stand.',10,1,'890100000092',700.00,'3 kg','14x35x33 cm','SUP002','2026-06-26 00:20:44','2026-06-26 00:20:44'),(93,'PROD093','Swingline 747 Classic',76,24,1000.00,'pcs','active','Premium quality Swingline 747 Classic Staplers from Swingline.',10,1,'890100000093',700.00,'7 kg','30x16x15 cm','SUP003','2026-06-26 00:20:45','2026-06-26 00:20:45'),(94,'PROD094','Bostitch Office Staple',77,24,100.00,'pcs','active','Premium quality Bostitch Office Staple Staplers from Bostitch.',10,1,'890100000094',70.00,'4 kg','13x15x13 cm','SUP004','2026-06-26 00:20:45','2026-06-26 00:20:45'),(95,'PROD095','PaperPro InSpire',78,24,100.00,'pcs','active','Premium quality PaperPro InSpire Staplers from PaperPro.',10,1,'890100000095',70.00,'7 kg','14x34x12 cm','SUP005','2026-06-26 00:20:45','2026-06-26 00:20:45'),(96,'PROD096','Rapid Supreme Press',79,24,1000.00,'pcs','active','Premium quality Rapid Supreme Press Staplers from Rapid.',10,1,'890100000096',700.00,'6 kg','14x47x23 cm','SUP006','2026-06-26 00:20:45','2026-06-26 00:20:45'),(97,'PROD097','Fellowes Powershred',80,25,1000.00,'pcs','active','Premium quality Fellowes Powershred Paper Shredders from Fellowes.',10,1,'890100000097',700.00,'10 kg','50x48x37 cm','SUP007','2026-06-26 00:20:45','2026-06-26 00:20:45'),(98,'PROD098','Amazon Basics Shredder',52,25,1000.00,'pcs','active','Premium quality Amazon Basics Shredder Paper Shredders from Amazon Basics.',10,1,'890100000098',700.00,'10 kg','17x45x9 cm','SUP008','2026-06-26 00:20:45','2026-06-26 00:20:45'),(99,'PROD099','Aurora AU800SD',81,25,1000.00,'pcs','active','Premium quality Aurora AU800SD Paper Shredders from Aurora.',10,1,'890100000099',700.00,'6 kg','11x12x17 cm','SUP009','2026-06-26 00:20:45','2026-06-26 00:20:45'),(100,'PROD100','Royal Sovereign Shred',82,25,1000.00,'box','active','Premium quality Royal Sovereign Shred Paper Shredders from Royal Sovereign.',10,1,'890100000100',700.00,'10 kg','45x17x18 cm','SUP010','2026-06-26 00:20:46','2026-06-26 00:20:46');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_record_id` bigint(20) unsigned NOT NULL,
  `purchase_id` varchar(255) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `current_stock_qty` int(11) DEFAULT NULL,
  `stock_price` decimal(15,2) NOT NULL,
  `selling_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_record_id_foreign` (`purchase_record_id`),
  KEY `purchase_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_purchase_record_id_foreign` FOREIGN KEY (`purchase_record_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
INSERT INTO `purchase_items` VALUES (1,1,'GRN-2026-0001',1,'iPhone 15 Pro','PROD001',22,NULL,7000.00,10000.00,0.00,154000.00,'2026-02-15 20:21:15','2026-02-15 20:21:15'),(2,1,'GRN-2026-0001',2,'Samsung Galaxy S24','PROD002',19,NULL,7000.00,10000.00,0.00,133000.00,'2026-02-15 20:21:15','2026-02-15 20:21:15'),(3,1,'GRN-2026-0001',3,'Google Pixel 8','PROD003',15,NULL,7000.00,10000.00,0.00,105000.00,'2026-02-15 20:21:15','2026-02-15 20:21:15'),(4,1,'GRN-2026-0001',4,'OnePlus 12','PROD004',18,NULL,7000.00,10000.00,0.00,126000.00,'2026-02-15 20:21:15','2026-02-15 20:21:15'),(5,1,'GRN-2026-0001',5,'MacBook Air M3','PROD005',29,NULL,7000.00,10000.00,0.00,203000.00,'2026-02-15 20:21:15','2026-02-15 20:21:15'),(6,2,'GRN-2026-0002',4,'OnePlus 12','PROD004',27,NULL,7000.00,10000.00,0.00,189000.00,'2026-02-05 16:21:16','2026-02-05 16:21:16'),(7,2,'GRN-2026-0002',5,'MacBook Air M3','PROD005',30,NULL,7000.00,10000.00,0.00,210000.00,'2026-02-05 16:21:16','2026-02-05 16:21:16'),(8,2,'GRN-2026-0002',6,'Dell XPS 13','PROD006',25,NULL,7000.00,10000.00,0.00,175000.00,'2026-02-05 16:21:16','2026-02-05 16:21:16'),(9,2,'GRN-2026-0002',7,'HP Spectre x360','PROD007',14,NULL,7000.00,10000.00,0.00,98000.00,'2026-02-05 16:21:16','2026-02-05 16:21:16'),(10,3,'GRN-2026-0003',7,'HP Spectre x360','PROD007',20,NULL,7000.00,10000.00,0.00,140000.00,'2026-02-02 21:21:17','2026-02-02 21:21:17'),(11,3,'GRN-2026-0003',8,'Lenovo ThinkPad X1','PROD008',19,NULL,7000.00,10000.00,0.00,133000.00,'2026-02-02 21:21:17','2026-02-02 21:21:17'),(12,3,'GRN-2026-0003',9,'Sony WH-1000XM5','PROD009',17,NULL,7000.00,10000.00,0.00,119000.00,'2026-02-02 21:21:17','2026-02-02 21:21:17'),(13,3,'GRN-2026-0003',10,'Bose QuietComfort Ultra','PROD010',10,NULL,7000.00,10000.00,0.00,70000.00,'2026-02-02 21:21:17','2026-02-02 21:21:17'),(14,3,'GRN-2026-0003',11,'Apple AirPods Pro','PROD011',15,NULL,7000.00,10000.00,0.00,105000.00,'2026-02-02 21:21:17','2026-02-02 21:21:17'),(15,4,'GRN-2026-0004',10,'Bose QuietComfort Ultra','PROD010',29,NULL,7000.00,10000.00,0.00,203000.00,'2026-03-08 21:21:17','2026-03-08 21:21:17'),(16,4,'GRN-2026-0004',11,'Apple AirPods Pro','PROD011',12,NULL,7000.00,10000.00,0.00,84000.00,'2026-03-08 21:21:17','2026-03-08 21:21:17'),(17,4,'GRN-2026-0004',12,'Sennheiser Momentum 4','PROD012',12,NULL,7000.00,10000.00,0.00,84000.00,'2026-03-08 21:21:17','2026-03-08 21:21:17'),(18,5,'GRN-2026-0005',13,'Anker PowerCore 24K','PROD013',10,NULL,700.00,1000.00,0.00,7000.00,'2026-03-06 18:21:18','2026-03-06 18:21:18'),(19,5,'GRN-2026-0005',14,'Logitech MX Master 3S','PROD014',21,NULL,700.00,1000.00,0.00,14700.00,'2026-03-06 18:21:18','2026-03-06 18:21:18'),(20,5,'GRN-2026-0005',15,'Apple Magic Keyboard','PROD015',11,NULL,700.00,1000.00,0.00,7700.00,'2026-03-06 18:21:18','2026-03-06 18:21:18'),(21,6,'GRN-2026-0006',16,'Samsung T7 Shield SSD','PROD016',10,NULL,700.00,1000.00,0.00,7000.00,'2026-03-24 23:21:18','2026-03-24 23:21:18'),(22,6,'GRN-2026-0006',17,'Amazon Echo Dot','PROD017',22,NULL,700.00,1000.00,0.00,15400.00,'2026-03-24 23:21:18','2026-03-24 23:21:18'),(23,6,'GRN-2026-0006',18,'Google Nest Hub','PROD018',26,NULL,700.00,1000.00,0.00,18200.00,'2026-03-24 23:21:18','2026-03-24 23:21:18'),(24,7,'GRN-2026-0007',19,'Philips Hue Starter Kit','PROD019',18,NULL,700.00,1000.00,0.00,12600.00,'2026-04-13 17:21:19','2026-04-13 17:21:19'),(25,7,'GRN-2026-0007',20,'Ring Video Doorbell','PROD020',12,NULL,7000.00,10000.00,0.00,84000.00,'2026-04-13 17:21:19','2026-04-13 17:21:19'),(26,7,'GRN-2026-0007',21,'Nike Dri-FIT Tee','PROD021',22,NULL,700.00,1000.00,0.00,15400.00,'2026-04-13 17:21:19','2026-04-13 17:21:19'),(27,7,'GRN-2026-0007',22,'Adidas Originals Tee','PROD022',30,NULL,700.00,1000.00,0.00,21000.00,'2026-04-13 17:21:19','2026-04-13 17:21:19'),(28,7,'GRN-2026-0007',23,'Puma Classic Tee','PROD023',25,NULL,700.00,1000.00,0.00,17500.00,'2026-04-13 17:21:19','2026-04-13 17:21:19'),(29,8,'GRN-2026-0008',22,'Adidas Originals Tee','PROD022',25,NULL,700.00,1000.00,0.00,17500.00,'2026-04-11 21:21:20','2026-04-11 21:21:20'),(30,8,'GRN-2026-0008',23,'Puma Classic Tee','PROD023',19,NULL,700.00,1000.00,0.00,13300.00,'2026-04-11 21:21:20','2026-04-11 21:21:20'),(31,8,'GRN-2026-0008',24,'Under Armour Sport Tee','PROD024',24,NULL,700.00,1000.00,0.00,16800.00,'2026-04-11 21:21:20','2026-04-11 21:21:20'),(32,9,'GRN-2026-0009',25,'Levis 501 Original','PROD025',23,NULL,700.00,1000.00,0.00,16100.00,'2026-04-22 21:21:21','2026-04-22 21:21:21'),(33,9,'GRN-2026-0009',26,'Wrangler Authentic Jeans','PROD026',28,NULL,700.00,1000.00,0.00,19600.00,'2026-04-22 21:21:21','2026-04-22 21:21:21'),(34,9,'GRN-2026-0009',27,'Lee Rider Slim Jeans','PROD027',23,NULL,700.00,1000.00,0.00,16100.00,'2026-04-22 21:21:21','2026-04-22 21:21:21'),(35,9,'GRN-2026-0009',28,'Diesel D-Strukt Jeans','PROD028',10,NULL,700.00,1000.00,0.00,7000.00,'2026-04-22 21:21:21','2026-04-22 21:21:21'),(36,10,'GRN-2026-0010',28,'Diesel D-Strukt Jeans','PROD028',30,NULL,700.00,1000.00,0.00,21000.00,'2026-04-28 22:21:21','2026-04-28 22:21:21'),(37,10,'GRN-2026-0010',29,'Patagonia Torrentshell','PROD029',25,NULL,700.00,1000.00,0.00,17500.00,'2026-04-28 22:21:21','2026-04-28 22:21:21'),(38,10,'GRN-2026-0010',30,'The North Face Resolve','PROD030',17,NULL,700.00,1000.00,0.00,11900.00,'2026-04-28 22:21:21','2026-04-28 22:21:21'),(39,10,'GRN-2026-0010',31,'Columbia Watertight','PROD031',28,NULL,700.00,1000.00,0.00,19600.00,'2026-04-28 22:21:21','2026-04-28 22:21:21'),(40,11,'GRN-2026-0011',31,'Columbia Watertight','PROD031',10,NULL,700.00,1000.00,0.00,7000.00,'2026-05-14 17:21:22','2026-05-14 17:21:22'),(41,11,'GRN-2026-0011',32,'Arc`teryx Beta LT','PROD032',28,NULL,7000.00,10000.00,0.00,196000.00,'2026-05-14 17:21:22','2026-05-14 17:21:22'),(42,11,'GRN-2026-0011',33,'Darn Tough Cushion','PROD033',11,NULL,700.00,1000.00,0.00,7700.00,'2026-05-14 17:21:22','2026-05-14 17:21:22'),(43,11,'GRN-2026-0011',34,'Smartwool Run Socks','PROD034',18,NULL,70.00,100.00,0.00,1260.00,'2026-05-14 17:21:22','2026-05-14 17:21:22'),(44,12,'GRN-2026-0012',34,'Smartwool Run Socks','PROD034',11,NULL,70.00,100.00,0.00,770.00,'2026-04-27 20:21:23','2026-04-27 20:21:23'),(45,12,'GRN-2026-0012',35,'Nike Everyday Cushion','PROD035',16,NULL,70.00,100.00,0.00,1120.00,'2026-04-27 20:21:23','2026-04-27 20:21:23'),(46,12,'GRN-2026-0012',36,'Adidas Athletic Socks','PROD036',24,NULL,70.00,100.00,0.00,1680.00,'2026-04-27 20:21:23','2026-04-27 20:21:23'),(47,12,'GRN-2026-0012',37,'New Era 59FIFTY Cap','PROD037',22,NULL,700.00,1000.00,0.00,15400.00,'2026-04-27 20:21:23','2026-04-27 20:21:23'),(48,12,'GRN-2026-0012',38,'Nike Legacy91 Cap','PROD038',30,NULL,700.00,1000.00,0.00,21000.00,'2026-04-27 20:21:23','2026-04-27 20:21:23'),(49,13,'GRN-2026-0013',37,'New Era 59FIFTY Cap','PROD037',24,NULL,700.00,1000.00,0.00,16800.00,'2026-06-11 17:21:23','2026-06-11 17:21:23'),(50,13,'GRN-2026-0013',38,'Nike Legacy91 Cap','PROD038',19,NULL,700.00,1000.00,0.00,13300.00,'2026-06-11 17:21:23','2026-06-11 17:21:23'),(51,13,'GRN-2026-0013',39,'Adidas Superlite Cap','PROD039',17,NULL,700.00,1000.00,0.00,11900.00,'2026-06-11 17:21:23','2026-06-11 17:21:23'),(52,14,'GRN-2026-0014',40,'Carhartt Beanie','PROD040',27,NULL,700.00,1000.00,0.00,18900.00,'2026-06-18 15:21:25','2026-06-18 15:21:25'),(53,14,'GRN-2026-0014',41,'Keurig K-Elite','PROD041',26,NULL,7000.00,10000.00,0.00,182000.00,'2026-06-18 15:21:25','2026-06-18 15:21:25'),(54,14,'GRN-2026-0014',42,'Nespresso Vertuo Next','PROD042',27,NULL,7000.00,10000.00,0.00,189000.00,'2026-06-18 15:21:25','2026-06-18 15:21:25'),(55,14,'GRN-2026-0014',43,'Breville Barista','PROD043',22,NULL,7000.00,10000.00,0.00,154000.00,'2026-06-18 15:21:25','2026-06-18 15:21:25'),(56,14,'GRN-2026-0014',44,'Hamilton FlexBrew','PROD044',30,NULL,700.00,1000.00,0.00,21000.00,'2026-06-18 15:21:25','2026-06-18 15:21:25'),(57,15,'GRN-2026-0015',43,'Breville Barista','PROD043',30,NULL,7000.00,10000.00,0.00,210000.00,'2026-06-04 20:21:26','2026-06-04 20:21:26'),(58,15,'GRN-2026-0015',44,'Hamilton FlexBrew','PROD044',29,NULL,700.00,1000.00,0.00,20300.00,'2026-06-04 20:21:26','2026-06-04 20:21:26'),(59,15,'GRN-2026-0015',45,'Toshiba EM131A5C','PROD045',27,NULL,700.00,1000.00,0.00,18900.00,'2026-06-04 20:21:26','2026-06-04 20:21:26'),(60,15,'GRN-2026-0015',46,'Panasonic NN-SN686S','PROD046',21,NULL,7000.00,10000.00,0.00,147000.00,'2026-06-04 20:21:26','2026-06-04 20:21:26');
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_return_items`
--

DROP TABLE IF EXISTS `purchase_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_return_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_return_record_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `return_type` varchar(255) NOT NULL DEFAULT 'damaged',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_return_items_purchase_return_record_id_foreign` (`purchase_return_record_id`),
  KEY `purchase_return_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_return_items_purchase_return_record_id_foreign` FOREIGN KEY (`purchase_return_record_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_return_items`
--

LOCK TABLES `purchase_return_items` WRITE;
/*!40000 ALTER TABLE `purchase_return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_return_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_returns`
--

DROP TABLE IF EXISTS `purchase_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_return_id` varchar(255) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `return_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_returns_purchase_return_id_unique` (`purchase_return_id`),
  KEY `purchase_returns_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_returns_branch_id_foreign` (`branch_id`),
  KEY `purchase_returns_created_by_foreign` (`created_by`),
  CONSTRAINT `purchase_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_returns`
--

LOCK TABLES `purchase_returns` WRITE;
/*!40000 ALTER TABLE `purchase_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` varchar(255) NOT NULL,
  `supplier_id` varchar(32) NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `purchase_date` date NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'Cash',
  `amount_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_due` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_notes` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_reference` varchar(255) DEFAULT NULL,
  `card_type` varchar(255) DEFAULT NULL,
  `card_last_four` varchar(255) DEFAULT NULL,
  `loan_interest_rate` decimal(5,2) DEFAULT NULL,
  `loan_term_months` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_purchase_id_unique` (`purchase_id`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  KEY `purchases_branch_id_foreign` (`branch_id`),
  CONSTRAINT `purchases_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE,
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (1,'GRN-2026-0001','SUP001',1,'2026-02-16','REF-50504',721000.00,57680.00,0.00,778680.00,'Cash',778680.00,0.00,'Paid in full','Purchase record 1 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-15 20:21:15','2026-06-26 00:21:16'),(2,'GRN-2026-0002','SUP002',2,'2026-02-05','REF-80793',672000.00,53760.00,0.00,725760.00,'Bank Transfer',725760.00,0.00,'Paid in full','Purchase record 2 via seeder','BOC','TXN901653',NULL,NULL,NULL,NULL,'2026-02-05 16:21:16','2026-06-26 00:21:17'),(3,'GRN-2026-0003','SUP003',3,'2026-02-03','REF-21897',567000.00,45360.00,50.00,612310.00,'Cash',612310.00,0.00,'Paid in full','Purchase record 3 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-02-02 21:21:17','2026-06-26 00:21:17'),(4,'GRN-2026-0004','SUP004',4,'2026-03-09','REF-50318',371000.00,29680.00,0.00,400680.00,'Bank Transfer',400680.00,0.00,'Paid in full','Purchase record 4 via seeder','BOC','TXN202521',NULL,NULL,NULL,NULL,'2026-03-08 21:21:17','2026-06-26 00:21:18'),(5,'GRN-2026-0005','SUP005',5,'2026-03-06','REF-63001',29400.00,2352.00,0.00,31752.00,'Cash',31752.00,0.00,'Paid in full','Purchase record 5 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-06 18:21:18','2026-06-26 00:21:18'),(6,'GRN-2026-0006','SUP006',1,'2026-03-25','REF-47517',40600.00,3248.00,50.00,43798.00,'Bank Transfer',43798.00,0.00,'Paid in full','Purchase record 6 via seeder','BOC','TXN972024',NULL,NULL,NULL,NULL,'2026-03-24 23:21:18','2026-06-26 00:21:19'),(7,'GRN-2026-0007','SUP007',2,'2026-04-13','REF-76109',150500.00,12040.00,0.00,162540.00,'Cash',162540.00,0.00,'Paid in full','Purchase record 7 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-13 17:21:19','2026-06-26 00:21:19'),(8,'GRN-2026-0008','SUP008',3,'2026-04-12','REF-22450',47600.00,3808.00,0.00,51408.00,'Bank Transfer',51408.00,0.00,'Paid in full','Purchase record 8 via seeder','BOC','TXN828696',NULL,NULL,NULL,NULL,'2026-04-11 21:21:20','2026-06-26 00:21:21'),(9,'GRN-2026-0009','SUP009',4,'2026-04-23','REF-81527',58800.00,4704.00,50.00,63454.00,'Cash',63454.00,0.00,'Paid in full','Purchase record 9 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-22 21:21:21','2026-06-26 00:21:21'),(10,'GRN-2026-0010','SUP010',5,'2026-04-29','REF-61037',70000.00,5600.00,0.00,75600.00,'Bank Transfer',75600.00,0.00,'Paid in full','Purchase record 10 via seeder','BOC','TXN124963',NULL,NULL,NULL,NULL,'2026-04-28 22:21:21','2026-06-26 00:21:22'),(11,'GRN-2026-0011','SUP001',1,'2026-05-14','REF-73960',211960.00,16956.80,0.00,228916.80,'Cash',228916.80,0.00,'Paid in full','Purchase record 11 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-05-14 17:21:22','2026-06-26 00:21:23'),(12,'GRN-2026-0012','SUP002',2,'2026-04-28','REF-93795',39970.00,3197.60,50.00,43117.60,'Bank Transfer',43117.60,0.00,'Paid in full','Purchase record 12 via seeder','BOC','TXN900688',NULL,NULL,NULL,NULL,'2026-04-27 20:21:23','2026-06-26 00:21:23'),(13,'GRN-2026-0013','SUP003',3,'2026-06-11','REF-19814',42000.00,3360.00,0.00,45360.00,'Cash',45360.00,0.00,'Paid in full','Purchase record 13 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-11 17:21:23','2026-06-26 00:21:25'),(14,'GRN-2026-0014','SUP004',4,'2026-06-18','REF-43881',564900.00,45192.00,0.00,610092.00,'Bank Transfer',610092.00,0.00,'Paid in full','Purchase record 14 via seeder','BOC','TXN978229',NULL,NULL,NULL,NULL,'2026-06-18 15:21:25','2026-06-26 00:21:26'),(15,'GRN-2026-0015','SUP005',5,'2026-06-05','REF-65233',396200.00,31696.00,50.00,427846.00,'Cash',427846.00,0.00,'Paid in full','Purchase record 15 via seeder',NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-04 20:21:26','2026-06-26 00:21:28');
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_return_items`
--

DROP TABLE IF EXISTS `sales_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_return_items` (
  `return_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `return_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `condition` varchar(255) NOT NULL DEFAULT 'sellable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`return_item_id`),
  KEY `sales_return_items_return_id_foreign` (`return_id`),
  KEY `sales_return_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sales_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `sales_return_items_return_id_foreign` FOREIGN KEY (`return_id`) REFERENCES `sales_returns` (`return_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_return_items`
--

LOCK TABLES `sales_return_items` WRITE;
/*!40000 ALTER TABLE `sales_return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_return_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_returns`
--

DROP TABLE IF EXISTS `sales_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_returns` (
  `return_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `return_number` varchar(255) NOT NULL,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `refund_amount` decimal(12,2) NOT NULL,
  `refund_method` varchar(255) NOT NULL DEFAULT 'cash',
  `remaining_credit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `reason` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`return_id`),
  UNIQUE KEY `sales_returns_return_number_unique` (`return_number`),
  KEY `sales_returns_invoice_id_foreign` (`invoice_id`),
  KEY `sales_returns_created_by_foreign` (`created_by`),
  CONSTRAINT `sales_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_returns_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_returns`
--

LOCK TABLES `sales_returns` WRITE;
/*!40000 ALTER TABLE `sales_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_transactions`
--

DROP TABLE IF EXISTS `stock_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_transactions` (
  `transaction_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `brand_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `subcategory_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `transaction_type` enum('in','out','adjustment','initial') NOT NULL DEFAULT 'in',
  `quantity_before` int(11) NOT NULL DEFAULT 0,
  `quantity_changed` int(11) NOT NULL,
  `quantity_after` int(11) NOT NULL DEFAULT 0,
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `stock_transactions_stock_id_foreign` (`stock_id`),
  KEY `stock_transactions_product_id_foreign` (`product_id`),
  KEY `stock_transactions_branch_id_foreign` (`branch_id`),
  KEY `stock_transactions_brand_id_foreign` (`brand_id`),
  KEY `stock_transactions_category_id_foreign` (`category_id`),
  KEY `stock_transactions_subcategory_id_foreign` (`subcategory_id`),
  KEY `stock_transactions_created_by_foreign` (`created_by`),
  KEY `stock_transactions_transaction_type_index` (`transaction_type`),
  KEY `stock_transactions_created_at_index` (`created_at`),
  KEY `stock_transactions_reference_number_index` (`reference_number`),
  CONSTRAINT `stock_transactions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_transactions_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_transactions_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`stock_id`) ON DELETE CASCADE,
  CONSTRAINT `stock_transactions_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `sub_categories` (`subcategory_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_transactions`
--

LOCK TABLES `stock_transactions` WRITE;
/*!40000 ALTER TABLE `stock_transactions` DISABLE KEYS */;
INSERT INTO `stock_transactions` VALUES (1,1,1,1,2,1,1,'iPhone 15 Pro','initial',0,76,76,'INIT-STK-1','Initial stock setup via seeder',1,'2026-06-26 00:21:07','2026-06-26 00:21:07'),(2,4,2,1,1,1,1,'Samsung Galaxy S24','initial',0,142,142,'INIT-STK-4','Initial stock setup via seeder',1,'2026-06-26 00:21:07','2026-06-26 00:21:07'),(3,7,3,1,11,1,1,'Google Pixel 8','initial',0,81,81,'INIT-STK-7','Initial stock setup via seeder',1,'2026-06-26 00:21:07','2026-06-26 00:21:07'),(4,10,4,1,12,1,1,'OnePlus 12','initial',0,87,87,'INIT-STK-10','Initial stock setup via seeder',1,'2026-06-26 00:21:07','2026-06-26 00:21:07'),(5,13,5,1,2,1,2,'MacBook Air M3','initial',0,105,105,'INIT-STK-13','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(6,16,6,1,6,1,2,'Dell XPS 13','initial',0,114,114,'INIT-STK-16','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(7,19,7,1,7,1,2,'HP Spectre x360','initial',0,59,59,'INIT-STK-19','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(8,22,8,1,13,1,2,'Lenovo ThinkPad X1','initial',0,92,92,'INIT-STK-22','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(9,25,9,1,3,1,3,'Sony WH-1000XM5','initial',0,125,125,'INIT-STK-25','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(10,28,10,1,14,1,3,'Bose QuietComfort Ultra','initial',0,99,99,'INIT-STK-28','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(11,31,11,1,2,1,3,'Apple AirPods Pro','initial',0,26,26,'INIT-STK-31','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(12,34,12,1,15,1,3,'Sennheiser Momentum 4','initial',0,52,52,'INIT-STK-34','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(13,37,13,1,16,1,4,'Anker PowerCore 24K','initial',0,27,27,'INIT-STK-37','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(14,40,14,1,8,1,4,'Logitech MX Master 3S','initial',0,71,71,'INIT-STK-40','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(15,43,15,1,2,1,4,'Apple Magic Keyboard','initial',0,80,80,'INIT-STK-43','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(16,46,16,1,1,1,4,'Samsung T7 Shield SSD','initial',0,52,52,'INIT-STK-46','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(17,49,17,1,17,1,5,'Amazon Echo Dot','initial',0,23,23,'INIT-STK-49','Initial stock setup via seeder',1,'2026-06-26 00:21:08','2026-06-26 00:21:08'),(18,52,18,1,11,1,5,'Google Nest Hub','initial',0,32,32,'INIT-STK-52','Initial stock setup via seeder',1,'2026-06-26 00:21:09','2026-06-26 00:21:09'),(19,55,19,1,18,1,5,'Philips Hue Starter Kit','initial',0,138,138,'INIT-STK-55','Initial stock setup via seeder',1,'2026-06-26 00:21:09','2026-06-26 00:21:09'),(20,58,20,1,19,1,5,'Ring Video Doorbell','initial',0,47,47,'INIT-STK-58','Initial stock setup via seeder',1,'2026-06-26 00:21:09','2026-06-26 00:21:09'),(21,61,21,1,4,2,6,'Nike Dri-FIT Tee','initial',0,63,63,'INIT-STK-61','Initial stock setup via seeder',1,'2026-06-26 00:21:09','2026-06-26 00:21:09'),(22,64,22,1,5,2,6,'Adidas Originals Tee','initial',0,56,56,'INIT-STK-64','Initial stock setup via seeder',1,'2026-06-26 00:21:09','2026-06-26 00:21:09'),(23,67,23,1,20,2,6,'Puma Classic Tee','initial',0,113,113,'INIT-STK-67','Initial stock setup via seeder',1,'2026-06-26 00:21:09','2026-06-26 00:21:09'),(24,70,24,1,21,2,6,'Under Armour Sport Tee','initial',0,77,77,'INIT-STK-70','Initial stock setup via seeder',1,'2026-06-26 00:21:09','2026-06-26 00:21:09'),(25,73,25,1,22,2,7,'Levis 501 Original','initial',0,133,133,'INIT-STK-73','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(26,76,26,1,23,2,7,'Wrangler Authentic Jeans','initial',0,124,124,'INIT-STK-76','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(27,79,27,1,24,2,7,'Lee Rider Slim Jeans','initial',0,149,149,'INIT-STK-79','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(28,82,28,1,25,2,7,'Diesel D-Strukt Jeans','initial',0,125,125,'INIT-STK-82','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(29,85,29,1,26,2,8,'Patagonia Torrentshell','initial',0,135,135,'INIT-STK-85','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(30,88,30,1,27,2,8,'The North Face Resolve','initial',0,146,146,'INIT-STK-88','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(31,91,31,1,28,2,8,'Columbia Watertight','initial',0,108,108,'INIT-STK-91','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(32,94,32,1,29,2,8,'Arc`teryx Beta LT','initial',0,147,147,'INIT-STK-94','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(33,97,33,1,30,2,9,'Darn Tough Cushion','initial',0,78,78,'INIT-STK-97','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(34,100,34,1,31,2,9,'Smartwool Run Socks','initial',0,72,72,'INIT-STK-100','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(35,103,35,1,4,2,9,'Nike Everyday Cushion','initial',0,92,92,'INIT-STK-103','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(36,106,36,1,5,2,9,'Adidas Athletic Socks','initial',0,136,136,'INIT-STK-106','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(37,109,37,1,32,2,10,'New Era 59FIFTY Cap','initial',0,70,70,'INIT-STK-109','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(38,112,38,1,4,2,10,'Nike Legacy91 Cap','initial',0,22,22,'INIT-STK-112','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(39,115,39,1,5,2,10,'Adidas Superlite Cap','initial',0,145,145,'INIT-STK-115','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(40,118,40,1,33,2,10,'Carhartt Beanie','initial',0,124,124,'INIT-STK-118','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(41,121,41,1,34,3,11,'Keurig K-Elite','initial',0,33,33,'INIT-STK-121','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(42,124,42,1,35,3,11,'Nespresso Vertuo Next','initial',0,59,59,'INIT-STK-124','Initial stock setup via seeder',1,'2026-06-26 00:21:10','2026-06-26 00:21:10'),(43,127,43,1,10,3,11,'Breville Barista','initial',0,49,49,'INIT-STK-127','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(44,130,44,1,36,3,11,'Hamilton FlexBrew','initial',0,73,73,'INIT-STK-130','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(45,133,45,1,37,3,12,'Toshiba EM131A5C','initial',0,37,37,'INIT-STK-133','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(46,136,46,1,38,3,12,'Panasonic NN-SN686S','initial',0,73,73,'INIT-STK-136','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(47,139,47,1,39,3,12,'Black+Decker EM720','initial',0,147,147,'INIT-STK-139','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(48,142,48,1,1,3,12,'Samsung MS19M','initial',0,145,145,'INIT-STK-142','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(49,145,49,1,40,3,13,'Vitamix 5200','initial',0,37,37,'INIT-STK-145','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(50,148,50,1,9,3,13,'Ninja Professional','initial',0,55,55,'INIT-STK-148','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(51,151,51,1,41,3,13,'NutriBullet Pro','initial',0,79,79,'INIT-STK-151','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(52,154,52,1,42,3,13,'Magic Bullet Blender','initial',0,109,109,'INIT-STK-154','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(53,157,53,1,10,3,14,'Breville Bit More','initial',0,64,64,'INIT-STK-157','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(54,160,54,1,43,3,14,'Cuisinart CPT-180','initial',0,86,86,'INIT-STK-160','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(55,163,55,1,44,3,14,'KitchenAid 2-Slice','initial',0,70,70,'INIT-STK-163','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(56,166,56,1,45,3,14,'Oster 4-Slice Toaster','initial',0,83,83,'INIT-STK-166','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(57,169,57,1,46,3,15,'Cosori Pro II','initial',0,22,22,'INIT-STK-169','Initial stock setup via seeder',1,'2026-06-26 00:21:11','2026-06-26 00:21:11'),(58,172,58,1,9,3,15,'Ninja AF101','initial',0,105,105,'INIT-STK-172','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(59,175,59,1,47,3,15,'Instant Vortex Plus','initial',0,58,58,'INIT-STK-175','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(60,178,60,1,18,3,15,'Philips Essential Air','initial',0,67,67,'INIT-STK-178','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(61,181,61,1,48,4,16,'Wilson NFL Duke','initial',0,105,105,'INIT-STK-181','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(62,184,62,1,5,4,16,'Adidas Al Rihla','initial',0,79,79,'INIT-STK-184','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(63,187,63,1,4,4,16,'Nike Strike Ball','initial',0,83,83,'INIT-STK-187','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(64,190,64,1,49,4,16,'Spalding TF-1000 Ball','initial',0,90,90,'INIT-STK-190','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(65,193,65,1,50,4,17,'Bowflex SelectTech','initial',0,107,107,'INIT-STK-193','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(66,196,66,1,51,4,17,'CAP Cast Iron Dumbbell','initial',0,95,95,'INIT-STK-196','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(67,199,67,1,52,4,17,'Amazon Basics Neoprene','initial',0,46,46,'INIT-STK-199','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(68,202,68,1,53,4,17,'Rogue Rubber Hex','initial',0,67,67,'INIT-STK-202','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(69,205,69,1,54,4,18,'Lululemon 5mm Mat','initial',0,63,63,'INIT-STK-205','Initial stock setup via seeder',1,'2026-06-26 00:21:12','2026-06-26 00:21:12'),(70,208,70,1,55,4,18,'Manduka PRO Mat','initial',0,146,146,'INIT-STK-208','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(71,211,71,1,56,4,18,'Gaiam Essentials Mat','initial',0,135,135,'INIT-STK-211','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(72,214,72,1,57,4,18,'Jade Harmony Mat','initial',0,56,56,'INIT-STK-214','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(73,217,73,1,58,4,19,'Hydro Flask 32oz','initial',0,95,95,'INIT-STK-217','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(74,220,74,1,59,4,19,'Yeti Rambler 26oz','initial',0,138,138,'INIT-STK-220','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(75,223,75,1,60,4,19,'CamelBak Chute Mag','initial',0,134,134,'INIT-STK-223','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(76,226,76,1,61,4,19,'Nalgene Wide Mouth','initial',0,44,44,'INIT-STK-226','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(77,229,77,1,4,4,20,'Nike Air Zoom Pegasus','initial',0,39,39,'INIT-STK-229','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(78,232,78,1,5,4,20,'Adidas Ultraboost','initial',0,33,33,'INIT-STK-232','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(79,235,79,1,62,4,20,'Brooks Ghost 15','initial',0,66,66,'INIT-STK-235','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(80,238,80,1,63,4,20,'Asics Gel-Kayano 30','initial',0,29,29,'INIT-STK-238','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(81,241,81,1,64,5,21,'Moleskine Classic','initial',0,104,104,'INIT-STK-241','Initial stock setup via seeder',1,'2026-06-26 00:21:13','2026-06-26 00:21:13'),(82,244,82,1,65,5,21,'Rhodia Webnotebook','initial',0,23,23,'INIT-STK-244','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(83,247,83,1,66,5,21,'Leuchtturm1917 Medium','initial',0,23,23,'INIT-STK-247','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(84,250,84,1,67,5,21,'Field Notes 3-Pack','initial',0,114,114,'INIT-STK-250','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(85,253,85,1,68,5,22,'Pilot G2 Gel Pens','initial',0,104,104,'INIT-STK-253','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(86,256,86,1,69,5,22,'Uni-ball Onyx Pens','initial',0,49,49,'INIT-STK-256','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(87,259,87,1,70,5,22,'Parker Jotter Classic','initial',0,46,46,'INIT-STK-259','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(88,262,88,1,71,5,22,'Sharpie S-Gel Pens','initial',0,107,107,'INIT-STK-262','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(89,265,89,1,72,5,23,'Mesh Desk Organizer','initial',0,135,135,'INIT-STK-265','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(90,268,90,1,73,5,23,'Wooden Desk Caddy','initial',0,37,37,'INIT-STK-268','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(91,271,91,1,74,5,23,'Leather Desk Pad','initial',0,130,130,'INIT-STK-271','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(92,274,92,1,75,5,23,'Monitor Stand Riser','initial',0,129,129,'INIT-STK-274','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(93,277,93,1,76,5,24,'Swingline 747 Classic','initial',0,107,107,'INIT-STK-277','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(94,280,94,1,77,5,24,'Bostitch Office Staple','initial',0,148,148,'INIT-STK-280','Initial stock setup via seeder',1,'2026-06-26 00:21:14','2026-06-26 00:21:14'),(95,283,95,1,78,5,24,'PaperPro InSpire','initial',0,112,112,'INIT-STK-283','Initial stock setup via seeder',1,'2026-06-26 00:21:15','2026-06-26 00:21:15'),(96,286,96,1,79,5,24,'Rapid Supreme Press','initial',0,78,78,'INIT-STK-286','Initial stock setup via seeder',1,'2026-06-26 00:21:15','2026-06-26 00:21:15'),(97,289,97,1,80,5,25,'Fellowes Powershred','initial',0,80,80,'INIT-STK-289','Initial stock setup via seeder',1,'2026-06-26 00:21:15','2026-06-26 00:21:15'),(98,292,98,1,52,5,25,'Amazon Basics Shredder','initial',0,114,114,'INIT-STK-292','Initial stock setup via seeder',1,'2026-06-26 00:21:15','2026-06-26 00:21:15'),(99,295,99,1,81,5,25,'Aurora AU800SD','initial',0,121,121,'INIT-STK-295','Initial stock setup via seeder',1,'2026-06-26 00:21:15','2026-06-26 00:21:15'),(100,298,100,1,82,5,25,'Royal Sovereign Shred','initial',0,40,40,'INIT-STK-298','Initial stock setup via seeder',1,'2026-06-26 00:21:15','2026-06-26 00:21:15'),(114,71,24,2,21,2,6,'Under Armour Sport Tee','adjustment',113,5,118,NULL,'Data entry error correction',1,'2026-06-02 07:22:08','2026-06-02 07:22:08'),(115,279,93,3,76,5,24,'Swingline 747 Classic','adjustment',79,6,85,NULL,'Batch Adj [GRN: GRN-2026-0009]: Expired items discarded',1,'2026-06-03 19:22:08','2026-06-03 19:22:08'),(116,181,61,1,48,4,16,'Wilson NFL Duke','adjustment',105,15,120,NULL,'System syncing discrepancy adjustment',1,'2026-06-11 08:22:08','2026-06-11 08:22:08'),(117,205,69,1,54,4,18,'Lululemon 5mm Mat','adjustment',63,-8,55,NULL,'Batch Adj [GRN: GRN-2026-0008]: Returned by delivery agent due to slight damage',1,'2026-06-25 21:22:09','2026-06-25 21:22:09'),(118,20,7,2,7,1,2,'HP Spectre x360','adjustment',26,-2,24,NULL,'Data entry error correction',1,'2026-06-18 23:22:09','2026-06-18 23:22:09'),(119,89,30,2,27,2,8,'The North Face Resolve','adjustment',149,3,152,NULL,'Batch Adj [GRN: GRN-2026-0005]: Returned by delivery agent due to slight damage',1,'2026-06-01 22:22:09','2026-06-01 22:22:09'),(120,32,11,2,2,1,3,'Apple AirPods Pro','adjustment',82,8,90,NULL,'Data entry error correction',1,'2026-06-24 02:22:09','2026-06-24 02:22:09'),(121,140,47,2,39,3,12,'Black+Decker EM720','adjustment',134,-8,126,NULL,'Batch Adj [GRN: GRN-2026-0010]: Damaged packaging - stock removed',1,'2026-06-03 08:22:09','2026-06-03 08:22:09'),(122,294,98,3,52,5,25,'Amazon Basics Shredder','adjustment',57,-10,47,NULL,'Physical count discrepancy resolved',1,'2026-06-07 03:22:10','2026-06-07 03:22:10'),(123,81,27,3,24,2,7,'Lee Rider Slim Jeans','adjustment',57,-2,55,NULL,'Batch Adj [GRN: GRN-2026-0009]: Returned by delivery agent due to slight damage',1,'2026-06-15 13:22:10','2026-06-15 13:22:10'),(124,226,76,1,61,4,19,'Nalgene Wide Mouth','adjustment',44,10,54,NULL,'Returned by delivery agent due to slight damage',1,'2026-06-10 10:22:11','2026-06-10 10:22:11'),(125,231,77,3,4,4,20,'Nike Air Zoom Pegasus','adjustment',33,11,44,NULL,'Batch Adj [GRN: GRN-2026-0008]: Expired items discarded',1,'2026-06-06 12:22:11','2026-06-06 12:22:11'),(126,300,100,3,82,5,25,'Royal Sovereign Shred','adjustment',128,-7,121,NULL,'Data entry error correction',1,'2026-06-24 14:22:11','2026-06-24 14:22:11'),(127,161,54,2,43,3,14,'Cuisinart CPT-180','adjustment',74,7,81,NULL,'Batch Adj [GRN: GRN-2026-0010]: Returned by delivery agent due to slight damage',1,'2026-06-05 12:22:11','2026-06-05 12:22:11'),(128,264,88,3,71,5,22,'Sharpie S-Gel Pens','adjustment',138,14,152,NULL,'System syncing discrepancy adjustment',1,'2026-06-15 19:22:11','2026-06-15 19:22:11'),(129,131,44,2,36,3,11,'Hamilton FlexBrew','adjustment',146,11,157,NULL,'Batch Adj [GRN: GRN-2026-0009]: Damaged packaging - stock removed',1,'2026-06-10 22:22:11','2026-06-10 22:22:11'),(130,276,92,3,75,5,23,'Monitor Stand Riser','adjustment',128,2,130,NULL,'Damaged packaging - stock removed',1,'2026-06-16 03:22:11','2026-06-16 03:22:11'),(131,121,41,1,34,3,11,'Keurig K-Elite','adjustment',33,4,37,NULL,'Batch Adj [GRN: GRN-2026-0005]: Returned by delivery agent due to slight damage',1,'2026-06-05 16:22:12','2026-06-05 16:22:12'),(132,179,60,2,18,3,15,'Philips Essential Air','adjustment',94,10,104,NULL,'Found extra stock during audit',1,'2026-06-18 14:22:12','2026-06-18 14:22:12'),(133,171,57,3,46,3,15,'Cosori Pro II','adjustment',88,-5,83,NULL,'Batch Adj [GRN: GRN-2026-0005]: Data entry error correction',1,'2026-06-11 11:22:12','2026-06-11 11:22:12');
/*!40000 ALTER TABLE `stock_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stocks` (
  `stock_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `brand_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `subcategory_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `minimum_stock_level` int(11) NOT NULL DEFAULT 10,
  `maximum_stock_level` int(11) NOT NULL DEFAULT 100,
  `last_restocked_date` date DEFAULT NULL,
  `location_in_warehouse` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stock_id`),
  UNIQUE KEY `stocks_product_id_branch_id_unique` (`product_id`,`branch_id`),
  KEY `stocks_branch_id_foreign` (`branch_id`),
  KEY `stocks_brand_id_foreign` (`brand_id`),
  KEY `stocks_category_id_foreign` (`category_id`),
  KEY `stocks_subcategory_id_foreign` (`subcategory_id`),
  CONSTRAINT `stocks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE,
  CONSTRAINT `stocks_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE,
  CONSTRAINT `stocks_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `stocks_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `sub_categories` (`subcategory_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` VALUES (1,1,1,2,1,1,'iPhone 15 Pro',76,15,400,'2026-06-16','Aisle C-7','2026-06-26 00:20:46','2026-06-26 00:20:46'),(2,1,2,2,1,1,'iPhone 15 Pro',104,15,400,'2026-06-21','Aisle B-6','2026-06-26 00:20:46','2026-06-26 00:20:46'),(3,1,3,2,1,1,'iPhone 15 Pro',101,15,400,'2026-06-08','Aisle A-8','2026-06-26 00:20:46','2026-06-26 00:20:46'),(4,2,1,1,1,1,'Samsung Galaxy S24',142,15,400,'2026-06-02','Aisle D-10','2026-06-26 00:20:46','2026-06-26 00:20:46'),(5,2,2,1,1,1,'Samsung Galaxy S24',47,15,400,'2026-06-06','Aisle E-8','2026-06-26 00:20:46','2026-06-26 00:20:46'),(6,2,3,1,1,1,'Samsung Galaxy S24',73,15,400,'2026-06-11','Aisle C-6','2026-06-26 00:20:46','2026-06-26 00:20:46'),(7,3,1,11,1,1,'Google Pixel 8',81,15,400,'2026-06-25','Aisle A-9','2026-06-26 00:20:46','2026-06-26 00:20:46'),(8,3,2,11,1,1,'Google Pixel 8',116,15,400,'2026-06-07','Aisle D-4','2026-06-26 00:20:47','2026-06-26 00:20:47'),(9,3,3,11,1,1,'Google Pixel 8',138,15,400,'2026-06-14','Aisle A-1','2026-06-26 00:20:47','2026-06-26 00:20:47'),(10,4,1,12,1,1,'OnePlus 12',87,15,400,'2026-06-02','Aisle F-3','2026-06-26 00:20:47','2026-06-26 00:20:47'),(11,4,2,12,1,1,'OnePlus 12',28,15,400,'2026-06-02','Aisle E-9','2026-06-26 00:20:47','2026-06-26 00:20:47'),(12,4,3,12,1,1,'OnePlus 12',107,15,400,'2026-06-02','Aisle E-1','2026-06-26 00:20:47','2026-06-26 00:20:47'),(13,5,1,2,1,2,'MacBook Air M3',105,15,400,'2026-06-09','Aisle B-1','2026-06-26 00:20:47','2026-06-26 00:20:47'),(14,5,2,2,1,2,'MacBook Air M3',147,15,400,'2026-06-09','Aisle B-2','2026-06-26 00:20:47','2026-06-26 00:20:47'),(15,5,3,2,1,2,'MacBook Air M3',42,15,400,'2026-06-06','Aisle E-5','2026-06-26 00:20:47','2026-06-26 00:20:47'),(16,6,1,6,1,2,'Dell XPS 13',114,15,400,'2026-05-30','Aisle F-7','2026-06-26 00:20:47','2026-06-26 00:20:47'),(17,6,2,6,1,2,'Dell XPS 13',94,15,400,'2026-06-20','Aisle A-3','2026-06-26 00:20:47','2026-06-26 00:20:47'),(18,6,3,6,1,2,'Dell XPS 13',69,15,400,'2026-06-22','Aisle E-10','2026-06-26 00:20:47','2026-06-26 00:20:47'),(19,7,1,7,1,2,'HP Spectre x360',59,15,400,'2026-06-12','Aisle E-7','2026-06-26 00:20:47','2026-06-26 00:20:47'),(20,7,2,7,1,2,'HP Spectre x360',24,15,400,'2026-06-14','Aisle A-8','2026-06-26 00:20:47','2026-06-26 00:22:09'),(21,7,3,7,1,2,'HP Spectre x360',128,15,400,'2026-06-22','Aisle A-8','2026-06-26 00:20:47','2026-06-26 00:20:47'),(22,8,1,13,1,2,'Lenovo ThinkPad X1',92,15,400,'2026-06-02','Aisle B-1','2026-06-26 00:20:47','2026-06-26 00:20:47'),(23,8,2,13,1,2,'Lenovo ThinkPad X1',70,15,400,'2026-05-27','Aisle B-7','2026-06-26 00:20:48','2026-06-26 00:20:48'),(24,8,3,13,1,2,'Lenovo ThinkPad X1',68,15,400,'2026-06-20','Aisle B-2','2026-06-26 00:20:48','2026-06-26 00:20:48'),(25,9,1,3,1,3,'Sony WH-1000XM5',125,15,400,'2026-06-07','Aisle A-1','2026-06-26 00:20:48','2026-06-26 00:20:48'),(26,9,2,3,1,3,'Sony WH-1000XM5',97,15,400,'2026-06-06','Aisle C-9','2026-06-26 00:20:48','2026-06-26 00:20:48'),(27,9,3,3,1,3,'Sony WH-1000XM5',120,15,400,'2026-06-18','Aisle A-5','2026-06-26 00:20:48','2026-06-26 00:20:48'),(28,10,1,14,1,3,'Bose QuietComfort Ultra',99,15,400,'2026-05-27','Aisle E-8','2026-06-26 00:20:48','2026-06-26 00:20:48'),(29,10,2,14,1,3,'Bose QuietComfort Ultra',30,15,400,'2026-05-30','Aisle F-9','2026-06-26 00:20:48','2026-06-26 00:20:48'),(30,10,3,14,1,3,'Bose QuietComfort Ultra',90,15,400,'2026-06-09','Aisle A-2','2026-06-26 00:20:48','2026-06-26 00:20:48'),(31,11,1,2,1,3,'Apple AirPods Pro',26,15,400,'2026-05-28','Aisle F-5','2026-06-26 00:20:48','2026-06-26 00:20:48'),(32,11,2,2,1,3,'Apple AirPods Pro',90,15,400,'2026-06-03','Aisle F-8','2026-06-26 00:20:48','2026-06-26 00:22:09'),(33,11,3,2,1,3,'Apple AirPods Pro',97,15,400,'2026-06-17','Aisle F-10','2026-06-26 00:20:48','2026-06-26 00:20:48'),(34,12,1,15,1,3,'Sennheiser Momentum 4',52,15,400,'2026-06-24','Aisle C-3','2026-06-26 00:20:48','2026-06-26 00:20:48'),(35,12,2,15,1,3,'Sennheiser Momentum 4',84,15,400,'2026-06-10','Aisle D-4','2026-06-26 00:20:48','2026-06-26 00:20:48'),(36,12,3,15,1,3,'Sennheiser Momentum 4',65,15,400,'2026-05-29','Aisle A-4','2026-06-26 00:20:48','2026-06-26 00:20:48'),(37,13,1,16,1,4,'Anker PowerCore 24K',27,15,400,'2026-06-01','Aisle D-2','2026-06-26 00:20:48','2026-06-26 00:20:48'),(38,13,2,16,1,4,'Anker PowerCore 24K',65,15,400,'2026-06-07','Aisle F-4','2026-06-26 00:20:48','2026-06-26 00:20:48'),(39,13,3,16,1,4,'Anker PowerCore 24K',50,15,400,'2026-05-27','Aisle F-3','2026-06-26 00:20:48','2026-06-26 00:20:48'),(40,14,1,8,1,4,'Logitech MX Master 3S',71,15,400,'2026-06-10','Aisle B-5','2026-06-26 00:20:49','2026-06-26 00:20:49'),(41,14,2,8,1,4,'Logitech MX Master 3S',143,15,400,'2026-05-30','Aisle A-1','2026-06-26 00:20:49','2026-06-26 00:20:49'),(42,14,3,8,1,4,'Logitech MX Master 3S',43,15,400,'2026-06-10','Aisle C-8','2026-06-26 00:20:49','2026-06-26 00:20:49'),(43,15,1,2,1,4,'Apple Magic Keyboard',80,15,400,'2026-06-22','Aisle D-4','2026-06-26 00:20:49','2026-06-26 00:20:49'),(44,15,2,2,1,4,'Apple Magic Keyboard',110,15,400,'2026-06-03','Aisle C-6','2026-06-26 00:20:49','2026-06-26 00:20:49'),(45,15,3,2,1,4,'Apple Magic Keyboard',22,15,400,'2026-06-09','Aisle B-4','2026-06-26 00:20:49','2026-06-26 00:20:49'),(46,16,1,1,1,4,'Samsung T7 Shield SSD',52,15,400,'2026-06-06','Aisle D-8','2026-06-26 00:20:49','2026-06-26 00:20:49'),(47,16,2,1,1,4,'Samsung T7 Shield SSD',116,15,400,'2026-06-18','Aisle F-10','2026-06-26 00:20:49','2026-06-26 00:20:49'),(48,16,3,1,1,4,'Samsung T7 Shield SSD',89,15,400,'2026-06-15','Aisle D-7','2026-06-26 00:20:49','2026-06-26 00:20:49'),(49,17,1,17,1,5,'Amazon Echo Dot',23,15,400,'2026-06-17','Aisle E-3','2026-06-26 00:20:49','2026-06-26 00:20:49'),(50,17,2,17,1,5,'Amazon Echo Dot',104,15,400,'2026-06-08','Aisle F-5','2026-06-26 00:20:49','2026-06-26 00:20:49'),(51,17,3,17,1,5,'Amazon Echo Dot',47,15,400,'2026-06-04','Aisle F-7','2026-06-26 00:20:49','2026-06-26 00:20:49'),(52,18,1,11,1,5,'Google Nest Hub',32,15,400,'2026-06-21','Aisle F-2','2026-06-26 00:20:49','2026-06-26 00:20:49'),(53,18,2,11,1,5,'Google Nest Hub',68,15,400,'2026-06-19','Aisle A-10','2026-06-26 00:20:50','2026-06-26 00:20:50'),(54,18,3,11,1,5,'Google Nest Hub',126,15,400,'2026-06-21','Aisle F-8','2026-06-26 00:20:50','2026-06-26 00:20:50'),(55,19,1,18,1,5,'Philips Hue Starter Kit',138,15,400,'2026-06-13','Aisle F-10','2026-06-26 00:20:50','2026-06-26 00:20:50'),(56,19,2,18,1,5,'Philips Hue Starter Kit',23,15,400,'2026-05-28','Aisle B-6','2026-06-26 00:20:50','2026-06-26 00:20:50'),(57,19,3,18,1,5,'Philips Hue Starter Kit',121,15,400,'2026-06-22','Aisle F-10','2026-06-26 00:20:50','2026-06-26 00:20:50'),(58,20,1,19,1,5,'Ring Video Doorbell',47,15,400,'2026-06-14','Aisle F-4','2026-06-26 00:20:50','2026-06-26 00:20:50'),(59,20,2,19,1,5,'Ring Video Doorbell',113,15,400,'2026-06-09','Aisle C-1','2026-06-26 00:20:50','2026-06-26 00:20:50'),(60,20,3,19,1,5,'Ring Video Doorbell',32,15,400,'2026-06-14','Aisle D-9','2026-06-26 00:20:50','2026-06-26 00:20:50'),(61,21,1,4,2,6,'Nike Dri-FIT Tee',63,15,400,'2026-06-18','Aisle C-8','2026-06-26 00:20:50','2026-06-26 00:20:50'),(62,21,2,4,2,6,'Nike Dri-FIT Tee',112,15,400,'2026-06-17','Aisle F-9','2026-06-26 00:20:50','2026-06-26 00:20:50'),(63,21,3,4,2,6,'Nike Dri-FIT Tee',37,15,400,'2026-06-15','Aisle B-10','2026-06-26 00:20:50','2026-06-26 00:20:50'),(64,22,1,5,2,6,'Adidas Originals Tee',56,15,400,'2026-06-24','Aisle D-9','2026-06-26 00:20:50','2026-06-26 00:20:50'),(65,22,2,5,2,6,'Adidas Originals Tee',138,15,400,'2026-06-03','Aisle C-3','2026-06-26 00:20:50','2026-06-26 00:20:50'),(66,22,3,5,2,6,'Adidas Originals Tee',23,15,400,'2026-05-31','Aisle D-9','2026-06-26 00:20:51','2026-06-26 00:20:51'),(67,23,1,20,2,6,'Puma Classic Tee',113,15,400,'2026-06-20','Aisle E-2','2026-06-26 00:20:51','2026-06-26 00:20:51'),(68,23,2,20,2,6,'Puma Classic Tee',58,15,400,'2026-06-19','Aisle D-8','2026-06-26 00:20:51','2026-06-26 00:20:51'),(69,23,3,20,2,6,'Puma Classic Tee',26,15,400,'2026-06-11','Aisle E-6','2026-06-26 00:20:51','2026-06-26 00:20:51'),(70,24,1,21,2,6,'Under Armour Sport Tee',77,15,400,'2026-06-04','Aisle A-5','2026-06-26 00:20:51','2026-06-26 00:20:51'),(71,24,2,21,2,6,'Under Armour Sport Tee',118,15,400,'2026-06-06','Aisle A-7','2026-06-26 00:20:51','2026-06-26 00:22:08'),(72,24,3,21,2,6,'Under Armour Sport Tee',142,15,400,'2026-06-09','Aisle E-2','2026-06-26 00:20:51','2026-06-26 00:20:51'),(73,25,1,22,2,7,'Levis 501 Original',133,15,400,'2026-06-14','Aisle C-5','2026-06-26 00:20:51','2026-06-26 00:20:51'),(74,25,2,22,2,7,'Levis 501 Original',35,15,400,'2026-06-11','Aisle A-7','2026-06-26 00:20:51','2026-06-26 00:20:51'),(75,25,3,22,2,7,'Levis 501 Original',60,15,400,'2026-06-01','Aisle F-10','2026-06-26 00:20:51','2026-06-26 00:20:51'),(76,26,1,23,2,7,'Wrangler Authentic Jeans',124,15,400,'2026-05-27','Aisle A-10','2026-06-26 00:20:51','2026-06-26 00:20:51'),(77,26,2,23,2,7,'Wrangler Authentic Jeans',106,15,400,'2026-06-17','Aisle F-4','2026-06-26 00:20:51','2026-06-26 00:20:51'),(78,26,3,23,2,7,'Wrangler Authentic Jeans',73,15,400,'2026-05-28','Aisle D-1','2026-06-26 00:20:51','2026-06-26 00:20:51'),(79,27,1,24,2,7,'Lee Rider Slim Jeans',149,15,400,'2026-06-10','Aisle F-6','2026-06-26 00:20:51','2026-06-26 00:20:51'),(80,27,2,24,2,7,'Lee Rider Slim Jeans',34,15,400,'2026-06-16','Aisle D-5','2026-06-26 00:20:51','2026-06-26 00:20:51'),(81,27,3,24,2,7,'Lee Rider Slim Jeans',55,15,400,'2026-06-18','Aisle E-1','2026-06-26 00:20:52','2026-06-26 00:22:10'),(82,28,1,25,2,7,'Diesel D-Strukt Jeans',125,15,400,'2026-05-31','Aisle B-8','2026-06-26 00:20:52','2026-06-26 00:20:52'),(83,28,2,25,2,7,'Diesel D-Strukt Jeans',56,15,400,'2026-06-12','Aisle E-1','2026-06-26 00:20:52','2026-06-26 00:20:52'),(84,28,3,25,2,7,'Diesel D-Strukt Jeans',40,15,400,'2026-06-06','Aisle B-5','2026-06-26 00:20:52','2026-06-26 00:20:52'),(85,29,1,26,2,8,'Patagonia Torrentshell',135,15,400,'2026-06-03','Aisle C-1','2026-06-26 00:20:52','2026-06-26 00:20:52'),(86,29,2,26,2,8,'Patagonia Torrentshell',93,15,400,'2026-06-03','Aisle D-7','2026-06-26 00:20:52','2026-06-26 00:20:52'),(87,29,3,26,2,8,'Patagonia Torrentshell',51,15,400,'2026-06-14','Aisle E-6','2026-06-26 00:20:52','2026-06-26 00:20:52'),(88,30,1,27,2,8,'The North Face Resolve',146,15,400,'2026-06-10','Aisle C-2','2026-06-26 00:20:52','2026-06-26 00:20:52'),(89,30,2,27,2,8,'The North Face Resolve',152,15,400,'2026-06-01','Aisle F-5','2026-06-26 00:20:52','2026-06-26 00:22:09'),(90,30,3,27,2,8,'The North Face Resolve',20,15,400,'2026-06-02','Aisle A-8','2026-06-26 00:20:52','2026-06-26 00:20:52'),(91,31,1,28,2,8,'Columbia Watertight',108,15,400,'2026-06-07','Aisle B-3','2026-06-26 00:20:52','2026-06-26 00:20:52'),(92,31,2,28,2,8,'Columbia Watertight',50,15,400,'2026-06-12','Aisle E-1','2026-06-26 00:20:52','2026-06-26 00:20:52'),(93,31,3,28,2,8,'Columbia Watertight',71,15,400,'2026-06-22','Aisle F-9','2026-06-26 00:20:52','2026-06-26 00:20:52'),(94,32,1,29,2,8,'Arc`teryx Beta LT',147,15,400,'2026-05-31','Aisle F-5','2026-06-26 00:20:52','2026-06-26 00:20:52'),(95,32,2,29,2,8,'Arc`teryx Beta LT',49,15,400,'2026-06-06','Aisle C-2','2026-06-26 00:20:52','2026-06-26 00:20:52'),(96,32,3,29,2,8,'Arc`teryx Beta LT',46,15,400,'2026-06-17','Aisle C-9','2026-06-26 00:20:52','2026-06-26 00:20:52'),(97,33,1,30,2,9,'Darn Tough Cushion',78,15,400,'2026-05-31','Aisle D-9','2026-06-26 00:20:53','2026-06-26 00:20:53'),(98,33,2,30,2,9,'Darn Tough Cushion',92,15,400,'2026-06-03','Aisle B-3','2026-06-26 00:20:53','2026-06-26 00:20:53'),(99,33,3,30,2,9,'Darn Tough Cushion',69,15,400,'2026-06-07','Aisle C-5','2026-06-26 00:20:53','2026-06-26 00:20:53'),(100,34,1,31,2,9,'Smartwool Run Socks',72,15,400,'2026-06-21','Aisle D-3','2026-06-26 00:20:53','2026-06-26 00:20:53'),(101,34,2,31,2,9,'Smartwool Run Socks',108,15,400,'2026-06-17','Aisle A-6','2026-06-26 00:20:53','2026-06-26 00:20:53'),(102,34,3,31,2,9,'Smartwool Run Socks',107,15,400,'2026-06-17','Aisle F-6','2026-06-26 00:20:53','2026-06-26 00:20:53'),(103,35,1,4,2,9,'Nike Everyday Cushion',92,15,400,'2026-05-31','Aisle D-10','2026-06-26 00:20:53','2026-06-26 00:20:53'),(104,35,2,4,2,9,'Nike Everyday Cushion',133,15,400,'2026-06-02','Aisle B-10','2026-06-26 00:20:53','2026-06-26 00:20:53'),(105,35,3,4,2,9,'Nike Everyday Cushion',76,15,400,'2026-06-11','Aisle F-7','2026-06-26 00:20:53','2026-06-26 00:20:53'),(106,36,1,5,2,9,'Adidas Athletic Socks',136,15,400,'2026-06-21','Aisle C-10','2026-06-26 00:20:53','2026-06-26 00:20:53'),(107,36,2,5,2,9,'Adidas Athletic Socks',127,15,400,'2026-06-05','Aisle D-9','2026-06-26 00:20:53','2026-06-26 00:20:53'),(108,36,3,5,2,9,'Adidas Athletic Socks',95,15,400,'2026-06-02','Aisle D-2','2026-06-26 00:20:54','2026-06-26 00:20:54'),(109,37,1,32,2,10,'New Era 59FIFTY Cap',70,15,400,'2026-06-01','Aisle D-9','2026-06-26 00:20:54','2026-06-26 00:20:54'),(110,37,2,32,2,10,'New Era 59FIFTY Cap',33,15,400,'2026-06-13','Aisle C-6','2026-06-26 00:20:54','2026-06-26 00:20:54'),(111,37,3,32,2,10,'New Era 59FIFTY Cap',91,15,400,'2026-06-05','Aisle D-8','2026-06-26 00:20:54','2026-06-26 00:20:54'),(112,38,1,4,2,10,'Nike Legacy91 Cap',22,15,400,'2026-06-06','Aisle B-4','2026-06-26 00:20:54','2026-06-26 00:20:54'),(113,38,2,4,2,10,'Nike Legacy91 Cap',67,15,400,'2026-05-27','Aisle E-6','2026-06-26 00:20:54','2026-06-26 00:20:54'),(114,38,3,4,2,10,'Nike Legacy91 Cap',33,15,400,'2026-06-04','Aisle C-2','2026-06-26 00:20:54','2026-06-26 00:20:54'),(115,39,1,5,2,10,'Adidas Superlite Cap',145,15,400,'2026-06-03','Aisle F-4','2026-06-26 00:20:54','2026-06-26 00:20:54'),(116,39,2,5,2,10,'Adidas Superlite Cap',21,15,400,'2026-06-23','Aisle D-6','2026-06-26 00:20:54','2026-06-26 00:20:54'),(117,39,3,5,2,10,'Adidas Superlite Cap',21,15,400,'2026-06-25','Aisle B-3','2026-06-26 00:20:54','2026-06-26 00:20:54'),(118,40,1,33,2,10,'Carhartt Beanie',124,15,400,'2026-06-07','Aisle F-9','2026-06-26 00:20:54','2026-06-26 00:20:54'),(119,40,2,33,2,10,'Carhartt Beanie',40,15,400,'2026-05-31','Aisle D-3','2026-06-26 00:20:54','2026-06-26 00:20:54'),(120,40,3,33,2,10,'Carhartt Beanie',92,15,400,'2026-06-12','Aisle D-1','2026-06-26 00:20:54','2026-06-26 00:20:54'),(121,41,1,34,3,11,'Keurig K-Elite',37,15,400,'2026-06-25','Aisle E-10','2026-06-26 00:20:55','2026-06-26 00:22:12'),(122,41,2,34,3,11,'Keurig K-Elite',24,15,400,'2026-06-01','Aisle D-9','2026-06-26 00:20:55','2026-06-26 00:20:55'),(123,41,3,34,3,11,'Keurig K-Elite',119,15,400,'2026-06-22','Aisle D-7','2026-06-26 00:20:55','2026-06-26 00:20:55'),(124,42,1,35,3,11,'Nespresso Vertuo Next',59,15,400,'2026-06-04','Aisle B-10','2026-06-26 00:20:55','2026-06-26 00:20:55'),(125,42,2,35,3,11,'Nespresso Vertuo Next',135,15,400,'2026-06-19','Aisle F-2','2026-06-26 00:20:55','2026-06-26 00:20:55'),(126,42,3,35,3,11,'Nespresso Vertuo Next',90,15,400,'2026-06-08','Aisle C-2','2026-06-26 00:20:55','2026-06-26 00:20:55'),(127,43,1,10,3,11,'Breville Barista',49,15,400,'2026-06-18','Aisle A-5','2026-06-26 00:20:55','2026-06-26 00:20:55'),(128,43,2,10,3,11,'Breville Barista',143,15,400,'2026-06-06','Aisle B-10','2026-06-26 00:20:55','2026-06-26 00:20:55'),(129,43,3,10,3,11,'Breville Barista',80,15,400,'2026-05-27','Aisle D-10','2026-06-26 00:20:56','2026-06-26 00:20:56'),(130,44,1,36,3,11,'Hamilton FlexBrew',73,15,400,'2026-06-10','Aisle B-2','2026-06-26 00:20:56','2026-06-26 00:20:56'),(131,44,2,36,3,11,'Hamilton FlexBrew',157,15,400,'2026-06-05','Aisle B-2','2026-06-26 00:20:56','2026-06-26 00:22:11'),(132,44,3,36,3,11,'Hamilton FlexBrew',135,15,400,'2026-06-02','Aisle F-3','2026-06-26 00:20:56','2026-06-26 00:20:56'),(133,45,1,37,3,12,'Toshiba EM131A5C',37,15,400,'2026-06-16','Aisle C-1','2026-06-26 00:20:56','2026-06-26 00:20:56'),(134,45,2,37,3,12,'Toshiba EM131A5C',76,15,400,'2026-06-22','Aisle D-5','2026-06-26 00:20:56','2026-06-26 00:20:56'),(135,45,3,37,3,12,'Toshiba EM131A5C',135,15,400,'2026-06-02','Aisle E-9','2026-06-26 00:20:56','2026-06-26 00:20:56'),(136,46,1,38,3,12,'Panasonic NN-SN686S',73,15,400,'2026-05-27','Aisle F-1','2026-06-26 00:20:56','2026-06-26 00:20:56'),(137,46,2,38,3,12,'Panasonic NN-SN686S',45,15,400,'2026-06-07','Aisle A-10','2026-06-26 00:20:56','2026-06-26 00:20:56'),(138,46,3,38,3,12,'Panasonic NN-SN686S',69,15,400,'2026-05-27','Aisle F-9','2026-06-26 00:20:57','2026-06-26 00:20:57'),(139,47,1,39,3,12,'Black+Decker EM720',147,15,400,'2026-06-19','Aisle B-5','2026-06-26 00:20:57','2026-06-26 00:20:57'),(140,47,2,39,3,12,'Black+Decker EM720',126,15,400,'2026-06-18','Aisle E-5','2026-06-26 00:20:57','2026-06-26 00:22:09'),(141,47,3,39,3,12,'Black+Decker EM720',44,15,400,'2026-06-15','Aisle E-5','2026-06-26 00:20:57','2026-06-26 00:20:57'),(142,48,1,1,3,12,'Samsung MS19M',145,15,400,'2026-06-11','Aisle E-8','2026-06-26 00:20:57','2026-06-26 00:20:57'),(143,48,2,1,3,12,'Samsung MS19M',54,15,400,'2026-06-21','Aisle E-2','2026-06-26 00:20:57','2026-06-26 00:20:57'),(144,48,3,1,3,12,'Samsung MS19M',29,15,400,'2026-06-11','Aisle F-7','2026-06-26 00:20:57','2026-06-26 00:20:57'),(145,49,1,40,3,13,'Vitamix 5200',37,15,400,'2026-06-11','Aisle C-9','2026-06-26 00:20:57','2026-06-26 00:20:57'),(146,49,2,40,3,13,'Vitamix 5200',113,15,400,'2026-06-21','Aisle E-10','2026-06-26 00:20:57','2026-06-26 00:20:57'),(147,49,3,40,3,13,'Vitamix 5200',31,15,400,'2026-06-15','Aisle C-8','2026-06-26 00:20:57','2026-06-26 00:20:57'),(148,50,1,9,3,13,'Ninja Professional',55,15,400,'2026-06-06','Aisle F-9','2026-06-26 00:20:57','2026-06-26 00:20:57'),(149,50,2,9,3,13,'Ninja Professional',142,15,400,'2026-06-17','Aisle D-1','2026-06-26 00:20:57','2026-06-26 00:20:57'),(150,50,3,9,3,13,'Ninja Professional',95,15,400,'2026-06-25','Aisle C-9','2026-06-26 00:20:57','2026-06-26 00:20:57'),(151,51,1,41,3,13,'NutriBullet Pro',79,15,400,'2026-06-12','Aisle F-2','2026-06-26 00:20:58','2026-06-26 00:20:58'),(152,51,2,41,3,13,'NutriBullet Pro',150,15,400,'2026-06-08','Aisle A-10','2026-06-26 00:20:58','2026-06-26 00:20:58'),(153,51,3,41,3,13,'NutriBullet Pro',61,15,400,'2026-06-15','Aisle D-4','2026-06-26 00:20:58','2026-06-26 00:20:58'),(154,52,1,42,3,13,'Magic Bullet Blender',109,15,400,'2026-06-05','Aisle A-5','2026-06-26 00:20:58','2026-06-26 00:20:58'),(155,52,2,42,3,13,'Magic Bullet Blender',109,15,400,'2026-06-08','Aisle B-4','2026-06-26 00:20:58','2026-06-26 00:20:58'),(156,52,3,42,3,13,'Magic Bullet Blender',111,15,400,'2026-06-15','Aisle B-2','2026-06-26 00:20:58','2026-06-26 00:20:58'),(157,53,1,10,3,14,'Breville Bit More',64,15,400,'2026-06-08','Aisle E-6','2026-06-26 00:20:58','2026-06-26 00:20:58'),(158,53,2,10,3,14,'Breville Bit More',118,15,400,'2026-06-20','Aisle E-5','2026-06-26 00:20:58','2026-06-26 00:20:58'),(159,53,3,10,3,14,'Breville Bit More',78,15,400,'2026-06-11','Aisle D-2','2026-06-26 00:20:58','2026-06-26 00:20:58'),(160,54,1,43,3,14,'Cuisinart CPT-180',86,15,400,'2026-06-19','Aisle D-10','2026-06-26 00:20:59','2026-06-26 00:20:59'),(161,54,2,43,3,14,'Cuisinart CPT-180',81,15,400,'2026-05-28','Aisle A-3','2026-06-26 00:20:59','2026-06-26 00:22:11'),(162,54,3,43,3,14,'Cuisinart CPT-180',79,15,400,'2026-06-18','Aisle D-7','2026-06-26 00:20:59','2026-06-26 00:20:59'),(163,55,1,44,3,14,'KitchenAid 2-Slice',70,15,400,'2026-06-05','Aisle C-1','2026-06-26 00:20:59','2026-06-26 00:20:59'),(164,55,2,44,3,14,'KitchenAid 2-Slice',35,15,400,'2026-06-02','Aisle E-6','2026-06-26 00:20:59','2026-06-26 00:20:59'),(165,55,3,44,3,14,'KitchenAid 2-Slice',31,15,400,'2026-05-30','Aisle E-9','2026-06-26 00:20:59','2026-06-26 00:20:59'),(166,56,1,45,3,14,'Oster 4-Slice Toaster',83,15,400,'2026-06-11','Aisle C-10','2026-06-26 00:20:59','2026-06-26 00:20:59'),(167,56,2,45,3,14,'Oster 4-Slice Toaster',132,15,400,'2026-06-23','Aisle B-8','2026-06-26 00:20:59','2026-06-26 00:20:59'),(168,56,3,45,3,14,'Oster 4-Slice Toaster',84,15,400,'2026-05-28','Aisle D-8','2026-06-26 00:20:59','2026-06-26 00:20:59'),(169,57,1,46,3,15,'Cosori Pro II',22,15,400,'2026-06-15','Aisle B-2','2026-06-26 00:20:59','2026-06-26 00:20:59'),(170,57,2,46,3,15,'Cosori Pro II',24,15,400,'2026-06-07','Aisle E-1','2026-06-26 00:20:59','2026-06-26 00:20:59'),(171,57,3,46,3,15,'Cosori Pro II',83,15,400,'2026-06-09','Aisle B-4','2026-06-26 00:20:59','2026-06-26 00:22:12'),(172,58,1,9,3,15,'Ninja AF101',105,15,400,'2026-06-11','Aisle F-6','2026-06-26 00:20:59','2026-06-26 00:20:59'),(173,58,2,9,3,15,'Ninja AF101',20,15,400,'2026-06-15','Aisle E-8','2026-06-26 00:20:59','2026-06-26 00:20:59'),(174,58,3,9,3,15,'Ninja AF101',147,15,400,'2026-06-21','Aisle E-9','2026-06-26 00:20:59','2026-06-26 00:20:59'),(175,59,1,47,3,15,'Instant Vortex Plus',58,15,400,'2026-05-27','Aisle B-2','2026-06-26 00:20:59','2026-06-26 00:20:59'),(176,59,2,47,3,15,'Instant Vortex Plus',122,15,400,'2026-06-20','Aisle E-8','2026-06-26 00:20:59','2026-06-26 00:20:59'),(177,59,3,47,3,15,'Instant Vortex Plus',123,15,400,'2026-06-17','Aisle D-2','2026-06-26 00:20:59','2026-06-26 00:20:59'),(178,60,1,18,3,15,'Philips Essential Air',67,15,400,'2026-06-23','Aisle F-8','2026-06-26 00:21:00','2026-06-26 00:21:00'),(179,60,2,18,3,15,'Philips Essential Air',104,15,400,'2026-06-04','Aisle F-10','2026-06-26 00:21:00','2026-06-26 00:22:12'),(180,60,3,18,3,15,'Philips Essential Air',138,15,400,'2026-06-03','Aisle C-4','2026-06-26 00:21:00','2026-06-26 00:21:00'),(181,61,1,48,4,16,'Wilson NFL Duke',120,15,400,'2026-06-04','Aisle D-1','2026-06-26 00:21:00','2026-06-26 00:22:08'),(182,61,2,48,4,16,'Wilson NFL Duke',28,15,400,'2026-06-17','Aisle C-5','2026-06-26 00:21:00','2026-06-26 00:21:00'),(183,61,3,48,4,16,'Wilson NFL Duke',94,15,400,'2026-06-15','Aisle D-9','2026-06-26 00:21:00','2026-06-26 00:21:00'),(184,62,1,5,4,16,'Adidas Al Rihla',79,15,400,'2026-06-11','Aisle B-1','2026-06-26 00:21:00','2026-06-26 00:21:00'),(185,62,2,5,4,16,'Adidas Al Rihla',141,15,400,'2026-06-05','Aisle A-10','2026-06-26 00:21:00','2026-06-26 00:21:00'),(186,62,3,5,4,16,'Adidas Al Rihla',108,15,400,'2026-06-02','Aisle C-9','2026-06-26 00:21:00','2026-06-26 00:21:00'),(187,63,1,4,4,16,'Nike Strike Ball',83,15,400,'2026-06-10','Aisle B-2','2026-06-26 00:21:00','2026-06-26 00:21:00'),(188,63,2,4,4,16,'Nike Strike Ball',74,15,400,'2026-06-21','Aisle B-2','2026-06-26 00:21:00','2026-06-26 00:21:00'),(189,63,3,4,4,16,'Nike Strike Ball',45,15,400,'2026-06-14','Aisle B-8','2026-06-26 00:21:00','2026-06-26 00:21:00'),(190,64,1,49,4,16,'Spalding TF-1000 Ball',90,15,400,'2026-06-08','Aisle F-8','2026-06-26 00:21:00','2026-06-26 00:21:00'),(191,64,2,49,4,16,'Spalding TF-1000 Ball',55,15,400,'2026-06-10','Aisle A-9','2026-06-26 00:21:00','2026-06-26 00:21:00'),(192,64,3,49,4,16,'Spalding TF-1000 Ball',145,15,400,'2026-06-17','Aisle C-4','2026-06-26 00:21:00','2026-06-26 00:21:00'),(193,65,1,50,4,17,'Bowflex SelectTech',107,15,400,'2026-06-14','Aisle D-2','2026-06-26 00:21:00','2026-06-26 00:21:00'),(194,65,2,50,4,17,'Bowflex SelectTech',63,15,400,'2026-06-20','Aisle B-10','2026-06-26 00:21:00','2026-06-26 00:21:00'),(195,65,3,50,4,17,'Bowflex SelectTech',39,15,400,'2026-06-04','Aisle D-8','2026-06-26 00:21:00','2026-06-26 00:21:00'),(196,66,1,51,4,17,'CAP Cast Iron Dumbbell',95,15,400,'2026-06-07','Aisle D-5','2026-06-26 00:21:00','2026-06-26 00:21:00'),(197,66,2,51,4,17,'CAP Cast Iron Dumbbell',49,15,400,'2026-06-25','Aisle D-7','2026-06-26 00:21:00','2026-06-26 00:21:00'),(198,66,3,51,4,17,'CAP Cast Iron Dumbbell',79,15,400,'2026-06-19','Aisle D-6','2026-06-26 00:21:01','2026-06-26 00:21:01'),(199,67,1,52,4,17,'Amazon Basics Neoprene',46,15,400,'2026-06-15','Aisle E-4','2026-06-26 00:21:01','2026-06-26 00:21:01'),(200,67,2,52,4,17,'Amazon Basics Neoprene',49,15,400,'2026-06-25','Aisle C-6','2026-06-26 00:21:01','2026-06-26 00:21:01'),(201,67,3,52,4,17,'Amazon Basics Neoprene',66,15,400,'2026-06-02','Aisle B-9','2026-06-26 00:21:01','2026-06-26 00:21:01'),(202,68,1,53,4,17,'Rogue Rubber Hex',67,15,400,'2026-05-31','Aisle C-1','2026-06-26 00:21:01','2026-06-26 00:21:01'),(203,68,2,53,4,17,'Rogue Rubber Hex',56,15,400,'2026-06-21','Aisle E-2','2026-06-26 00:21:01','2026-06-26 00:21:01'),(204,68,3,53,4,17,'Rogue Rubber Hex',49,15,400,'2026-05-27','Aisle E-9','2026-06-26 00:21:01','2026-06-26 00:21:01'),(205,69,1,54,4,18,'Lululemon 5mm Mat',55,15,400,'2026-06-09','Aisle D-8','2026-06-26 00:21:01','2026-06-26 00:22:09'),(206,69,2,54,4,18,'Lululemon 5mm Mat',65,15,400,'2026-06-01','Aisle D-7','2026-06-26 00:21:01','2026-06-26 00:21:01'),(207,69,3,54,4,18,'Lululemon 5mm Mat',89,15,400,'2026-06-09','Aisle F-10','2026-06-26 00:21:01','2026-06-26 00:21:01'),(208,70,1,55,4,18,'Manduka PRO Mat',146,15,400,'2026-06-02','Aisle C-9','2026-06-26 00:21:01','2026-06-26 00:21:01'),(209,70,2,55,4,18,'Manduka PRO Mat',25,15,400,'2026-06-07','Aisle F-9','2026-06-26 00:21:01','2026-06-26 00:21:01'),(210,70,3,55,4,18,'Manduka PRO Mat',100,15,400,'2026-06-04','Aisle F-5','2026-06-26 00:21:01','2026-06-26 00:21:01'),(211,71,1,56,4,18,'Gaiam Essentials Mat',135,15,400,'2026-06-03','Aisle F-10','2026-06-26 00:21:01','2026-06-26 00:21:01'),(212,71,2,56,4,18,'Gaiam Essentials Mat',134,15,400,'2026-06-07','Aisle A-3','2026-06-26 00:21:02','2026-06-26 00:21:02'),(213,71,3,56,4,18,'Gaiam Essentials Mat',102,15,400,'2026-06-03','Aisle E-2','2026-06-26 00:21:02','2026-06-26 00:21:02'),(214,72,1,57,4,18,'Jade Harmony Mat',56,15,400,'2026-06-19','Aisle C-8','2026-06-26 00:21:02','2026-06-26 00:21:02'),(215,72,2,57,4,18,'Jade Harmony Mat',94,15,400,'2026-06-22','Aisle A-8','2026-06-26 00:21:02','2026-06-26 00:21:02'),(216,72,3,57,4,18,'Jade Harmony Mat',121,15,400,'2026-06-16','Aisle D-3','2026-06-26 00:21:02','2026-06-26 00:21:02'),(217,73,1,58,4,19,'Hydro Flask 32oz',95,15,400,'2026-06-21','Aisle A-6','2026-06-26 00:21:02','2026-06-26 00:21:02'),(218,73,2,58,4,19,'Hydro Flask 32oz',93,15,400,'2026-06-14','Aisle F-5','2026-06-26 00:21:02','2026-06-26 00:21:02'),(219,73,3,58,4,19,'Hydro Flask 32oz',124,15,400,'2026-06-06','Aisle F-3','2026-06-26 00:21:02','2026-06-26 00:21:02'),(220,74,1,59,4,19,'Yeti Rambler 26oz',138,15,400,'2026-05-31','Aisle B-7','2026-06-26 00:21:02','2026-06-26 00:21:02'),(221,74,2,59,4,19,'Yeti Rambler 26oz',132,15,400,'2026-05-31','Aisle F-10','2026-06-26 00:21:02','2026-06-26 00:21:02'),(222,74,3,59,4,19,'Yeti Rambler 26oz',27,15,400,'2026-05-31','Aisle E-7','2026-06-26 00:21:02','2026-06-26 00:21:02'),(223,75,1,60,4,19,'CamelBak Chute Mag',134,15,400,'2026-06-18','Aisle D-9','2026-06-26 00:21:02','2026-06-26 00:21:02'),(224,75,2,60,4,19,'CamelBak Chute Mag',22,15,400,'2026-06-19','Aisle B-8','2026-06-26 00:21:03','2026-06-26 00:21:03'),(225,75,3,60,4,19,'CamelBak Chute Mag',125,15,400,'2026-06-01','Aisle E-7','2026-06-26 00:21:03','2026-06-26 00:21:03'),(226,76,1,61,4,19,'Nalgene Wide Mouth',54,15,400,'2026-06-11','Aisle A-9','2026-06-26 00:21:03','2026-06-26 00:22:10'),(227,76,2,61,4,19,'Nalgene Wide Mouth',88,15,400,'2026-06-14','Aisle D-5','2026-06-26 00:21:03','2026-06-26 00:21:03'),(228,76,3,61,4,19,'Nalgene Wide Mouth',37,15,400,'2026-06-04','Aisle D-2','2026-06-26 00:21:03','2026-06-26 00:21:03'),(229,77,1,4,4,20,'Nike Air Zoom Pegasus',39,15,400,'2026-06-09','Aisle A-4','2026-06-26 00:21:03','2026-06-26 00:21:03'),(230,77,2,4,4,20,'Nike Air Zoom Pegasus',45,15,400,'2026-06-15','Aisle A-10','2026-06-26 00:21:03','2026-06-26 00:21:03'),(231,77,3,4,4,20,'Nike Air Zoom Pegasus',44,15,400,'2026-06-13','Aisle E-7','2026-06-26 00:21:03','2026-06-26 00:22:11'),(232,78,1,5,4,20,'Adidas Ultraboost',33,15,400,'2026-06-11','Aisle C-9','2026-06-26 00:21:03','2026-06-26 00:21:03'),(233,78,2,5,4,20,'Adidas Ultraboost',25,15,400,'2026-06-05','Aisle E-10','2026-06-26 00:21:03','2026-06-26 00:21:03'),(234,78,3,5,4,20,'Adidas Ultraboost',92,15,400,'2026-06-09','Aisle B-2','2026-06-26 00:21:03','2026-06-26 00:21:03'),(235,79,1,62,4,20,'Brooks Ghost 15',66,15,400,'2026-06-10','Aisle F-1','2026-06-26 00:21:03','2026-06-26 00:21:03'),(236,79,2,62,4,20,'Brooks Ghost 15',34,15,400,'2026-06-14','Aisle A-9','2026-06-26 00:21:03','2026-06-26 00:21:03'),(237,79,3,62,4,20,'Brooks Ghost 15',32,15,400,'2026-06-08','Aisle D-4','2026-06-26 00:21:03','2026-06-26 00:21:03'),(238,80,1,63,4,20,'Asics Gel-Kayano 30',29,15,400,'2026-06-18','Aisle A-4','2026-06-26 00:21:03','2026-06-26 00:21:03'),(239,80,2,63,4,20,'Asics Gel-Kayano 30',63,15,400,'2026-06-10','Aisle D-4','2026-06-26 00:21:03','2026-06-26 00:21:03'),(240,80,3,63,4,20,'Asics Gel-Kayano 30',86,15,400,'2026-06-23','Aisle C-2','2026-06-26 00:21:03','2026-06-26 00:21:03'),(241,81,1,64,5,21,'Moleskine Classic',104,15,400,'2026-06-18','Aisle F-9','2026-06-26 00:21:03','2026-06-26 00:21:03'),(242,81,2,64,5,21,'Moleskine Classic',131,15,400,'2026-06-22','Aisle A-2','2026-06-26 00:21:03','2026-06-26 00:21:03'),(243,81,3,64,5,21,'Moleskine Classic',101,15,400,'2026-06-05','Aisle D-9','2026-06-26 00:21:03','2026-06-26 00:21:03'),(244,82,1,65,5,21,'Rhodia Webnotebook',23,15,400,'2026-06-10','Aisle F-8','2026-06-26 00:21:04','2026-06-26 00:21:04'),(245,82,2,65,5,21,'Rhodia Webnotebook',131,15,400,'2026-06-13','Aisle A-4','2026-06-26 00:21:04','2026-06-26 00:21:04'),(246,82,3,65,5,21,'Rhodia Webnotebook',29,15,400,'2026-06-01','Aisle B-8','2026-06-26 00:21:04','2026-06-26 00:21:04'),(247,83,1,66,5,21,'Leuchtturm1917 Medium',23,15,400,'2026-06-11','Aisle A-3','2026-06-26 00:21:04','2026-06-26 00:21:04'),(248,83,2,66,5,21,'Leuchtturm1917 Medium',130,15,400,'2026-05-28','Aisle F-9','2026-06-26 00:21:04','2026-06-26 00:21:04'),(249,83,3,66,5,21,'Leuchtturm1917 Medium',120,15,400,'2026-06-09','Aisle B-2','2026-06-26 00:21:04','2026-06-26 00:21:04'),(250,84,1,67,5,21,'Field Notes 3-Pack',114,15,400,'2026-06-16','Aisle A-4','2026-06-26 00:21:04','2026-06-26 00:21:04'),(251,84,2,67,5,21,'Field Notes 3-Pack',105,15,400,'2026-05-28','Aisle A-8','2026-06-26 00:21:04','2026-06-26 00:21:04'),(252,84,3,67,5,21,'Field Notes 3-Pack',74,15,400,'2026-06-14','Aisle A-8','2026-06-26 00:21:04','2026-06-26 00:21:04'),(253,85,1,68,5,22,'Pilot G2 Gel Pens',104,15,400,'2026-06-15','Aisle E-10','2026-06-26 00:21:04','2026-06-26 00:21:04'),(254,85,2,68,5,22,'Pilot G2 Gel Pens',108,15,400,'2026-06-08','Aisle B-9','2026-06-26 00:21:04','2026-06-26 00:21:04'),(255,85,3,68,5,22,'Pilot G2 Gel Pens',110,15,400,'2026-06-23','Aisle B-3','2026-06-26 00:21:04','2026-06-26 00:21:04'),(256,86,1,69,5,22,'Uni-ball Onyx Pens',49,15,400,'2026-06-24','Aisle C-3','2026-06-26 00:21:04','2026-06-26 00:21:04'),(257,86,2,69,5,22,'Uni-ball Onyx Pens',81,15,400,'2026-06-06','Aisle E-4','2026-06-26 00:21:04','2026-06-26 00:21:04'),(258,86,3,69,5,22,'Uni-ball Onyx Pens',22,15,400,'2026-06-16','Aisle C-3','2026-06-26 00:21:04','2026-06-26 00:21:04'),(259,87,1,70,5,22,'Parker Jotter Classic',46,15,400,'2026-06-07','Aisle E-2','2026-06-26 00:21:04','2026-06-26 00:21:04'),(260,87,2,70,5,22,'Parker Jotter Classic',122,15,400,'2026-05-28','Aisle F-10','2026-06-26 00:21:04','2026-06-26 00:21:04'),(261,87,3,70,5,22,'Parker Jotter Classic',134,15,400,'2026-06-08','Aisle F-1','2026-06-26 00:21:04','2026-06-26 00:21:04'),(262,88,1,71,5,22,'Sharpie S-Gel Pens',107,15,400,'2026-06-13','Aisle C-2','2026-06-26 00:21:05','2026-06-26 00:21:05'),(263,88,2,71,5,22,'Sharpie S-Gel Pens',37,15,400,'2026-06-21','Aisle E-4','2026-06-26 00:21:05','2026-06-26 00:21:05'),(264,88,3,71,5,22,'Sharpie S-Gel Pens',152,15,400,'2026-06-11','Aisle E-3','2026-06-26 00:21:05','2026-06-26 00:22:11'),(265,89,1,72,5,23,'Mesh Desk Organizer',135,15,400,'2026-05-30','Aisle C-2','2026-06-26 00:21:05','2026-06-26 00:21:05'),(266,89,2,72,5,23,'Mesh Desk Organizer',132,15,400,'2026-05-28','Aisle E-2','2026-06-26 00:21:05','2026-06-26 00:21:05'),(267,89,3,72,5,23,'Mesh Desk Organizer',67,15,400,'2026-06-24','Aisle D-2','2026-06-26 00:21:05','2026-06-26 00:21:05'),(268,90,1,73,5,23,'Wooden Desk Caddy',37,15,400,'2026-06-03','Aisle D-3','2026-06-26 00:21:05','2026-06-26 00:21:05'),(269,90,2,73,5,23,'Wooden Desk Caddy',58,15,400,'2026-05-31','Aisle F-9','2026-06-26 00:21:05','2026-06-26 00:21:05'),(270,90,3,73,5,23,'Wooden Desk Caddy',50,15,400,'2026-06-11','Aisle D-4','2026-06-26 00:21:05','2026-06-26 00:21:05'),(271,91,1,74,5,23,'Leather Desk Pad',130,15,400,'2026-06-16','Aisle F-8','2026-06-26 00:21:05','2026-06-26 00:21:05'),(272,91,2,74,5,23,'Leather Desk Pad',24,15,400,'2026-05-28','Aisle A-5','2026-06-26 00:21:05','2026-06-26 00:21:05'),(273,91,3,74,5,23,'Leather Desk Pad',50,15,400,'2026-05-27','Aisle C-2','2026-06-26 00:21:05','2026-06-26 00:21:05'),(274,92,1,75,5,23,'Monitor Stand Riser',129,15,400,'2026-06-13','Aisle A-5','2026-06-26 00:21:05','2026-06-26 00:21:05'),(275,92,2,75,5,23,'Monitor Stand Riser',21,15,400,'2026-06-07','Aisle E-10','2026-06-26 00:21:05','2026-06-26 00:21:05'),(276,92,3,75,5,23,'Monitor Stand Riser',130,15,400,'2026-06-23','Aisle C-9','2026-06-26 00:21:05','2026-06-26 00:22:11'),(277,93,1,76,5,24,'Swingline 747 Classic',107,15,400,'2026-06-07','Aisle F-5','2026-06-26 00:21:05','2026-06-26 00:21:05'),(278,93,2,76,5,24,'Swingline 747 Classic',116,15,400,'2026-06-03','Aisle D-10','2026-06-26 00:21:05','2026-06-26 00:21:05'),(279,93,3,76,5,24,'Swingline 747 Classic',85,15,400,'2026-06-07','Aisle D-5','2026-06-26 00:21:05','2026-06-26 00:22:08'),(280,94,1,77,5,24,'Bostitch Office Staple',148,15,400,'2026-06-05','Aisle A-5','2026-06-26 00:21:05','2026-06-26 00:21:05'),(281,94,2,77,5,24,'Bostitch Office Staple',118,15,400,'2026-06-07','Aisle C-1','2026-06-26 00:21:05','2026-06-26 00:21:05'),(282,94,3,77,5,24,'Bostitch Office Staple',26,15,400,'2026-06-21','Aisle E-2','2026-06-26 00:21:05','2026-06-26 00:21:05'),(283,95,1,78,5,24,'PaperPro InSpire',112,15,400,'2026-06-14','Aisle D-6','2026-06-26 00:21:05','2026-06-26 00:21:05'),(284,95,2,78,5,24,'PaperPro InSpire',57,15,400,'2026-06-15','Aisle B-1','2026-06-26 00:21:06','2026-06-26 00:21:06'),(285,95,3,78,5,24,'PaperPro InSpire',103,15,400,'2026-06-02','Aisle B-4','2026-06-26 00:21:06','2026-06-26 00:21:06'),(286,96,1,79,5,24,'Rapid Supreme Press',78,15,400,'2026-06-19','Aisle C-5','2026-06-26 00:21:06','2026-06-26 00:21:06'),(287,96,2,79,5,24,'Rapid Supreme Press',59,15,400,'2026-05-28','Aisle A-9','2026-06-26 00:21:06','2026-06-26 00:21:06'),(288,96,3,79,5,24,'Rapid Supreme Press',39,15,400,'2026-06-08','Aisle C-5','2026-06-26 00:21:06','2026-06-26 00:21:06'),(289,97,1,80,5,25,'Fellowes Powershred',80,15,400,'2026-05-30','Aisle A-7','2026-06-26 00:21:06','2026-06-26 00:21:06'),(290,97,2,80,5,25,'Fellowes Powershred',132,15,400,'2026-06-12','Aisle F-9','2026-06-26 00:21:06','2026-06-26 00:21:06'),(291,97,3,80,5,25,'Fellowes Powershred',72,15,400,'2026-06-16','Aisle A-3','2026-06-26 00:21:06','2026-06-26 00:21:06'),(292,98,1,52,5,25,'Amazon Basics Shredder',114,15,400,'2026-05-30','Aisle E-7','2026-06-26 00:21:06','2026-06-26 00:21:06'),(293,98,2,52,5,25,'Amazon Basics Shredder',93,15,400,'2026-06-10','Aisle E-10','2026-06-26 00:21:06','2026-06-26 00:21:06'),(294,98,3,52,5,25,'Amazon Basics Shredder',47,15,400,'2026-05-27','Aisle F-3','2026-06-26 00:21:06','2026-06-26 00:22:10'),(295,99,1,81,5,25,'Aurora AU800SD',121,15,400,'2026-06-02','Aisle E-3','2026-06-26 00:21:06','2026-06-26 00:21:06'),(296,99,2,81,5,25,'Aurora AU800SD',35,15,400,'2026-06-11','Aisle A-1','2026-06-26 00:21:07','2026-06-26 00:21:07'),(297,99,3,81,5,25,'Aurora AU800SD',48,15,400,'2026-06-05','Aisle B-1','2026-06-26 00:21:07','2026-06-26 00:21:07'),(298,100,1,82,5,25,'Royal Sovereign Shred',40,15,400,'2026-06-16','Aisle F-2','2026-06-26 00:21:07','2026-06-26 00:21:07'),(299,100,2,82,5,25,'Royal Sovereign Shred',118,15,400,'2026-05-27','Aisle D-2','2026-06-26 00:21:07','2026-06-26 00:21:07'),(300,100,3,82,5,25,'Royal Sovereign Shred',121,15,400,'2026-05-28','Aisle C-3','2026-06-26 00:21:07','2026-06-26 00:22:11');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_code` varchar(255) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stores_store_code_unique` (`store_code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES (1,'STR001','Warehouse Section 1','2026-06-26 00:20:29','2026-06-26 00:20:29'),(2,'STR002','Warehouse Section 2','2026-06-26 00:20:29','2026-06-26 00:20:29'),(3,'STR003','Warehouse Section 3','2026-06-26 00:20:29','2026-06-26 00:20:29'),(4,'STR004','Warehouse Section 4','2026-06-26 00:20:30','2026-06-26 00:20:30'),(5,'STR005','Warehouse Section 5','2026-06-26 00:20:30','2026-06-26 00:20:30');
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_categories` (
  `subcategory_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subcategory_code` varchar(20) DEFAULT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subcategory_id`),
  KEY `sub_categories_category_id_foreign` (`category_id`),
  CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
INSERT INTO `sub_categories` VALUES (1,'SUB001','Smartphones','Sample description for Smartphones',1,'2026-06-26','2026-06-26 00:20:23','2026-06-26 00:20:23'),(2,'SUB002','Laptops','Sample description for Laptops',1,'2026-06-26','2026-06-26 00:20:23','2026-06-26 00:20:23'),(3,'SUB003','Audio','Sample description for Audio',1,'2026-06-26','2026-06-26 00:20:23','2026-06-26 00:20:23'),(4,'SUB004','Accessories','Sample description for Accessories',1,'2026-06-26','2026-06-26 00:20:23','2026-06-26 00:20:23'),(5,'SUB005','Smart Home','Sample description for Smart Home',1,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(6,'SUB006','T-Shirts','Sample description for T-Shirts',2,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(7,'SUB007','Jeans','Sample description for Jeans',2,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(8,'SUB008','Jackets','Sample description for Jackets',2,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(9,'SUB009','Socks','Sample description for Socks',2,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(10,'SUB010','Caps','Sample description for Caps',2,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(11,'SUB011','Coffee Makers','Sample description for Coffee Makers',3,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(12,'SUB012','Microwaves','Sample description for Microwaves',3,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(13,'SUB013','Blenders','Sample description for Blenders',3,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(14,'SUB014','Toasters','Sample description for Toasters',3,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(15,'SUB015','Air Fryers','Sample description for Air Fryers',3,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(16,'SUB016','Footballs','Sample description for Footballs',4,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(17,'SUB017','Dumbbells','Sample description for Dumbbells',4,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(18,'SUB018','Yoga Mats','Sample description for Yoga Mats',4,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(19,'SUB019','Water Bottles','Sample description for Water Bottles',4,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(20,'SUB020','Running Shoes','Sample description for Running Shoes',4,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(21,'SUB021','Notebooks','Sample description for Notebooks',5,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(22,'SUB022','Pens','Sample description for Pens',5,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(23,'SUB023','Desk Organizers','Sample description for Desk Organizers',5,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(24,'SUB024','Staplers','Sample description for Staplers',5,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24'),(25,'SUB025','Paper Shredders','Sample description for Paper Shredders',5,'2026-06-26','2026-06-26 00:20:24','2026-06-26 00:20:24');
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `supplier_id` varchar(32) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `payment_terms` varchar(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`supplier_id`),
  UNIQUE KEY `suppliers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES ('SUP001','Supplier 1 Ltd','Contact Person 1','+9471111110','supplier1@example.com','No. 1, Industrial Estate, Colombo','TX-1000000','Net 30','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),('SUP002','Supplier 2 Ltd','Contact Person 2','+9471111111','supplier2@example.com','No. 2, Industrial Estate, Colombo','TX-1000001','Net 30','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),('SUP003','Supplier 3 Ltd','Contact Person 3','+9471111112','supplier3@example.com','No. 3, Industrial Estate, Colombo','TX-1000002','Net 30','2026-06-26','2026-06-26 00:20:25','2026-06-26 00:20:25'),('SUP004','Supplier 4 Ltd','Contact Person 4','+9471111113','supplier4@example.com','No. 4, Industrial Estate, Colombo','TX-1000003','Net 30','2026-06-26','2026-06-26 00:20:26','2026-06-26 00:20:26'),('SUP005','Supplier 5 Ltd','Contact Person 5','+9471111114','supplier5@example.com','No. 5, Industrial Estate, Colombo','TX-1000004','Net 30','2026-06-26','2026-06-26 00:20:26','2026-06-26 00:20:26'),('SUP006','Supplier 6 Ltd','Contact Person 6','+9471111115','supplier6@example.com','No. 6, Industrial Estate, Colombo','TX-1000005','Net 30','2026-06-26','2026-06-26 00:20:26','2026-06-26 00:20:26'),('SUP007','Supplier 7 Ltd','Contact Person 7','+9471111116','supplier7@example.com','No. 7, Industrial Estate, Colombo','TX-1000006','Net 30','2026-06-26','2026-06-26 00:20:26','2026-06-26 00:20:26'),('SUP008','Supplier 8 Ltd','Contact Person 8','+9471111117','supplier8@example.com','No. 8, Industrial Estate, Colombo','TX-1000007','Net 30','2026-06-26','2026-06-26 00:20:26','2026-06-26 00:20:26'),('SUP009','Supplier 9 Ltd','Contact Person 9','+9471111118','supplier9@example.com','No. 9, Industrial Estate, Colombo','TX-1000008','Net 30','2026-06-26','2026-06-26 00:20:26','2026-06-26 00:20:26'),('SUP010','Supplier 10 Ltd','Contact Person 10','+9471111119','supplier10@example.com','No. 10, Industrial Estate, Colombo','TX-1000009','Net 30','2026-06-26','2026-06-26 00:20:26','2026-06-26 00:20:26');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@example.com',NULL,'$2y$12$xWtL/d3SpAVyvDr.LYnIYeaOuIJ5hxdm79EjhgzSqjF.EXawLhVs.',NULL,'2026-06-26 00:20:21','2026-06-26 00:20:21','admin'),(2,'SAP','admin@sap.com',NULL,'$2y$12$zc6kVeoen30QqcJYMlrT5OZScUYqZsgsWE46OuZx2VByN6M.E663e',NULL,'2026-06-26 00:20:22','2026-06-26 00:20:22','admin'),(3,'Employee 1','employee1@example.com',NULL,'$2y$12$hi8tBxP8y6SE4Fb90XneW.Ujxkug/jzKDMvOrRGQXGLKn4xNG7bXa',NULL,'2026-06-26 00:20:22','2026-06-26 00:20:22','employee'),(4,'Employee 2','employee2@example.com',NULL,'$2y$12$GGc62T1b0espJeeCr6aO3eoZLgYLyLQRb/XZ.Tkb4m5B26mreJGJC',NULL,'2026-06-26 00:20:22','2026-06-26 00:20:22','employee'),(5,'Employee 3','employee3@example.com',NULL,'$2y$12$amtKjZiruMp63zxqhMhUCumJgoNLbdf4.x1HHJUbndfoFbpsGvAoK',NULL,'2026-06-26 00:20:23','2026-06-26 00:20:23','employee'),(6,'Employee 4','employee4@example.com',NULL,'$2y$12$JCTxoZCWroxYG2eZkHu.k.oyZZTNil7uVVhcyXeNSx.7.pZnZB9si',NULL,'2026-06-26 00:20:23','2026-06-26 00:20:23','employee');
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

-- Dump completed on 2026-06-26 12:53:26
