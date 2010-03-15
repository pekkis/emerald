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


