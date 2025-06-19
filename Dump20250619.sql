-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bt3
-- ------------------------------------------------------
-- Server version	8.0.39

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (4,'Quần','','2025-05-19 07:13:47'),(5,'Áo','','2025-05-19 07:13:56'),(6,'Mũ','','2025-05-19 07:14:02'),(7,'Set','','2025-05-19 07:14:07'),(8,'Giày','','2025-05-25 16:06:40');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_suppliers`
--

DROP TABLE IF EXISTS `product_suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_suppliers` (
  `product_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `import_quantity` int DEFAULT '0',
  PRIMARY KEY (`product_id`,`supplier_id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `fk_product_suppliers_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product_suppliers_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_suppliers`
--

LOCK TABLES `product_suppliers` WRITE;
/*!40000 ALTER TABLE `product_suppliers` DISABLE KEYS */;
INSERT INTO `product_suppliers` VALUES (15,4,300000.00,'2025-03-19 12:17:01',12),(16,5,170000.00,'2025-05-24 23:57:12',11),(17,4,3500000.00,'2025-04-19 13:01:40',13),(18,4,450000.00,'2025-02-19 13:05:18',10),(19,4,550000.00,'2025-05-19 13:08:19',3),(20,6,225000.00,'2025-05-19 13:10:39',26),(21,6,490000.00,'2025-05-19 13:12:26',20),(22,6,500000.00,'2025-05-19 13:14:33',25),(23,4,10000000.00,'2025-02-19 13:17:09',8),(29,5,600000.00,'2025-05-19 16:45:08',18),(30,7,1500000.00,'2025-05-19 16:44:47',15),(31,8,10000000.00,'2025-05-19 16:56:21',9),(32,8,9500000.00,'2025-05-19 17:01:50',5),(33,9,3500000.00,'2025-05-19 17:10:44',32),(34,14,15000000.00,'2025-03-24 20:24:10',15),(35,11,13000000.00,'2025-05-24 20:36:15',12);
/*!40000 ALTER TABLE `product_suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (15,'XANNY CAP','Feng Collection',580000.00,12,'682abe7a003a1.png',6,'2025-05-19 07:15:38'),(16,'Lauren DAVIES','Feng Collection',250000.00,11,'682ac7c0b1c6a.png',6,'2025-05-19 07:55:12'),(17,'Bodega Pad TOP','Feng Collection',650000.00,13,'682ac8f3b1149.png',5,'2025-05-19 08:00:19'),(18,'XANNY v2.','Feng Collection',700000.00,10,'682aca114161e.png',6,'2025-05-19 08:05:05'),(19,'DENIM BLACK','Feng Collection',950000.00,3,'682acaabdb5b9.png',4,'2025-05-19 08:07:39'),(20,'5 Panel CAP','Feng Collection',450000.00,26,'682acb4d991b8.png',6,'2025-05-19 08:10:21'),(21,'RACING Jacket','Feng Collection',830000.00,20,'682acbb46047b.png',5,'2025-05-19 08:12:04'),(22,'MULTI Pocket Short','Feng Collection',750000.00,25,'682acc3b25eea.png',4,'2025-05-19 08:14:19'),(23,'RED Cyber','Feng Collection',12990000.00,8,'682accd46a71a.png',7,'2025-05-19 08:16:52'),(29,'DSW LEOPARD','Feng Collection',890000.00,18,'682afd4506b25.png',5,'2025-05-19 11:43:33'),(30,'HADES Camo Jersey','Feng Collection',3490000.00,15,'682afd7a8d80d.png',7,'2025-05-19 11:44:26'),(31,'3D Monogram Puff-Sleeve Blouse','Feng Collection',25000000.00,9,'682b002d28bd7.png',5,'2025-05-19 11:55:57'),(32,'LOICHOI Fergus','Feng Collection',14900000.00,5,'682b0173f130c.png',7,'2025-05-19 12:01:23'),(33,'BAPE Fullzip Hoodie','Feng Collection',10000000.00,32,'682b034c0b37f.png',5,'2025-05-19 12:09:16'),(34,'GUCCI Valkyrie','Feng Collection',25000000.00,15,'6831c87a56ce3.png',5,'2025-05-24 15:24:10'),(35,'VERSACE Baroque','Feng Collection',17500000.00,12,'6831cb4f33ca8.png',5,'2025-05-24 15:36:15');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `description` text,
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (4,'Feng Ltd','feng@gmail.com','0334839575','Hoàn Kiếm - Hà Nội','',1,'2025-05-19 07:16:42'),(5,'Davies Inc','davies@gmail.cm','0914795915','Thanh Xuân - Hà Nội','',1,'2025-05-19 07:49:11'),(6,'Leninn.Co','leninn@gmail.com','0977158756','Tây Hồ - Hà Nội','',1,'2025-05-19 08:09:54'),(7,'HADES','hades@gmail.com','0987364121','Thủ Đức - TPHCM','',1,'2025-05-19 08:19:32'),(8,'Louis Vuitton','lv@gmail.com','0989756343','Washington DC','',1,'2025-05-19 11:54:05'),(9,'A BATHING APE','bape@gmail.com','0900234234','Paris - France','',1,'2025-05-19 12:10:19'),(10,'CHANEL.ltd','chanel@gmail.com','0987787656','California - USA','',1,'2025-05-19 12:15:58'),(11,'Versace','vsc@gmail.com','0323565444','Roma - Italy','',1,'2025-05-19 12:16:54'),(12,'Nina Ricci','nnrc@gmail.com','0223444222','Moscow - Russia','',1,'2025-05-19 12:27:43'),(13,'Ralph Lauren','rlauren@gmail.com','0900232111','Roma - Italy','',1,'2025-05-19 12:28:18'),(14,'GUCCI','gc@gmail.com','0912121212','Pensylvenia - USA','',1,'2025-05-19 12:29:10');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'admin','$2y$10$c3e/e8JKTDHfmQKqH2PU9uAZv09cZU4ajQWQAvsOvdDTwEGSnx2s2');
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

-- Dump completed on 2025-06-19 21:16:02
