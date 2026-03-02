USER_ID=$(shell id -u)
DC = @USER_ID=$(USER_ID) docker compose
DC_RUN = ${DC} run --rm sio_test
DC_EXEC = ${DC} exec sio_test

.PHONY: help
.DEFAULT_GOAL := help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\\033[36m%-30s\\033[0m %s\\n", $$1, $$2}' $(MAKEFILE_LIST)

init: down-hard build install up db-init success-message console ## 🥳 Полная инициализация проекта

build: ## Build services.
	${DC} build $(c)

up: ## Create and start services.
	${DC} up -d $(c)

down: ## Stop and remove containers.
	${DC} down $(c)

down-hard: ## Stop and remove containers and volumes.
	${DC} down -v $(c)

db-init: ## Create db and run migrations.
	${DC_RUN} sh -c "\
		php bin/console doctrine:database:create --no-interaction || true && \
		php bin/console doctrine:migrations:migrate --no-interaction \
	"

db-seed: ## Run migrations.
	${DC_RUN} php bin/console doctrine:migrations:migrate

install: ## Install dependencies without running the whole application.
	${DC_RUN} composer install

console: ## Login in console.
	${DC_EXEC} /bin/bash

test: ## Run tests.
	${DC_EXEC} php bin/phpunit

success-message:
	@echo "You can now access the application at http://localhost:8337"
	@echo "Good luck! 🚀"

db-status: ## db status.
	${DC_EXEC} php bin/console doctrine:migrations:status
	@echo ""
	docker compose exec database psql -U app -d app -c "SELECT COUNT(*) FROM product;"
	docker compose exec database psql -U app -d app -c "SELECT COUNT(*) FROM coupon;"

