#!make

DOCKER_DATABASE=whatsup74-database
DOCKER_PHP=whatsup74-php-fpm

DOCKER_PHP_EXEC=docker-compose exec -T ${DOCKER_PHP}
DOCKER_DB_EXEC=docker-compose exec -T ${DOCKER_DATABASE}

.DEFAULT_GOAL := help
.PHONY: help
help:
	@echo "\033[33mUsage:\033[0m\n  make [target] [arg=\"val\"...]\n\n\033[33mTargets:\033[0m"
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' Makefile| sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

# make tests

.PHONY: up
up: ## Start containers
	@echo "--> start containers ..."
	@docker-compose up -d

.PHONY: down
down: ## Stop containers
	@echo "--> stop containers ..."
	@docker-compose down

.PHONY: restart
restart: ## Restart containers
	@make down
	@make up

.PHONY: logs-database
logs-database: ## Logs database
	@docker-compose logs ${DOCKER_DATABASE}

.PHONY: ps
ps: ## List containers
	@docker ps -a

.PHONY: db
db: ## Connect to database
	@docker exec -it ${DOCKER_DATABASE} mysql -u root

.PHONY: php
php: ## Connect to php
	@docker exec -it ${DOCKER_PHP} sh

.PHONT: migration
migration: ## Migrate schema
	@${DOCKER_PHP_EXEC} bin/console doctrine:migration:migrate --no-interaction

.PHONY: fixtures-all
fixtures: ## Create all fixtures while keeping the data
	@echo "--> Make fixtures ..."
	@${DOCKER_PHP_EXEC} bin/console doctrine:fixtures:load --append --no-interaction

.PHONY: tests
tests: ## Run tests
	@echo "--> Run tests ..."
	@${DOCKER_PHP_EXEC} bin/phpunit --testdox tests/