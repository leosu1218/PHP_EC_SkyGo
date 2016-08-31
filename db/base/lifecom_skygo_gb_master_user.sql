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
-- Table structure for table `gb_master_user`
--

DROP TABLE IF EXISTS `gb_master_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gb_master_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `email` varchar(40) NOT NULL,
  `account` varchar(20) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `salt` varchar(7) NOT NULL,
  `bank_account` varchar(45) NOT NULL,
  `bank_code` varchar(5) NOT NULL,
  `bank_name` varchar(40) NOT NULL,
  `bank_account_name` varchar(40) NOT NULL,
  `create_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1=active,0=disable,2=locked',
  `creator_id` int(11) NOT NULL COMMENT 'creator platform user id',
  `editor_id` int(11) NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gb_master_user_creator_key_idx` (`creator_id`),
  KEY `gb_master_user_editor_key_idx` (`editor_id`),
  CONSTRAINT `gb_master_user_creator_key` FOREIGN KEY (`creator_id`) REFERENCES `platform_user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `gb_master_user_editor_key` FOREIGN KEY (`editor_id`) REFERENCES `platform_user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='GroupBuyingMaster';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gb_master_user`
--

LOCK TABLES `gb_master_user` WRITE;
/*!40000 ALTER TABLE `gb_master_user` DISABLE KEYS */;
INSERT INTO `gb_master_user` VALUES (1,'陳奕瑞','chen.cyr@109life.com','master01','7067a25e68cb7b3b3eee240b64a418b9ac5001bc','84895','0796664431222','006','第一銀行','陳奕瑞','2015-07-02 00:00:00',1,1,1,'0000-00-00 00:00:00'),(3,'簡妤倢','jai@gmail.com','jaichien','55cdfbcb41b35b48b52d65cee66513fbeddd37af','51556','0123456789','700','中華郵政','簡妤倢','2015-11-17 07:43:19',1,1,1,'2015-11-17 07:43:19');
/*!40000 ALTER TABLE `gb_master_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-18 15:59:31
