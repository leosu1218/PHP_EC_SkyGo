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
-- Table structure for table `general_activity`
--

DROP TABLE IF EXISTS `general_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `general_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `master_id` int(11) NOT NULL,
  `buy_max` int(11) NOT NULL,
  `buy_min` int(11) NOT NULL,
  `price` float NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '0=尚未開始, 1=進行中, 2=已結案',
  `buyer_counter` float NOT NULL,
  `returner_counter` int(11) NOT NULL DEFAULT '0',
  `note` text,
  `product_counter` int(11) NOT NULL DEFAULT '0',
  `delivery_date` datetime DEFAULT NULL,
  `request_statement_date` datetime DEFAULT NULL,
  `response_statement_date` datetime DEFAULT NULL,
  `close_date` datetime DEFAULT NULL,
  `apply_charge_off_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `general_activity_product_id_gapid_idx` (`product_id`),
  KEY `general_activity_platform_user_id_gapuid_idx` (`master_id`),
  CONSTRAINT `general_activity_platform_user_id_gapuid` FOREIGN KEY (`master_id`) REFERENCES `platform_user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `general_activity_product_id_gapid` FOREIGN KEY (`product_id`) REFERENCES `wholesale_product` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_activity`
--

LOCK TABLES `general_activity` WRITE;
/*!40000 ALTER TABLE `general_activity` DISABLE KEYS */;
INSERT INTO `general_activity` VALUES (1,'原廠出售IPHONE 6S',2,1,0,0,8900,'2015-10-01 00:00:00','2016-05-29 00:00:00',0,0,0,'',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'限時熱賣IPHONE 6S+',3,1,0,0,10999,'2015-10-01 00:00:00','2016-10-30 00:00:00',0,0,0,'',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'尚未開始',2,1,0,0,7999,'2015-10-20 00:00:00','2016-10-30 00:00:00',0,0,0,'',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'已經結',2,2,0,0,7999,'2015-10-01 00:00:00','2016-10-02 00:00:00',0,0,0,'',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,'asdcasdc',3,1,0,0,10,'2016-03-01 00:00:00','2016-12-31 00:00:00',0,0,0,NULL,0,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `general_activity` ENABLE KEYS */;
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
