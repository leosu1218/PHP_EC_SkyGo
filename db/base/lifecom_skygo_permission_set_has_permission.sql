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
-- Table structure for table `permission_set_has_permission`
--

DROP TABLE IF EXISTS `permission_set_has_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_set_has_permission` (
  `id` int(35) NOT NULL,
  `psid` int(35) NOT NULL,
  `pid` int(35) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `psid_idx` (`psid`),
  KEY `pid_idx` (`pid`),
  CONSTRAINT `pid` FOREIGN KEY (`pid`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `psid` FOREIGN KEY (`psid`) REFERENCES `permission_set` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_set_has_permission`
--

LOCK TABLES `permission_set_has_permission` WRITE;
/*!40000 ALTER TABLE `permission_set_has_permission` DISABLE KEYS */;
INSERT INTO `permission_set_has_permission` VALUES (1,1,1),(2,1,5),(3,1,8),(10,2,1),(11,2,2),(12,2,3),(13,2,4),(14,2,5),(15,2,6),(16,2,7),(17,2,8),(18,2,9),(30,3,13),(31,3,15),(40,4,10),(41,4,11),(42,4,12),(43,4,13),(44,4,14),(45,4,15),(46,4,16),(47,4,17),(50,5,160),(51,5,161),(52,5,170),(53,5,171),(54,5,180),(55,5,181),(60,6,160),(61,6,161),(62,6,162),(63,6,163),(64,6,164),(65,6,170),(66,6,171),(67,6,172),(68,6,173),(69,6,174),(70,6,180),(71,6,181),(80,7,140),(81,7,141),(83,7,150),(84,7,151),(85,7,290),(86,7,291),(90,8,140),(91,8,141),(92,8,142),(93,8,143),(94,8,144),(95,8,150),(96,8,151),(97,8,152),(98,8,153),(99,8,154),(100,8,290),(101,8,291),(102,8,292),(103,8,293),(104,8,294),(110,9,51),(111,9,54),(112,9,60),(113,9,61),(114,9,190),(115,9,191),(116,9,220),(117,9,221),(118,9,300),(119,9,301),(130,10,50),(131,10,51),(132,10,52),(133,10,53),(134,10,54),(135,10,55),(136,10,60),(137,10,61),(138,10,62),(139,10,63),(140,10,64),(141,10,65),(142,10,66),(143,10,67),(144,10,68),(145,10,69),(146,10,190),(147,10,191),(148,10,192),(149,10,193),(150,10,194),(151,10,220),(152,10,221),(153,10,222),(154,10,223),(155,10,224),(156,10,300),(157,10,301),(158,10,302),(159,10,303),(160,10,304),(170,11,20),(171,11,24),(172,11,25),(173,11,29),(174,11,30),(175,11,34),(176,11,40),(177,11,44),(178,11,84),(179,11,86),(180,11,133),(181,11,134),(182,11,270),(183,11,271),(184,11,310),(185,11,311),(186,11,320),(187,11,321),(190,12,20),(191,12,21),(192,12,22),(193,12,23),(194,12,24),(195,12,25),(196,12,26),(197,12,27),(198,12,28),(199,12,29),(200,12,30),(201,12,31),(202,12,32),(203,12,33),(204,12,34),(205,12,40),(206,12,41),(207,12,42),(208,12,43),(209,12,44),(210,12,80),(211,12,81),(212,12,82),(213,12,83),(214,12,84),(215,12,85),(216,12,86),(217,12,90),(218,12,91),(219,12,130),(220,12,131),(221,12,132),(222,12,133),(223,12,134),(224,12,270),(225,12,271),(226,12,272),(227,12,273),(228,12,274),(229,12,310),(230,12,311),(231,12,312),(232,12,313),(233,12,314),(234,12,320),(235,12,321),(236,12,322),(237,12,323),(238,12,324),(240,13,30),(241,13,34),(242,13,40),(243,13,44),(244,13,100),(245,13,101),(246,13,200),(247,13,201),(248,13,300),(249,13,301),(260,14,30),(261,14,31),(262,14,32),(263,14,33),(264,14,34),(265,14,40),(266,14,41),(267,14,42),(268,14,43),(269,14,44),(270,14,100),(271,14,101),(272,14,102),(273,14,103),(274,14,104),(275,14,200),(276,14,201),(277,14,202),(278,14,203),(279,14,204),(280,14,300),(281,14,301),(282,14,302),(283,14,303),(284,14,304),(290,15,100),(291,15,101),(292,15,133),(293,15,134),(310,16,110),(311,16,111),(312,16,112),(313,16,113),(314,16,114),(315,16,130),(316,16,131),(317,16,132),(318,16,133),(319,16,134);
/*!40000 ALTER TABLE `permission_set_has_permission` ENABLE KEYS */;
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