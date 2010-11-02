CREATE SEQUENCE emerald_filelib_folder_id_seq;

CREATE TABLE "emerald_filelib_folder" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_filelib_folder_id_seq'),
  "parent_id" int  DEFAULT NULL,
  "foldername" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("parent_id","foldername"),
  FOREIGN KEY ("parent_id") REFERENCES "emerald_filelib_folder" ("id") ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE SEQUENCE emerald_filelib_file_id_seq;

CREATE TABLE "emerald_filelib_file" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_filelib_file_id_seq'),
  "folder_id" int  NOT NULL,
  "mimetype" varchar(255) NOT NULL,
  "fileprofile" varchar(255) NOT NULL DEFAULT 'default',
  "filesize" int DEFAULT NULL,
  "filename" varchar(255) NOT NULL,
  "filelink" varchar(1000) DEFAULT NULL,
  "date_uploaded" timestamp NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("filename","folder_id"),
  FOREIGN KEY ("folder_id") REFERENCES "emerald_filelib_folder" ("id") ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE "emerald_application_option" (
  "identifier" varchar(255) NOT NULL DEFAULT '',
  "strvalue" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("identifier")
);

CREATE SEQUENCE emerald_form_id_seq;

CREATE TABLE "emerald_form" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_form_id_seq'),
  "name" varchar(255) NOT NULL,
  "description" text NOT NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE SEQUENCE emerald_form_field_id_seq;

CREATE TABLE "emerald_form_field" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_form_field_id_seq'),
  "form_id" int  NOT NULL,
  "type" smallint NOT NULL,
  "order_id" smallint NOT NULL DEFAULT '0',
  "title" varchar(255) DEFAULT NULL,
  "mandatory" smallint NOT NULL DEFAULT '0',
  "options" text,
  PRIMARY KEY ("id"),
    FOREIGN KEY ("form_id") REFERENCES "emerald_form" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "emerald_shard" (
  "id" int  NOT NULL,
  "namespace" varchar(255) NOT NULL DEFAULT 'EmCore',
  "name" varchar(255) NOT NULL,
  "module" varchar(255) NOT NULL DEFAULT 'core',
  "controller" varchar(255) NOT NULL DEFAULT 'index',
  "action" varchar(255) NOT NULL DEFAULT 'index',
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE SEQUENCE emerald_ugroup_id_seq;


CREATE TABLE "emerald_ugroup" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_ugroup_id_seq'),
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE SEQUENCE emerald_user_id_seq;

CREATE TABLE "emerald_user" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_user_id_seq'),
  "email" varchar(255) NOT NULL,
  "passwd" varchar(255) NOT NULL,
  "firstname" varchar(255) DEFAULT NULL,
  "lastname" varchar(255) DEFAULT NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  UNIQUE ("email")
);


