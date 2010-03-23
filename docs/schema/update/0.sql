ALTER TABLE page ADD COLUMN redirect_id integer NULL;ALTER TABLE
ALTER TABLE page ADD FOREIGN KEY(redirect_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE SET NULL;

	
CREATE TABLE "permission_locale_ugroup" (
  "locale_locale" varchar(6) NOT NULL,
  "ugroup_id" int  NOT NULL,
  "permission" smallint  NOT NULL,
  PRIMARY KEY ("locale_locale","ugroup_id"),
  FOREIGN KEY ("locale_locale") REFERENCES "locale" ("locale") ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY ("ugroup_id") REFERENCES "ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE SEQUENCE activity_id_seq;

CREATE TABLE "activity" (
  "id" int  NOT NULL DEFAULT NEXTVAL('activity_id_seq'),
  "category" varchar(255) NOT NULL,
  "name" varchar(255) NOT NULL,CREATE TABLE "permission_activity_ugroup"
(
"activity_id" int not null,
"ugroup_id" int  NOT NULL,
PRIMARY KEY ("activity_id", "ugroup_id"),
FOREIGN KEY(activity_id) REFERENCES "activity" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("ugroup_id") REFERENCES "ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

  PRIMARY KEY ("id"),
  UNIQUE ("category","name")
);


CREATE TABLE "permission_activity_ugroup"
(
"activity_id" int not null,
"ugroup_id" int  NOT NULL,
PRIMARY KEY ("activity_id", "ugroup_id"),
FOREIGN KEY(activity_id) REFERENCES "activity" ("id") ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY ("ugroup_id") REFERENCES "ugroup" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO activity (category, name) VALUES ('Administration', 'Edit activity permissions');
INSERT INTO activity (category, name) VALUES ('Administration', 'Clear caches');
INSERT INTO activity (category, name) VALUES ('Administration', 'Expose admin panel');

