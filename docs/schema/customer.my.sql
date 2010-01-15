-- MySQL dump 10.13  Distrib 5.1.40, for apple-darwin10.2.0 (i386)
--
-- Host: localhost    Database: lemerald
-- ------------------------------------------------------
-- Server version	5.1.40-log
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS application_option;

CREATE TABLE application_option (
  identifier varchar(255) NOT NULL DEFAULT '',
  strvalue varchar(255) DEFAULT NULL,
  PRIMARY KEY (identifier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS filelib_file;

CREATE TABLE filelib_file (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  folder_id int(10) unsigned NOT NULL,
  mimetype varchar(255) NOT NULL,
  size int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  link varchar(1000) DEFAULT NULL,
  path varchar(255) NOT NULL,
  created timestamp NULL DEFAULT NULL,
  modified timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`,folder_id),
  KEY folder_id (folder_id),
  KEY mimetype (mimetype),
  CONSTRAINT filelib_file_ibfk_1 FOREIGN KEY (folder_id) REFERENCES filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS filelib_folder;

CREATE TABLE filelib_folder (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  parent_id int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  created timestamp NULL DEFAULT NULL,
  modified timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY parent_id_name (parent_id,`name`),
  KEY parent_id (parent_id),
  CONSTRAINT filelib_folder_ibfk_1 FOREIGN KEY (parent_id) REFERENCES filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS form;

CREATE TABLE form (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  description text NOT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified timestamp NULL DEFAULT NULL,
  created_by int(10) unsigned DEFAULT NULL,
  modified_by int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`),
  KEY created_by (created_by),
  KEY modified_by (modified_by),
  CONSTRAINT form_ibfk_1 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT form_ibfk_2 FOREIGN KEY (modified_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS form_field;

CREATE TABLE form_field (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  form_id int(10) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  order_id smallint(6) NOT NULL DEFAULT '0',
  title varchar(255) DEFAULT NULL,
  mandatory tinyint(4) NOT NULL DEFAULT '0',
  `options` text,
  PRIMARY KEY (id),
  KEY form_id (form_id),
  CONSTRAINT form_field_ibfk_1 FOREIGN KEY (form_id) REFERENCES form (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS formcontent;

CREATE TABLE formcontent (
  page_id int(10) unsigned NOT NULL,
  form_id int(10) unsigned DEFAULT NULL,
  email_subject varchar(255) NOT NULL,
  email_from varchar(255) NOT NULL,
  email_to varchar(255) NOT NULL,
  form_lock tinyint(4) NOT NULL DEFAULT '0',
  redirect_page_id int(10) unsigned NOT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified timestamp NULL DEFAULT NULL,
  created_by int(10) unsigned DEFAULT NULL,
  modified_by int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (page_id),
  KEY form_id (form_id),
  KEY redirect_page_id (redirect_page_id),
  KEY created_by (created_by),
  KEY modified_by (modified_by),
  CONSTRAINT formcontent_ibfk_1 FOREIGN KEY (page_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT formcontent_ibfk_2 FOREIGN KEY (form_id) REFERENCES form (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT formcontent_ibfk_3 FOREIGN KEY (redirect_page_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT formcontent_ibfk_4 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT formcontent_ibfk_5 FOREIGN KEY (modified_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS htmlcontent;

CREATE TABLE htmlcontent (
  page_id int(10) unsigned NOT NULL,
  block_id int(10) unsigned NOT NULL,
  content text,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified timestamp NULL DEFAULT NULL,
  created_by int(10) unsigned DEFAULT NULL,
  modified_by int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (page_id,block_id),
  KEY created_by (created_by),
  KEY modified_by (modified_by),
  CONSTRAINT htmlcontent_ibfk_1 FOREIGN KEY (page_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT htmlcontent_ibfk_2 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT htmlcontent_ibfk_3 FOREIGN KEY (modified_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS locale;

CREATE TABLE locale (
  locale char(6) NOT NULL,
  page_start int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (locale),
  KEY page_start (page_start),
  CONSTRAINT locale_ibfk_1 FOREIGN KEY (page_start) REFERENCES `page` (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS locale_option;

CREATE TABLE locale_option (
  locale_locale char(6) NOT NULL,
  identifier varchar(255) NOT NULL DEFAULT '',
  strvalue varchar(255) DEFAULT NULL,
  PRIMARY KEY (locale_locale,identifier),
  CONSTRAINT locale_option_ibfk_1 FOREIGN KEY (locale_locale) REFERENCES locale (locale) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS login_redirect;

CREATE TABLE login_redirect (
  page_id int(10) unsigned NOT NULL,
  redirect_page_id int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (page_id),
  KEY redirect_page_id (redirect_page_id),
  CONSTRAINT login_redirect_ibfk_1 FOREIGN KEY (page_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT login_redirect_ibfk_2 FOREIGN KEY (redirect_page_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS news_channel;

CREATE TABLE news_channel (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  page_id int(10) unsigned NOT NULL,
  items_per_page tinyint(4) NOT NULL DEFAULT '10',
  link_readmore varchar(255) NOT NULL,
  allow_syndication tinyint(4) NOT NULL DEFAULT '1',
  default_months_valid tinyint(4) DEFAULT '12',
  title varchar(255) NOT NULL,
  description text,
  locale char(6) DEFAULT NULL,
  copyright varchar(255) DEFAULT NULL,
  managing_editor varchar(255) DEFAULT NULL,
  webmaster varchar(255) DEFAULT NULL,
  category varchar(255) DEFAULT NULL,
  ttl smallint(6) NOT NULL DEFAULT '60',
  skip_hours varchar(255) DEFAULT NULL,
  skip_days varchar(255) DEFAULT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified timestamp NULL DEFAULT NULL,
  created_by int(10) unsigned DEFAULT NULL,
  modified_by int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY page_id (page_id),
  CONSTRAINT news_channel_ibfk_1 FOREIGN KEY (page_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS news_item;

CREATE TABLE news_item (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  news_channel_id int(10) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  description text,
  article text,
  author varchar(255) DEFAULT NULL,
  category varchar(255) DEFAULT NULL,
  comments varchar(255) DEFAULT NULL,
  enclosure varchar(255) DEFAULT NULL,
  valid_start datetime NOT NULL,
  valid_end datetime DEFAULT NULL,
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified timestamp NULL DEFAULT NULL,
  created_by int(10) unsigned DEFAULT NULL,
  modified_by int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY news_channel_id (news_channel_id),
  KEY created_by (created_by),
  KEY modified_by (modified_by),
  CONSTRAINT news_item_ibfk_1 FOREIGN KEY (news_channel_id) REFERENCES news_channel (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT news_item_ibfk_2 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT news_item_ibfk_3 FOREIGN KEY (modified_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS page;

CREATE TABLE `page` (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  parent_id int(10) unsigned DEFAULT NULL,
  locale char(6) NOT NULL,
  order_id smallint(6) NOT NULL DEFAULT '0',
  layout varchar(255) DEFAULT 'Default',
  title varchar(255) NOT NULL,
  beautifurl varchar(1000) DEFAULT NULL,
  path varchar(255) NULL,
  shard_id int(10) unsigned NOT NULL,
  visibility tinyint(3) unsigned NOT NULL DEFAULT '1',
  created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified timestamp NULL DEFAULT NULL,
  created_by int(10) unsigned DEFAULT NULL,
  modified_by int(10) unsigned DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY parent_id_title (parent_id,title),
  KEY parent_id (parent_id),
  KEY created_by (created_by),
  KEY modified_by (modified_by),
  KEY shard_id (shard_id),
  KEY locale (locale),
  KEY iisiurl_index (beautifurl(255)),
  CONSTRAINT page_ibfk_1 FOREIGN KEY (parent_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT page_ibfk_2 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT page_ibfk_3 FOREIGN KEY (modified_by) REFERENCES `user` (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT page_ibfk_4 FOREIGN KEY (shard_id) REFERENCES shard (id) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT page_ibfk_5 FOREIGN KEY (locale) REFERENCES locale (locale) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS permission_folder_ugroup;

CREATE TABLE permission_folder_ugroup (
  folder_id int(10) unsigned NOT NULL,
  ugroup_id int(10) unsigned NOT NULL,
  permission int(10) unsigned NOT NULL,
  PRIMARY KEY (folder_id,ugroup_id),
  KEY ugroup_id (ugroup_id),
  CONSTRAINT permission_folder_ugroup_ibfk_1 FOREIGN KEY (folder_id) REFERENCES filelib_folder (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT permission_folder_ugroup_ibfk_2 FOREIGN KEY (ugroup_id) REFERENCES ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS permission_page_ugroup;

CREATE TABLE permission_page_ugroup (
  page_id int(10) unsigned NOT NULL,
  ugroup_id int(10) unsigned NOT NULL,
  permission int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (page_id,ugroup_id),
  KEY ugroup_id (ugroup_id),
  CONSTRAINT permission_page_ugroup_ibfk_1 FOREIGN KEY (page_id) REFERENCES `page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT permission_page_ugroup_ibfk_2 FOREIGN KEY (ugroup_id) REFERENCES ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS shard;

CREATE TABLE shard (
  id int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  module varchar(255) NOT NULL DEFAULT 'core',
  controller varchar(255) NOT NULL DEFAULT 'index',
  `action` varchar(255) NOT NULL DEFAULT 'index',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ugroup;

CREATE TABLE ugroup (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS user;

CREATE TABLE `user` (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  passwd char(32) NOT NULL,
  firstname varchar(255) DEFAULT NULL,
  lastname varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS user_option;

CREATE TABLE user_option (
  user_id int(10) unsigned NOT NULL,
  identifier varchar(255) NOT NULL DEFAULT '',
  strvalue varchar(255) DEFAULT NULL,
  PRIMARY KEY (user_id,identifier),
  CONSTRAINT user_option_ibfk_1 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `user_ugroup`
--

DROP TABLE IF EXISTS user_ugroup;

CREATE TABLE user_ugroup (
  user_id int(10) unsigned NOT NULL,
  ugroup_id int(10) unsigned NOT NULL,
  PRIMARY KEY (user_id,ugroup_id),
  KEY group_id (ugroup_id),
  CONSTRAINT user_ugroup_ibfk_1 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT user_ugroup_ibfk_2 FOREIGN KEY (ugroup_id) REFERENCES ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


INSERT INTO shard (id, name, module, controller, action, status) VALUES(1, 'Html', 'core', 'html-content', 'index', 3);
INSERT INTO shard (id, name, module, controller, action, status) VALUES(2, 'Form', 'core', 'form-content', 'index', 3);
INSERT INTO shard (id, name, module, controller, action, status) VALUES(3, 'News', 'core', 'news', 'index', 3);


CREATE UNIQUE INDEX page_beautifurl_idx ON page (beautifurl);

