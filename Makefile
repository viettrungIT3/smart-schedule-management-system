
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

# --- Git/GitHub automation ---
PR_TITLE?=auto: update
PR_BODY?=Auto-generated PR
PR_BASE?=develop
PR_HEAD?=

pr:
	@if [ -z "$(PR_HEAD)" ]; then echo "Set PR_HEAD=<branch>"; exit 1; fi
	gh pr create --base $(PR_BASE) --head $(PR_HEAD) --title "$(PR_TITLE)" --body "$(PR_BODY)"

pr-label:
	@if [ -z "$(PR)" ]; then echo "Set PR=<number>"; exit 1; fi
	gh pr edit $(PR) --add-label feature,db || true

pr-reviewer:
	@if [ -z "$(PR)" ]; then echo "Set PR=<number>"; exit 1; fi
	# sửa reviewer theo team của bạn
	gh pr edit $(PR) --add-reviewer viettrungIT3 || true

pr-open:
	@if [ -z "$(PR)" ]; then echo "Set PR=<number>"; exit 1; fi
	gh pr view $(PR) --web

pr-automerge:
	@if [ -z "$(PR)" ]; then echo "Set PR=<number>"; exit 1; fi
	gh pr edit $(PR) --add-label automerge

# Start feature/hotfix branches quickly
N?=00
NAME?=task-name

feature-start:
	git checkout develop && git pull --ff-only && git checkout -b $(N)/feature/$(NAME)

hotfix-start:
	git checkout master && git pull --ff-only && git checkout -b $(N)/hotfix/$(NAME)

feature-pr:
	$(MAKE) pr PR_BASE=develop PR_HEAD=$(shell git rev-parse --abbrev-ref HEAD) PR_TITLE="feat: $(NAME)" PR_BODY="Auto PR for feature $(NAME)"

hotfix-pr:
	$(MAKE) pr PR_BASE=master PR_HEAD=$(shell git rev-parse --abbrev-ref HEAD) PR_TITLE="hotfix: $(NAME)" PR_BODY="Auto PR for hotfix $(NAME)"


