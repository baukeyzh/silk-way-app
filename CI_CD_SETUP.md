# 🔧 Настройка CI/CD для Silk Way

## 📋 Предварительные требования

### 1. GitHub Repository
- Создайте репозиторий на GitHub
- Скопируйте URL репозитория

### 2. Docker Hub (опционально)
- Создайте аккаунт на Docker Hub
- Создайте репозиторий для образа приложения

### 3. Сервер для деплоя
- VPS или облачный сервер
- Установленный Docker и Docker Compose
- SSH доступ к серверу

## 🚀 Настройка GitHub Actions

### 1. Секреты GitHub

Перейдите в настройки репозитория → Secrets and variables → Actions и добавьте:

#### Для Docker Hub (если используете):
```
DOCKER_USERNAME=your-dockerhub-username
DOCKER_PASSWORD=your-dockerhub-password
```

#### Для деплоя на сервер:
```
HOST=your-server-ip
USERNAME=your-server-username
SSH_KEY=your-private-ssh-key
```

### 2. Настройка SSH ключей

На сервере:
```bash
# Создайте SSH ключ
ssh-keygen -t rsa -b 4096 -C "github-actions"

# Добавьте публичный ключ в authorized_keys
cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys

# Скопируйте приватный ключ для GitHub Secrets
cat ~/.ssh/id_rsa
```

### 3. Настройка сервера

На сервере создайте директорию для проекта:
```bash
mkdir -p /var/www/silk-way
cd /var/www/silk-way

# Клонируйте репозиторий
git clone https://github.com/your-username/silk-way-app.git .

# Настройте права доступа
sudo chown -R $USER:$USER /var/www/silk-way
chmod +x scripts/deploy.sh
```

## 🔄 Настройка автоматического деплоя

### 1. Обновите GitHub Actions

Отредактируйте файл `.github/workflows/ci.yml`:

```yaml
# Замените your-username на ваш GitHub username
- name: Build and push Docker image
  uses: docker/build-push-action@v5
  with:
    context: .
    push: true
    tags: |
      your-username/silk-way:latest
      your-username/silk-way:${{ github.sha }}
```

### 2. Настройте деплой скрипт

Обновите `scripts/deploy.sh` для вашего сервера:

```bash
# Замените на ваш репозиторий
git pull origin main
```

## 🐳 Настройка Docker

### 1. Локальная разработка

```bash
# Запуск в режиме разработки
make dev

# Или напрямую
docker-compose -f docker-compose.dev.yml up -d
```

### 2. Продакшн деплой

```bash
# Автоматический деплой
make deploy

# Или вручную
docker-compose up -d --build
```

## 🔐 Настройка безопасности

### 1. Обновите .env.example

```env
# Основные настройки
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# База данных (для продакшна)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=silk_way
DB_USERNAME=silk_way_user
DB_PASSWORD=secure_password

# Redis
REDIS_HOST=redis
REDIS_PORT=6379

# Кэш и сессии
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 2. Настройте SSL

Добавьте SSL сертификаты в `docker/nginx/sites/silk-way.conf`:

```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    ssl_certificate /etc/ssl/certs/yourdomain.com.crt;
    ssl_certificate_key /etc/ssl/private/yourdomain.com.key;
    
    # Остальная конфигурация...
}
```

## 📊 Мониторинг

### 1. Логи

```bash
# Просмотр логов
make logs

# Логи конкретного сервиса
docker-compose logs app
docker-compose logs nginx
docker-compose logs redis
```

### 2. Статус

```bash
# Статус контейнеров
make status

# Проверка здоровья
make health
```

## 🔄 Обновление

### 1. Автоматическое обновление

При пуше в ветку `main`:
- Автоматически запускаются тесты
- Собирается Docker образ
- Деплоится на сервер

### 2. Ручное обновление

```bash
# На сервере
cd /var/www/silk-way
git pull origin main
docker-compose down
docker-compose up -d --build
```

## 🐛 Устранение неполадок

### 1. GitHub Actions не запускаются

- Проверьте, что файлы в `.github/workflows/` корректны
- Убедитесь, что секреты настроены правильно
- Проверьте права доступа к репозиторию

### 2. Деплой не работает

- Проверьте SSH соединение: `ssh user@server`
- Убедитесь, что Docker запущен на сервере
- Проверьте логи GitHub Actions

### 3. Приложение не запускается

- Проверьте логи: `docker-compose logs`
- Убедитесь, что база данных доступна
- Проверьте переменные окружения

## 📞 Поддержка

При возникновении проблем:

1. Проверьте логи GitHub Actions
2. Проверьте логи на сервере: `make logs`
3. Создайте Issue в репозитории
4. Обратитесь к документации Docker и Laravel

## 🎯 Следующие шаги

1. Настройте мониторинг (Prometheus, Grafana)
2. Добавьте уведомления в Slack/Telegram
3. Настройте автоматические бэкапы
4. Добавьте тесты производительности
5. Настройте CDN для статических файлов

