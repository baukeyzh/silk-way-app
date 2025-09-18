# 🚛 Silk Way - Система управления грузоперевозками

[![CI/CD](https://github.com/your-username/silk-way-app/actions/workflows/ci.yml/badge.svg)](https://github.com/your-username/silk-way-app/actions/workflows/ci.yml)
[![Security](https://github.com/your-username/silk-way-app/actions/workflows/security.yml/badge.svg)](https://github.com/your-username/silk-way-app/actions/workflows/security.yml)
[![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)

Современное веб-приложение на Laravel для управления грузоперевозками с многоязычной поддержкой, системой ролей и Docker контейнеризацией.

## ✨ Особенности

- 🌐 **Многоязычность**: Русский, казахский, китайский
- 👥 **Система ролей**: Администратор, сотрудник склада, водитель
- 🐳 **Docker**: Полная контейнеризация приложения
- 🔐 **Безопасность**: Система подтверждения аккаунтов
- 📱 **Адаптивность**: Работает на всех устройствах
- 🚀 **CI/CD**: Автоматическое тестирование и деплой
- 📊 **Статистика**: Детальная аналитика по грузам

## 🎯 Функциональность

### 👑 **Администратор:**
- Управление пользователями и подтверждение аккаунтов
- Статистика по грузам и пользователям
- Управление переводами
- Полный доступ ко всем функциям системы

### 📦 **Сотрудник склада:**
- Создание и управление грузами
- Отслеживание статуса грузов
- Управление заявками водителей
- Просмотр статистики по своим грузам

### 🚗 **Водитель:**
- Просмотр доступных грузов
- Подача заявок на грузы
- Управление своими машинами
- Отслеживание статуса заявок

## 🛠 Технологии

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Tailwind CSS, Font Awesome, Vite
- **База данных**: SQLite (разработка), MySQL/PostgreSQL (продакшн)
- **Кэширование**: Redis
- **Контейнеризация**: Docker, Docker Compose
- **CI/CD**: GitHub Actions
- **Тестирование**: PHPUnit, PHP CS Fixer

## 🚀 Быстрый старт

### С Docker (рекомендуется)

```bash
# Клонируем репозиторий
git clone <repository-url>
cd silk-way-app

# Запускаем автоматическую настройку
make setup

# Запускаем в режиме разработки
make dev
```

Приложение будет доступно по адресу: http://localhost:8000

### Без Docker

```bash
# Устанавливаем зависимости
composer install
npm install

# Настраиваем окружение
cp .env.example .env
php artisan key:generate

# Настраиваем базу данных
touch database/database.sqlite
php artisan migrate:fresh --seed

# Собираем фронтенд
npm run build

# Запускаем сервер
php artisan serve
```

## 🐳 Docker команды

```bash
# Развертывание
make deploy

# Режим разработки
make dev

# Просмотр логов
make logs

# Вход в контейнер
make shell

# Очистка
make clean
```

## 📊 Тестовые аккаунты

| Роль | Email | Пароль |
|------|-------|--------|
| Администратор | admin@example.com | password |
| Сотрудник склада | warehouse@example.com | password |
| Водитель | driver@example.com | password |

## 🌐 Многоязычность

Система поддерживает 3 языка:
- 🇷🇺 **Русский** (по умолчанию)
- 🇰🇿 **Казахский**
- 🇨🇳 **Китайский**

Переключение языков доступно на страницах входа и регистрации.

## 🗄 База данных

### Основные таблицы:
- `users` - Пользователи системы
- `cargo` - Грузы (с мультиязычными полями)
- `cars` - Машины водителей
- `cargo_applications` - Заявки на грузы
- `translations` - Переводы системы

### Миграции:
```bash
# Запуск миграций
php artisan migrate

# Откат миграций
php artisan migrate:rollback

# Пересоздание базы данных
php artisan migrate:fresh --seed
```

## 🧪 Тестирование

```bash
# Запуск всех тестов
make test

# Тесты с покрытием
make test-coverage

# Проверка кода
make lint

# Исправление кода
make lint-fix
```

## 🚀 Деплой

### Автоматический деплой

```bash
# Развертывание на сервере
make deploy
```

### Ручной деплой

```bash
# Сборка и запуск
docker-compose up -d --build

# Миграции
docker-compose exec app php artisan migrate --force

# Очистка кэша
make cache-clear
```

Подробное руководство по деплою: [DEPLOYMENT.md](DEPLOYMENT.md)

## 📁 Структура проекта

```
silk-way-app/
├── app/                    # Код приложения
│   ├── Http/Controllers/   # Контроллеры
│   ├── Models/            # Модели
│   ├── Services/          # Сервисы
│   └── Helpers/           # Хелперы
├── database/              # Миграции и сидеры
├── docker/               # Docker конфигурации
├── resources/views/       # Blade шаблоны
├── routes/               # Маршруты
├── scripts/              # Скрипты деплоя
├── tests/                # Тесты
├── .github/workflows/    # GitHub Actions
├── docker-compose.yml    # Docker Compose
├── Makefile             # Команды управления
└── README.md            # Документация
```

## 🔧 Разработка

### Добавление новых функций

1. Создайте миграцию: `php artisan make:migration create_table_name`
2. Создайте модель: `php artisan make:model ModelName`
3. Создайте контроллер: `php artisan make:controller ControllerName`
4. Добавьте маршруты в `routes/web.php`
5. Создайте представления в `resources/views/`
6. Добавьте тесты в `tests/`

### Добавление переводов

1. Добавьте ключи в `database/seeders/TranslationSeeder.php`
2. Запустите сидер: `php artisan db:seed --class=TranslationSeeder`
3. Используйте в коде: `\App\Helpers\LocalizationHelper::t('key')`

## 🔐 Безопасность

- ✅ Аутентификация и авторизация
- ✅ CSRF защита
- ✅ Валидация данных
- ✅ SQL injection защита
- ✅ XSS защита
- ✅ Система подтверждения аккаунтов
- ✅ Роли и права доступа

## 📈 Мониторинг

```bash
# Статус контейнеров
make status

# Проверка здоровья
make health

# Просмотр логов
make logs

# Создание бэкапа
make backup
```

## 🤝 Вклад в проект

1. Форкните репозиторий
2. Создайте ветку для функции: `git checkout -b feature/amazing-feature`
3. Зафиксируйте изменения: `git commit -m 'Add amazing feature'`
4. Отправьте в ветку: `git push origin feature/amazing-feature`
5. Создайте Pull Request

## 📝 Лицензия

Этот проект лицензирован под MIT License - см. файл [LICENSE](LICENSE) для деталей.

## 👥 Авторы

- **Silk Way Team** - *Изначальная разработка* - [GitHub](https://github.com/silk-way-team)

## 🙏 Благодарности

- [Laravel](https://laravel.com) - PHP фреймворк
- [Tailwind CSS](https://tailwindcss.com) - CSS фреймворк
- [Docker](https://docker.com) - Контейнеризация
- [Font Awesome](https://fontawesome.com) - Иконки

## 📞 Поддержка

Если у вас есть вопросы или проблемы:

1. Проверьте [Issues](https://github.com/your-username/silk-way-app/issues)
2. Создайте новый Issue с подробным описанием
3. Свяжитесь с командой разработки

---

**Silk Way** - Современное решение для управления грузоперевозками 🚛✨
