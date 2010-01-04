-- MySQL dump 10.13  Distrib 5.1.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: lemerald
-- ------------------------------------------------------
-- Server version	5.1.37-1ubuntu5-log

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_option` (
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `strvalue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_option`
--

LOCK TABLES `application_option` WRITE;
/*!40000 ALTER TABLE `application_option` DISABLE KEYS */;
INSERT INTO `application_option` VALUES ('default_locale','fi');
/*!40000 ALTER TABLE `application_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filelib_file`
--

DROP TABLE IF EXISTS `filelib_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filelib_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folder_id` int(10) unsigned NOT NULL,
  `mimetype` varchar(255) NOT NULL,
  `size` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(1000) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`folder_id`),
  KEY `folder_id` (`folder_id`),
  KEY `mimetype` (`mimetype`),
  CONSTRAINT `filelib_file_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filelib_file`
--

LOCK TABLES `filelib_file` WRITE;
/*!40000 ALTER TABLE `filelib_file` DISABLE KEYS */;
INSERT INTO `filelib_file` VALUES (20,4,'image/jpeg',29806,'img01.jpg','layout/img01.jpg','',NULL,NULL);
/*!40000 ALTER TABLE `filelib_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filelib_folder`
--

DROP TABLE IF EXISTS `filelib_folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filelib_folder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id_name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `filelib_folder_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filelib_folder`
--

