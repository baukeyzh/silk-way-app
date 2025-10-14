# MySQL и phpMyAdmin - Инструкция по использованию

## 🚀 Что было установлено

В проект добавлены следующие сервисы:
- **MySQL 8.0** - основная база данных
- **phpMyAdmin** - веб-интерфейс для управления базой данных

## 📋 Конфигурация

### MySQL
- **Порт:** 3306
- **База данных:** silk_way
- **Root пароль:** root_password
- **Пользователь:** silk_way_user
- **Пароль пользователя:** silk_way_password

### phpMyAdmin
- **URL:** http://localhost:8080
- **Логин (root):** root
- **Пароль (root):** root_password

## 🔧 Запуск

### Development окружение
```bash
docker-compose -f docker-compose.dev.yml up -d
```

### Production окружение
```bash
docker-compose up -d
```

## 🌐 Доступ к phpMyAdmin

После запуска контейнеров, откройте в браузере:
```
http://localhost:8080
```

Введите данные для входа:
- **Сервер:** mysql (оставьте пустым или укажите "mysql")
- **Пользователь:** root
- **Пароль:** root_password

## 🔄 Подключение Laravel к MySQL

Обновите файл `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=silk_way
DB_USERNAME=silk_way_user
DB_PASSWORD=silk_way_password
```

Или для root пользователя:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=silk_way
DB_USERNAME=root
DB_PASSWORD=root_password
```

## 📦 Импорт существующей базы данных

База данных из файла `database/full_mysql_dump.sql` будет автоматически импортирована при первом запуске MySQL контейнера.

Если нужно импортировать вручную:

### Через phpMyAdmin
1. Откройте http://localhost:8080
2. Войдите с учетными данными
3. Выберите базу данных `silk_way`
4. Перейдите на вкладку "Импорт"
5. Выберите файл `database/full_mysql_dump.sql`
6. Нажмите "Вперед"

### Через командную строку
```bash
docker exec -i silk-way-mysql-dev mysql -u root -proot_password silk_way < database/full_mysql_dump.sql
```

## 🔄 Миграции

После настройки подключения выполните миграции:

```bash
docker exec silk-way-app-dev php artisan migrate
```

Или с сидами:
```bash
docker exec silk-way-app-dev php artisan migrate:fresh --seed
```

## 📊 Управление контейнерами

### Просмотр логов MySQL
```bash
docker logs silk-way-mysql-dev
```

### Подключение к MySQL через терминал
```bash
docker exec -it silk-way-mysql-dev mysql -u root -proot_password
```

### Остановка сервисов
```bash
docker-compose -f docker-compose.dev.yml down
```

### Остановка с удалением volumes (ВНИМАНИЕ: удалит данные)
```bash
docker-compose -f docker-compose.dev.yml down -v
```

## 🔐 Смена паролей (рекомендуется для production)

Для production окружения измените пароли в `docker-compose.yml`:

```yaml
mysql:
  environment:
    MYSQL_ROOT_PASSWORD: ваш_безопасный_пароль
    MYSQL_PASSWORD: другой_безопасный_пароль
```

И соответственно в phpMyAdmin:
```yaml
phpmyadmin:
  environment:
    PMA_PASSWORD: ваш_безопасный_пароль
```

## 📝 Дополнительная информация

### Сброс базы данных
```bash
docker-compose -f docker-compose.dev.yml down -v
docker-compose -f docker-compose.dev.yml up -d
```

### Резервное копирование
```bash
docker exec silk-way-mysql-dev mysqldump -u root -proot_password silk_way > backup.sql
```

### Восстановление из резервной копии
```bash
docker exec -i silk-way-mysql-dev mysql -u root -proot_password silk_way < backup.sql
```

## ⚠️ Troubleshooting

### Порт 3306 занят
Если порт 3306 уже используется, измените в docker-compose файле:
```yaml
ports:
  - "3307:3306"  # используем порт 3307 на хосте
```

### Порт 8080 занят
Если порт 8080 уже используется, измените для phpMyAdmin:
```yaml
ports:
  - "8081:80"  # используем порт 8081 на хосте
```

### Ошибка подключения из Laravel
Убедитесь что:
1. MySQL контейнер запущен: `docker ps`
2. Используется правильный хост: `DB_HOST=mysql` (не localhost!)
3. Контейнер app зависит от mysql (уже настроено)


