CREATE TABLE emerald_filelib_folder (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  parent_id int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  visible tinyint unsigned NOT NULL default 1,
  PRIMARY KEY (id),
  UNIQUE KEY parent_id_name (parent_id,`name`),
  KEY parent_id (parent_id),
  CONSTRAINT filelib_folder_ibfk_1 FOREIGN KEY (parent_id) REFERENCES emerald_filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE emerald_filelib_file (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  folder_id int(10) unsigned NOT NULL,
  mimetype varchar(255) NOT NULL,
  profile varchar(255) NOT NULL DEFAULT 'default',
  size int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  link varchar(1000) DEFAULT NULL,
  path varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY `name` (`name`,folder_id),
  KEY folder_id (folder_id),
  KEY mimetype (mimetype),
  CONSTRAINT filelib_file_ibfk_1 FOREIGN KEY (folder_id) REFERENCES emerald_filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


