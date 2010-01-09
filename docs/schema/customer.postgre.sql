
CREATE TABLE "application_option" (
  "identifier" varchar(255) NOT NULL DEFAULT '',
  "strvalue" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("identifier")
);

CREATE SEQUENCE filelib_folder_id_seq;


CREATE TABLE "filelib_folder" (
  "id" int  NOT NULL DEFAULT NEXTVAL('filelib_folder_id_seq'),
  "parent_id" int  DEFAULT NULL,
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("parent_id","name"),
    FOREIGN KEY ("parent_id") REFERENCES "filelib_folder" ("id") ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE SEQUENCE form_id_seq;

CREATE TABLE "form" (
  "id" int  NOT NULL DEFAULT NEXTVAL('form_id_seq'),
  "name" varchar(255) NOT NULL,
  "description" text NOT NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE SEQUENCE form_field_id_seq;

CREATE TABLE "form_field" (
  "id" int  NOT NULL DEFAULT NEXTVAL('form_field_id_seq'),
  "form_id" int  NOT NULL,
  "type" smallint NOT NULL,
  "order_id" smallint NOT NULL DEFAULT '0',
  "title" varchar(255) DEFAULT NULL,
  "mandatory" smallint NOT NULL DEFAULT '0',
  "options" text,
  PRIMARY KEY ("id"),
    FOREIGN KEY ("form_id") REFERENCES "form" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "shard" (
  "id" int  NOT NULL,
  "name" varchar(255) NOT NULL,
  "module" varchar(255) NOT NULL DEFAULT 'core',
  "controller" varchar(255) NOT NULL DEFAULT 'index',
  "action" varchar(255) NOT NULL DEFAULT 'index',
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE SEQUENCE ugroup_id_seq;


CREATE TABLE "ugroup" (
  "id" int  NOT NULL DEFAULT NEXTVAL('ugroup_id_seq'),
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE SEQUENCE user_id_seq;

CREATE TABLE "user" (
  "id" int  NOT NULL DEFAULT NEXTVAL('user_id_seq'),
  "email" varchar(255) NOT NULL,
  "passwd" char(32) NOT NULL,
  "firstname" varchar(255) DEFAULT NULL,
  "lastname" varchar(255) DEFAULT NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("email")
);


CREATE TABLE "user_option" (
  "user_id" int  NOT NULL,
  "identifier" varchar(255) NOT NULL DEFAULT '',
  "strvalue" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("user_id","identifier"),
  FOREIGN KEY ("user_id") REFERENCES "user" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "user_ugroup" (
  "user_id" int  NOT NULL,
  "ugroup_id" int  NOT NULL,
  PRIMARY KEY ("user_id","ugroup_id"),
    FOREIGN KEY ("user_id") REFERENCES "user" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE SEQUENCE filelib_file_id_seq;

CREATE TABLE "filelib_file" (
  "id" int  NOT NULL DEFAULT NEXTVAL('filelib_file_id_seq'),
  "folder_id" int  NOT NULL,
  "mimetype" varchar(255) NOT NULL,
  "size" int DEFAULT NULL,
  "name" varchar(255) NOT NULL,
  "link" varchar(1000) DEFAULT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("name","folder_id"),
      FOREIGN KEY ("folder_id") REFERENCES "filelib_folder" ("id") ON DELETE NO ACTION ON UPDATE CASCADE
);


CREATE TABLE "permission_folder_ugroup" (
  "folder_id" int  NOT NULL,
  "ugroup_id" int  NOT NULL,
  "permission" smallint  NOT NULL,
  PRIMARY KEY ("folder_id","ugroup_id"),
    FOREIGN KEY ("folder_id") REFERENCES "filelib_folder" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "locale" (
  "locale" varchar(6) NOT NULL,
  "page_start" int  DEFAULT NULL,
  PRIMARY KEY ("locale")
);

CREATE TABLE "locale_option" (
  "locale_locale" varchar(6) NOT NULL,
  "identifier" varchar(255) NOT NULL DEFAULT '',
  "strvalue" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("locale_locale","identifier"),
  FOREIGN KEY ("locale_locale") REFERENCES "locale" ("locale") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE SEQUENCE news_channel_id_seq;

CREATE SEQUENCE page_id_seq;

CREATE TABLE "page" (
  "id" int  NOT NULL DEFAULT NEXTVAL('page_id_seq'),
  "parent_id" int  DEFAULT NULL,
  "locale" varchar(6) NOT NULL,
  "order_id" smallint NOT NULL DEFAULT '0',
  "layout" varchar(255) DEFAULT 'Default',
  "title" varchar(255) NOT NULL,
  "beautifurl" varchar(1000) DEFAULT NULL,
  "path" varchar(255) NOT NULL,
  "shard_id" int  NOT NULL,
  "visibility" smallint  NOT NULL DEFAULT '1',
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("parent_id","title"),
  FOREIGN KEY ("parent_id") REFERENCES "page" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("shard_id") REFERENCES "shard" ("id") ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY ("locale") REFERENCES "locale" ("locale") ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE "permission_page_ugroup" (
  "page_id" int  NOT NULL,
  "ugroup_id" int  NOT NULL,
  "permission" smallint  NOT NULL,
  PRIMARY KEY ("page_id","ugroup_id"),
    FOREIGN KEY ("page_id") REFERENCES "page" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE "news_channel" (
  "id" int  NOT NULL DEFAULT NEXTVAL('news_channel_id_seq'),
  "page_id" int  NOT NULL,
  "items_per_page" smallint NOT NULL DEFAULT '10',
  "link_readmore" varchar(255) NOT NULL,
  "allow_syndication" smallint NOT NULL DEFAULT '1',
  "default_months_valid" smallint DEFAULT '12',
  "title" varchar(255) NOT NULL,
  "description" text,
  "locale" varchar(6) DEFAULT NULL,
  "copyright" varchar(255) DEFAULT NULL,
  "managing_editor" varchar(255) DEFAULT NULL,
  "webmaster" varchar(255) DEFAULT NULL,
  "category" varchar(255) DEFAULT NULL,
  "ttl" smallint NOT NULL DEFAULT '60',
  "skip_hours" varchar(255) DEFAULT NULL,
  "skip_days" varchar(255) DEFAULT NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("page_id"),
  FOREIGN KEY ("page_id") REFERENCES "page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE SEQUENCE news_item_id_seq;

CREATE TABLE "news_item" (
  "id" int  NOT NULL DEFAULT NEXTVAL('news_item_id_seq'),
  "news_channel_id" int  NOT NULL,
  "title" varchar(255) NOT NULL,
  "description" text,
  "article" text,
  "author" varchar(255) DEFAULT NULL,
  "category" varchar(255) DEFAULT NULL,
  "comments" varchar(255) DEFAULT NULL,
  "enclosure" varchar(255) DEFAULT NULL,
  "valid_start" timestamp NOT NULL,
  "valid_end" timestamp DEFAULT NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
    FOREIGN KEY ("news_channel_id") REFERENCES "news_channel" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE "formcontent" (
  "page_id" int  NOT NULL,
  "form_id" int  DEFAULT NULL,
  "email_subject" varchar(255) NOT NULL,
  "email_from" varchar(255) NOT NULL,
  "email_to" varchar(255) NOT NULL,
  "redirect_page_id" int  NOT NULL,
  PRIMARY KEY ("page_id"),
      FOREIGN KEY ("page_id") REFERENCES "page" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("form_id") REFERENCES "form" ("id") ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY ("redirect_page_id") REFERENCES "page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "htmlcontent" (
  "page_id" int  NOT NULL,
  "block_id" int  NOT NULL,
  "content" text,
  PRIMARY KEY ("page_id","block_id"),
  FOREIGN KEY ("page_id") REFERENCES "page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE locale ADD FOREIGN KEY ("page_start") REFERENCES "page" ("id") ON DELETE SET NULL ON UPDATE CASCADE;


INSERT INTO shard (id, name, module, controller, action, status) VALUES(1, 'Html', 'core', 'html-content', 'index', 3);
INSERT INTO shard (id, name, module, controller, action, status) VALUES(2, 'Form', 'core', 'form-content', 'index', 3);
INSERT INTO shard (id, name, module, controller, action, status) VALUES(3, 'News', 'core', 'news', 'index', 3);

