DROP TABLE IF EXISTS `filelib_file`;
DROP TABLE IF EXISTS `filelib_folder`;

CREATE TABLE `filelib_folder` (
  `id` integer unsigned NOT NULL auto_increment,
  `parent_id` integer unsigned default NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NULL,
  `modified` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `parent_id_name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `filelib_folder_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `filelib_file` (
  `id` integer unsigned NOT NULL auto_increment,
  `folder_id` integer unsigned NOT NULL,
  `mimetype` varchar(255) NOT NULL,
  `size` int(11) default NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(1000) NULL,
  `path` varchar(255) NOT NULL,
  `created` timestamp NULL,
  `modified` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`,`folder_id`),
  KEY `folder_id` (`folder_id`),
  KEY `mimetype` (`mimetype`),
  CONSTRAINT `filelib_file_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `filelib_folder` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

