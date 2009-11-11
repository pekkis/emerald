-- MySQL dump 10.11
--
-- Host: localhost    Database: emerald
-- ------------------------------------------------------
-- Server version	5.0.75-0ubuntu10.2-log

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
-- Table structure for table `filelib_folder`
--

DROP TABLE IF EXISTS `filelib_folder`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `filelib_folder` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `parent_id` bigint(20) unsigned default NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `parent_id_name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `filelib_folder_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `filelib_folder`
--

LOCK TABLES `filelib_folder` WRITE;
/*!40000 ALTER TABLE `filelib_folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `filelib_folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filelib_file`
--

DROP TABLE IF EXISTS `filelib_file`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `filelib_file` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `folder_id` bigint(20) unsigned NOT NULL,
  `mimetype` varchar(255) NOT NULL,
  `size` int(11) default NULL,
  `name` varchar(255) NOT NULL,
  `iisiurl` text NOT NULL,
  `path` varchar(255) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`,`folder_id`),
  KEY `folder_id` (`folder_id`),
  KEY `mimetype` (`mimetype`),
  CONSTRAINT `filelib_file_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `filelib_file`
--

LOCK TABLES `filelib_file` WRITE;
/*!40000 ALTER TABLE `filelib_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `filelib_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'emerald'
--
DELIMITER ;;
DELIMITER ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-11-11 14:10:58
