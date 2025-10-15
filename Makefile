
# ---- Configuration ----
PROJECT=ssms
DOCKER_COMPOSE=docker compose
APP_SERVICE=app
DB_SERVICE=db
COMPOSE_FILE ?= docker-compose.yml
ENV_FILE     ?= .env
include .env
export

# ---- Helpers ----
DC = docker compose --env-file $(ENV_FILE) -f $(COMPOSE_FILE)

# 1) Khởi tạo dự án: build, up, cấu hình .env trong src, generate key
init:
	$(DOCKER_COMPOSE) build
	$(DOCKER_COMPOSE) up -d
	# Thiết lập .env cho dự án CI4 sẵn có (thư mục src được mount vào /var/www/html)
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && if [ ! -f .env ] && [ -f env ]; then cp env .env; fi"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && sed -i 's/^# app\\.baseURL.*/app.baseURL = \"http:\/\/localhost:8088\"/' .env || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && sed -i 's/^app\\.baseURL.*/app.baseURL = \"http:\/\/localhost:8088\"/' .env || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && sed -i 's/^database\\.default\\.hostname.*/database.default.hostname = db/' .env || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && sed -i 's/^database\\.default\\.database.*/database.default.database = scheduleflow/' .env || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && sed -i 's/^database\\.default\\.username.*/database.default.username = ssms/' .env || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && sed -i 's/^database\\.default\\.password.*/database.default.password = ssms_pass/' .env || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && sed -i 's/^database\\.default\\.DBDriver.*/database.default.DBDriver = MySQLi/' .env || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && php spark key:generate || true"
	@$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) bash -lc "cd /var/www/html && [ -d writable ] && chown -R www-data:www-data writable || true"

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

restart:
	$(DOCKER_COMPOSE) down
	$(DOCKER_COMPOSE) up -d

cli:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) bash

migrate:
	$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) php spark migrate

migrate-status:
	$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) php spark migrate:status

seed:
	$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) php spark db:seed DevSeeder

test:
	$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) ./vendor/bin/phpunit

logs:
	$(DOCKER_COMPOSE) logs -f $(APP_SERVICE) $(DB_SERVICE)

composer-install:
	$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) composer install

composer-update:
	$(DOCKER_COMPOSE) exec -T $(APP_SERVICE) composer update

clean:
	$(DOCKER_COMPOSE) down -v --remove-orphans


