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

-- Все переводы из базы данных (347 шт)
INSERT INTO `translations` (`key`, `ru`, `kz`, `cn`) VALUES
('welcome', 'Добро пожаловать', 'Қош келдіңіз', '欢迎'),
('login', 'Войти', 'Кіру', '登录'),
('logout', 'Выйти', 'Шығу', '退出'),
('email', 'Email', 'Email', '邮箱'),
('password', 'Пароль', 'Құпия сөз', '密码'),
('save', 'Сохранить', 'Сақтау', '保存'),
('edit', 'Редактировать', 'Өңдеу', '编辑'),
('delete', 'Удалить', 'Жою', '删除'),
('cancel', 'Отмена', 'Бас тарту', '取消'),
('back', 'Назад', 'Артқа', '返回'),
('next', 'Далее', 'Келесі', '下一步'),
('previous', 'Предыдущий', 'Алдыңғы', '上一个'),
('search', 'Поиск', 'Іздеу', '搜索'),
('search_placeholder', 'Поиск по марке, модели или номеру...', 'Марка, модель немесе нөмір бойынша іздеу...', '按品牌、型号或车牌号搜索...'),
('filter', 'Фильтр', 'Сүзгі', '筛选'),
('clear', 'Очистить', 'Тазалау', '清除'),
('cargo', 'Груз', 'Жүк', '货物'),
('cargo_type', 'Тип груза', 'Жүк түрі', '货物类型'),
('from_location', 'Откуда', 'Қайдан', '从哪里'),
('to_location', 'Куда', 'Қайда', '到哪里'),
('volume', 'Объем', 'Көлем', '体积'),
('weight', 'Вес', 'Салмақ', '重量'),
('ready_date', 'Дата готовности', 'Дайындық күні', '准备日期'),
('comment', 'Комментарий', 'Түсініктеме', '评论'),
('status', 'Статус', 'Күй', '状态'),
('available', 'Доступен', 'Қолжетімді', '可用'),
('in_progress', 'В работе', 'Жұмыс істеп тұр', '进行中'),
('delivered', 'Доставлен', 'Жеткізілді', '已交付'),
('car', 'Машина', 'Машина', '汽车'),
('cars.all_cars', 'Все машины', 'Барлық машиналар', '所有车辆'),
('cars.all_cars_description', 'Список всех зарегистрированных машин в системе', 'Жүйеде тіркелген барлық машиналар тізімі', '系统中所有注册车辆的列表'),
('cars.add_car', 'Добавить машину', 'Машина қосу', '添加车辆'),
('cars.all_statuses', 'Все статусы', 'Барлық күйлер', '所有状态'),
('cars.filter', 'Фильтровать', 'Сүзгі', '筛选'),
('cars.table_car', 'Машина', 'Машина', '车辆'),
('cars.table_driver', 'Водитель', 'Жүргізуші', '司机'),
('cars.table_trailer', 'Прицеп', 'Тіркеме', '拖车'),
('cars.table_status', 'Статус', 'Күй', '状态'),
('cars.table_actions', 'Действия', 'Әрекеттер', '操作'),
('cars.status_active', 'Активна', 'Белсенді', '活跃'),
('cars.status_inactive', 'Неактивна', 'Белсенді емес', '不活跃'),
('cars.driver', 'Водитель:', 'Жүргізуші:', '司机:'),
('cars.email', 'Email:', 'Email:', '邮箱:'),
('cars.trailer', 'Прицеп:', 'Тіркеме:', '拖车:'),
('cars.dimensions', 'Габариты:', 'Өлшемдер:', '尺寸:'),
('cars.view', 'Просмотр', 'Көру', '查看'),
('cars.edit', 'Редактировать', 'Өңдеу', '编辑'),
('welcome.title', 'Silk Way - Система управления грузоперевозками', 'Silk Way - Жүк тасымалдау басқару жүйесі', 'Silk Way - 货运管理系统'),
('welcome.system_title', 'Система управления грузоперевозками', 'Жүк тасымалдау басқару жүйесі', '货运管理系统'),
('welcome.cargo_management', 'Управление грузами', 'Жүктерді басқару', '货物管理'),
('welcome.cargo_management_desc', 'Создание, редактирование и отслеживание грузов', 'Жүктерді құру, өңдеу және қадағалау', '创建、编辑和跟踪货物'),
('welcome.create_applications', 'Создание заявок на перевозку', 'Тасымалдауға өтініштер құру', '创建运输申请'),
('welcome.track_delivery', 'Отслеживание статуса доставки', 'Жеткізу күйін қадағалау', '跟踪交付状态'),
('welcome.route_management', 'Управление маршрутами', 'Маршруттарды басқару', '路线管理'),
('welcome.car_management', 'Управление машинами', 'Машиналарды басқару', '车辆管理'),
('welcome.car_management_desc', 'Регистрация и управление автопарком водителей', 'Жүргізушілер автопаркін тіркеу және басқару', '司机车队注册和管理'),
('welcome.register_cars', 'Регистрация машин и прицепов', 'Машиналар мен тіркемелерді тіркеу', '车辆和拖车注册'),
('welcome.technical_specs', 'Учет технических характеристик', 'Техникалық сипаттамаларды есепке алу', '技术规格记录'),
('welcome.upload_docs', 'Загрузка документов ПДД', 'Жол қауіпсіздігі қағидалары құжаттарын жүктеу', '上传交通规则文件'),
('welcome.user_management', 'Управление пользователями', 'Пайдаланушыларды басқару', '用户管理'),
('welcome.user_management_desc', 'Роли и права доступа в системе', 'Жүйедегі рөлдер мен қол жеткізу құқықтары', '系统中的角色和访问权限'),
('welcome.admins', 'Администраторы', 'Әкімшілер', '管理员'),
('welcome.warehouse_workers', 'Складские работники', 'Қойма жұмысшылары', '仓库工人'),
('welcome.drivers', 'Водители', 'Жүргізушілер', '司机'),
('welcome.demo_title', 'Демонстрация системы', 'Жүйені көрсету', '系统演示'),
('welcome.demo_description', 'Для тестирования системы используйте следующие учетные данные:', 'Жүйені сынау үшін келесі есептік деректерді пайдаланыңыз:', '要测试系统，请使用以下凭据：'),
('welcome.test_drivers', 'Тестовые водители:', 'Сынақ жүргізушілері:', '测试司机：'),
('auth.login', 'Вход - Silk Way', 'Кіру - Silk Way', '登录 - Silk Way'),
('auth.email_placeholder', 'Email адрес', 'Email мекенжайы', '邮箱地址'),
('auth.password_placeholder', 'Пароль', 'Құпия сөз', '密码'),
('auth.login_button', 'Войти', 'Кіру', '登录'),
('auth.no_account', 'Нет аккаунта?', 'Есептік жазба жоқ па?', '没有账户？'),
('auth.register_link', 'Зарегистрироваться', 'Тіркелу', '注册'),
('auth.register_title', 'Регистрация - Silk Way', 'Тіркеу - Silk Way', '注册 - Silk Way'),
('auth.register_heading', 'Регистрация', 'Тіркеу', '注册'),
('auth.register_desc', 'Создайте аккаунт для работы в системе', 'Жүйеде жұмыс істеу үшін есептік жазба құрыңыз', '创建账户以在系统中工作'),
('auth.full_name', 'Полное имя', 'Толық аты', '全名'),
('auth.password_confirmation', 'Подтверждение пароля', 'Құпия сөзді растау', '确认密码'),
('auth.select_role', 'Выберите роль', 'Рөлді таңдаңыз', '选择角色'),
('auth.warehouse_employee', 'Сотрудник склада', 'Қойма қызметкері', '仓库员工'),
('auth.driver_role', 'Водитель', 'Жүргізуші', '司机'),
('auth.register_button', 'Зарегистрироваться', 'Тіркелу', '注册'),
('auth.have_account', 'Уже есть аккаунт?', 'Есептік жазба бар ма?', '已有账户？'),
('auth.login_link', 'Войти', 'Кіру', '登录'),
('cargo.available_cargo', 'Доступные грузы', 'Қолжетімді жүктер', '可用货物'),
('cargo.available_cargo_desc', 'Список всех доступных для перевозки грузов', 'Тасымалдауға қолжетімді барлық жүктер тізімі', '所有可用于运输的货物列表'),
('cargo.add_cargo', 'Добавить груз', 'Жүк қосу', '添加货物'),
('cargo.search_placeholder', 'Поиск по маршруту или типу груза...', 'Маршрут немесе жүк түрі бойынша іздеу...', '按路线或货物类型搜索...'),
('cargo.all_statuses', 'Все статусы', 'Барлық күйлер', '所有状态'),
('cargo.status_available', 'Доступен', 'Қолжетімді', '可用'),
('cargo.status_picked_up', 'Забран', 'Алынған', '已取'),
('cargo.status_delivered', 'Доставлен', 'Жеткізілген', '已送达'),
('cargo.table_route', 'Маршрут', 'Маршрут', '路线'),
('cargo.table_cargo', 'Груз', 'Жүк', '货物'),
('cargo.table_readiness', 'Готовность', 'Дайындық', '准备状态'),
('cargo.table_status', 'Статус', 'Күй', '状态'),
('cargo.table_created', 'Создан', 'Құрылған', '创建时间'),
('cargo.table_actions', 'Действия', 'Әрекеттер', '操作'),
('cargo.no_cargo_found', 'Грузы не найдены', 'Жүктер табылмады', '未找到货物'),
('cargo.no_cargo_desc', 'В данный момент нет доступных грузов для перевозки', 'Қазіргі уақытта тасымалдауға қолжетімді жүктер жоқ', '目前没有可运输的货物'),
('cargo.change_search', 'Попробуйте изменить параметры поиска', 'Іздеу параметрлерін өзгертуге тырысыңыз', '尝试更改搜索参数'),
('cargo.reset_filters', 'Сбросить фильтры', 'Сүзгілерді қалпына келтіру', '重置筛选器'),
('admin.dashboard_title', 'Админ-панель', 'Әкімші панелі', '管理面板'),
('admin.dashboard_desc', 'Управление системой и пользователями', 'Жүйе мен пайдаланушыларды басқару', '系统和用户管理'),
('admin.total_cargo', 'Всего грузов', 'Барлығы жүктер', '货物总数'),
('admin.available_cargo', 'Доступные грузы', 'Қолжетімді жүктер', '可用货物'),
('admin.picked_up_cargo', 'Забранные грузы', 'Алынған жүктер', '已取货物'),
('admin.pending_users', 'Пользователи на подтверждение', 'Бекіту күтудегі пайдаланушылар', '等待确认的用户'),
('admin.pending_users_desc', 'Подтвердите или отклоните заявки на регистрацию', 'Тіркеу өтініштерін бекітіңіз немесе бас тартыңыз', '确认或拒绝注册申请'),
('admin.approved_users', 'Подтвержденные пользователи', 'Бекітілген пайдаланушылар', '已确认用户'),
('admin.approved_users_desc', 'Активные пользователи системы', 'Жүйенің белсенді пайдаланушылары', '系统的活跃用户'),
('admin.approve', 'Подтвердить', 'Бекіту', '确认'),
('admin.reject', 'Отклонить', 'Бас тарту', '拒绝'),
('admin.toggle_approval', 'Отозвать доступ', 'Қол жеткізуді алып тастау', '撤销访问权限'),
('admin.translations_button', 'Переводы', 'Аудармалар', '翻译'),
('admin.user_name', 'Имя', 'Аты', '姓名'),
('admin.user_email', 'Email', 'Email', '邮箱'),
('admin.user_role', 'Роль', 'Рөл', '角色'),
('admin.user_actions', 'Действия', 'Әрекеттер', '操作'),
('admin.administrator', 'Администратор', 'Әкімші', '管理员'),
('admin.registered_at', 'Зарегистрирован', 'Тіркелген', '已注册'),
('admin.approved_at', 'Подтвержден', 'Бекітілген', '已确认'),
('admin.confirm_reject_user', 'Отклонить этого пользователя?', 'Бұл пайдаланушыны бас тарту керек пе?', '拒绝这个用户？'),
('admin.confirm_delete_user', 'Удалить этого пользователя?', 'Бұл пайдаланушыны жою керек пе?', '删除这个用户？'),
('admin.users_management_title', 'Управление пользователями', 'Пайдаланушыларды басқару', '用户管理'),
('admin.users_management_heading', 'Управление пользователями', 'Пайдаланушыларды басқару', '用户管理'),
('admin.users_management_desc', 'Управление пользователями системы и их правами доступа', 'Жүйе пайдаланушыларын және олардың қол жетімділік құқықтарын басқару', '管理系统用户及其访问权限'),
('admin.table_user', 'Пользователь', 'Пайдаланушы', '用户'),
('admin.table_role', 'Роль', 'Рөл', '角色'),
('admin.table_status', 'Статус', 'Күй', '状态'),
('admin.table_registration_date', 'Дата регистрации', 'Тіркелу күні', '注册日期'),
('admin.status_approved', 'Подтвержден', 'Расталды', '已确认'),
('admin.status_pending', 'Ожидает', 'Күтуде', '等待中'),
('admin.toggle_access_title', 'Переключить доступ', 'Қол жетімділікті ауыстыру', '切换访问权限'),
('admin.delete_user_title', 'Удалить пользователя', 'Пайдаланушыны жою', '删除用户'),
('cargo.add_cargo_button', 'Добавить груз', 'Жүк қосу', '添加货物'),
('cargo.filter_button', 'Фильтровать', 'Сүзгілеу', '筛选'),
('cargo.volume_weight', 'м³, кг', 'м³, кг', '立方米, 公斤'),
('cargo.view_button', 'Просмотр', 'Көру', '查看'),
('cargo.try_change_search', 'Попробуйте изменить параметры поиска', 'Іздеу параметрлерін өзгертуге тырысыңыз', '尝试更改搜索参数'),
('cargo.confirm_delete', 'Удалить этот груз?', 'Бұл жүкті жою керек пе?', '删除这个货物？'),
('cargo.volume_label', 'Объем:', 'Көлемі:', '体积：'),
('cargo.weight_label', 'Вес:', 'Салмағы:', '重量：'),
('cargo.readiness_label', 'Готовность:', 'Дайындық:', '准备状态：'),
('cargo.created_label', 'Создан:', 'Құрылған:', '创建时间：'),
('applications.title', 'Заявки на грузы', 'Жүктерге өтініштер', '货物申请'),
('applications.heading', 'Заявки на грузы', 'Жүктерге өтініштер', '货物申请'),
('applications.admin_desc', 'Все заявки в системе', 'Жүйедегі барлық өтініштер', '系统中的所有申请'),
('applications.driver_desc', 'Заявки на ваши грузы', 'Сіздің жүктеріңізге өтініштер', '您货物的申请'),
('applications.status_label', 'Статус', 'Күй', '状态'),
('applications.all_statuses', 'Все статусы', 'Барлық күйлер', '所有状态'),
('applications.status_pending', 'Ожидает', 'Күтуде', '等待中'),
('applications.status_approved', 'Подтверждена', 'Бекітілді', '已确认'),
('applications.status_rejected', 'Отклонена', 'Қабылданбады', '已拒绝'),
('applications.search_label', 'Поиск', 'Іздеу', '搜索'),
('applications.search_placeholder', 'Поиск по маршруту или водителю', 'Маршрут немесе жүргізуші бойынша іздеу', '按路线或司机搜索'),
('applications.search_button', 'Поиск', 'Іздеу', '搜索'),
('applications.table_route', 'Маршрут', 'Маршрут', '路线'),
('applications.table_driver', 'Водитель', 'Жүргізуші', '司机'),
('applications.table_status', 'Статус', 'Күй', '状态'),
('applications.table_submitted', 'Подана', 'Ұсынылды', '已提交'),
('applications.table_actions', 'Действия', 'Әрекеттер', '操作'),
('applications.status_pending_short', 'Ожидает', 'Күтуде', '等待中'),
('applications.status_approved_short', 'Подтверждена', 'Бекітілген', '已确认'),
('applications.status_rejected_short', 'Отклонена', 'Бас тартылған', '已拒绝'),
('applications.view_details', 'Подробнее', 'Толығырақ', '详情'),
('applications.approve_button', 'Подтвердить', 'Бекіту', '确认'),
('applications.reject_button', 'Отклонить', 'Бас тарту', '拒绝'),
('applications.driver_label', 'Водитель:', 'Жүргізуші:', '司机：'),
('applications.submitted_label', 'Подана:', 'Берілген:', '提交时间：'),
('applications.confirm_approve', 'Подтвердить заявку этого водителя?', 'Бұл жүргізушінің өтінішін бекіту керек пе?', '确认这个司机的申请？'),
('applications.confirm_reject', 'Отклонить заявку этого водителя?', 'Бұл жүргізушінің өтінішін бас тарту керек пе?', '拒绝这个司机的申请？'),
('applications.no_applications', 'Заявки не найдены', 'Өтініштер табылмады', '未找到申请'),
('applications.no_applications_desc', 'В данный момент нет заявок на перевозку', 'Қазіргі уақытта тасымалдауға өтініштер жоқ', '目前没有运输申请'),
('applications.back_button', 'Назад', 'Артқа', '返回'),
('my_cargo.title', 'Мои грузы', 'Менің жүктерім', '我的货物'),
('my_cargo.heading', 'Мои грузы', 'Менің жүктерім', '我的货物'),
('my_cargo.description', 'Грузы, которые вы забрали для доставки', 'Сіз жеткізу үшін алған жүктер', '您已取走用于交付的货物'),
('my_cargo.back_button', 'Назад', 'Артқа', '返回'),
('my_cargo.table_route', 'Маршрут', 'Маршрут', '路线'),
('my_cargo.table_cargo', 'Груз', 'Жүк', '货物'),
('my_cargo.table_picked', 'Забран', 'Алынған', '已取'),
('my_cargo.table_status', 'Статус', 'Күй', '状态'),
('my_cargo.status_in_delivery', 'В доставке', 'Жеткізуде', '运输中'),
('my_cargo.status_delivered', 'Доставлен', 'Жеткізілген', '已送达'),
('my_cargo.mark_delivered', 'Доставлен', 'Жеткізілген', '已送达'),
('my_cargo.confirm_mark_delivered', 'Отметить груз как доставленный?', 'Жүкті жеткізілген деп белгілеу керек пе?', '将货物标记为已送达？'),
('my_cargo.view_button', 'Просмотр', 'Көру', '查看'),
('my_cargo.volume_label', 'Объем:', 'Көлемі:', '体积：'),
('my_cargo.weight_label', 'Вес:', 'Салмағы:', '重量：'),
('my_cargo.picked_label', 'Забран:', 'Алынған:', '已取：'),
('my_cargo.no_cargo_title', 'У вас пока нет забранных грузов', 'Сізде әзірше алынған жүктер жоқ', '您目前没有已取的货物'),
('my_cargo.no_cargo_desc', 'Заберите груз для доставки, чтобы он появился в этом списке', 'Жеткізуге жүк алыңыз, ол осы тізімде пайда болады', '取走货物进行交付，它将出现在此列表中'),
('my_cargo.stats_total_picked', 'Всего забрано', 'Барлығы алынған', '总共已取'),
('my_cargo.stats_in_delivery', 'В доставке', 'Жеткізуде', '运输中'),
('my_cargo.stats_delivered', 'Доставлено', 'Жеткізілген', '已送达'),
('my_applications.title', 'Мои заявки', 'Менің өтініштерім', '我的申请'),
('my_applications.heading', 'Мои заявки', 'Менің өтініштерім', '我的申请'),
('my_applications.description', 'Отслеживайте статус ваших заявок на перевозку грузов', 'Жүктерді тасымалдауға арналған өтініштеріңіздің күйін қадағалаңыз', '跟踪您货物运输申请的状态'),
('my_applications.view_cargo_button', 'Посмотреть грузы', 'Жүктерді көру', '查看货物'),
('my_applications.stats_pending', 'Ожидают', 'Күтуде', '等待中'),
('my_applications.stats_approved', 'Подтверждены', 'Бекітілген', '已确认'),
('my_applications.stats_rejected', 'Отклонены', 'Бас тартылған', '已拒绝'),
('my_applications.pending_title', 'Ожидающие заявки', 'Күтудегі өтініштер', '等待中的申请'),
('my_applications.approved_title', 'Подтвержденные заявки', 'Бекітілген өтініштер', '已确认的申请'),
('my_applications.rejected_title', 'Отклоненные заявки', 'Бас тартылған өтініштер', '已拒绝的申请'),
('my_applications.table_route', 'Маршрут', 'Маршрут', '路线'),
('my_applications.table_cargo', 'Груз', 'Жүк', '货物'),
('my_applications.table_submitted', 'Подана', 'Берілген', '提交时间'),
('my_applications.table_actions', 'Действия', 'Әрекеттер', '操作'),
('my_applications.status_pending', 'Ожидает', 'Күтуде', '等待中'),
('my_applications.status_approved', 'Подтверждено', 'Бекітілген', '已确认'),
('my_applications.status_rejected', 'Отклонено', 'Бас тартылған', '已拒绝'),
('my_applications.view_details', 'Подробнее', 'Толығырақ', '详情'),
('my_applications.volume_weight', 'м³, кг', 'м³, кг', '立方米, 公斤'),
('my_applications.volume_label', 'Объем:', 'Көлемі:', '体积：'),
('my_applications.weight_label', 'Вес:', 'Салмағы:', '重量：'),
('my_applications.submitted_label', 'Подана:', 'Берілген:', '提交时间：'),
('my_applications.driver_notes_label', 'Ваши заметки:', 'Сіздің ескертпелеріңіз:', '您的备注：'),
('my_applications.no_applications_title', 'У вас пока нет заявок', 'Сізде әзірше өтініштер жоқ', '您目前没有申请'),
('my_applications.no_applications_desc', 'Подайте заявку на любой доступный груз, чтобы начать работу', 'Жұмысты бастау үшін кез келген қолжетімді жүкке өтініш беріңіз', '申请任何可用的货物开始工作'),
('my_applications.view_available_cargo', 'Посмотреть доступные грузы', 'Қолжетімді жүктерді көру', '查看可用货物'),
('my_applications.back_button', 'Назад', 'Артқа', '返回'),
('admin.delete_user', 'Удалить', 'Жою', '删除'),
('admin.confirm_delete', 'Удалить этого пользователя?', 'Бұл пайдаланушыны жою керек пе?', '删除此用户？'),
('admin.confirm_reject', 'Отклонить этого пользователя?', 'Бұл пайдаланушыны бас тарту керек пе?', '拒绝此用户？'),
('admin.warehouse_employee', 'Сотрудник склада', 'Қойма қызметкері', '仓库员工'),
('admin.driver', 'Водитель', 'Жүргізуші', '司机'),
('admin.registered', 'Зарегистрирован:', 'Тіркелген:', '已注册：'),
('admin.approved', 'Подтвержден:', 'Бекітілген:', '已确认：'),
('applications.all_applications', 'Все заявки в системе', 'Жүйедегі барлық өтініштер', '系统中的所有申请'),
('applications.your_cargo_applications', 'Заявки на ваши грузы', 'Сіздің жүктеріңізге өтініштер', '您货物的申请'),
('cargo.create_title', 'Добавить груз - Silk Way', 'Жүк қосу - Silk Way', '添加货物 - Silk Way'),
('cargo.new_cargo', 'Новый груз', 'Жаңа жүк', '新货物'),
('cargo.create_desc', 'Заполните информацию о грузе для отправки', 'Жіберуге арналған жүк туралы ақпаратты толтырыңыз', '填写要发送的货物信息'),
('cargo.from_location', 'Откуда', 'Қайдан', '从哪里'),
('cargo.to_location', 'Куда', 'Қайда', '到哪里'),
('cargo.cargo_type', 'Тип груза', 'Жүк түрі', '货物类型'),
('cargo.volume', 'Объем (м³)', 'Көлем (м³)', '体积 (m³)'),
('cargo.weight', 'Вес (кг)', 'Салмақ (кг)', '重量 (kg)'),
('cargo.ready_date', 'Дата и время готовности', 'Дайындық күні мен уақыты', '准备就绪的日期和时间'),
('cargo.comment', 'Комментарий / контакт', 'Түсініктеме / байланыс', '评论/联系'),
('cargo.comment_placeholder', 'Дополнительная информация, контактные данные...', 'Қосымша ақпарат, байланыс деректері...', '附加信息、联系信息...'),
('cargo.cancel', 'Отмена', 'Бас тарту', '取消'),
('cargo.create_cargo', 'Создать груз', 'Жүк құру', '创建货物'),
('cargo.edit_title', 'Редактировать груз - Silk Way', 'Жүкті өңдеу - Silk Way', '编辑货物 - Silk Way'),
('cargo.edit_cargo', 'Редактировать груз', 'Жүкті өңдеу', '编辑货物'),
('cargo.update_cargo', 'Обновить груз', 'Жүкті жаңарту', '更新货物'),
('cars.create_title', 'Добавить машину - Silk Way', 'Машина қосу - Silk Way', '添加车辆 - Silk Way'),
('cars.new_car', 'Новая машина', 'Жаңа машина', '新车辆'),
('cars.create_desc', 'Заполните информацию о машине и прицепе', 'Машина мен тіркеме туралы ақпаратты толтырыңыз', '填写车辆和拖车信息'),
('cars.brand', 'Марка', 'Марка', '品牌'),
('cars.model', 'Модель', 'Модель', '型号'),
('cars.license_plate', 'Гос. номер', 'Мемлекеттік нөмір', '车牌号'),
('cars.max_weight', 'Макс. вес (т)', 'Макс. салмақ (т)', '最大重量 (t)'),
('cars.trailer_type', 'Тип прицепа', 'Тіркеме түрі', '拖车类型'),
('cars.trailer_length', 'Длина прицепа (м)', 'Тіркеме ұзындығы (м)', '拖车长度 (m)'),
('cars.trailer_width', 'Ширина прицепа (м)', 'Тіркеме ені (м)', '拖车宽度 (m)'),
('cars.trailer_height', 'Высота прицепа (м)', 'Тіркеме биіктігі (м)', '拖车高度 (m)'),
('cars.vehicle_document', 'Документ ПДД (PDF)', 'Жол қауіпсіздігі қағидалары құжаты (PDF)', '交通规则文件 (PDF)'),
('cars.create_car', 'Создать машину', 'Машина құру', '创建车辆'),
('cars.edit_title', 'Редактировать машину - Silk Way', 'Машинаны өңдеу - Silk Way', '编辑车辆 - Silk Way'),
('cars.update_car', 'Обновить машину', 'Машинаны жаңарту', '更新车辆'),
('common.success', 'Успешно!', 'Сәтті!', '成功！'),
('common.error', 'Ошибка!', 'Қате!', '错误！'),
('common.warning', 'Внимание!', 'Назар аударыңыз!', '注意！'),
('common.info', 'Информация', 'Ақпарат', '信息'),
('common.confirm', 'Подтверждение', 'Растау', '确认'),
('common.yes', 'Да', 'Иә', '是'),
('common.no', 'Нет', 'Жоқ', '否'),
('common.loading', 'Загрузка...', 'Жүктелуде...', '加载中...'),
('common.no_data', 'Нет данных', 'Деректер жоқ', '无数据'),
('common.actions', 'Действия', 'Әрекеттер', '操作'),
('common.view', 'Просмотр', 'Көру', '查看'),
('common.edit', 'Редактировать', 'Өңдеу', '编辑'),
('common.delete', 'Удалить', 'Жою', '删除'),
('common.back', 'Назад', 'Артқа', '返回'),
('common.close', 'Закрыть', 'Жабу', '关闭'),
('brand', 'Марка', 'Марка', '品牌'),
('model', 'Модель', 'Модель', '型号'),
('license_plate', 'Гос. номер', 'Мемлекеттік нөмір', '车牌号'),
('max_weight', 'Макс. вес', 'Макс. салмақ', '最大重量'),
('trailer_type', 'Тип прицепа', 'Тіркеме түрі', '拖车类型'),
('trailer_length', 'Длина прицепа', 'Тіркеме ұзындығы', '拖车长度'),
('trailer_width', 'Ширина прицепа', 'Тіркеме ені', '拖车宽度'),
('trailer_height', 'Высота прицепа', 'Тіркеме биіктігі', '拖车高度'),
('trailer_volume', 'Объем прицепа', 'Тіркеме көлемі', '拖车体积'),
('user', 'Пользователь', 'Пайдаланушы', '用户'),
('name', 'Имя', 'Аты', '姓名'),
('role', 'Роль', 'Рөл', '角色'),
('admin', 'Администратор', 'Әкімші', '管理员'),
('driver', 'Водитель', 'Жүргізуші', '司机'),
('warehouse_employee', 'Сотрудник склада', 'Қойма қызметкері', '仓库员工'),
('approved', 'Одобрено', 'Бекітілді', '已批准'),
('pending_approval', 'Ожидает одобрения', 'Бекіту күтуде', '等待批准'),
('application', 'Заявка', 'Өтініш', '申请'),
('pending', 'Ожидает', 'Күтуде', '等待中'),
('rejected', 'Отклонено', 'Қабылданбады', '已拒绝'),
('dashboard', 'Панель управления', 'Басқару панелі', '控制面板'),
('translations', 'Переводы', 'Аудармалар', '翻译'),
('manage_translations', 'Управление переводами', 'Аудармаларды басқару', '管理翻译'),
('add_translation', 'Добавить перевод', 'Аударма қосу', '添加翻译'),
('edit_translation', 'Редактировать перевод', 'Аударманы өңдеу', '编辑翻译'),
('translation_key', 'Ключ перевода', 'Аударма кілті', '翻译键'),
('translation_group', 'Группа', 'Топ', '组'),
('translation_description', 'Описание', 'Сипаттама', '描述'),
('russian', 'Русский', 'Орысша', '俄语'),
('kazakh', 'Казахский', 'Қазақша', '哈萨克语'),
('chinese', 'Китайский', 'Қытайша', '中文'),
('header.admin_panel', 'Админ-панель', 'Админ панель', '管理面板'),
('header.users', 'Пользователи', 'Пайдаланушылар', '用户'),
('header.cargo', 'Грузы', 'Жүктер', '货物'),
('header.add_cargo', 'Добавить груз', 'Жүк қосу', '添加货物'),
('header.applications', 'Заявки', 'Өтініштер', '申请'),
('header.all_cars', 'Все машины', 'Барлық көліктер', '所有车辆'),
('header.my_cargo', 'Мои грузы', 'Менің жүктерім', '我的货物'),
('header.my_applications', 'Мои заявки', 'Менің өтініштерім', '我的申请'),
('header.my_cars', 'Мои машины', 'Менің көліктерім', '我的车辆'),
('header.logout', 'Выйти', 'Шығу', '退出'),
('header.role_admin', 'Админ', 'Админ', '管理员'),
('header.role_warehouse', 'Склад', 'Қойма', '仓库'),
('header.role_driver', 'Водитель', 'Жүргізуші', '司机'),
('header.profile', 'Профиль', 'Профиль', '个人资料'),
('header.cars', 'Машины', 'Көліктер', '车辆'),
('header.footer_text', 'Система управления грузоперевозками.', 'Жүк тасымалдау басқару жүйесі.', '货运管理系统。'),
('auth.email', 'Email', 'Email', '邮箱'),
('auth.password', 'Пароль', 'Құпия сөз', '密码'),
('auth.full_name_placeholder', 'Введите ваше полное имя', 'Толық атыңызды енгізіңіз', '请输入您的全名'),
('auth.password_confirmation_placeholder', 'Подтвердите пароль', 'Құпия сөзді растаңыз', '请确认密码'),
('admin.add_translation', 'Добавить перевод', 'Аударма қосу', '添加翻译'),
('admin.export', 'Экспорт', 'Экспорт', '导出'),
('admin.clear_cache', 'Очистить кэш', 'Кэшті тазалау', '清除缓存'),
('admin.search_by_key', 'Поиск по ключу', 'Кілт бойынша іздеу', '按键搜索'),
('admin.search_placeholder', 'Введите ключ перевода...', 'Аударма кілтін енгізіңіз...', '输入翻译键...'),
('admin.all_groups', 'Все группы', 'Барлық топтар', '所有组'),
('admin.filter', 'Фильтр', 'Сүзгі', '过滤'),
('admin.clear_filters', 'Сбросить фильтры', 'Сүзгілерді тазалау', '清除过滤器'),
('admin.table_key', 'Ключ', 'Кілт', '键'),
('admin.table_russian', 'Русский', 'Орысша', '俄语'),
('admin.table_kazakh', 'Қазақша', 'Қазақша', '哈萨克语'),
('admin.table_chinese', '中文', 'Қытайша', '中文'),
('admin.table_group', 'Группа', 'Топ', '组'),
('admin.table_actions', 'Действия', 'Әрекеттер', '操作'),
('admin.no_translations_found', 'Переводы не найдены', 'Аудармалар табылмады', '未找到翻译'),
('admin.try_change_search', 'Попробуйте изменить параметры поиска', 'Іздеу параметрлерін өзгертіп көріңіз', '尝试更改搜索参数'),
('admin.no_translations_desc', 'В данный момент нет переводов в системе', 'Қазіргі уақытта жүйеде аудармалар жоқ', '目前系统中没有翻译'),
('admin.reset_filters', 'Сбросить фильтры', 'Сүзгілерді тазалау', '重置过滤器');

-- Всего: 347 переводов




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
-- ✅ 347 полных переводов (RU, KZ, CN) - ВСЕ переводы системы!
-- ============================================
