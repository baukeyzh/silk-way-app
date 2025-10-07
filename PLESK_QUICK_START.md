# ⚡ Быстрый старт для Plesk

## 🎯 Основная информация

- **Репозиторий**: https://github.com/baukeyzh/silk-way-app.git
- **Ветка**: main
- **Document Root**: `/httpdocs/public` (не `/httpdocs`!)
- **PHP**: 8.2 или выше
- **База данных**: SQLite (включена)

## 🚀 Быстрое развертывание через Plesk Git

### 1. В Plesk панели

1. **Домены** → `fruck.kz` → **Git** → **Добавить репозиторий**
2. **URL**: `https://github.com/baukeyzh/silk-way-app.git`
3. **Ветка**: `main`
4. **Путь**: `httpdocs`
5. **Действия после развертывания**:

```bash
#!/bin/bash
cd {DEPLOYMENT_PATH}

# Создать .env
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Настройка
php artisan key:generate --force
chmod -R 775 storage bootstrap/cache
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite

# Деплой
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

6. **Развернуть**

### 2. Настройка хостинга

1. **Настройки хостинга** → **Document Root**: `/httpdocs/public`
2. **Настройки PHP** → **Версия**: PHP 8.2+
3. **SSL/TLS** → Установить Let's Encrypt

### 3. Редактировать .env (SSH)

```bash
ssh username@fruck.kz
cd /var/www/vhosts/fruck.kz/httpdocs
nano .env
```

Изменить:
```env
APP_URL=https://fruck.kz
APP_ENV=production
APP_DEBUG=false
DB_DATABASE=/var/www/vhosts/fruck.kz/httpdocs/database/database.sqlite
```

Сохранить: `Ctrl+O`, `Enter`, `Ctrl+X`

```bash
php artisan config:cache
```

### 4. Готово! ✅

Открыть: **https://fruck.kz**

## 🔄 Обновление

### Через Plesk
Git → **Обновить из репозитория**

### Через SSH
```bash
cd /var/www/vhosts/fruck.kz/httpdocs
git pull origin main
php artisan migrate --force
php artisan config:cache
```

## 🐛 Если что-то не работает

### Ошибка 500
```bash
chmod -R 775 storage bootstrap/cache
php artisan config:clear
php artisan cache:clear
```

### БД не работает
```bash
chmod 664 database/database.sqlite
php artisan migrate:fresh --seed --force
```

### Стили не отображаются
```bash
php artisan storage:link
```

## 📝 Логи

```bash
# Laravel
tail -f /var/www/vhosts/fruck.kz/httpdocs/storage/logs/laravel.log

# Сервер
tail -f /var/www/vhosts/system/fruck.kz/logs/error_log
```

## ⚠️ Важно!

- ✅ **vendor** и **node_modules** УЖЕ в репозитории (не нужен composer/npm)
- ✅ **Ассеты собраны** (не нужен npm run build)
- ✅ Document Root = `/httpdocs/public` (НЕ `/httpdocs`)
- ✅ После развертывания проверить `.env` файл

---

**Полная инструкция**: `PLESK_DEPLOYMENT.md`

