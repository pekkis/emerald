ALTER TABLE emerald_page ADD COLUMN redirect_id integer NULL;
ALTER TABLE emerald_page ADD FOREIGN KEY(redirect_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE SET NULL;

	
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

ALTER TABLE emerald_filelib_folder ADD COLUMN visible smallint NOT NULL default 1;

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

ALTER TABLE emerald_shard ADD column namespace varchar(255) NOT NULL default 'EmCore';

ALTER TABLE emerald_user ALTER passwd TYPE varchar(255);

ALTER TABLE emerald_page ADD COLUMN customurl varchar(1000) DEFAULT NULL;

ALTER TABLE emerald_locale DROP COLUMN page_start;



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

ALTER TABLE emerald_news_item ADD COLUMN taggable_id integer NULL;
ALTER TABLE emerald_news_item ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE emerald_page ADD COLUMN taggable_id integer NULL;
ALTER TABLE emerald_page ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES (5, 'Tag Cloud', 'em-core', 'tag', 'cloud', 3);

ALTER TABLE emerald_page ADD COLUMN class_css varchar(255) DEFAULT NULL;

ALTER TABLE emerald_filelib_file ADD COLUMN date_uploaded timestamp NOT NULL;

ALTER TABLE emerald_filelib_folder DROP COLUMN visible;

