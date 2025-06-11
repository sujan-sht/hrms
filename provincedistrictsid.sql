-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: localhost    Database: db_hei_hrms_dev
-- ------------------------------------------------------
-- Server version	8.0.40-0ubuntu0.22.04.1

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
-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinces` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `province_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES (1,'Province No 1','2024-11-12 09:05:24','2024-11-12 09:05:24'),(2,'Madhesh Province','2024-11-12 09:05:24','2024-11-12 09:05:24'),(3,'Bagmati Province','2024-11-12 09:05:24','2024-11-12 09:05:24'),(4,'Gandaki Pradesh','2024-11-12 09:05:24','2024-11-12 09:05:24'),(5,'Lumbini Province','2024-11-12 09:05:24','2024-11-12 09:05:24'),(6,'Karnali Pradesh','2024-11-12 09:05:24','2024-11-12 09:05:24'),(7,'Sudurpashchim Pradesh','2024-11-12 09:05:24','2024-11-12 09:05:24');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `districts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `province_id` int DEFAULT NULL,
  `district_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
INSERT INTO `districts` VALUES (1,1,'Bhojpur','2024-11-12 09:05:24','2024-11-12 09:05:24'),(2,1,'Dhankuta','2024-11-12 09:05:24','2024-11-12 09:05:24'),(3,1,'Ilam','2024-11-12 09:05:24','2024-11-12 09:05:24'),(4,1,'Jhapa','2024-11-12 09:05:24','2024-11-12 09:05:24'),(5,1,'Khotang','2024-11-12 09:05:24','2024-11-12 09:05:24'),(6,1,'Morang','2024-11-12 09:05:24','2024-11-12 09:05:24'),(7,1,'Okhaldhunga','2024-11-12 09:05:24','2024-11-12 09:05:24'),(8,1,'Panchthar','2024-11-12 09:05:24','2024-11-12 09:05:24'),(9,1,'Sankhuwasabha','2024-11-12 09:05:24','2024-11-12 09:05:24'),(10,1,'Solukhumbu','2024-11-12 09:05:24','2024-11-12 09:05:24'),(11,1,'Sunsari','2024-11-12 09:05:24','2024-11-12 09:05:24'),(12,1,'Taplejung','2024-11-12 09:05:24','2024-11-12 09:05:24'),(13,1,'Terhathum','2024-11-12 09:05:24','2024-11-12 09:05:24'),(14,1,'Udayapur','2024-11-12 09:05:24','2024-11-12 09:05:24'),(15,2,'Bara','2024-11-12 09:05:24','2024-11-12 09:05:24'),(16,2,'Parsa','2024-11-12 09:05:24','2024-11-12 09:05:24'),(17,2,'Dhanusha','2024-11-12 09:05:24','2024-11-12 09:05:24'),(18,2,'Mahottari','2024-11-12 09:05:24','2024-11-12 09:05:24'),(19,2,'Rautahat','2024-11-12 09:05:24','2024-11-12 09:05:24'),(20,2,'Saptari','2024-11-12 09:05:24','2024-11-12 09:05:24'),(21,2,'Sarlahi','2024-11-12 09:05:24','2024-11-12 09:05:24'),(22,2,'Siraha','2024-11-12 09:05:24','2024-11-12 09:05:24'),(23,3,'Bhaktapur','2024-11-12 09:05:24','2024-11-12 09:05:24'),(24,3,'Chitwan','2024-11-12 09:05:24','2024-11-12 09:05:24'),(25,3,'Dhading','2024-11-12 09:05:24','2024-11-12 09:05:24'),(26,3,'Dolakha','2024-11-12 09:05:24','2024-11-12 09:05:24'),(27,3,'Kathmandu','2024-11-12 09:05:24','2024-11-12 09:05:24'),(28,3,'Kavrepalanchok','2024-11-12 09:05:24','2024-11-12 09:05:24'),(29,3,'Lalitpur','2024-11-12 09:05:24','2024-11-12 09:05:24'),(30,3,'Makwanpur','2024-11-12 09:05:24','2024-11-12 09:05:24'),(31,3,'Nuwakot','2024-11-12 09:05:24','2024-11-12 09:05:24'),(32,3,'Ramechhap','2024-11-12 09:05:24','2024-11-12 09:05:24'),(33,3,'Rasuwa','2024-11-12 09:05:24','2024-11-12 09:05:24'),(34,3,'Sindhuli','2024-11-12 09:05:24','2024-11-12 09:05:24'),(35,3,'Sindhupalchok','2024-11-12 09:05:24','2024-11-12 09:05:24'),(36,4,'Baglung','2024-11-12 09:05:24','2024-11-12 09:05:24'),(37,4,'Gorkha','2024-11-12 09:05:24','2024-11-12 09:05:24'),(38,4,'Kaski','2024-11-12 09:05:24','2024-11-12 09:05:24'),(39,4,'Lamjung','2024-11-12 09:05:24','2024-11-12 09:05:24'),(40,4,'Manang','2024-11-12 09:05:24','2024-11-12 09:05:24'),(41,4,'Mustang','2024-11-12 09:05:24','2024-11-12 09:05:24'),(42,4,'Myagdi','2024-11-12 09:05:24','2024-11-12 09:05:24'),(43,4,'Nawalpur','2024-11-12 09:05:24','2024-11-12 09:05:24'),(44,4,'Parbat','2024-11-12 09:05:24','2024-11-12 09:05:24'),(45,4,'Syangja','2024-11-12 09:05:24','2024-11-12 09:05:24'),(46,4,'Tanahun','2024-11-12 09:05:24','2024-11-12 09:05:24'),(47,5,'Arghakhanchi','2024-11-12 09:05:24','2024-11-12 09:05:24'),(48,5,'Banke','2024-11-12 09:05:24','2024-11-12 09:05:24'),(49,5,'Bardiya','2024-11-12 09:05:24','2024-11-12 09:05:24'),(50,5,'Dang','2024-11-12 09:05:24','2024-11-12 09:05:24'),(51,5,'Eastern Rukum','2024-11-12 09:05:24','2024-11-12 09:05:24'),(52,5,'Gulmi','2024-11-12 09:05:24','2024-11-12 09:05:24'),(53,5,'Kapilavastu','2024-11-12 09:05:24','2024-11-12 09:05:24'),(54,5,'Parasi','2024-11-12 09:05:24','2024-11-12 09:05:24'),(55,5,'Palpa','2024-11-12 09:05:24','2024-11-12 09:05:24'),(56,5,'Pyuthan','2024-11-12 09:05:24','2024-11-12 09:05:24'),(57,5,'Rolpa','2024-11-12 09:05:24','2024-11-12 09:05:24'),(58,5,'Rupandehi','2024-11-12 09:05:24','2024-11-12 09:05:24'),(59,6,'Dailekh','2024-11-12 09:05:24','2024-11-12 09:05:24'),(60,6,'Dolpa','2024-11-12 09:05:24','2024-11-12 09:05:24'),(61,6,'Humla','2024-11-12 09:05:24','2024-11-12 09:05:24'),(62,6,'Jajarkot','2024-11-12 09:05:24','2024-11-12 09:05:24'),(63,6,'Jumla','2024-11-12 09:05:24','2024-11-12 09:05:24'),(64,6,'Kalikot','2024-11-12 09:05:24','2024-11-12 09:05:24'),(65,6,'Mugu','2024-11-12 09:05:24','2024-11-12 09:05:24'),(66,6,'Salyan','2024-11-12 09:05:24','2024-11-12 09:05:24'),(67,6,'Surkhet','2024-11-12 09:05:24','2024-11-12 09:05:24'),(68,6,'Western Rukum','2024-11-12 09:05:24','2024-11-12 09:05:24'),(69,7,'Achham','2024-11-12 09:05:24','2024-11-12 09:05:24'),(70,7,'Baitadi','2024-11-12 09:05:24','2024-11-12 09:05:24'),(71,7,'Bajhang','2024-11-12 09:05:24','2024-11-12 09:05:24'),(72,7,'Bajura','2024-11-12 09:05:24','2024-11-12 09:05:24'),(73,7,'Dadeldhura','2024-11-12 09:05:24','2024-11-12 09:05:24'),(74,7,'Darchula','2024-11-12 09:05:24','2024-11-12 09:05:24'),(75,7,'Doti','2024-11-12 09:05:24','2024-11-12 09:05:24'),(76,7,'Kailali','2024-11-12 09:05:24','2024-11-12 09:05:24'),(77,7,'Kanchanpur','2024-11-12 09:05:24','2024-11-12 09:05:24');
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provinces_districts`
--

DROP TABLE IF EXISTS `provinces_districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinces_districts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_id` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces_districts`
--

