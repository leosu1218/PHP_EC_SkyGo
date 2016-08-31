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
-- Table structure for table `consumer_user`
--

DROP TABLE IF EXISTS `consumer_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(50) DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `account` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `hash` varchar(120) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `oauth_id` varchar(45) DEFAULT NULL,
  `oauth_type` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `consignee_name` varchar(45) DEFAULT NULL,
  `consignee_phone` varchar(45) DEFAULT NULL,
  `consignee_address` varchar(45) DEFAULT NULL,
  `create_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`,`group_id`),
  KEY `domainName` (`domain_name`),
  KEY `email` (`email`),
  KEY `appID` (`group_id`,`account`,`hash`),
  CONSTRAINT `consumer_user_group_cug` FOREIGN KEY (`group_id`) REFERENCES `consumer_user_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consumer_user`
--

LOCK TABLES `consumer_user` WRITE;
/*!40000 ALTER TABLE `consumer_user` DISABLE KEYS */;
INSERT INTO `consumer_user` VALUES (1,'109life.com',2,'groupbuying','groupbuying@109life.com','groupbuying@109life.com','------','------',NULL,NULL,NULL,NULL,NULL,NULL,'0000-00-00 00:00:00'),(2,'109life.com',1,'leo','leo@gmail','leo@gmail','96fb5be45251cb12581f4b48ad10dd10ceabd021','YP0pM',NULL,NULL,NULL,'leo','0983000000','qqq','0000-00-00 00:00:00'),(3,'109life.com',1,'1','1@1','1@1','bf95b737525b830216c61fdfe8d138547514dd25','74055',NULL,NULL,'1',NULL,NULL,NULL,'2016-01-27 15:47:52'),(4,'109life.com',1,'a','a@a','a@a','c4a2f144537f79fa7a2d9d10d62e18cc12fae12f','37108',NULL,NULL,'098888888',NULL,NULL,NULL,'2016-03-10 17:44:27');
/*!40000 ALTER TABLE `consumer_user` ENABLE KEYS */;
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
