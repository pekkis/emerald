ALTER TABLE emerald_page ADD COLUMN redirect_id integer unsigned NULL;
ALTER TABLE emerald_page ADD FOREIGN KEY(redirect_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE SET NULL;
	
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

ALTER TABLE emerald_filelib_folder ADD COLUMN visible tinyint unsigned NOT NULL default 1;

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

ALTER TABLE emerald_shard ADD column namespace varchar(255) NOT NULL default 'EmCore' AFTER id;

UPDATE emerald_activity SET category = 'administration';
UPDATE emerald_activity set name = 'expose' WHERE id = 3;
UPDATE emerald_activity set name = 'edit_activities' WHERE id = 1;
UPDATE emerald_activity set name = 'clear_caches' WHERE id = 2;

INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_users');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_locales');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_forms');
INSERT INTO emerald_activity (category, name) VALUES ('administration', 'edit_options');

ALTER TABLE emerald_user CHANGE passwd passwd varchar(255) NOT NULL;

ALTER TABLE emerald_page ADD COLUMN customurl varchar(1000) DEFAULT NULL AFTER beautifurl;

ALTER TABLE emerald_locale DROP COLUMN page_start;


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

ALTER TABLE emerald_news_item ADD COLUMN taggable_id integer unsigned NULL;
ALTER TABLE emerald_news_item ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE emerald_page ADD COLUMN taggable_id integer NULL;
ALTER TABLE emerald_page ADD FOREIGN KEY(taggable_id) REFERENCES emerald_taggable(id) ON DELETE NO ACTION ON UPDATE CASCADE;

INSERT INTO emerald_shard (id, name, module, controller, action, status) VALUES (5, 'Tag Cloud', 'em-core', 'tag', 'cloud', 3);

ALTER TABLE emerald_page ADD COLUMN class_css varchar(255) DEFAULT NULL;

ALTER TABLE emerald_filelib_file ADD COLUMN date_uploaded datetime NOT NULL;

ALTER TABLE emerald_filelib_folder DROP COLUMN visible;

