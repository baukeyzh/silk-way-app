# ⚡ Быстрый старт для Plesk (БЕЗ SSH)

## 🎯 Основная информация

- **Репозиторий**: https://github.com/baukeyzh/silk-way-app.git
- **Ветка**: main
- **Document Root**: `/httpdocs/public` ⚠️ (не `/httpdocs`!)
- **PHP**: 8.2 или выше
- **База данных**: SQLite (включена)
- **SSH**: НЕ требуется! Все через веб-интерфейс Plesk

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

### 3. Редактировать .env через File Manager

1. **В Plesk:** "Файлы" → `httpdocs` → Найти `.env`
2. **Правый клик** → "Редактировать"
3. **Изменить:**

```env
APP_URL=https://fruck.kz
APP_ENV=production
APP_DEBUG=false
DB_DATABASE=/var/www/vhosts/fruck.kz/httpdocs/database/database.sqlite
```

4. **Сохранить файл**
5. **Очистить кэш** через "Планировщик заданий":
   - Создать задание
   - Команда: `cd /var/www/vhosts/fruck.kz/httpdocs && php artisan config:cache`
   - Запустить сейчас

### 4. Готово! ✅

Открыть: **https://fruck.kz**

## 🔄 Обновление

### Через Plesk (основной метод)
1. Git → **"Pull"** или **"Обновить из репозитория"**
2. Скрипт выполнится автоматически

### Если нужны дополнительные команды
1. **Планировщик заданий** → Создать задание
2. **Команда:**
```bash
cd /var/www/vhosts/fruck.kz/httpdocs && php artisan migrate --force && php artisan config:cache
```
3. **Запустить сейчас**

## 🐛 Если что-то не работает

### Ошибка 500
1. **File Manager** → папки `storage` и `bootstrap/cache`
2. **Правый клик** → Изменить права → **775**
3. **Применить рекурсивно**
4. **Планировщик** → команда:
   ```bash
   cd /var/www/vhosts/fruck.kz/httpdocs && php artisan config:clear && php artisan cache:clear
   ```

### БД не работает
1. **File Manager** → `database/database.sqlite`
2. **Правый клик** → Изменить права → **664**
3. Или удалить файл и Git → **Pull** (пересоздаст БД)

### Стили не отображаются
1. Проверить `public/storage` через **File Manager**
2. Проверить **APP_URL** в `.env`
3. Очистить кэш браузера (Ctrl+Shift+R)

## 📝 Логи (через Plesk)

### Laravel логи
1. **"Файлы"** → `httpdocs/storage/logs/laravel.log`
2. Или скачать и открыть

### Логи сервера
1. **"Сайты и домены"** → ваш домен → **"Логи"**
2. Просмотреть **error_log**

## ⚠️ Важно!

- ✅ **vendor** и **node_modules** УЖЕ в репозитории (не нужен composer/npm)
- ✅ **Ассеты собраны** (не нужен npm run build)
- ✅ Document Root = `/httpdocs/public` (НЕ `/httpdocs`)
- ✅ После развертывания проверить `.env` файл

---

**Полная инструкция**: `PLESK_DEPLOYMENT.md`

