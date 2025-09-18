# 🚀 Руководство по развертыванию Silk Way

## 📋 Требования

- Docker 20.10+
- Docker Compose 2.0+
- Git
- 2GB RAM минимум
- 10GB свободного места

## 🐳 Развертывание с Docker

### 1. Клонирование репозитория

```bash
git clone <repository-url>
cd silk-way-app
```

### 2. Настройка окружения

```bash
# Копируем файл окружения
cp .env.example .env

# Редактируем настройки (опционально)
nano .env
```

### 3. Автоматический деплой

```bash
# Запускаем скрипт деплоя
./scripts/deploy.sh
```

### 4. Ручной деплой

```bash
# Собираем и запускаем контейнеры
docker-compose up -d --build

# Запускаем миграции
docker-compose exec app php artisan migrate --force

# Запускаем сидеры
docker-compose exec app php artisan db:seed --force

# Очищаем кэш
docker-compose exec app php artisan cache:clear
```

## 🔧 Управление приложением

### Полезные команды

```bash
# Просмотр логов
docker-compose logs -f

# Остановка всех контейнеров
docker-compose down

# Перезапуск приложения
docker-compose restart

# Вход в контейнер приложения
docker-compose exec app bash

# Выполнение команд Laravel
docker-compose exec app php artisan [command]

# Обновление зависимостей
docker-compose exec app composer install
docker-compose exec app npm install && npm run build
```

### Мониторинг

```bash
# Статус контейнеров
docker-compose ps

# Использование ресурсов
docker stats

# Логи конкретного сервиса
docker-compose logs app
docker-compose logs nginx
docker-compose logs redis
```

## 🌐 Настройка веб-сервера

### Nginx (рекомендуется)

1. Установите Nginx на сервер
2. Скопируйте конфигурацию из `docker/nginx/sites/silk-way.conf`
3. Настройте SSL сертификаты
4. Обновите upstream для проксирования на `localhost:8000`

### Apache

1. Установите Apache с mod_rewrite
2. Настройте виртуальный хост
3. Укажите DocumentRoot на `public/` директорию

## 🔐 Настройка безопасности

### Переменные окружения

```env
# Основные настройки
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# База данных
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

# Кэш и сессии
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
```

### SSL сертификаты

```bash
# Используйте Let's Encrypt
certbot --nginx -d yourdomain.com

# Или добавьте сертификаты в docker/nginx/sites/
```

## 📊 Мониторинг и логи

### Логи приложения

```bash
# Laravel логи
docker-compose exec app tail -f storage/logs/laravel.log

# Nginx логи
docker-compose logs nginx

# Все логи
docker-compose logs
```

### Мониторинг производительности

```bash
# Использование ресурсов
docker stats

# Проверка здоровья
curl -f http://localhost:8000/health

# Проверка базы данных
docker-compose exec app php artisan tinker
```

## 🔄 Обновление приложения

### Автоматическое обновление

```bash
# Обновляем код
git pull origin main

# Перезапускаем с новой версией
./scripts/deploy.sh
```

### Ручное обновление

```bash
# Останавливаем контейнеры
docker-compose down

# Обновляем код
git pull origin main

# Пересобираем образы
docker-compose build --no-cache

# Запускаем миграции
docker-compose up -d
docker-compose exec app php artisan migrate --force

# Очищаем кэш
docker-compose exec app php artisan cache:clear
```

## 🐛 Устранение неполадок

### Частые проблемы

1. **Контейнер не запускается**
   ```bash
   docker-compose logs app
   ```

2. **Ошибки базы данных**
   ```bash
   docker-compose exec app php artisan migrate:status
   ```

3. **Проблемы с правами доступа**
   ```bash
   docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
   ```

4. **Очистка кэша**
   ```bash
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan view:clear
   ```

### Восстановление из бэкапа

```bash
# Останавливаем приложение
docker-compose down

# Восстанавливаем базу данных
cp backup/database.sqlite database/database.sqlite

# Запускаем приложение
docker-compose up -d
```

## 📞 Поддержка

При возникновении проблем:

1. Проверьте логи: `docker-compose logs`
2. Убедитесь, что все контейнеры запущены: `docker-compose ps`
3. Проверьте доступность приложения: `curl http://localhost:8000`
4. Создайте issue в репозитории с подробным описанием проблемы

## 🔗 Полезные ссылки

- [Docker Documentation](https://docs.docker.com/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Nginx Configuration](https://nginx.org/en/docs/)
