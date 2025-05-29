CREATE DATABASE  IF NOT EXISTS `imfinalproject` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `imfinalproject`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: imfinalproject
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addons`
--

DROP TABLE IF EXISTS `addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addons` (
  `addon_id` int NOT NULL AUTO_INCREMENT,
  `category_id` varchar(255) DEFAULT NULL,
  `basefood_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`addon_id`),
  KEY `basefood_id` (`basefood_id`),
  CONSTRAINT `addons_ibfk_1` FOREIGN KEY (`basefood_id`) REFERENCES `base_foods` (`basefood_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addons`
--

LOCK TABLES `addons` WRITE;
/*!40000 ALTER TABLE `addons` DISABLE KEYS */;
INSERT INTO `addons` VALUES (33,'2',1,'Egg',17.00,'',1,'2025-05-24 03:44:28','2025-05-24 04:15:35'),(34,'2',1,'Tokwat Baboy',25.00,'',1,'2025-05-24 03:44:49','2025-05-24 04:15:35'),(35,'2',1,'Tokwa',15.00,'',1,'2025-05-24 03:45:10','2025-05-24 16:35:37'),(36,'2',1,'Spring Onion',0.00,'',1,'2025-05-24 03:45:54','2025-05-24 04:15:35'),(37,'2',1,'Chili Garlic',0.00,'',1,'2025-05-24 03:46:30','2025-05-24 04:15:35'),(38,'2',1,'Lumpiang Toge',10.00,'',1,'2025-05-24 03:47:27','2025-05-24 04:15:35'),(39,'2',1,'Chicken',15.00,'',1,'2025-05-24 03:47:41','2025-05-24 04:15:35'),(40,'2',1,'Laman Loob',15.00,'',1,'2025-05-24 03:47:54','2025-05-24 04:15:35'),(41,'2',2,'Garlic Rice',15.00,'',1,'2025-05-24 03:48:57','2025-05-24 04:15:35'),(42,'2',2,'Spring Onion',0.00,'',1,'2025-05-24 03:49:13','2025-05-24 04:15:35'),(43,'2',2,'Fried Garlic',0.00,'',1,'2025-05-24 03:49:48','2025-05-24 04:15:35'),(44,'2',2,'Chili Garlic',0.00,'',1,'2025-05-24 03:50:08','2025-05-24 04:15:35'),(45,'2',2,'Soup',0.00,'',1,'2025-05-24 03:50:21','2025-05-24 04:15:35'),(46,'2',3,'Egg',17.00,'',1,'2025-05-24 03:50:40','2025-05-24 04:15:35'),(47,'2',3,'Spring Onion',0.00,'',1,'2025-05-24 03:50:53','2025-05-24 04:15:35'),(48,'2',3,'Fried Garlic',0.00,'',1,'2025-05-24 03:51:06','2025-05-24 04:15:35'),(49,'2',3,'Chili Garlic',0.00,'',1,'2025-05-24 03:51:42','2025-05-24 04:15:35'),(50,'2',3,'Tokwa',15.00,'',1,'2025-05-24 03:51:55','2025-05-24 04:15:35'),(51,'2',3,'Lumpiang Toge',10.00,'',1,'2025-05-24 03:52:34','2025-05-24 04:15:35'),(52,'2',3,'Chicharon',0.00,'',1,'2025-05-24 03:52:48','2025-05-24 04:15:35'),(53,'2',4,'Egg',17.00,'',1,'2025-05-24 03:53:05','2025-05-24 04:15:35'),(54,'2',4,'Spring Onion',0.00,'',1,'2025-05-24 03:53:18','2025-05-24 04:15:35'),(55,'2',4,'Fried Garlic',0.00,'',1,'2025-05-24 03:53:27','2025-05-24 04:15:35'),(56,'2',4,'Chili Garlic',0.00,'',1,'2025-05-24 03:53:40','2025-05-24 04:15:35'),(57,'2',4,'Soup',0.00,'',1,'2025-05-24 03:54:07','2025-05-24 04:15:35'),(58,'2',4,'Lumpiang Toge',10.00,'',1,'2025-05-24 03:54:26','2025-05-24 04:15:35'),(59,'2',4,'Chicken',15.00,'',1,'2025-05-24 03:54:42','2025-05-24 04:15:35'),(60,'2',4,'Beef',20.00,'',1,'2025-05-24 03:54:57','2025-05-24 04:15:35');
/*!40000 ALTER TABLE `addons` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-30  0:21:36
