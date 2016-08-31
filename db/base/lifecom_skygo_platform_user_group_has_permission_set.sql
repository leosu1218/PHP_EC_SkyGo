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
-- Table structure for table `platform_user_group_has_permission_set`
--

DROP TABLE IF EXISTS `platform_user_group_has_permission_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform_user_group_has_permission_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pugid` int(11) NOT NULL COMMENT 'platform user group id.',
  `psid` int(11) NOT NULL COMMENT 'permission set id.',
  PRIMARY KEY (`id`),
  KEY `pugid_idx` (`pugid`),
  KEY `psid_idx` (`psid`),
  CONSTRAINT `PUGIDhasPSID` FOREIGN KEY (`psid`) REFERENCES `permission_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `PUGIDusePSID` FOREIGN KEY (`pugid`) REFERENCES `platform_user_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_user_group_has_permission_set`
--

LOCK TABLES `platform_user_group_has_permission_set` WRITE;
/*!40000 ALTER TABLE `platform_user_group_has_permission_set` DISABLE KEYS */;
INSERT INTO `platform_user_group_has_permission_set` VALUES (1,1,2),(2,1,4),(3,1,6),(4,1,8),(5,1,10),(6,1,12),(7,1,14),(8,1,16),(49,2,2),(50,2,6),(51,2,10),(52,2,14);
/*!40000 ALTER TABLE `platform_user_group_has_permission_set` ENABLE KEYS */;
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
