-- ============================================
-- Silk Way - MySQL Seed Data
-- Тестовые данные для development
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Пользователи (password для всех: password)
INSERT INTO \`users\` (\`id\`, \`name\`, \`email\`, \`email_verified_at\`, \`password\`, \`role\`, \`approved\`, \`created_at\`, \`updated_at\`) VALUES
(1, 'Администратор', 'admin@example.com', NULL, '$2y$12$avDeE7ANrQRoiD1D/dEHLOuB8n5ysKEjErw3iTWUNXACsYuxIV.kG', 'admin', 1, '2025-10-08 14:02:43', '2025-10-08 14:02:43'),
(2, 'Иван Петров', 'warehouse@example.com', NULL, '$2y$12$oANejvMiBYORxghwGdZB/uFl41MX9SUCjA8J0M3KkG/iTcw3luMgy', 'warehouse_employee', 1, '2025-10-08 14:02:43', '2025-10-08 14:02:43'),
(3, 'Алексей Сидоров', 'driver@example.com', NULL, '$2y$12$kP.Qfil0akxM7khMHvFkOucDLTpyLYPZ94myIuvOQnxOLJiUNDVca', 'driver', 1, '2025-10-08 14:02:43', '2025-10-08 14:02:43');

SET FOREIGN_KEY_CHECKS = 1;
