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
-- Table structure for table `wholesale_product`
--

DROP TABLE IF EXISTS `wholesale_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wholesale_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ready_time` datetime NOT NULL COMMENT '上架日期',
  `removed_time` datetime NOT NULL COMMENT '下架日期',
  `active_maximum` float DEFAULT '999999' COMMENT '活動最少天數',
  `active_minimum` float DEFAULT '0' COMMENT '活動最多天數',
  `wholesale_price` float NOT NULL COMMENT '批發價格',
  `end_price` float NOT NULL COMMENT '末端價最少價格',
  `suggest_price` float NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '產品名稱',
  `detail` text,
  `product_group_id` int(11) DEFAULT NULL,
  `cover_photo_img` varchar(100) DEFAULT NULL,
  `explain_text` text,
  `active_groupbuying` tinyint(4) NOT NULL DEFAULT '0',
  `youtube_url` varchar(200) DEFAULT NULL,
  `media_type` int(15) NOT NULL DEFAULT '0' COMMENT '使用媒體的樣式\n\n0:images,1:youtube_url;',
  `tag` varchar(200) DEFAULT NULL,
  `product_length` int(10) DEFAULT NULL,
  `product_width` int(10) DEFAULT NULL,
  `product_height` int(10) DEFAULT NULL,
  `weight` int(10) DEFAULT NULL,
  `remark` varchar(45) DEFAULT NULL,
  `master_id` int(11) NOT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_group_id_product_pgidp_idx` (`product_group_id`),
  KEY `wholesale_product_cover_photo_idx` (`cover_photo_img`),
  CONSTRAINT `product_group_id_product_pgidp` FOREIGN KEY (`product_group_id`) REFERENCES `product_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wholesale_product`
--

LOCK TABLES `wholesale_product` WRITE;
/*!40000 ALTER TABLE `wholesale_product` DISABLE KEYS */;
INSERT INTO `wholesale_product` VALUES (2,'2014-08-04 13:29:31','2015-12-01 07:35:41',60,5,6000,10000,9000,'iPhone 6S','活動說明',6,'tB0DUJG8evpnHSi71QCZ.jpeg','產品說明',1,'https://www.youtube.com/embed/2U73sHP7WCc',0,'韓系,臉部清潔,LUSH',0,0,0,0,NULL,0,NULL),(3,'2014-03-01 00:00:00','2015-02-24 00:00:00',999999,13,10,10,10,'dasdc','asdcasdc',6,'QcVLvjPBzNZ7JgpWDRdH.jpeg','asdcasdc',1,'NULL',0,'韓系',10,10,10,1,NULL,1,NULL),(4,'2014-03-01 00:00:00','2015-03-31 00:00:00',999999,0,10,10,10,'asdc','asdcasdc',6,'9vsDOkZPcxpuGlURHTyo.jpeg','asdcadcac',0,'NULL',0,'asdc',10,10,10,10,NULL,1,NULL),(5,'2014-04-01 00:00:00','2015-04-30 00:00:00',999999,0,2,1,1,'asdc','asdcasdc',6,'LuZTEj3DmaUvrWSytJeQ.jpeg','asdcad<img src=\"upload/image/X9aFwVfcWKrvQLTt6GkZ.jpeg\">',0,'NULL',0,'asdc',0,0,0,0,NULL,1,'2016-05-12 14:34:53'),(6,'2016-05-04 00:00:00','2016-05-31 00:00:00',999999,0,10,1,1,'acsdc','acsdcad',6,'f5mcMyd0S4xBqArjvWXi.jpeg','asdcasdc',0,'NULL',0,'asdc',1,1,1,1,NULL,1,'2016-05-04 15:11:37'),(7,'2016-05-01 00:00:00','2016-05-31 00:00:00',999999,0,1,1,1,'dfbgb','asdcasdcadc',6,'MSAgPOCT29NWf6oVF3HI.jpeg','asdcasdcasdca',0,'NULL',0,'dfgb',0,0,0,0,NULL,1,'2016-05-12 15:14:15'),(8,'2016-05-01 00:00:00','2016-05-27 00:00:00',999999,0,1,20,1,'asdcasdc','asdcasdc',6,'yHPSBTeArd1fNVaFs0w8.jpeg','<img src=\"upload/image/OlZ9htmaxULPEwS3WvMY.jpeg\" height=\"455\" width=\"608\">asdcasdcdcascasdcadca<br>casd<br>ca<br>sdca<br>sdc<br>',0,'NULL',0,'asdcasdc',20,20,20,2,NULL,1,'2016-05-13 14:59:39');
/*!40000 ALTER TABLE `wholesale_product` ENABLE KEYS */;
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
