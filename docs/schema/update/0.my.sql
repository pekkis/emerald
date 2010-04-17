ALTER TABLE emerald_page ADD COLUMN redirect_id integer unsigned NULL;
ALTER TABLE emerald_page ADD FOREIGN KEY(redirect_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE SET NULL;
	
CREATE TABLE emerald_permission_locale_ugroup (
  locale_locale varchar(6) NOT NULL,
  ugroup_id integer unsigned NOT NULL,
  permission smallint unsigned NOT NULL,
  PRIMARY KEY (locale_locale,ugroup_id),
  FOREIGN KEY (locale_locale) REFERENCES emerald_locale (locale) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) engine=InnoDB;


CREATE TABLE emerald_activity (
  id integer unsigned NOT NULL auto_increment,
  category varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (category,name)
) engine=innodb;


CREATE TABLE emerald_permission_activity_ugroup
(
activity_id int unsigned not null,
ugroup_id int unsigned NOT NULL,
PRIMARY KEY (activity_id, ugroup_id),
FOREIGN KEY(activity_id) REFERENCES emerald_activity (id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup (id) ON DELETE CASCADE ON UPDATE CASCADE
) engine=InnoDB;

INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Edit activity permissions');
INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Clear caches');
INSERT INTO emerald_activity (category, name) VALUES ('Administration', 'Expose admin panel');

ALTER TABLE emerald_filelib_folder ADD COLUMN visible tinyint unsigned NOT NULL default 1;


