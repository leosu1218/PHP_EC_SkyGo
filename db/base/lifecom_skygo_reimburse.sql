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
-- Table structure for table `reimburse`
--

DROP TABLE IF EXISTS `reimburse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reimburse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reimburse_serial` varchar(20) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_state` int(11) NOT NULL,
  `buy_name` varchar(45) NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  `reimburse_name` varchar(30) NOT NULL,
  `reimburse_bank` varchar(30) NOT NULL,
  `reimburse_bank_branch` varchar(30) NOT NULL,
  `reimburse_account` varchar(30) NOT NULL,
  `reimburse_money` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `order_datetime` datetime NOT NULL,
  `pay_datetime` datetime NOT NULL,
  `state` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `consumer_user_id` int(11) NOT NULL,
  `order_serial` varchar(45) NOT NULL,
  `reimburse_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reimburse`
--

LOCK TABLES `reimburse` WRITE;
/*!40000 ALTER TABLE `reimburse` DISABLE KEYS */;
INSERT INTO `reimburse` VALUES (1,'111111',21,24,'sdcas','atm','asdc','asdc','asdc','31234',115,'2016-04-27 16:01:38','2016-04-27 16:01:41','2016-04-27 16:01:44',1,'asdcad',0,'21','2016-05-16 12:01:43'),(2,'16056038',137,24,'leo','neweb','','','','',15998,'2016-05-16 15:10:46','2016-04-13 17:33:18','2016-05-15 15:09:39',1,'不想等/等太久:',2,'1604135125704','2016-05-16 15:50:24');
/*!40000 ALTER TABLE `reimburse` ENABLE KEYS */;
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
