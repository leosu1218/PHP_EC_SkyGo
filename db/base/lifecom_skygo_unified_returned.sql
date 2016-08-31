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
-- Table structure for table `unified_returned`
--

DROP TABLE IF EXISTS `unified_returned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unified_returned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `activity_type` varchar(45) NOT NULL DEFAULT 'groupbuying',
  `receiver_address` varchar(150) NOT NULL,
  `receiver_name` varchar(45) NOT NULL,
  `receiver_phone_number` varchar(20) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `create_datetime` datetime NOT NULL,
  `close_datetime` datetime DEFAULT NULL,
  `delivery_datetime` datetime DEFAULT NULL,
  `delivery_channel` varchar(45) DEFAULT NULL,
  `delivery_number` varchar(90) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `remark` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id_UNIQUE` (`order_id`),
  CONSTRAINT `unified_returned_with_unified_order` FOREIGN KEY (`order_id`) REFERENCES `unified_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unified_returned`
--

LOCK TABLES `unified_returned` WRITE;
/*!40000 ALTER TABLE `unified_returned` DISABLE KEYS */;
INSERT INTO `unified_returned` VALUES (2,3,'groupbuying','台北市信義區安和路一段89號','Rex','0972831678',16,'2015-10-15 03:31:15','2015-10-19 06:47:39','2015-10-15 03:31:15','便利帶','RE00981',20,NULL),(3,1,'general','aaa','aaa','123',0,'2016-03-06 14:35:42',NULL,NULL,NULL,NULL,23,NULL);
/*!40000 ALTER TABLE `unified_returned` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-18 15:59:32