LOCK TABLES `filelib_folder` WRITE;
/*!40000 ALTER TABLE `filelib_folder` DISABLE KEYS */;
INSERT INTO `filelib_folder` VALUES (1,NULL,'root',NULL,NULL),(4,1,'layout',NULL,NULL);
/*!40000 ALTER TABLE `filelib_folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form`
--

DROP TABLE IF EXISTS `form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `form_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `form_ibfk_2` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_field` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(10) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  `order_id` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `mandatory` tinyint(4) NOT NULL DEFAULT '0',
  `options` text,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  CONSTRAINT `form_field_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formcontent` (
  `page_id` int(10) unsigned NOT NULL,
  `form_id` int(10) unsigned DEFAULT NULL,
  `email_subject` varchar(255) NOT NULL,
  `email_from` varchar(255) NOT NULL,
  `email_to` varchar(255) NOT NULL,
  `form_lock` tinyint(4) NOT NULL DEFAULT '0',
  `redirect_page_id` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`page_id`),
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
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `htmlcontent` (
  `page_id` int(10) unsigned NOT NULL,
  `block_id` int(10) unsigned NOT NULL,
  `content` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`page_id`,`block_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `htmlcontent_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `htmlcontent_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `htmlcontent_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `htmlcontent`
--

LOCK TABLES `htmlcontent` WRITE;
/*!40000 ALTER TABLE `htmlcontent` DISABLE KEYS */;
INSERT INTO `htmlcontent` VALUES (12,1,'<h2>Neehk, neehk! (lue: lamantiini neehkuttaa)</h2>\n<p>Tervetuloa, veppikulkijani. Min&auml; olen Mikko Forsstr&ouml;m, monille tutumpi itseselitteisell&auml; lempinimell&auml;ni <a href=\"/page/view/id/14\"><em>Pekkis</em></a>, ja n&auml;m&auml; ovat kotskasivuni. Edellisist&auml; onkin aikaa.</p>\n<p><a href=\"http://fi.wikipedia.org/wiki/Lamantiini\">Lamantiini</a> (<em>trichechus manatus</em>) puolestaan on merten kultainen noutaja, luonnostaan letke&auml; l&ouml;tk&auml;le. T&auml;m&auml; el&auml;imist&auml; jaloin tunnetaan my&ouml;s manaattina, joka puolestaan  <strong>ei tarkoita</strong> merilehm&auml;&auml;. Lamantiinin h&auml;nt&auml; on melan muotoinen, kun taas <em>merilehmien h&auml;nn&auml;t ovat haaroittuneita</em>. Ero on pieni, mutta sit&auml;kin merkitt&auml;v&auml;mpi.</p>\n<p><a href=\"/projects/web\">PHP- ja Internet-ekspertti</a>, <a href=\"/projects/games\">pelintekij&auml;</a>, <a href=\"/projects/writings\">luova kirjoittaja</a>, <a href=\"/projects/dictatorship\">tuleva diktaattori</a> ja <a href=\"/projects/manatee-art\">armoitettu lamantiinikansantaiteilija</a>. Olen yritt&auml;nyt monella saralla, vaihtelevin tuloksin. T&auml;m&auml; kotskaporttaali kaivaa sekametelisopasta syv&auml;llisi&auml;  merkityksi&auml;, joita siin&auml; ei ole, ja pist&auml;&auml; niin kutsutun el&auml;m&auml;ni kertaheitolla pakettiin. Tomusokeria p&auml;&auml;lle ja hyv&auml;lt\' n&auml;ytt&auml;&auml;.</p>','2009-12-26 12:53:25',NULL,NULL,NULL),(14,1,'<h2>Pekkiksen tarina</h2>\n<p>Ei ole tyhmi&auml; kysymyksi&auml;, on vain tyhmi&auml; ihmisi&auml;. Minulta useimmiten kysytty joutava kysymys on: \"Mist&auml; tulee lempinimi <strong>Pekkis</strong>?\" Aivan kuin vastausta ei voisi suoraan johtaa ristim&auml;nimest&auml;ni <em>Mikko Tapani Forsstr&ouml;m</em>. Diletantit.</p>\n<p>Ok. Yrit&auml;n selitt&auml;&auml;. Koittakaa pysy&auml; mukana. Ajatus virtaa t&auml;ss&auml; kohtaa polveilevasti.</p>\n<p>Aloitetaan lapsuudenyst&auml;v&auml;st&auml;ni Antti-Pekasta. Er&auml;&auml;n&auml; p&auml;iv&auml;n&auml; odotin h&auml;nt&auml; kyl&auml;&auml;n. Ovikello soi, rynt&auml;&auml;n innokkaana avaamaan, mutta oven takana ei olekaan AP vaan musta opiskelija myym&auml;ss&auml; tauluja. Antti-Pekka assosioituu v&auml;litt&ouml;m&auml;sti ja ikuisiksi ajoiksi mustaan opiskelijaan.</p>\n<p>Kuten tied&auml;tte, olen valtakunnan virallinen <a href=\"#\">lamantiinitaiteilija</a>. Lukioaikana k&auml;yt&auml;n mahtavia piirt&auml;j&auml;nlahjojani raapustelemaan karikatyyrej&auml; kavereistani. Antti-Pekan karikatyyrist&auml; tulee mustan opiskelijan k&auml;ynnin j&auml;lkeen luonnollisesti Musta Pekka.</p>\n<p>Karikatyyrit seikkalilevat \"sarjakuvissa\", joita tuherran vihkoihin tunnit pitk&auml;t. Er&auml;&auml;ss&auml; episodissa Musta Pekka, joka nyt jostain k&auml;sitt&auml;m&auml;tt&ouml;m&auml;st&auml; syyst&auml; ty&ouml;skentelee lainvalvojana, r&auml;j&auml;ht&auml;&auml; kappaleiksi. Hui kamalaa! Onko arkkivihollinen Cusiyucca (&auml;l&auml; kysy) lopulta voittanut?</p>\n<p>Ei! Musta Pekka ei suostu kuolemaan. Salaisen palvelun l&auml;&auml;k&auml;riryhm&auml; pelastaa miehen henkirievun, mutta Musta Pekka on nyt <em>Pekkanaator</em> - puoliksi kone, puoliksi ihminen ja kokonaan mielipuolista kostoa vannova.</p>\n<p>Hahmoja on monia, mutta yksi osoittautuu rakkaimmaksi. Omaksun peleiss&auml; nimimerkin \"Pekkanaator\", mutta joskus se ei mahdu merkkirajoituksiin. Silloin Pekkanaator lyhentyy muotoon \"Pekkis X\".</p>\n<p>Irc:ss&auml; peleihin omaksuttu alias saa uuden merkityksen. Syntyy ihmisten sukupolvi, joka tuntee minut t&auml;ll&auml; nimimerkill&auml;. Jollain tavalla, salakavalasti, nimi vuotaa tosiel&auml;m&auml;&auml;n ja ryhdyn v&auml;hitellen huomaamatta vastaamaan jos joku minua sill&auml; kutsuu.</p>\n<p>Tilanne k&auml;rjistyy. L&auml;hes kaikki uudet yst&auml;v&auml;ni ja tuttavani ovat ty&ouml;ympyr&ouml;ist&auml; ja/tai Internetist&auml;. Heille olen ensisijaisesti Pekkis, ja joskus joku kysyy minulta mik&auml; on nimeni. Ryhdyn itse kutsumaan itse&auml;ni Pekkikseksi yh&auml; useammissa asiayhteyksiss&auml;.</p>\n<p>Pian allekirjoitan s&auml;hk&ouml;postini nimimerkill&auml;ni, ja puhun itsest&auml;ni joskus kolmannessa persoonassa. On kuin olisin ulkoistanut persoonani. Pekkis tekee sit&auml;, Pekkis tekee t&auml;t&auml;, anna kun Pekkis n&auml;ytt&auml;&auml;. Vain &auml;itilleni, siskolleni ja joillekin vanhoista yst&auml;vist&auml;ni olen Mikko. Heist&auml; vain &auml;iti, jos en&auml;&auml; h&auml;nk&auml;&auml;n, on tiet&auml;m&auml;t&ouml;n minussa tapahtuneesta perustavanlaatuisesta muutoksesta.</p>','2009-12-27 17:05:32',NULL,NULL,NULL);
/*!40000 ALTER TABLE `htmlcontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locale`
--

DROP TABLE IF EXISTS `locale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locale` (
  `locale` char(6) NOT NULL,
  `page_start` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`locale`),
  KEY `page_start` (`page_start`),
  CONSTRAINT `locale_ibfk_1` FOREIGN KEY (`page_start`) REFERENCES `page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locale`
--

LOCK TABLES `locale` WRITE;
/*!40000 ALTER TABLE `locale` DISABLE KEYS */;
INSERT INTO `locale` VALUES ('fi',12);
/*!40000 ALTER TABLE `locale` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locale_option`
--

DROP TABLE IF EXISTS `locale_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locale_option` (
  `locale_locale` char(6) NOT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `strvalue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`locale_locale`,`identifier`),
  CONSTRAINT `locale_option_ibfk_1` FOREIGN KEY (`locale_locale`) REFERENCES `locale` (`locale`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locale_option`
--

LOCK TABLES `locale_option` WRITE;
/*!40000 ALTER TABLE `locale_option` DISABLE KEYS */;
INSERT INTO `locale_option` VALUES ('fi','title','Lamantiini 2k10');
/*!40000 ALTER TABLE `locale_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_redirect`
--

DROP TABLE IF EXISTS `login_redirect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_redirect` (
  `page_id` int(10) unsigned NOT NULL,
  `redirect_page_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`page_id`),
  KEY `redirect_page_id` (`redirect_page_id`),
  CONSTRAINT `login_redirect_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `login_redirect_ibfk_2` FOREIGN KEY (`redirect_page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned NOT NULL,
  `items_per_page` tinyint(4) NOT NULL DEFAULT '10',
  `link_readmore` varchar(255) NOT NULL,
  `allow_syndication` tinyint(4) NOT NULL DEFAULT '1',
  `default_months_valid` tinyint(4) DEFAULT '12',
  `title` varchar(255) NOT NULL,
  `description` text,
  `locale` char(6) DEFAULT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  `managing_editor` varchar(255) DEFAULT NULL,
  `webmaster` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `ttl` smallint(6) NOT NULL DEFAULT '60',
  `skip_hours` varchar(255) DEFAULT NULL,
  `skip_days` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`),
  CONSTRAINT `news_channel_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_channel`
--

LOCK TABLES `news_channel` WRITE;
/*!40000 ALTER TABLE `news_channel` DISABLE KEYS */;
INSERT INTO `news_channel` VALUES (1,21,10,'Lue lisää',1,12,'Nyyssit','Pekkis nyyssit',NULL,NULL,NULL,NULL,NULL,60,NULL,NULL,'2009-12-31 18:58:25',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `news_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_item`
--

DROP TABLE IF EXISTS `news_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_channel_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `article` text,
  `author` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `enclosure` varchar(255) DEFAULT NULL,
  `valid_start` datetime NOT NULL,
  `valid_end` datetime DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `news_channel_id` (`news_channel_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `news_item_ibfk_1` FOREIGN KEY (`news_channel_id`) REFERENCES `news_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `news_item_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `news_item_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_item`
--

LOCK TABLES `news_item` WRITE;
/*!40000 ALTER TABLE `news_item` DISABLE KEYS */;
INSERT INTO `news_item` VALUES (1,1,'Tussiuutinen','Tussilassa on nähty loso','<p>Tussilan veljekset!</p>',NULL,NULL,NULL,NULL,'2010-01-04 00:00:00','2011-01-04 00:00:00','2010-01-04 18:01:39',NULL,NULL,NULL,1),(2,1,'Tussikasvo tän pitäs olla eka','Tussitesti 2','<p>Tussutan lussia</p>',NULL,NULL,NULL,NULL,'2010-01-01 00:00:00','2011-01-04 00:00:00','2010-01-04 18:13:15',NULL,NULL,NULL,1),(3,1,'Tussilan vaarilla oli talo','Tussi lussi mussi','<p>Na na naa naa, hey hey hey, good bye!</p>',NULL,NULL,NULL,NULL,'2010-01-04 00:00:00','2011-01-04 00:00:00','2010-01-04 18:14:15',NULL,NULL,NULL,1),(4,1,'Lusautapa tussia','Tusander','<p>Tussin lussutus uutinen ei ole kirjoitettu viel&auml; oikein ja se on losokka</p>',NULL,NULL,NULL,NULL,'2010-01-05 00:00:00','2011-01-07 00:00:00','2010-01-04 18:21:50',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `news_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `locale` char(6) NOT NULL,
  `order_id` smallint(6) NOT NULL DEFAULT '0',
  `layout` varchar(255) DEFAULT 'Default',
  `title` varchar(255) NOT NULL,
  `beautifurl` varchar(1000) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `shard_id` int(10) unsigned NOT NULL,
  `visibility` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id_title` (`parent_id`,`title`),
  KEY `parent_id` (`parent_id`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `shard_id` (`shard_id`),
  KEY `locale` (`locale`),
  KEY `iisiurl_index` (`beautifurl`(255)),
  CONSTRAINT `page_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_3` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_4` FOREIGN KEY (`shard_id`) REFERENCES `shard` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `page_ibfk_5` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (12,NULL,'fi',0,'Default','Etusivu','fi/etusivu','[12]',1,1,'2009-12-26 12:39:49',NULL,NULL,NULL,0),(13,NULL,'fi',0,'Default','Lätinää','fi/latinaa','[13]',1,1,'2009-12-27 16:35:05',NULL,NULL,NULL,0),(14,13,'fi',0,'Default','Pekkiksen synty','fi/latinaa/pekkiksen_synty','[13];[14]',1,1,'2009-12-27 16:36:18',NULL,NULL,NULL,0),(21,NULL,'fi',0,'Default','Nyyssit','fi/nyyssit','[21]',5,1,'2009-12-30 19:09:08',NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_folder_ugroup`
--

DROP TABLE IF EXISTS `permission_folder_ugroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_folder_ugroup` (
  `folder_id` int(10) unsigned NOT NULL,
  `ugroup_id` int(10) unsigned NOT NULL,
  `permission` int(10) unsigned NOT NULL,
  PRIMARY KEY (`folder_id`,`ugroup_id`),
  KEY `ugroup_id` (`ugroup_id`),
  CONSTRAINT `permission_folder_ugroup_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `filelib_folder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_folder_ugroup_ibfk_2` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_folder_ugroup`
--

LOCK TABLES `permission_folder_ugroup` WRITE;
/*!40000 ALTER TABLE `permission_folder_ugroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_folder_ugroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_page_ugroup`
--

DROP TABLE IF EXISTS `permission_page_ugroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_page_ugroup` (
  `page_id` int(10) unsigned NOT NULL,
  `ugroup_id` int(10) unsigned NOT NULL,
  `permission` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`,`ugroup_id`),
  KEY `ugroup_id` (`ugroup_id`),
  CONSTRAINT `permission_page_ugroup_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_page_ugroup_ibfk_2` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_page_ugroup`
--

LOCK TABLES `permission_page_ugroup` WRITE;
/*!40000 ALTER TABLE `permission_page_ugroup` DISABLE KEYS */;
INSERT INTO `permission_page_ugroup` VALUES (12,1,4),(12,2,7),(13,1,5),(13,2,7),(14,1,4),(14,2,7),(21,1,4),(21,2,7);
/*!40000 ALTER TABLE `permission_page_ugroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shard`
--

DROP TABLE IF EXISTS `shard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shard` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL DEFAULT 'core',
  `controller` varchar(255) NOT NULL DEFAULT 'index',
  `action` varchar(255) NOT NULL DEFAULT 'index',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shard`
--

LOCK TABLES `shard` WRITE;
/*!40000 ALTER TABLE `shard` DISABLE KEYS */;
INSERT INTO `shard` VALUES (1,'Htmlcontent','core','html-content','index',3),(2,'Breadcrumb','core','index','index',1),(3,'Menu','core','index','index',1),(4,'Sitemap','core','index','index',1),(5,'News','core','news','index',3),(7,'Formcontent','core','index','index',3),(9,'Login','core','index','page',3),(10,'Randomimage','core','index','index',3);
/*!40000 ALTER TABLE `shard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ugroup`
--

DROP TABLE IF EXISTS `ugroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ugroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `passwd` char(32) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (8,'root@example.com','bd9059497b4af2bb913a8522747af2de',NULL,NULL,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_option`
--

DROP TABLE IF EXISTS `user_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_option` (
  `user_id` int(10) unsigned NOT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `strvalue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`identifier`),
  CONSTRAINT `user_option_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_ugroup` (
  `user_id` int(10) unsigned NOT NULL,
  `ugroup_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`ugroup_id`),
  KEY `group_id` (`ugroup_id`),
  CONSTRAINT `user_ugroup_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_ugroup_ibfk_2` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_ugroup`
--

LOCK TABLES `user_ugroup` WRITE;
/*!40000 ALTER TABLE `user_ugroup` DISABLE KEYS */;
INSERT INTO `user_ugroup` VALUES (8,2);
/*!40000 ALTER TABLE `user_ugroup` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-01-04 23:13:46
