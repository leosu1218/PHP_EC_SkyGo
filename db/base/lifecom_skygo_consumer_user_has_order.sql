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
-- Table structure for table `consumer_user_has_order`
--

DROP TABLE IF EXISTS `consumer_user_has_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_user_has_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `order_id_idx` (`order_id`),
  CONSTRAINT `order_id_cuo` FOREIGN KEY (`order_id`) REFERENCES `unified_order` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `user_id_cuo` FOREIGN KEY (`user_id`) REFERENCES `consumer_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consumer_user_has_order`
--

LOCK TABLES `consumer_user_has_order` WRITE;
/*!40000 ALTER TABLE `consumer_user_has_order` DISABLE KEYS */;
INSERT INTO `consumer_user_has_order` VALUES (1,1,20),(2,2,21),(3,2,22),(4,2,23),(5,2,24),(6,2,25),(7,2,26),(8,2,27),(9,2,28),(10,2,29),(11,2,30),(12,2,31),(13,2,32),(14,2,33),(15,2,34),(16,2,35),(17,2,36),(18,2,37),(19,2,38),(20,2,39),(21,2,40),(22,2,41),(23,2,42),(24,2,43),(25,2,44),(26,2,45),(27,1,46),(28,2,47),(29,2,48),(30,2,49),(31,2,50),(32,2,51),(33,2,52),(34,2,53),(35,2,54),(36,2,55),(37,2,56),(38,2,57),(39,2,58),(40,2,59),(41,2,60),(42,2,61),(43,2,62),(44,2,63),(45,2,64),(46,2,65),(47,2,66),(48,2,67),(49,2,68),(50,2,69),(51,2,70),(52,2,71),(53,2,72),(54,2,73),(55,2,74),(56,2,75),(57,2,76),(58,2,77),(59,2,78),(60,2,79),(61,2,80),(62,2,81),(63,2,82),(64,2,83),(65,2,84),(66,2,85),(67,2,86),(68,2,87),(69,2,88),(70,2,89),(71,2,90),(72,2,91),(73,2,92),(74,1,93),(75,1,94),(76,1,95),(77,2,96),(78,2,97),(79,2,98),(80,2,99),(81,2,100),(82,2,101),(83,2,102),(84,3,103),(85,2,104),(86,2,105),(87,2,106),(88,2,107),(89,2,108),(90,2,109),(91,2,110),(92,2,111),(93,2,112),(94,2,113),(95,2,114),(96,2,115),(97,2,116),(98,2,117),(99,2,118),(100,2,119),(101,2,120),(102,2,121),(103,2,122),(104,2,123),(105,2,124),(106,2,125),(107,2,126),(108,1,127),(109,2,128),(110,2,129),(111,2,130),(112,2,131),(113,2,132),(114,2,133),(115,2,134),(116,2,135),(117,2,136),(118,2,137);
/*!40000 ALTER TABLE `consumer_user_has_order` ENABLE KEYS */;
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
