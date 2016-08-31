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
-- Table structure for table `platform_user_has_permission`
--

DROP TABLE IF EXISTS `platform_user_has_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform_user_has_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform_user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_puhp_idx` (`platform_user_id`),
  KEY `permission_id_puhp_idx` (`permission_id`),
  CONSTRAINT `permission_id_puhp` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `platform_user_id_puhp` FOREIGN KEY (`platform_user_id`) REFERENCES `platform_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1411 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_user_has_permission`
--

LOCK TABLES `platform_user_has_permission` WRITE;
/*!40000 ALTER TABLE `platform_user_has_permission` DISABLE KEYS */;
INSERT INTO `platform_user_has_permission` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,1,6),(7,1,7),(8,1,8),(9,1,9),(10,1,10),(11,1,11),(12,1,12),(13,1,13),(14,1,14),(15,1,15),(16,1,16),(17,1,17),(18,1,20),(19,1,21),(20,1,22),(21,1,23),(22,1,24),(23,1,25),(24,1,26),(25,1,27),(26,1,28),(27,1,29),(28,1,30),(29,1,31),(30,1,32),(31,1,33),(32,1,34),(33,1,40),(34,1,41),(35,1,42),(36,1,43),(37,1,44),(38,1,50),(39,1,51),(40,1,52),(41,1,53),(42,1,54),(43,1,55),(44,1,60),(45,1,61),(46,1,62),(47,1,62),(48,1,63),(49,1,64),(50,1,65),(51,1,66),(52,1,67),(53,1,68),(54,1,69),(55,1,70),(56,1,80),(57,1,81),(58,1,82),(59,1,83),(60,1,84),(61,1,85),(62,1,86),(63,1,90),(64,1,91),(65,1,100),(66,1,101),(67,1,102),(68,1,103),(69,1,104),(70,1,110),(71,1,111),(72,1,112),(73,1,113),(74,1,114),(75,1,120),(76,1,121),(77,1,122),(78,1,123),(79,1,124),(80,1,130),(81,1,131),(82,1,132),(83,1,133),(84,1,134),(85,1,140),(86,1,141),(87,1,142),(88,1,143),(89,1,144),(90,1,150),(91,1,151),(92,1,152),(93,1,153),(94,1,154),(95,1,160),(96,1,161),(97,1,162),(98,1,163),(99,1,164),(100,1,170),(101,1,171),(102,1,172),(103,1,173),(104,1,174),(105,1,180),(106,1,181),(107,1,182),(108,1,183),(109,1,184),(110,1,190),(111,1,191),(112,1,192),(113,1,193),(114,1,194),(115,1,200),(116,1,201),(117,1,202),(118,1,203),(119,1,204),(120,1,210),(121,1,211),(122,1,212),(123,1,213),(124,1,214),(125,1,220),(126,1,221),(127,1,222),(128,1,223),(129,1,224),(130,1,230),(131,1,231),(132,1,232),(133,1,233),(134,1,234),(135,1,240),(136,1,241),(137,1,242),(138,1,243),(139,1,244),(140,1,250),(141,1,251),(142,1,252),(143,1,253),(144,1,254),(145,1,260),(146,1,261),(147,1,262),(148,1,263),(149,1,264),(150,1,270),(151,1,271),(152,1,272),(153,1,273),(154,1,274),(155,1,280),(156,1,281),(157,1,282),(158,1,283),(159,1,284),(160,1,290),(161,1,291),(162,1,292),(163,1,293),(164,1,294),(165,1,300),(166,1,301),(167,1,302),(168,1,303),(169,1,304),(170,1,310),(171,1,311),(172,1,312),(173,1,313),(174,1,314),(175,1,320),(176,1,321),(177,1,322),(178,1,323),(179,1,324),(180,1,330),(181,1,331),(182,1,332),(183,1,333),(184,1,334),(185,1,340),(186,1,341),(187,1,342),(188,1,343),(189,1,344),(190,1,1),(191,1,2),(192,1,3),(193,1,4),(194,1,5),(195,1,6),(196,1,7),(197,1,8),(198,1,9),(199,1,10),(200,1,11),(201,1,12),(202,1,13),(203,1,14),(204,1,15),(205,1,16),(206,1,17),(207,1,20),(208,1,21),(209,1,22),(210,1,23),(211,1,24),(212,1,25),(213,1,26),(214,1,27),(215,1,28),(216,1,29),(217,1,30),(218,1,31),(219,1,32),(220,1,33),(221,1,34),(222,1,40),(223,1,41),(224,1,42),(225,1,43),(226,1,44),(227,1,50),(228,1,51),(229,1,52),(230,1,53),(231,1,54),(232,1,55),(233,1,60),(234,1,61),(235,1,62),(236,1,62),(237,1,63),(238,1,64),(239,1,65),(240,1,66),(241,1,67),(242,1,68),(243,1,69),(244,1,70),(245,1,80),(246,1,81),(247,1,82),(248,1,83),(249,1,84),(250,1,85),(251,1,86),(252,1,90),(253,1,91),(254,1,100),(255,1,101),(256,1,102),(257,1,103),(258,1,104),(259,1,110),(260,1,111),(261,1,112),(262,1,113),(263,1,114),(264,1,120),(265,1,121),(266,1,122),(267,1,123),(268,1,124),(269,1,130),(270,1,131),(271,1,132),(272,1,133),(273,1,134),(274,1,140),(275,1,141),(276,1,142),(277,1,143),(278,1,144),(279,1,150),(280,1,151),(281,1,152),(282,1,153),(283,1,154),(284,1,160),(285,1,161),(286,1,162),(287,1,163),(288,1,164),(289,1,170),(290,1,171),(291,1,172),(292,1,173),(293,1,174),(294,1,180),(295,1,181),(296,1,182),(297,1,183),(298,1,184),(299,1,190),(300,1,191),(301,1,192),(302,1,193),(303,1,194),(304,1,200),(305,1,201),(306,1,202),(307,1,203),(308,1,204),(309,1,210),(310,1,211),(311,1,212),(312,1,213),(313,1,214),(314,1,220),(315,1,221),(316,1,222),(317,1,223),(318,1,224),(319,1,230),(320,1,231),(321,1,232),(322,1,233),(323,1,234),(324,1,240),(325,1,241),(326,1,242),(327,1,243),(328,1,244),(329,1,250),(330,1,251),(331,1,252),(332,1,253),(333,1,254),(334,1,260),(335,1,261),(336,1,262),(337,1,263),(338,1,264),(339,1,270),(340,1,271),(341,1,272),(342,1,273),(343,1,274),(344,1,280),(345,1,281),(346,1,282),(347,1,283),(348,1,284),(349,1,290),(350,1,291),(351,1,292),(352,1,293),(353,1,294),(354,1,300),(355,1,301),(356,1,302),(357,1,303),(358,1,304),(359,1,310),(360,1,311),(361,1,312),(362,1,313),(363,1,314),(364,1,320),(365,1,321),(366,1,322),(367,1,323),(368,1,324),(369,1,330),(370,1,331),(371,1,332),(372,1,333),(373,1,334),(374,1,340),(375,1,341),(376,1,342),(377,1,343),(378,1,344),(379,1,160),(380,1,161),(381,1,162),(382,1,163),(383,1,164),(384,1,170),(385,1,171),(386,1,172),(387,1,173),(388,1,174),(389,1,180),(390,1,181),(391,1,51),(392,1,54),(393,1,60),(394,1,61),(395,1,190),(396,1,191),(397,1,220),(398,1,221),(399,1,300),(400,1,301),(1226,2,100),(1227,2,101),(1228,2,133),(1229,2,134),(1230,1,50),(1231,1,51),(1232,1,52),(1233,1,53),(1234,1,54),(1235,1,55),(1236,1,60),(1237,1,61),(1238,1,62),(1239,1,63),(1240,1,64),(1241,1,65),(1242,1,66),(1243,1,67),(1244,1,68),(1245,1,69),(1246,1,190),(1247,1,191),(1248,1,192),(1249,1,193),(1250,1,194),(1251,1,220),(1252,1,221),(1253,1,222),(1254,1,223),(1255,1,224),(1256,1,300),(1257,1,301),(1258,1,302),(1259,1,303),(1260,1,304),(1261,1,30),(1262,1,31),(1263,1,32),(1264,1,33),(1265,1,34),(1266,1,40),(1267,1,41),(1268,1,42),(1269,1,43),(1270,1,44),(1271,1,100),(1272,1,101),(1273,1,102),(1274,1,103),(1275,1,104),(1276,1,200),(1277,1,201),(1278,1,202),(1279,1,203),(1280,1,204),(1281,1,300),(1282,1,301),(1283,1,302),(1284,1,303),(1285,1,304),(1286,1,50),(1287,1,51),(1288,1,52),(1289,1,53),(1290,1,54),(1291,1,55),(1292,1,60),(1293,1,61),(1294,1,62),(1295,1,63),(1296,1,64),(1297,1,65),(1298,1,66),(1299,1,67),(1300,1,68),(1301,1,69),(1302,1,190),(1303,1,191),(1304,1,192),(1305,1,193),(1306,1,194),(1307,1,220),(1308,1,221),(1309,1,222),(1310,1,223),(1311,1,224),(1312,1,300),(1313,1,301),(1314,1,302),(1315,1,303),(1316,1,304),(1317,1,30),(1318,1,31),(1319,1,32),(1320,1,33),(1321,1,34),(1322,1,40),(1323,1,41),(1324,1,42),(1325,1,43),(1326,1,44),(1327,1,100),(1328,1,101),(1329,1,102),(1330,1,103),(1331,1,104),(1332,1,200),(1333,1,201),(1334,1,202),(1335,1,203),(1336,1,204),(1337,1,300),(1338,1,301),(1339,1,302),(1340,1,303),(1341,1,304),(1364,1,160),(1365,1,161),(1366,1,162),(1367,1,163),(1368,1,164),(1369,1,170),(1370,1,171),(1371,1,172),(1372,1,173),(1373,1,174),(1374,1,180),(1375,1,181),(1376,1,50),(1377,1,51),(1378,1,52),(1379,1,53),(1380,1,54),(1381,1,55),(1382,1,60),(1383,1,61),(1384,1,62),(1385,1,63),(1386,1,64),(1387,1,65),(1388,1,66),(1389,1,67),(1390,1,68),(1391,1,69),(1392,1,190),(1393,1,191),(1394,1,192),(1395,1,193),(1396,1,194),(1397,1,220),(1398,1,221),(1399,1,222),(1400,1,223),(1401,1,224),(1402,1,300),(1403,1,301),(1404,1,302),(1405,1,303),(1406,1,304),(1407,1,100),(1408,1,101),(1409,1,133),(1410,1,134);
/*!40000 ALTER TABLE `platform_user_has_permission` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-18 15:59:33