LOCK TABLES `provinces_districts` WRITE;
/*!40000 ALTER TABLE `provinces_districts` DISABLE KEYS */;
INSERT INTO `provinces_districts` VALUES (1,'Bagmati Province','[\"23\", \"24\", \"25\", \"26\", \"27\", \"28\", \"29\", \"30\", \"31\", \"32\", \"33\", \"34\", \"35\"]','2024-11-12 09:21:15','2024-11-12 09:21:15'),(2,'Province 1','[\"1\", \"2\", \"3\", \"4\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\", \"11\", \"12\", \"13\", \"14\"]','2024-11-12 09:23:01','2024-11-12 09:23:01'),(3,'Province 2','[\"15\", \"16\", \"17\", \"18\", \"19\", \"20\", \"21\", \"22\"]','2024-11-12 09:24:16','2024-11-12 09:24:16'),(4,'Gandaki Pradesh','[\"36\", \"37\", \"38\", \"39\", \"40\", \"41\", \"42\", \"43\", \"44\", \"45\", \"46\"]','2024-11-12 09:24:53','2024-11-12 09:24:53'),(5,'Province 5','[\"47\", \"48\", \"49\", \"50\", \"51\", \"52\", \"53\", \"54\", \"55\", \"56\", \"57\", \"58\"]','2024-11-12 09:25:59','2024-11-12 09:25:59'),(6,'Karnali Pradesh','[\"59\", \"60\", \"61\", \"62\", \"63\", \"64\", \"65\", \"66\", \"67\", \"68\"]','2024-11-12 09:26:48','2024-11-12 09:26:48'),(7,'Sudurpaschim Pradesh','[\"69\", \"70\", \"71\", \"72\", \"73\", \"74\", \"75\", \"76\", \"77\"]','2024-11-12 09:27:40','2024-11-12 09:27:40');
/*!40000 ALTER TABLE `provinces_districts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-15 12:55:43
