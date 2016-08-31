CREATE DATABASE  IF NOT EXISTS `lifecom_skygo` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `lifecom_skygo`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: lifecom_skygo
-- ------------------------------------------------------
-- Server version	5.6.25

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `order_has_spec`
--

DROP TABLE IF EXISTS `order_has_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_has_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `spec_id` int(11) NOT NULL,
  `unit_price` float NOT NULL,
  `total_price` float NOT NULL,
  `spec_amount` float NOT NULL,
  `other_cost` float DEFAULT '0',
  `cost_type` varchar(45) DEFAULT 'normal',
  `fare` float DEFAULT '0',
  `fare_type` varchar(45) DEFAULT 'normal',
  `discount` float DEFAULT '0',
  `discount_type` varchar(45) DEFAULT 'normal',
  `activity_type` varchar(45) NOT NULL,
  `activity_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_has_product_oid_idx` (`order_id`),
  KEY `order_has_product_pid_idx` (`product_id`),
  KEY `order_has_product_psid_idx` (`spec_id`),
  CONSTRAINT `order_has_product_oid` FOREIGN KEY (`order_id`) REFERENCES `unified_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_has_product_pid` FOREIGN KEY (`product_id`) REFERENCES `wholesale_product` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `order_has_product_psid` FOREIGN KEY (`spec_id`) REFERENCES `wholesale_product_spec` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_has_spec`
--

LOCK TABLES `order_has_spec` WRITE;
/*!40000 ALTER TABLE `order_has_spec` DISABLE KEYS */;
INSERT INTO `order_has_spec` VALUES (1,20,2,1,8800,8800,2,0,'normal',0,'宅配',0,'normal','groupbuying','2'),(2,21,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(3,22,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(4,23,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(5,24,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(6,25,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(7,26,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(8,27,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(9,28,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(10,29,2,1,8900,8900,1,0,'normal',0,'郵局',0,'normal','general','1'),(11,30,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(12,31,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(13,32,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(14,33,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(15,34,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(16,35,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(17,36,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(18,37,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(19,38,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(20,39,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(21,40,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(22,41,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(23,42,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(24,43,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(25,44,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(26,45,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(27,47,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(28,48,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(29,49,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(30,50,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(31,51,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(32,52,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(33,53,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(34,54,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(35,55,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(36,56,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(37,57,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(38,58,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(39,59,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(40,60,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(41,61,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(42,62,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(43,63,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(44,64,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(45,65,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(46,66,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(47,67,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(48,68,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(49,69,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(50,70,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(51,71,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(52,72,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(53,73,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(54,74,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(55,75,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(56,76,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(57,77,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(58,78,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(59,79,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(60,80,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(61,81,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(62,82,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(63,83,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(64,84,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(65,85,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(66,86,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(67,87,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(68,88,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(69,89,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(70,90,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(71,91,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(72,92,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(73,95,2,1,8800,8800,1,0,'normal',0,'郵局',0,'normal','groupbuying','2'),(74,96,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(75,97,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(76,98,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(77,99,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(78,100,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(79,101,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(80,102,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(81,103,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(82,104,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(83,105,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(84,106,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(85,107,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(86,108,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(87,109,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(88,110,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(89,111,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(90,112,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(91,113,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(92,114,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(93,115,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(94,116,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(95,117,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(96,118,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(97,119,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(98,120,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(99,121,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(100,122,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(101,123,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(102,124,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(103,125,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(104,126,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(105,127,3,10,100,100,1,0,'normal',70,'宅配',0,'normal','groupbuying','3'),(106,128,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(107,129,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(108,130,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(109,131,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(110,132,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(111,133,3,10,10,10,1,0,'normal',70,'宅配',0,'normal','general','5'),(112,134,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(113,134,2,2,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(114,135,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(115,135,2,2,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(116,136,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(117,136,2,2,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(118,137,2,1,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4'),(119,137,2,2,7999,7999,1,0,'normal',0,'郵局',0,'normal','general','4');
/*!40000 ALTER TABLE `order_has_spec` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-18 15:59:33
