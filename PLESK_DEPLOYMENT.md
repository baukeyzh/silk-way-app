# 🚀 Руководство по развертыванию в Plesk

## 📋 Предварительные требования

- Plesk панель управления
- PHP 8.2 или выше
- Доступ по SSH (опционально, но рекомендуется)
- Git установлен на сервере

## 🔧 Метод 1: Развертывание через Git в Plesk (рекомендуется)

### 1. Настройка Git репозитория в Plesk

1. **Войдите в Plesk панель**
   - Перейдите на `https://ваш-сервер:8443`

2. **Выберите ваш домен**
   - В разделе "Сайты и домены" выберите `fruck.kz`

3. **Перейдите в раздел Git**
   - Нажмите на вкладку "Git"
   - Нажмите "Добавить репозиторий"

4. **Настройте Git репозиторий**
   ```
   URL репозитория: https://github.com/baukeyzh/silk-way-app.git
   Ветка: main
   Путь развертывания: httpdocs
   Режим развертывания: Обычный
   ```

5. **Действия после развертывания**
   
   В разделе "Действия после развертывания" добавьте следующий скрипт:
   
   ```bash
   #!/bin/bash
   cd {DEPLOYMENT_PATH}
   
   # Создаем .env файл из примера
   if [ ! -f .env ]; then
       cp .env.example .env
       echo "APP_KEY=" >> .env
   fi
   
   # Генерируем ключ приложения
   php artisan key:generate --force
   
   # Настраиваем права доступа для storage и cache
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   
   # Создаем директорию для БД если её нет
   mkdir -p database
   touch database/database.sqlite
   chmod 664 database/database.sqlite
   
   # Создаем символическую ссылку для storage
   php artisan storage:link
   
   # Запускаем миграции
   php artisan migrate --force
   
   # Запускаем сидеры
   php artisan db:seed --force
   
   # Очищаем кэш
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   
   echo "Развертывание завершено!"
   ```

6. **Нажмите "Развернуть"**

### 2. Настройка Document Root

1. **Перейдите в настройки хостинга**
   - "Сайты и домены" → ваш домен → "Настройки хостинга"

2. **Измените Document Root**
   ```
   Корневая директория документов: /httpdocs/public
   ```

3. **Сохраните изменения**

### 3. Настройка PHP

1. **Перейдите в настройки PHP**
   - "Сайты и домены" → ваш домен → "Настройки PHP"

2. **Установите версию PHP**
   - Выберите PHP 8.2 или выше

3. **Настройте php.ini**
   ```ini
   upload_max_filesize = 64M
   post_max_size = 64M
   max_execution_time = 300
   memory_limit = 256M
   ```

### 4. Настройка .env файла

Подключитесь по SSH и отредактируйте `.env`:

```bash
ssh username@fruck.kz
cd /var/www/vhosts/fruck.kz/httpdocs
nano .env
```

Обновите следующие параметры:

```env
APP_NAME="Silk Way"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://fruck.kz

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/vhosts/fruck.kz/httpdocs/database/database.sqlite

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

### 5. Установка SSL сертификата

1. **В Plesk перейдите в "SSL/TLS сертификаты"**
2. **Установите бесплатный сертификат Let's Encrypt**
   - Нажмите "Установить бесплатный базовый сертификат, предоставляемый Let's Encrypt"
   - Выберите домен
   - Нажмите "Получить сертификат"

## 🔧 Метод 2: Ручное развертывание через SSH

### 1. Подключитесь к серверу

```bash
ssh username@fruck.kz
```

### 2. Перейдите в директорию сайта

```bash
cd /var/www/vhosts/fruck.kz
```

### 3. Удалите старую директорию httpdocs (если есть)

```bash
# Сделайте резервную копию если нужно
mv httpdocs httpdocs.backup

# Клонируйте репозиторий
git clone https://github.com/baukeyzh/silk-way-app.git httpdocs
cd httpdocs
```

### 4. Настройте приложение

```bash
# Создайте .env файл
cp .env.example .env

# Отредактируйте .env
nano .env

