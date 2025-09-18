# Silk Way - Makefile для управления проектом

.PHONY: help install dev build test clean deploy logs shell

# Цвета для вывода
GREEN=\033[0;32m
YELLOW=\033[1;33m
RED=\033[0;31m
NC=\033[0m # No Color

help: ## Показать справку
	@echo "$(GREEN)Silk Way - Система управления грузоперевозками$(NC)"
	@echo ""
	@echo "$(YELLOW)Доступные команды:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(GREEN)%-15s$(NC) %s\n", $$1, $$2}'

install: ## Установить зависимости
	@echo "$(GREEN)Устанавливаем зависимости...$(NC)"
	composer install
	npm install

dev: ## Запустить в режиме разработки
	@echo "$(GREEN)Запускаем в режиме разработки...$(NC)"
	docker-compose -f docker-compose.dev.yml up -d
	@echo "$(YELLOW)Приложение доступно по адресу: http://localhost:8000$(NC)"
	@echo "$(YELLOW)MailHog доступен по адресу: http://localhost:8025$(NC)"

build: ## Собрать Docker образы
	@echo "$(GREEN)Собираем Docker образы...$(NC)"
	docker-compose build --no-cache

test: ## Запустить тесты
	@echo "$(GREEN)Запускаем тесты...$(NC)"
	php artisan test

test-coverage: ## Запустить тесты с покрытием
	@echo "$(GREEN)Запускаем тесты с покрытием...$(NC)"
	php artisan test --coverage

lint: ## Проверить код с помощью PHP CS Fixer
	@echo "$(GREEN)Проверяем код...$(NC)"
	composer require --dev friendsofphp/php-cs-fixer
	vendor/bin/php-cs-fixer fix --dry-run --diff

lint-fix: ## Исправить код с помощью PHP CS Fixer
	@echo "$(GREEN)Исправляем код...$(NC)"
	composer require --dev friendsofphp/php-cs-fixer
	vendor/bin/php-cs-fixer fix

deploy: ## Развернуть приложение
	@echo "$(GREEN)Разворачиваем приложение...$(NC)"
	./scripts/deploy.sh

logs: ## Показать логи
	@echo "$(GREEN)Показываем логи...$(NC)"
	docker-compose logs -f

shell: ## Войти в контейнер приложения
	@echo "$(GREEN)Входим в контейнер...$(NC)"
	docker-compose exec app bash

clean: ## Очистить контейнеры и образы
	@echo "$(GREEN)Очищаем контейнеры и образы...$(NC)"
	docker-compose down -v
	docker system prune -f

fresh: ## Пересоздать базу данных
	@echo "$(GREEN)Пересоздаем базу данных...$(NC)"
	docker-compose exec app php artisan migrate:fresh --seed

cache-clear: ## Очистить кэш
	@echo "$(GREEN)Очищаем кэш...$(NC)"
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan view:clear

assets: ## Собрать фронтенд ресурсы
	@echo "$(GREEN)Собираем фронтенд ресурсы...$(NC)"
	npm run build

assets-watch: ## Следить за изменениями фронтенда
	@echo "$(GREEN)Следим за изменениями фронтенда...$(NC)"
	npm run dev

status: ## Показать статус контейнеров
	@echo "$(GREEN)Статус контейнеров:$(NC)"
	docker-compose ps

backup: ## Создать бэкап базы данных
	@echo "$(GREEN)Создаем бэкап базы данных...$(NC)"
	mkdir -p backups
	cp database/database.sqlite backups/database-$(shell date +%Y%m%d-%H%M%S).sqlite
	@echo "$(YELLOW)Бэкап создан в папке backups/$(NC)"

restore: ## Восстановить базу данных из бэкапа
	@echo "$(GREEN)Восстанавливаем базу данных...$(NC)"
	@echo "$(YELLOW)Доступные бэкапы:$(NC)"
	@ls -la backups/ 2>/dev/null || echo "Нет бэкапов"
	@echo "$(YELLOW)Используйте: make restore-backup BACKUP=filename$(NC)"

restore-backup: ## Восстановить конкретный бэкап
	@if [ -z "$(BACKUP)" ]; then echo "$(RED)Укажите BACKUP=filename$(NC)"; exit 1; fi
	@echo "$(GREEN)Восстанавливаем бэкап $(BACKUP)...$(NC)"
	cp backups/$(BACKUP) database/database.sqlite
	@echo "$(YELLOW)Бэкап восстановлен$(NC)"

health: ## Проверить здоровье приложения
	@echo "$(GREEN)Проверяем здоровье приложения...$(NC)"
	@curl -f http://localhost:8000 > /dev/null 2>&1 && echo "$(GREEN)✅ Приложение работает$(NC)" || echo "$(RED)❌ Приложение недоступно$(NC)"

update: ## Обновить зависимости
	@echo "$(GREEN)Обновляем зависимости...$(NC)"
	composer update
	npm update

security: ## Проверить безопасность
	@echo "$(GREEN)Проверяем безопасность...$(NC)"
	composer audit

setup: ## Первоначальная настройка проекта
	@echo "$(GREEN)Настраиваем проект...$(NC)"
	@if [ ! -f .env ]; then cp .env.example .env; echo "$(YELLOW)Создан .env файл$(NC)"; fi
	@make install
	@make build
	@make fresh
	@echo "$(GREEN)✅ Проект настроен!$(NC)"
	@echo "$(YELLOW)Запустите: make dev$(NC)"
