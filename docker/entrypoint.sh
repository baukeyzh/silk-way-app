#!/bin/bash
set -e

# Ждем пока база данных будет готова
echo "Waiting for database to be ready..."

# Создаем базу данных если её нет
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating SQLite database..."
    touch /var/www/html/database/database.sqlite
fi

# Генерируем ключ приложения если его нет
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Запускаем миграции
echo "Running database migrations..."
php artisan migrate --force

# Запускаем сидеры
echo "Running database seeders..."
php artisan db:seed --force

# Очищаем кэш
echo "Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Устанавливаем права доступа
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "Application is ready!"

# Запускаем переданную команду
exec "$@"
