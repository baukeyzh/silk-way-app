#!/bin/bash

# Скрипт деплоя для Silk Way
set -e

echo "🚀 Начинаем деплой Silk Way..."

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Функция для вывода сообщений
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
    exit 1
}

# Проверяем наличие Docker
if ! command -v docker &> /dev/null; then
    error "Docker не установлен. Пожалуйста, установите Docker."
fi

if ! command -v docker-compose &> /dev/null; then
    error "Docker Compose не установлен. Пожалуйста, установите Docker Compose."
fi

# Проверяем наличие .env файла
if [ ! -f .env ]; then
    warn ".env файл не найден. Копируем из .env.example..."
    cp .env.example .env
    log "Не забудьте настроить переменные в .env файле!"
fi

# Останавливаем контейнеры
log "Останавливаем существующие контейнеры..."
docker-compose down

# Собираем образы
log "Собираем Docker образы..."
docker-compose build --no-cache

# Запускаем контейнеры
log "Запускаем контейнеры..."
docker-compose up -d

# Ждем пока контейнеры запустятся
log "Ждем запуска контейнеров..."
sleep 10

# Проверяем статус контейнеров
log "Проверяем статус контейнеров..."
docker-compose ps

# Запускаем миграции
log "Запускаем миграции базы данных..."
docker-compose exec -T app php artisan migrate --force

# Запускаем сидеры
log "Запускаем сидеры базы данных..."
docker-compose exec -T app php artisan db:seed --force

# Очищаем кэш
log "Очищаем кэш приложения..."
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan view:clear

# Устанавливаем права доступа
log "Устанавливаем права доступа..."
docker-compose exec -T app chown -R www-data:www-data /var/www/html/storage
docker-compose exec -T app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec -T app chmod -R 755 /var/www/html/storage
docker-compose exec -T app chmod -R 755 /var/www/html/bootstrap/cache

# Проверяем здоровье приложения
log "Проверяем здоровье приложения..."
if curl -f http://localhost:8000 > /dev/null 2>&1; then
    log "✅ Приложение успешно развернуто и доступно по адресу http://localhost:8000"
else
    warn "⚠️  Приложение может быть недоступно. Проверьте логи: docker-compose logs"
fi

log "🎉 Деплой завершен!"
log "📊 Статус контейнеров:"
docker-compose ps

log "📝 Полезные команды:"
echo "  Просмотр логов: docker-compose logs -f"
echo "  Остановка: docker-compose down"
echo "  Перезапуск: docker-compose restart"
echo "  Вход в контейнер: docker-compose exec app bash"
