-- MySQL dump 10.11
--
-- Host: localhost    Database: emerald_customer
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6-log

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
-- Table structure for table `application_option`
--

DROP TABLE IF EXISTS `application_option`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `application_option` (
  `identifier` varchar(255) NOT NULL default '',
  `strvalue` varchar(255) default NULL,
  PRIMARY KEY  (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `application_option`
--

LOCK TABLES `application_option` WRITE;
/*!40000 ALTER TABLE `application_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_option` ENABLE KEYS */;
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
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`,`folder_id`),
  KEY `folder_id` (`folder_id`),
  KEY `mimetype` (`mimetype`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `filelib_file_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `filelib_file_ibfk_2` FOREIGN KEY (`mimetype`) REFERENCES `filelib_mimetype` (`mimetype`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `filelib_file_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `filelib_file_ibfk_4` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `filelib_file`
--

LOCK TABLES `filelib_file` WRITE;
/*!40000 ALTER TABLE `filelib_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `filelib_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filelib_filetype`
--

DROP TABLE IF EXISTS `filelib_filetype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `filelib_filetype` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `filetype_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `filetype_name` (`filetype_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `filelib_filetype`
--

LOCK TABLES `filelib_filetype` WRITE;
/*!40000 ALTER TABLE `filelib_filetype` DISABLE KEYS */;
INSERT INTO `filelib_filetype` VALUES (3,'archive'),(2,'document'),(1,'image'),(5,'unknown'),(4,'video');
/*!40000 ALTER TABLE `filelib_filetype` ENABLE KEYS */;
UNLOCK TABLES;

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
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `parent_id_name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `filelib_folder_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `filelib_folder_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `filelib_folder_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `filelib_folder`
--

LOCK TABLES `filelib_folder` WRITE;
/*!40000 ALTER TABLE `filelib_folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `filelib_folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filelib_mimetype`
--

DROP TABLE IF EXISTS `filelib_mimetype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `filelib_mimetype` (
  `mimetype` varchar(255) NOT NULL default '',
  `filetype_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`mimetype`),
  KEY `filetype_id` (`filetype_id`),
  CONSTRAINT `filelib_mimetype_ibfk_1` FOREIGN KEY (`filetype_id`) REFERENCES `filelib_filetype` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `filelib_mimetype`
--

LOCK TABLES `filelib_mimetype` WRITE;
/*!40000 ALTER TABLE `filelib_mimetype` DISABLE KEYS */;
INSERT INTO `filelib_mimetype` VALUES ('image/gif',1),('image/jpeg',1),('image/png',1),('application/pdf',2),('video/x-ms-wmv',4),('application/octet-stream',5);
/*!40000 ALTER TABLE `filelib_mimetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form`
--

DROP TABLE IF EXISTS `form`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `form` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `form_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `form_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `form`
--

LOCK TABLES `form` WRITE;
/*!40000 ALTER TABLE `form` DISABLE KEYS */;
/*!40000 ALTER TABLE `form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_field`
--

DROP TABLE IF EXISTS `form_field`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `form_field` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `form_id` bigint(20) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  `order_id` smallint(6) NOT NULL default '0',
  `title` varchar(255) default NULL,
  `mandatory` tinyint(4) NOT NULL default '0',
  `options` text,
  PRIMARY KEY  (`id`),
  KEY `form_id` (`form_id`),
  CONSTRAINT `form_field_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `form_field`
--

LOCK TABLES `form_field` WRITE;
/*!40000 ALTER TABLE `form_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formcontent`
--

DROP TABLE IF EXISTS `formcontent`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `formcontent` (
  `page_id` bigint(20) unsigned NOT NULL,
  `form_id` bigint(20) unsigned default NULL,
  `email_subject` varchar(255) NOT NULL,
  `email_from` varchar(255) NOT NULL,
  `email_to` varchar(255) NOT NULL,
  `form_lock` tinyint(4) NOT NULL default '0',
  `redirect_page_id` bigint(20) unsigned NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`page_id`),
  KEY `form_id` (`form_id`),
  KEY `redirect_page_id` (`redirect_page_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `formcontent_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `formcontent_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formcontent_ibfk_3` FOREIGN KEY (`redirect_page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `formcontent_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formcontent_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `formcontent`
--

LOCK TABLES `formcontent` WRITE;
/*!40000 ALTER TABLE `formcontent` DISABLE KEYS */;
/*!40000 ALTER TABLE `formcontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `htmlcontent`
--

DROP TABLE IF EXISTS `htmlcontent`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `htmlcontent` (
  `page_id` bigint(20) unsigned NOT NULL,
  `block_id` bigint(20) unsigned NOT NULL,
  `content` text,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`page_id`,`block_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `htmlcontent_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `htmlcontent_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `htmlcontent_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `htmlcontent`
--

LOCK TABLES `htmlcontent` WRITE;
/*!40000 ALTER TABLE `htmlcontent` DISABLE KEYS */;
/*!40000 ALTER TABLE `htmlcontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locale`
--

DROP TABLE IF EXISTS `locale`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `locale` (
  `locale` char(6) NOT NULL,
  `page_start` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`locale`),
  KEY `page_start` (`page_start`),
  CONSTRAINT `locale_ibfk_1` FOREIGN KEY (`page_start`) REFERENCES `page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `locale`
--

LOCK TABLES `locale` WRITE;
/*!40000 ALTER TABLE `locale` DISABLE KEYS */;
/*!40000 ALTER TABLE `locale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locale_option`
--

DROP TABLE IF EXISTS `locale_option`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `locale_option` (
  `locale_locale` char(6) NOT NULL,
  `identifier` varchar(255) NOT NULL default '',
  `strvalue` varchar(255) default NULL,
  PRIMARY KEY  (`locale_locale`,`identifier`),
  CONSTRAINT `locale_option_ibfk_1` FOREIGN KEY (`locale_locale`) REFERENCES `locale` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `locale_option`
--

LOCK TABLES `locale_option` WRITE;
/*!40000 ALTER TABLE `locale_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `locale_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_redirect`
--

DROP TABLE IF EXISTS `login_redirect`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `login_redirect` (
  `page_id` bigint(20) unsigned NOT NULL,
  `redirect_page_id` bigint(20) unsigned default NULL,
  PRIMARY KEY  (`page_id`),
  KEY `redirect_page_id` (`redirect_page_id`),
  CONSTRAINT `login_redirect_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `login_redirect_ibfk_2` FOREIGN KEY (`redirect_page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `login_redirect`
--

LOCK TABLES `login_redirect` WRITE;
/*!40000 ALTER TABLE `login_redirect` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_redirect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_channel`
--

DROP TABLE IF EXISTS `news_channel`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `news_channel` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `page_id` bigint(20) unsigned NOT NULL,
  `items_per_page` tinyint(4) NOT NULL default '10',
  `link_readmore` varchar(255) NOT NULL,
  `allow_syndication` tinyint(4) NOT NULL default '1',
  `default_months_valid` tinyint(4) default '12',
  `title` varchar(255) NOT NULL,
  `description` text,
  `locale` char(6) default NULL,
  `copyright` varchar(255) default NULL,
  `managing_editor` varchar(255) default NULL,
  `webmaster` varchar(255) default NULL,
  `category` varchar(255) default NULL,
  `ttl` smallint(6) NOT NULL default '60',
  `skip_hours` varchar(255) default NULL,
  `skip_days` varchar(255) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  `status` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `page_id` (`page_id`),
  CONSTRAINT `news_channel_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `news_channel`
--

LOCK TABLES `news_channel` WRITE;
/*!40000 ALTER TABLE `news_channel` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_item`
--

DROP TABLE IF EXISTS `news_item`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `news_item` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `news_channel_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `article` text,
  `author` varchar(255) default NULL,
  `category` varchar(255) default NULL,
  `comments` varchar(255) default NULL,
  `enclosure` varchar(255) default NULL,
  `valid_start` datetime NOT NULL,
  `valid_end` datetime default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `news_channel_id` (`news_channel_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `news_item_ibfk_1` FOREIGN KEY (`news_channel_id`) REFERENCES `news_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_item_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `news_item_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `news_item`
--

LOCK TABLES `news_item` WRITE;
/*!40000 ALTER TABLE `news_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `page` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `parent_id` bigint(20) unsigned default NULL,
  `locale` char(6) NOT NULL,
  `order_id` smallint(6) NOT NULL default '0',
  `template` varchar(255) NOT NULL default 'default.phtml',
  `innertemplate` varchar(255) NOT NULL default 'default.phtml',
  `title` varchar(255) NOT NULL,
  `iisiurl` text NOT NULL,
  `path` varchar(255) NOT NULL,
  `shard_id` int(10) unsigned NOT NULL,
  `visibility` tinyint(3) unsigned NOT NULL default '1',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified` timestamp NULL default NULL,
  `created_by` bigint(20) unsigned default NULL,
  `modified_by` bigint(20) unsigned default NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `parent_id_title` (`parent_id`,`title`),
  KEY `parent_id` (`parent_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `shard_id` (`shard_id`),
  KEY `locale` (`locale`),
  KEY `iisiurl_index` (`iisiurl`(255)),
  CONSTRAINT `page_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_4` FOREIGN KEY (`shard_id`) REFERENCES `shard` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_5` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_filelib_folder_ugroup`
--

DROP TABLE IF EXISTS `permission_filelib_folder_ugroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `permission_filelib_folder_ugroup` (
  `folder_id` bigint(20) unsigned NOT NULL,
  `ugroup_id` bigint(20) unsigned NOT NULL,
  `permission` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`folder_id`,`ugroup_id`),
  KEY `ugroup_id` (`ugroup_id`),
  CONSTRAINT `permission_filelib_folder_ugroup_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `filelib_folder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_filelib_folder_ugroup_ibfk_2` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `permission_filelib_folder_ugroup`
--

LOCK TABLES `permission_filelib_folder_ugroup` WRITE;
/*!40000 ALTER TABLE `permission_filelib_folder_ugroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_filelib_folder_ugroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_page_ugroup`
--

DROP TABLE IF EXISTS `permission_page_ugroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `permission_page_ugroup` (
  `page_id` bigint(20) unsigned NOT NULL,
  `ugroup_id` bigint(20) unsigned NOT NULL,
  `permission` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`page_id`,`ugroup_id`),
  KEY `ugroup_id` (`ugroup_id`),
  CONSTRAINT `permission_page_ugroup_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_page_ugroup_ibfk_2` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `permission_page_ugroup`
--

LOCK TABLES `permission_page_ugroup` WRITE;
/*!40000 ALTER TABLE `permission_page_ugroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_page_ugroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `session` (
  `id` varchar(50) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `refreshed` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shard`
--

DROP TABLE IF EXISTS `shard`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `shard` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL default 'index',
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `shard`
--

LOCK TABLES `shard` WRITE;
/*!40000 ALTER TABLE `shard` DISABLE KEYS */;
INSERT INTO `shard` VALUES (1,'Htmlcontent','index',3),(2,'Breadcrumb','index',1),(3,'Menu','index',1),(4,'Sitemap','index',1),(5,'News','index',3),(7,'Formcontent','index',3),(9,'Login','page',3),(10,'Randomimage','index',3);
/*!40000 ALTER TABLE `shard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `sitemap`
--

DROP TABLE IF EXISTS `sitemap`;
/*!50001 DROP VIEW IF EXISTS `sitemap`*/;
/*!50001 CREATE TABLE `sitemap` (
  `id` bigint(20) unsigned,
  `parent_id` bigint(20) unsigned,
  `order_id` smallint(6),
  `title` varchar(255),
  `shard_id` int(10) unsigned,
  `created` timestamp,
  `modified` timestamp,
  `created_by` bigint(20) unsigned,
  `modified_by` bigint(20) unsigned,
  `status` int(11),
  `child_cnt` bigint(21)
) */;

--
-- Table structure for table `ugroup`
--

DROP TABLE IF EXISTS `ugroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ugroup` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ugroup`
--

LOCK TABLES `ugroup` WRITE;
/*!40000 ALTER TABLE `ugroup` DISABLE KEYS */;
INSERT INTO `ugroup` VALUES (1,'anonymous'),(2,'root');
/*!40000 ALTER TABLE `ugroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `passwd` char(32) NOT NULL,
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'anonymous@example.com','4ee6d203733c39cb3910c3371d56e1f3',NULL,NULL,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_option`
--

DROP TABLE IF EXISTS `user_option`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user_option` (
  `user_id` bigint(20) unsigned NOT NULL,
  `identifier` varchar(255) NOT NULL default '',
  `strvalue` varchar(255) default NULL,
  PRIMARY KEY  (`user_id`,`identifier`),
  CONSTRAINT `user_option_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user_option`
--

LOCK TABLES `user_option` WRITE;
/*!40000 ALTER TABLE `user_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_ugroup`
--

DROP TABLE IF EXISTS `user_ugroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user_ugroup` (
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `user_ugroup_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_ugroup_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user_ugroup`
--

LOCK TABLES `user_ugroup` WRITE;
/*!40000 ALTER TABLE `user_ugroup` DISABLE KEYS */;
INSERT INTO `user_ugroup` VALUES (1,1);
/*!40000 ALTER TABLE `user_ugroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'emerald_customer'
--
DELIMITER ;;
DELIMITER ;

--
-- Final view structure for view `sitemap`
--

/*!50001 DROP TABLE `sitemap`*/;
/*!50001 DROP VIEW IF EXISTS `sitemap`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sitemap` AS select `x`.`id` AS `id`,`x`.`parent_id` AS `parent_id`,`x`.`order_id` AS `order_id`,`x`.`title` AS `title`,`x`.`shard_id` AS `shard_id`,`x`.`created` AS `created`,`x`.`modified` AS `modified`,`x`.`created_by` AS `created_by`,`x`.`modified_by` AS `modified_by`,`x`.`status` AS `status`,(select count(0) AS `COUNT(*)` from `page` `y` where (`y`.`parent_id` = `x`.`id`)) AS `child_cnt` from `page` `x` group by `x`.`id` */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-10-31 10:17:22
