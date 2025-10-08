-- ============================================
-- Silk Way - Полный MySQL дамп
-- Структура таблиц + Тестовые данные
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- Удаление существующих таблиц
-- ============================================

DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cargo_applications`;
DROP TABLE IF EXISTS `cargo`;
DROP TABLE IF EXISTS `cars`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `translations`;
DROP TABLE IF EXISTS `users`;

-- ============================================
-- Таблица: users
-- ============================================

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'driver',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Тестовые пользователи (пароль для всех: password)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `approved`) VALUES
(1, 'Администратор', 'admin@example.com', NULL, '$2y$12$LQv3c1yycTNVdRUfbqw5zu3IlW9dUXMOUgKKD3YFzl8b3W9x6QPQu', NULL, NOW(), NOW(), 'admin', 1),
(2, 'Сотрудник Склада', 'warehouse@example.com', NULL, '$2y$12$LQv3c1yycTNVdRUfbqw5zu3IlW9dUXMOUgKKD3YFzl8b3W9x6QPQu', NULL, NOW(), NOW(), 'warehouse_employee', 1),
(3, 'Водитель', 'driver@example.com', NULL, '$2y$12$LQv3c1yycTNVdRUfbqw5zu3IlW9dUXMOUgKKD3YFzl8b3W9x6QPQu', NULL, NOW(), NOW(), 'driver', 1);

-- ============================================
-- Таблица: cargo
-- ============================================

CREATE TABLE `cargo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_location` varchar(255) NOT NULL,
  `to_location` varchar(255) NOT NULL,
  `cargo_type` varchar(255) NOT NULL,
  `volume` decimal(8,2) NOT NULL,
  `weight` decimal(8,2) NOT NULL,
  `ready_date` datetime NOT NULL,
  `comment` text,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `picked_by` bigint unsigned DEFAULT NULL,
  `from_location_rus` varchar(255) DEFAULT NULL,
  `from_location_kaz` varchar(255) DEFAULT NULL,
  `from_location_chn` varchar(255) DEFAULT NULL,
  `to_location_rus` varchar(255) DEFAULT NULL,
  `to_location_kaz` varchar(255) DEFAULT NULL,
  `to_location_chn` varchar(255) DEFAULT NULL,
  `cargo_type_rus` varchar(255) DEFAULT NULL,
  `cargo_type_kaz` varchar(255) DEFAULT NULL,
  `cargo_type_chn` varchar(255) DEFAULT NULL,
  `comment_rus` text,
  `comment_kaz` text,
  `comment_chn` text,
  PRIMARY KEY (`id`),
  KEY `cargo_created_by_foreign` (`created_by`),
  KEY `cargo_picked_by_foreign` (`picked_by`),
  CONSTRAINT `cargo_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `cargo_picked_by_foreign` FOREIGN KEY (`picked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Тестовые грузы
INSERT INTO `cargo` (`id`, `from_location`, `to_location`, `cargo_type`, `volume`, `weight`, `ready_date`, `comment`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Алматы', 'Астана', 'Электроника', 2.50, 150.00, NOW(), 'Хрупкий груз, требует осторожной перевозки', 'available', 2, NOW(), NOW()),
(2, 'Шымкент', 'Караганда', 'Продукты питания', 15.00, 1200.00, NOW(), 'Скоропортящийся груз', 'available', 2, NOW(), NOW()),
(3, 'Актобе', 'Алматы', 'Стройматериалы', 45.00, 3500.00, NOW(), 'Тяжелый груз', 'available', 2, NOW(), NOW());

-- ============================================
-- Таблица: cars
-- ============================================

CREATE TABLE `cars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `license_plate` varchar(255) NOT NULL,
  `max_weight` decimal(8,2) NOT NULL,
  `trailer_length` decimal(8,2) NOT NULL,
  `trailer_width` decimal(8,2) NOT NULL,
  `trailer_height` decimal(8,2) NOT NULL,
  `trailer_volume` decimal(8,2) NOT NULL,
  `trailer_type` varchar(255) NOT NULL,
  `trailer_type_rus` varchar(255) DEFAULT NULL,
  `trailer_type_kz` varchar(255) DEFAULT NULL,
  `trailer_type_cn` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cars_license_plate_unique` (`license_plate`),
  KEY `cars_user_id_foreign` (`user_id`),
  CONSTRAINT `cars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Тестовые машины
INSERT INTO `cars` (`id`, `user_id`, `brand`, `model`, `license_plate`, `max_weight`, `trailer_length`, `trailer_width`, `trailer_height`, `trailer_volume`, `trailer_type`, `trailer_type_rus`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 3, 'Mercedes-Benz', 'Actros', 'А123ВС77', 20.0, 13.6, 2.45, 2.7, 89.96, 'tent', 'Тентованный', 1, NOW(), NOW()),
(2, 3, 'Volvo', 'FH', 'К456ЕР01', 25.0, 15.0, 2.5, 3.0, 112.50, 'refrigerator', 'Рефрижератор', 1, NOW(), NOW()),
(3, 3, 'КамАЗ', '5320', 'Т789УМ186', 18.0, 12.0, 2.4, 2.5, 72.00, 'closed', 'Закрытый', 1, NOW(), NOW());

-- ============================================
-- Таблица: cargo_applications
-- ============================================

CREATE TABLE `cargo_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cargo_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned NOT NULL,
  `car_id` bigint unsigned NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `message` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cargo_applications_cargo_id_foreign` (`cargo_id`),
  KEY `cargo_applications_driver_id_foreign` (`driver_id`),
  KEY `cargo_applications_car_id_foreign` (`car_id`),
  CONSTRAINT `cargo_applications_cargo_id_foreign` FOREIGN KEY (`cargo_id`) REFERENCES `cargo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cargo_applications_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cargo_applications_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Таблица: translations
-- ============================================

CREATE TABLE `translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `ru` text,
  `kz` text,
  `cn` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Основные переводы (базовые - остальные загрузятся через сидер)
INSERT INTO `translations` (`key`, `ru`, `kz`, `cn`) VALUES
('welcome', 'Добро пожаловать', 'Қош келдіңіз', '欢迎'),
('login', 'Войти', 'Кіру', '登录'),
('register', 'Регистрация', 'Тіркелу', '注册'),
('logout', 'Выход', 'Шығу', '登出'),
('dashboard', 'Панель управления', 'Басқару тақтасы', '仪表板'),
('cargo', 'Грузы', 'Жүктер', '货物'),
('cars', 'Машины', 'Машиналар', '车辆'),
('users', 'Пользователи', 'Пайдаланушылар', '用户'),
('admin', 'Администратор', 'Әкімші', '管理员'),
('driver', 'Водитель', 'Жүргізуші', '司机'),
('warehouse_employee', 'Сотрудник склада', 'Қойма қызметкері', '仓库员工');

-- ПРИМЕЧАНИЕ: Для загрузки всех 347 переводов запустите:
-- php artisan db:seed --class=TranslationSeeder

-- ============================================
-- Служебные таблицы Laravel
-- ============================================

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- Готово!
-- ============================================
-- Этот дамп содержит:
-- ✅ Полную структуру таблиц
-- ✅ 3 тестовых пользователя (пароль: password)
-- ✅ 3 тестовых груза
-- ✅ 3 тестовые машины
-- ✅ 11 базовых переводов
--
-- Для загрузки всех 347 переводов запустите:
-- php artisan db:seed --class=TranslationSeeder
-- ============================================
