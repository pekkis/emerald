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

CREATE TABLE emerald_filelib_folder (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  parent_id int(10) unsigned DEFAULT NULL,
  foldername varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY parent_id_name (parent_id, foldername),
  KEY parent_id (parent_id),
  CONSTRAINT filelib_folder_ibfk_1 FOREIGN KEY (parent_id) REFERENCES emerald_filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

CREATE TABLE emerald_filelib_file (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  folder_id int(10) unsigned NOT NULL,
  mimetype varchar(255) NOT NULL,
  fileprofile varchar(255) NOT NULL DEFAULT 'default',
  filesize int(11) DEFAULT NULL,
  filename varchar(255) NOT NULL,
  filelink varchar(1000) DEFAULT NULL,
  date_uploaded datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (filename,folder_id),
  KEY folder_id (folder_id),
  KEY mimetype (mimetype),
  CONSTRAINT filelib_file_ibfk_1 FOREIGN KEY (folder_id) REFERENCES emerald_filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS emerald_application_option;

CREATE TABLE emerald_application_option (
  identifier varchar(255) NOT NULL DEFAULT '',
  strvalue varchar(255) DEFAULT NULL,
  PRIMARY KEY (identifier)
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_form;

CREATE TABLE emerald_form (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  description text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`)
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_form_field;

CREATE TABLE emerald_form_field (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  form_id int(10) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  order_id smallint(6) NOT NULL DEFAULT '0',
  title varchar(255) DEFAULT NULL,
  mandatory tinyint(4) NOT NULL DEFAULT '0',
  `options` text,
  PRIMARY KEY (id),
  KEY form_id (form_id),
  CONSTRAINT form_field_ibfk_1 FOREIGN KEY (form_id) REFERENCES emerald_form (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_formcontent;

CREATE TABLE emerald_formcontent (
  page_id int(10) unsigned NOT NULL,
  form_id int(10) unsigned DEFAULT NULL,
  email_subject varchar(255) NOT NULL,
  email_from varchar(255) NOT NULL,
  email_to varchar(255) NOT NULL,
  form_lock tinyint(4) NOT NULL DEFAULT '0',
  redirect_page_id int(10) unsigned NOT NULL,
  PRIMARY KEY (page_id),
  KEY form_id (form_id),
  KEY redirect_page_id (redirect_page_id),
  CONSTRAINT formcontent_ibfk_1 FOREIGN KEY (page_id) REFERENCES `emerald_page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT formcontent_ibfk_2 FOREIGN KEY (form_id) REFERENCES emerald_form (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT formcontent_ibfk_3 FOREIGN KEY (redirect_page_id) REFERENCES `emerald_page` (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_htmlcontent;

CREATE TABLE emerald_htmlcontent (
  page_id int(10) unsigned NOT NULL,
  block_id int(10) unsigned NOT NULL,
  content text,
  PRIMARY KEY (page_id,block_id),
  CONSTRAINT htmlcontent_ibfk_1 FOREIGN KEY (page_id) REFERENCES `emerald_page` (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS emerald_locale;

CREATE TABLE emerald_locale (
  locale char(6) NOT NULL,
  page_start int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (locale),
  KEY page_start (page_start),
  CONSTRAINT locale_ibfk_1 FOREIGN KEY (page_start) REFERENCES `emerald_page` (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS locale_option;

CREATE TABLE emerald_locale_option (
  locale_locale char(6) NOT NULL,
  identifier varchar(255) NOT NULL DEFAULT '',
  strvalue varchar(255) DEFAULT NULL,
  PRIMARY KEY (locale_locale,identifier),
  CONSTRAINT locale_option_ibfk_1 FOREIGN KEY (locale_locale) REFERENCES emerald_locale (locale) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS news_channel;

CREATE TABLE emerald_news_channel (
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
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY page_id (page_id),
  CONSTRAINT news_channel_ibfk_1 FOREIGN KEY (page_id) REFERENCES `emerald_page` (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_news_item;

CREATE TABLE emerald_news_item (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  news_channel_id int(10) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  description text,
  article text,
  taggable_id integer unsigned NULL,
  author varchar(255) DEFAULT NULL,
  category varchar(255) DEFAULT NULL,
  comments varchar(255) DEFAULT NULL,
  enclosure varchar(255) DEFAULT NULL,
  valid_start datetime NOT NULL,
  valid_end datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY news_channel_id (news_channel_id),
  CONSTRAINT news_item_ibfk_1 FOREIGN KEY (news_channel_id) REFERENCES emerald_news_channel (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_page_global;

CREATE TABLE emerald_page_global
(
id integer unsigned NOT NULL AUTO_INCREMENT,
PRIMARY KEY(id)
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_page;

CREATE TABLE `emerald_page` (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  global_id integer unsigned NOT NULL,
  locale char(6) NOT NULL,
  parent_id int(10) unsigned DEFAULT NULL,
  order_id smallint(6) NOT NULL DEFAULT '0',
  layout varchar(255) DEFAULT 'Default',
  title varchar(255) NOT NULL,
  beautifurl varchar(1000) DEFAULT NULL,
  customurl varchar(1000) DEFAULT NULL,
  path varchar(255) NULL,
  shard_id int(10) unsigned NOT NULL,
  visibility tinyint(3) unsigned NOT NULL DEFAULT '1',
  cache_seconds integer unsigned NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT '0',
  redirect_id integer unsigned NULL,
  taggable_id integer NULL,
  class_css varchar(255) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (global_id, locale),
  UNIQUE KEY parent_id_title (parent_id,title),
  KEY parent_id (parent_id),
  KEY shard_id (shard_id),
  KEY locale (locale),
  KEY iisiurl_index (beautifurl(255)),
  CONSTRAINT page_ibfk_1 FOREIGN KEY (global_id) REFERENCES emerald_page_global(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT page_ibfk_2 FOREIGN KEY (parent_id) REFERENCES `emerald_page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT page_ibfk_3 FOREIGN KEY (shard_id) REFERENCES emerald_shard (id) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT page_ibfk_4 FOREIGN KEY (locale) REFERENCES emerald_locale (locale) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY(redirect_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_permission_folder_ugroup;

CREATE TABLE emerald_permission_folder_ugroup (
  folder_id int(10) unsigned NOT NULL,
  ugroup_id int(10) unsigned NOT NULL,
  permission int(10) unsigned NOT NULL,
  PRIMARY KEY (folder_id,ugroup_id),
  KEY ugroup_id (ugroup_id),
  CONSTRAINT permission_folder_ugroup_ibfk_1 FOREIGN KEY (folder_id) REFERENCES emerald_filelib_folder (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT permission_folder_ugroup_ibfk_2 FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_permission_page_ugroup;

CREATE TABLE emerald_permission_page_ugroup (
  page_id int(10) unsigned NOT NULL,
  ugroup_id int(10) unsigned NOT NULL,
  permission int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (page_id,ugroup_id),
  KEY ugroup_id (ugroup_id),
  CONSTRAINT permission_page_ugroup_ibfk_1 FOREIGN KEY (page_id) REFERENCES `emerald_page` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT permission_page_ugroup_ibfk_2 FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_shard;

CREATE TABLE emerald_shard (
  id int(10) unsigned NOT NULL,
  namespace varchar(255) NOT NULL default 'EmCore',
  `name` varchar(255) NOT NULL,
  module varchar(255) NOT NULL DEFAULT 'core',
  controller varchar(255) NOT NULL DEFAULT 'index',
  `action` varchar(255) NOT NULL DEFAULT 'index',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`)
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_ugroup;

CREATE TABLE emerald_ugroup (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`)
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_user;

CREATE TABLE `emerald_user` (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  passwd varchar(255) NOT NULL,
  firstname varchar(255) DEFAULT NULL,
  lastname varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS emerald_user_option;

CREATE TABLE emerald_user_option (
  user_id int(10) unsigned NOT NULL,
  identifier varchar(255) NOT NULL DEFAULT '',
  strvalue varchar(255) DEFAULT NULL,
  PRIMARY KEY (user_id,identifier),
  CONSTRAINT user_option_ibfk_1 FOREIGN KEY (user_id) REFERENCES `emerald_user` (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `user_ugroup`
--

DROP TABLE IF EXISTS emerald_user_ugroup;

CREATE TABLE emerald_user_ugroup (
  user_id int(10) unsigned NOT NULL,
  ugroup_id int(10) unsigned NOT NULL,
  PRIMARY KEY (user_id,ugroup_id),
  KEY group_id (ugroup_id),
  CONSTRAINT user_ugroup_ibfk_1 FOREIGN KEY (user_id) REFERENCES `emerald_user` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT user_ugroup_ibfk_2 FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=XtraDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES(1, 'Html', 'em-core', 'html-content', 'index', 3);
INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES(2, 'Form', 'em-core', 'form-content', 'index', 3);
INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES(3, 'News', 'em-core', 'news', 'index', 3);

CREATE UNIQUE INDEX emerald_page_beautifurl_idx ON emerald_page (beautifurl);

CREATE TABLE emerald_permission_locale_ugroup (
  locale_locale varchar(6) NOT NULL,
  ugroup_id integer unsigned NOT NULL,
  permission smallint unsigned NOT NULL,
  PRIMARY KEY (locale_locale,ugroup_id),
  FOREIGN KEY (locale_locale) REFERENCES emerald_locale (locale) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) engine=XtraDB;

CREATE TABLE emerald_activity (
  id integer unsigned NOT NULL auto_increment,
  category varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (category,name)
) engine=XtraDB;

CREATE TABLE emerald_permission_activity_ugroup
(
activity_id int unsigned not null,
ugroup_id int unsigned NOT NULL,
PRIMARY KEY (activity_id, ugroup_id),
FOREIGN KEY(activity_id) REFERENCES emerald_activity (id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) engine=XtraDB;

INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Edit activity permissions');
INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Clear caches');
INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Expose admin panel');

CREATE TABLE emerald_customcontent
(
page_id integer unsigned NOT NULL,
block_id integer unsigned NOT NULL,
module varchar(255) NULL,
controller varchar(255) NULL,
action varchar(255) NULL,
params varchar(1000) NULL,
PRIMARY KEY(page_id, block_id),
FOREIGN KEY(page_id) REFERENCES emerald_page(id) ON DELETE CASCADE ON UPDATE CASCADE
) engine=XtraDB;

INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES (4, 'Custom', 'em-core', 'custom-content', 'index', 3);

UPDATE emerald_activity SET category = 'administration';
UPDATE emerald_activity set name = 'expose' WHERE id = 3;
UPDATE emerald_activity set name = 'edit_activities' WHERE id = 1;
UPDATE emerald_activity set name = 'clear_caches' WHERE id = 2;

INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_users');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_locales');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_forms');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_options');

CREATE TABLE emerald_tag
(
id integer unsigned NOT NULL auto_increment,
name varchar(255) NOT NULL,
PRIMARY KEY(id),
UNIQUE(name)
) engine=XtraDB;

CREATE TABLE emerald_taggable
(
id integer unsigned NOT NULL auto_increment,
type varchar(255) NOT NULL,
PRIMARY KEY(id)
) engine=xtraDB;

CREATE TABLE emerald_taggable_tag
(
taggable_id integer unsigned NOT NULL,
tag_id integer unsigned NOT NULL,
PRIMARY KEY(taggable_id, tag_id),
FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY(tag_id) REFERENCES emerald_tag(id) ON DELETE CASCADE ON UPDATE CASCADE
) engine=XtraDB;

CREATE INDEX taggable_type_idx ON emerald_taggable (type);

ALTER TABLE emerald_news_item ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE emerald_page ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES (5, 'Tag Cloud', 'em-core', 'tag', 'cloud', 3);



