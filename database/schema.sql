CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "role" varchar check("role" in('admin', 'warehouse_employee', 'driver')) not null default 'warehouse_employee',
  "approved" tinyint(1) not null default '0'
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "cargo_applications"(
  "id" integer primary key autoincrement not null,
  "cargo_id" integer not null,
  "driver_id" integer not null,
  "status" varchar check("status" in('pending', 'approved', 'rejected')) not null default 'pending',
  "driver_notes" text,
  "warehouse_notes" text,
  "contact_whatsapp" varchar,
  "contact_wechat" varchar,
  "pickup_contact" varchar,
  "pickup_address" varchar,
  "delivery_contact" varchar,
  "delivery_address" varchar,
  "approved_at" datetime,
  "approved_by" integer,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("cargo_id") references "cargo"("id") on delete cascade,
  foreign key("driver_id") references "users"("id"),
  foreign key("approved_by") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "cars"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "brand" varchar not null,
  "model" varchar not null,
  "license_plate" varchar not null,
  "max_weight" numeric not null,
  "trailer_length" numeric not null,
  "trailer_width" numeric not null,
  "trailer_height" numeric not null,
  "trailer_volume" numeric not null,
  "trailer_type" varchar check("trailer_type" in('tral', 'refrigerator', 'tent')) not null,
  "trailer_type_rus" varchar not null,
  "vehicle_document" varchar,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "brand_rus" varchar,
  "brand_kaz" varchar,
  "brand_chn" varchar,
  "model_rus" varchar,
  "model_kaz" varchar,
  "model_chn" varchar,
  "trailer_type_kaz" varchar,
  "trailer_type_chn" varchar,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE INDEX "cars_user_id_is_active_index" on "cars"("user_id", "is_active");
CREATE TABLE IF NOT EXISTS "cargo"(
  "id" integer primary key autoincrement not null,
  "from_location" varchar not null,
  "to_location" varchar not null,
  "cargo_type" varchar not null,
  "volume" numeric not null,
  "weight" numeric not null,
  "ready_date" datetime not null,
  "comment" text,
  "status" varchar not null default('available'),
  "created_by" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "picked_by" integer,
  "from_location_rus" varchar,
  "from_location_kaz" varchar,
  "from_location_chn" varchar,
  "to_location_rus" varchar,
  "to_location_kaz" varchar,
  "to_location_chn" varchar,
  "cargo_type_rus" varchar,
  "cargo_type_kaz" varchar,
  "cargo_type_chn" varchar,
  "comment_rus" text,
  "comment_kaz" text,
  "comment_chn" text,
  foreign key("created_by") references users("id") on delete no action on update no action,
  foreign key("picked_by") references "users"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "translations"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "rus" text not null,
  "kaz" text not null,
  "chn" text not null,
  "group" varchar not null default 'general',
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "translations_key_group_index" on "translations"("key", "group");
CREATE UNIQUE INDEX "translations_key_unique" on "translations"("key");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_07_31_140646_create_cargo_table',1);
INSERT INTO migrations VALUES(5,'2025_07_31_140652_add_role_to_users_table',1);
INSERT INTO migrations VALUES(6,'2025_08_02_171358_add_approved_to_users_table',1);
INSERT INTO migrations VALUES(7,'2025_08_02_173212_create_cargo_applications_table',1);
INSERT INTO migrations VALUES(8,'2025_08_23_134831_create_cars_table',1);
INSERT INTO migrations VALUES(9,'2025_08_23_151900_add_picked_by_to_cargo_table',1);
INSERT INTO migrations VALUES(10,'2025_12_19_000000_create_translations_table',1);
INSERT INTO migrations VALUES(11,'2025_12_19_000001_add_multilingual_fields_to_cargo_table',1);
INSERT INTO migrations VALUES(12,'2025_12_19_000002_add_multilingual_fields_to_cars_table',1);
INSERT INTO migrations VALUES(13,'2025_12_19_000003_rename_trailer_type_ru_to_trailer_type_rus',1);
