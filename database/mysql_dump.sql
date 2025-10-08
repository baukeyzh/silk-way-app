-- ============================================
-- Silk Way - MySQL Database Dump
-- Дамп базы данных для Plesk MySQL
-- ============================================
--
-- Инструкция:
-- 1. Создайте базу данных в Plesk: silk_way_db
-- 2. Импортируйте этот файл через phpMyAdmin
-- 3. Обновите .env файл с данными подключения
--
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- Структура и данные таблицы users
-- ============================================

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `approved`) VALUES
(1, 'Администратор', 'admin@example.com', NOW(), '$2y$12$LQv3c1yycTNVdRUfbqw5zu3IlW9dUXMOUgKKD3YFzl8b3W9x6QPQu', NULL, NOW(), NOW(), 'admin', 1),
(2, 'Сотрудник Склада', 'warehouse@example.com', NOW(), '$2y$12$LQv3c1yycTNVdRUfbqw5zu3IlW9dUXMOUgKKD3YFzl8b3W9x6QPQu', NULL, NOW(), NOW(), 'warehouse_staff', 1),
(3, 'Водитель', 'driver@example.com', NOW(), '$2y$12$LQv3c1yycTNVdRUfbqw5zu3IlW9dUXMOUgKKD3YFzl8b3W9x6QPQu', NULL, NOW(), NOW(), 'driver', 1);

-- ============================================
-- Примечание:
-- Все пользователи используют пароль: password
-- Hash: $2y$12$LQv3c1yycTNVdRUfbqw5zu3IlW9dUXMOUgKKD3YFzl8b3W9x6QPQu
-- ============================================

SET FOREIGN_KEY_CHECKS = 1;

