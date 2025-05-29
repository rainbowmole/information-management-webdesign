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
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash on Delivery','Paypal') NOT NULL,
  `payment_status` enum('Pending','Paid') DEFAULT 'Pending',
  `order_status` enum('Pending','Preparing','On the Way','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 08:47:58','2025-05-23 08:47:58'),(2,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 08:49:35','2025-05-23 08:49:35'),(3,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 08:57:56','2025-05-23 08:57:56'),(4,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 08:58:16','2025-05-23 08:58:16'),(5,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 08:59:22','2025-05-23 08:59:22'),(6,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 08:59:55','2025-05-23 08:59:55'),(7,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 09:00:46','2025-05-23 09:00:46'),(8,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 09:01:35','2025-05-23 09:01:35'),(9,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 09:03:09','2025-05-23 09:03:09'),(10,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 09:03:20','2025-05-23 09:03:20'),(11,3,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 09:04:30','2025-05-23 09:04:30'),(12,3,0.00,'Cash on Delivery','Pending','Pending','2025-05-23 09:05:29','2025-05-23 09:05:29'),(13,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-25 03:59:17','2025-05-25 03:59:17'),(14,1,0.00,'Cash on Delivery','Pending','Pending','2025-05-25 04:08:51','2025-05-25 04:08:51'),(37,5,147.44,'Cash on Delivery','Pending','Pending','2025-05-29 16:00:22',NULL),(38,5,147.44,'Cash on Delivery','Pending','Pending','2025-05-29 16:00:22',NULL),(39,5,147.44,'Cash on Delivery','Pending','Pending','2025-05-29 16:02:52',NULL),(40,5,147.44,'Cash on Delivery','Pending','Pending','2025-05-29 16:02:52',NULL),(41,5,141.84,'Cash on Delivery','Pending','Pending','2025-05-29 16:06:56',NULL),(42,5,162.00,'Cash on Delivery','Pending','Pending','2025-05-29 16:09:14',NULL),(43,6,214.24,'Cash on Delivery','Pending','Pending','2025-05-29 16:10:25',NULL),(44,1,264.24,'Cash on Delivery','Pending','Pending','2025-05-29 16:11:12',NULL),(45,5,139.60,'Cash on Delivery','Pending','Pending','2025-05-29 16:16:06',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-30  0:21:37
