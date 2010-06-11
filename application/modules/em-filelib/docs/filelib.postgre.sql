CREATE SEQUENCE emerald_filelib_folder_id_seq;

CREATE TABLE "emerald_filelib_folder" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_filelib_folder_id_seq'),
  "parent_id" int  DEFAULT NULL,
  "name" varchar(255) NOT NULL,
  "visible" smallint NOT NULL default 1,
  PRIMARY KEY ("id"),
  UNIQUE ("parent_id","name"),
    FOREIGN KEY ("parent_id") REFERENCES "emerald_filelib_folder" ("id") ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE SEQUENCE emerald_filelib_file_id_seq;

CREATE TABLE "emerald_filelib_file" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_filelib_file_id_seq'),
  "folder_id" int  NOT NULL,
  "mimetype" varchar(255) NOT NULL,
  "profile" varchar(255) NOT NULL DEFAULT 'default',
  "size" int DEFAULT NULL,
  "name" varchar(255) NOT NULL,
  "link" varchar(1000) DEFAULT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("name","folder_id"),
      FOREIGN KEY ("folder_id") REFERENCES "emerald_filelib_folder" ("id") ON DELETE NO ACTION ON UPDATE CASCADE
);
