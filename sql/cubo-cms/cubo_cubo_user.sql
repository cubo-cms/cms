-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: localhost    Database: cubo_cubo
-- ------------------------------------------------------
-- Server version	5.6.25-log

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
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `#` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `accesslevel` int(20) DEFAULT NULL,
  `author` int(20) DEFAULT NULL,
  `avatar` int(20) DEFAULT NULL,
  `blocked` tinyint(4) DEFAULT NULL,
  `contact` int(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `description` text,
  `editor` int(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT NULL,
  `key` varchar(100) DEFAULT NULL,
  `lastloggedin` datetime DEFAULT NULL,
  `logins` int(20) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `publisher` int(20) DEFAULT NULL,
  `role` int(20) DEFAULT NULL,
  `status` int(20) DEFAULT NULL,
  `title` text,
  `verified` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`#`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8mb4 COMMENT='User';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (0,'nobody',0,1,NULL,1,NULL,'1968-12-10 00:00:00','No user',NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,'1968-12-10 00:00:00',1,0,1,'Nobody',0),(1,'system',4,1,NULL,1,1,'1968-12-10 00:00:00','System user',NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,'1968-12-10 00:00:00',1,0,1,'System',0),(2,'admin',4,1,NULL,0,2,'1968-12-10 00:00:00','Administrator',NULL,NULL,1,NULL,NULL,0,NULL,'$2a$11$5a53280d799b63.210891uIFgDIUgD4j3EZ6CXczya.WIZ5zifsri',NULL,'1968-12-10 00:00:00',1,6,1,'Administrator',1),(1001,'papiando',4,1,10,0,10,'1968-12-10 00:00:00','Papiando',NULL,NULL,1,NULL,NULL,0,NULL,'$2a$11$5a53280d799b63.210891uIFgDIUgD4j3EZ6CXczya.WIZ5zifsri',NULL,'1968-12-10 00:00:00',1,2,1,'Papiando',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-21 15:22:45
