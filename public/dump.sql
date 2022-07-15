PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "migrations" ("id" integer not null primary key autoincrement, "migration" varchar not null, "batch" integer not null);
INSERT INTO migrations VALUES(1,'2014_10_12_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO migrations VALUES(3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO migrations VALUES(4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(5,'2022_07_14_180850_create_columns_table',1);
INSERT INTO migrations VALUES(6,'2022_07_14_180856_create_cards_table',1);
CREATE TABLE IF NOT EXISTS "users" ("id" integer not null primary key autoincrement, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "password_resets" ("email" varchar not null, "token" varchar not null, "created_at" datetime);
CREATE TABLE IF NOT EXISTS "failed_jobs" ("id" integer not null primary key autoincrement, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime default CURRENT_TIMESTAMP not null);
CREATE TABLE IF NOT EXISTS "personal_access_tokens" ("id" integer not null primary key autoincrement, "tokenable_type" varchar not null, "tokenable_id" integer not null, "name" varchar not null, "token" varchar not null, "abilities" text, "last_used_at" datetime, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "columns" ("id" integer not null primary key autoincrement, "title" varchar not null, "order" integer not null default '0', "created_at" datetime, "updated_at" datetime);
INSERT INTO columns VALUES(3,'Col1',0,'2022-07-15 11:34:58','2022-07-15 11:34:58');
INSERT INTO columns VALUES(5,'Col2',0,'2022-07-15 11:39:01','2022-07-15 11:39:01');
CREATE TABLE IF NOT EXISTS "cards" ("id" integer not null primary key autoincrement, "title" varchar not null, "description" text, "order" integer not null default '0', "column_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("column_id") references "columns"("id") on delete cascade);
INSERT INTO cards VALUES(4,'C1','C1',0,3,'2022-07-15 11:35:09','2022-07-15 11:35:09');
INSERT INTO cards VALUES(6,'C2','C2',0,5,'2022-07-15 11:39:12','2022-07-15 11:39:12');
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('migrations',6);
INSERT INTO sqlite_sequence VALUES('columns',5);
INSERT INTO sqlite_sequence VALUES('cards',6);
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
CREATE INDEX "password_resets_email_index" on "password_resets" ("email");
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs" ("uuid");
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens" ("tokenable_type", "tokenable_id");
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens" ("token");
COMMIT;