CREATE TABLE "emerald_user_option" (
  "user_id" int  NOT NULL,
  "identifier" varchar(255) NOT NULL DEFAULT '',
  "strvalue" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("user_id","identifier"),
  FOREIGN KEY ("user_id") REFERENCES "emerald_user" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "emerald_user_ugroup" (
  "user_id" int  NOT NULL,
  "ugroup_id" int  NOT NULL,
  PRIMARY KEY ("user_id","ugroup_id"),
    FOREIGN KEY ("user_id") REFERENCES "emerald_user" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "emerald_ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE "emerald_permission_folder_ugroup" (
  "folder_id" int  NOT NULL,
  "ugroup_id" int  NOT NULL,
  "permission" smallint  NOT NULL,
  PRIMARY KEY ("folder_id","ugroup_id"),
    FOREIGN KEY ("folder_id") REFERENCES "emerald_filelib_folder" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "emerald_ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "emerald_locale" (
  "locale" varchar(6) NOT NULL,
  PRIMARY KEY ("locale")
);

CREATE TABLE "emerald_locale_option" (
  "locale_locale" varchar(6) NOT NULL,
  "identifier" varchar(255) NOT NULL DEFAULT '',
  "strvalue" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("locale_locale","identifier"),
  FOREIGN KEY ("locale_locale") REFERENCES "emerald_locale" ("locale") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE SEQUENCE emerald_news_channel_id_seq;


CREATE SEQUENCE emerald_page_global_id_seq;

CREATE TABLE emerald_page_global
(
id integer NOT NULL DEFAULT NEXTVAL('emerald_page_global_id_seq'),
PRIMARY KEY(id)
);

DROP TABLE IF EXISTS emerald_page;

CREATE SEQUENCE emerald_page_id_seq;

CREATE TABLE "emerald_page" (
  "id" int NOT NULL DEFAULT NEXTVAL('emerald_page_id_seq'),
  "global_id" int NOT NULL,
  "locale" varchar(6) NOT NULL,
  "parent_id" int  DEFAULT NULL,
  "order_id" smallint NOT NULL DEFAULT '0',
  "layout" varchar(255) DEFAULT 'Default',
  "title" varchar(255) NOT NULL,
  "beautifurl" varchar(1000) DEFAULT NULL,
  "customurl" varchar(1000) DEFAULT NULL,
  "path" varchar(255) NULL,
  "shard_id" int  NOT NULL,
  "visibility" smallint  NOT NULL DEFAULT '1',
  "cache_seconds" integer NOT NULL DEFAULT 0,
  "redirect_id" integer NULL,
  "taggable_id" integer NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  "class_css" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("parent_id","title"),
  UNIQUE ("global_id","locale"),
  FOREIGN KEY ("global_id") REFERENCES "emerald_page_global" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("parent_id") REFERENCES "emerald_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("shard_id") REFERENCES "emerald_shard" ("id") ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY ("locale") REFERENCES "emerald_locale" ("locale") ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY("redirect_id") REFERENCES emerald_page("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "emerald_permission_page_ugroup" (
  "page_id" int  NOT NULL,
  "ugroup_id" int  NOT NULL,
  "permission" smallint  NOT NULL,
  PRIMARY KEY ("page_id","ugroup_id"),
    FOREIGN KEY ("page_id") REFERENCES "emerald_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "emerald_ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE "emerald_news_channel" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_news_channel_id_seq'),
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
  FOREIGN KEY ("page_id") REFERENCES "emerald_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE SEQUENCE emerald_news_item_id_seq;

CREATE TABLE "emerald_news_item" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_news_item_id_seq'),
  "news_channel_id" int  NOT NULL,
  "title" varchar(255) NOT NULL,
  "description" text,
  "article" text,
  "taggable_id" integer DEFAULT NULL,
  "author" varchar(255) DEFAULT NULL,
  "category" varchar(255) DEFAULT NULL,
  "comments" varchar(255) DEFAULT NULL,
  "enclosure" varchar(255) DEFAULT NULL,
  "valid_start" timestamp NOT NULL,
  "valid_end" timestamp DEFAULT NULL,
  "status" smallint  NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
    FOREIGN KEY ("news_channel_id") REFERENCES "emerald_news_channel" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE "emerald_formcontent" (
  "page_id" int  NOT NULL,
  "form_id" int  DEFAULT NULL,
  "email_subject" varchar(255) NOT NULL,
  "email_from" varchar(255) NOT NULL,
  "email_to" varchar(255) NOT NULL,
  "redirect_page_id" int  NOT NULL,
  PRIMARY KEY ("page_id"),
  FOREIGN KEY ("page_id") REFERENCES "emerald_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("form_id") REFERENCES "emerald_form" ("id") ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY ("redirect_page_id") REFERENCES "emerald_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE "emerald_htmlcontent" (
  "page_id" int  NOT NULL,
  "block_id" int  NOT NULL,
  "content" text,
  PRIMARY KEY ("page_id","block_id"),
  FOREIGN KEY ("page_id") REFERENCES "emerald_page" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE emerald_locale ADD FOREIGN KEY ("page_start") REFERENCES "emerald_page" ("id") ON DELETE SET NULL ON UPDATE CASCADE;


INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES(1, 'Html', 'em-core', 'html-content', 'index', 3);
INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES(2, 'Form', 'em-core', 'form-content', 'index', 3);
INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES(3, 'News', 'em-core', 'news', 'index', 3);

CREATE UNIQUE INDEX page_beautifurl_idx ON emerald_page (beautifurl);


CREATE TABLE "emerald_permission_locale_ugroup" (
  "locale_locale" varchar(6) NOT NULL,
  "ugroup_id" int  NOT NULL,
  "permission" smallint  NOT NULL,
  PRIMARY KEY ("locale_locale","ugroup_id"),
  FOREIGN KEY ("locale_locale") REFERENCES "emerald_locale" ("locale") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "emerald_ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE SEQUENCE emerald_activity_id_seq;

CREATE TABLE "emerald_activity" (
  "id" int  NOT NULL DEFAULT NEXTVAL('emerald_activity_id_seq'),
  "category" varchar(255) NOT NULL,
  "name" varchar(255) NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("category","name")
);

CREATE TABLE "emerald_permission_activity_ugroup"
(
"activity_id" int not null,
"ugroup_id" int  NOT NULL,
PRIMARY KEY ("activity_id", "ugroup_id"),
FOREIGN KEY(activity_id) REFERENCES "emerald_activity" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("ugroup_id") REFERENCES "emerald_ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Edit activity permissions');
INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Clear caches');
INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Expose admin panel');

CREATE TABLE emerald_customcontent
(
page_id integer NOT NULL,
block_id integer NOT NULL,
module varchar(255) NULL,
controller varchar(255) NULL,
action varchar(255) NULL,
params varchar(1000) NULL,
PRIMARY KEY(page_id, block_id),
FOREIGN KEY(page_id) REFERENCES emerald_page(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES (4, 'Custom', 'em-core', 'custom-content', 'index', 3);

UPDATE emerald_activity SET category = 'administration';
UPDATE emerald_activity set name = 'expose' WHERE id = 3;
UPDATE emerald_activity set name = 'edit_activities' WHERE id = 1;
UPDATE emerald_activity set name = 'clear_caches' WHERE id = 2;

INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_users');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_locales');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_forms');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_options');


CREATE SEQUENCE emerald_tag_id_seq;

CREATE TABLE emerald_tag
(
id integer NOT NULL DEFAULT NEXTVAL('emerald_tag_id_seq'),
name varchar(255) NOT NULL,
PRIMARY KEY(id),
UNIQUE(name)
);

CREATE SEQUENCE emerald_taggable_id_seq;

CREATE TABLE emerald_taggable
(
id integer NOT NULL DEFAULT NEXTVAL('emerald_taggable_id_seq'),
type varchar(255) NOT NULL,
PRIMARY KEY(id)
);

CREATE TABLE emerald_taggable_tag
(
taggable_id integer NOT NULL,
tag_id integer NOT NULL,
PRIMARY KEY(taggable_id, tag_id),
FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY(tag_id) REFERENCES emerald_tag(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX taggable_type_idx ON emerald_taggable (type);

ALTER TABLE emerald_news_item ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE emerald_page ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES (5, 'TagCloud', 'em-core', 'tag', 'cloud', 3);



