#!/bin/bash
set -e

# Ждем пока база данных будет готова
echo "Waiting for database to be ready..."

# Если используется MySQL, ждем пока он будет доступен
if [ "${DB_CONNECTION}" = "mysql" ]; then
    echo "Waiting for MySQL to be ready..."
    while ! nc -z ${DB_HOST} ${DB_PORT}; do
        sleep 1
    done
    echo "MySQL is ready!"
else
    # Создаем SQLite базу данных если её нет и используется SQLite
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        echo "Creating SQLite database..."
        touch /var/www/html/database/database.sqlite
    fi
fi

# Генерируем ключ приложения если его нет
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Запускаем миграции
echo "Running database migrations..."
php artisan migrate --force

# Запускаем сидеры (только если база пустая)
echo "Running database seeders..."
php artisan db:seed --force || echo "Seeding failed or database already seeded, continuing..."

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
