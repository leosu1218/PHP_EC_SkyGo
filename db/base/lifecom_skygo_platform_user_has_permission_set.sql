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
-- Table structure for table `platform_user_has_permission_set`
--

DROP TABLE IF EXISTS `platform_user_has_permission_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform_user_has_permission_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `puid` int(11) NOT NULL,
  `psid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `puid_idx` (`puid`),
  KEY `psid_idx` (`psid`),
  CONSTRAINT `PUIDhasPSID` FOREIGN KEY (`psid`) REFERENCES `permission_set` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `PUIDusePSID` FOREIGN KEY (`puid`) REFERENCES `platform_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_user_has_permission_set`
--

LOCK TABLES `platform_user_has_permission_set` WRITE;
/*!40000 ALTER TABLE `platform_user_has_permission_set` DISABLE KEYS */;
INSERT INTO `platform_user_has_permission_set` VALUES (39,1,6),(40,1,10),(41,1,15);
/*!40000 ALTER TABLE `platform_user_has_permission_set` ENABLE KEYS */;
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
