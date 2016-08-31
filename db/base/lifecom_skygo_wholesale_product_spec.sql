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
-- Table structure for table `wholesale_product_spec`
--

DROP TABLE IF EXISTS `wholesale_product_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wholesale_product_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `serial` varchar(45) NOT NULL,
  `can_sale_inventory` int(11) NOT NULL,
  `safe_inventory` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`serial`),
  KEY `wholesale_product_has_spec_wphs_idx` (`product_id`),
  CONSTRAINT `wholesale_product_has_spec_wphs` FOREIGN KEY (`product_id`) REFERENCES `wholesale_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wholesale_product_spec`
--

LOCK TABLES `wholesale_product_spec` WRITE;
/*!40000 ALTER TABLE `wholesale_product_spec` DISABLE KEYS */;
INSERT INTO `wholesale_product_spec` VALUES (1,'32G 玫瑰金','RX2245',100,10,2),(2,'64G 玫瑰金','RX2246',0,10,2),(3,'128G 玫瑰金','RX2247',100,10,2),(4,'32G 銀色','RX2248',100,10,2),(5,'64G 銀色','RX2249',100,10,2),(6,'128G 銀色','RX2250',100,10,2),(7,'32G 白色','RX2251',100,10,2),(8,'128G 玫瑰金','RX2252',100,10,2),(9,'128G 白色','RX2253',100,10,2),(10,'a','a',1,1,3),(11,'ascad','cdca',0,0,4),(12,'adc','sdcasdc',1,1,5),(13,'asdc','zdscas',1,1,2),(14,'asdca','asdc',1,1,2),(15,'asdc','asdc',1,1,6),(16,'dgfb','dgfb',1,1,7),(17,'asdc','adc',2,1,8);
/*!40000 ALTER TABLE `wholesale_product_spec` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-18 15:59:30
