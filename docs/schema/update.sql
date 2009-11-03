# 3.11.2009

ALTER TABLE page ADD COLUMN layout varchar(255) NOT NULL DEFAULT 'Default' AFTER order_id;
ALTER TABLE page DROP COLUMN template;
ALTER TABLE page DROP COLUMN innertemplate;
