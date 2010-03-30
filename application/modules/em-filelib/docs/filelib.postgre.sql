CREATE SEQUENCE filelib_folder_id_seq;

CREATE TABLE filelib_folder
(
  id integer NOT NULL DEFAULT NEXTVAL('filelib_folder_id_seq'),
  parent_id integer  default NULL,
  name varchar(255) NOT NULL,
  created timestamp NULL,
  modified timestamp NULL,
  PRIMARY KEY (id),
  UNIQUE (parent_id,name),
  FOREIGN KEY (parent_id) REFERENCES filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE SEQUENCE filelib_file_id_seq;

CREATE TABLE filelib_file
(
  id integer NOT NULL DEFAULT NEXTVAL('filelib_file_id_seq'),
  folder_id integer  NOT NULL,
  mimetype varchar(255) NOT NULL,
  size integer default NULL,
  name varchar(255) NOT NULL,
  link varchar(1000) NULL,
  path varchar(255) NOT NULL,
  created timestamp NULL,
  modified timestamp NULL,
  PRIMARY KEY (id),
  UNIQUE (name,folder_id),
  FOREIGN KEY (folder_id) REFERENCES filelib_folder (id) ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE INDEX mimetype ON filelib_file(mimetype);