# Сгенерируйте ключ приложения
php artisan key:generate

# Установите права доступа
chmod -R 775 storage bootstrap/cache
chown -R username:psacln storage bootstrap/cache

# Создайте базу данных
touch database/database.sqlite
chmod 664 database/database.sqlite

# Создайте символическую ссылку
php artisan storage:link

# Запустите миграции
php artisan migrate --force

# Запустите сидеры
php artisan db:seed --force

# Очистите и кэшируйте конфигурацию
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Настройте Document Root в Plesk

- Установите `/httpdocs/public` как корневую директорию

## 🔄 Обновление приложения

### Через Git в Plesk

1. Перейдите в раздел "Git"
2. Нажмите "Обновить из репозитория"
3. Скрипт после развертывания выполнится автоматически

### Через SSH

```bash
cd /var/www/vhosts/fruck.kz/httpdocs
git pull origin main

# Очистите кэш
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Запустите миграции (если есть новые)
php artisan migrate --force

# Кэшируйте конфигурацию
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ✅ Проверка установки

### 1. Проверьте доступность сайта

Откройте в браузере: `https://fruck.kz`

### 2. Проверьте права доступа

```bash
ls -la /var/www/vhosts/fruck.kz/httpdocs/storage
ls -la /var/www/vhosts/fruck.kz/httpdocs/bootstrap/cache
```

### 3. Проверьте логи

```bash
# Логи Laravel
tail -f /var/www/vhosts/fruck.kz/httpdocs/storage/logs/laravel.log

# Логи Apache/Nginx
tail -f /var/www/vhosts/system/fruck.kz/logs/error_log
```

## 🐛 Устранение проблем

### Ошибка 500 - Internal Server Error

1. **Проверьте права доступа**
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R username:psacln storage bootstrap/cache
   ```

2. **Проверьте APP_KEY в .env**
   ```bash
   php artisan key:generate --force
   ```

3. **Очистите кэш**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Ошибка базы данных

1. **Проверьте путь к БД в .env**
   ```env
   DB_DATABASE=/var/www/vhosts/fruck.kz/httpdocs/database/database.sqlite
   ```

2. **Проверьте права доступа к БД**
   ```bash
   chmod 664 database/database.sqlite
   chown username:psacln database/database.sqlite
   ```

3. **Пересоздайте БД**
   ```bash
   rm database/database.sqlite
   touch database/database.sqlite
   chmod 664 database/database.sqlite
   php artisan migrate:fresh --seed --force
   ```

### Не отображаются стили/скрипты

1. **Проверьте символическую ссылку**
   ```bash
   php artisan storage:link
   ```

2. **Проверьте APP_URL в .env**
   ```env
   APP_URL=https://fruck.kz
   ```

3. **Очистите кэш браузера**

### Ошибки при git pull

```bash
# Сбросьте локальные изменения
git reset --hard HEAD
git pull origin main
```

## 📞 Поддержка

При возникновении проблем:

1. Проверьте логи: `/var/www/vhosts/fruck.kz/httpdocs/storage/logs/laravel.log`
2. Проверьте права доступа к файлам и папкам
3. Убедитесь, что версия PHP 8.2 или выше
4. Проверьте настройки в `.env` файле

## 🎯 Важные замечания

1. **vendor и node_modules включены в репозиторий** - не нужно запускать `composer install` или `npm install`
2. **Ассеты уже собраны** - файлы в `public/build` готовы к использованию
3. **Используется SQLite** - не требуется MySQL сервер
4. **Document Root должен быть `/httpdocs/public`** - не `/httpdocs`!

## 🔐 Безопасность

После развертывания:

1. ✅ Установите SSL сертификат (Let's Encrypt)
2. ✅ Измените `APP_ENV=production` и `APP_DEBUG=false`
3. ✅ Установите сложный `APP_KEY`
4. ✅ Ограничьте доступ к `.env` файлу
5. ✅ Настройте регулярные бэкапы БД

---

**Готово!** Теперь ваше приложение развернуто и работает на Plesk! 🎉

