# 🚀 Быстрый старт MySQL + phpMyAdmin

## 1️⃣ Запустить контейнеры

```bash
docker-compose -f docker-compose.dev.yml up -d
```

## 2️⃣ Открыть phpMyAdmin

Откройте в браузере: **http://localhost:8080**

**Данные для входа:**
- Пользователь: `root`
- Пароль: `root_password`

## 3️⃣ Настроить Laravel для MySQL

Создайте файл `.env` (если его нет) и добавьте:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=silk_way
DB_USERNAME=silk_way_user
DB_PASSWORD=silk_way_password
```

## 4️⃣ Запустить миграции

```bash
docker exec silk-way-app-dev php artisan migrate
```

Или с сидами:
```bash
docker exec silk-way-app-dev php artisan migrate:fresh --seed
```

## 📊 Доступные сервисы

- **Приложение:** http://localhost:8000
- **phpMyAdmin:** http://localhost:8080
- **MailHog:** http://localhost:8025
- **MySQL:** localhost:3306

## 🛑 Остановить контейнеры

```bash
docker-compose -f docker-compose.dev.yml down
```

## 📝 Учетные данные MySQL

| Параметр | Значение |
|----------|----------|
| База данных | `silk_way` |
| Root пароль | `root_password` |
| Пользователь | `silk_way_user` |
| Пароль | `silk_way_password` |
| Хост (для Laravel) | `mysql` |
| Порт | `3306` |

---

Подробная инструкция: см. файл `MYSQL_SETUP.md`


