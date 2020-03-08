#!make

DOCKER_DATABASE=whatsup74-database
DOCKER_PHP=whatsup74-php-fpm
DOCKER_PHP_EXEC=docker-compose exec -T ${DOCKER_PHP}
DOCKER_DB_EXEC=docker-compose exec -T ${DOCKER_DATABASE}
DB_VOLUME=whatsup-php74-sf5_database

.DEFAULT_GOAL := help
.PHONY: help
help:
	@echo "\033[33mUsage:\033[0m\n  make [target] [arg=\"val\"...]\n\n\033[33mTargets:\033[0m"
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' Makefile| sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

# make tests

.PHONY: start
start: ## Start containers
	@echo "--> start containers ..."
	@docker-compose up -d

.PHONY: stop
stop: ## Stop containers
	@echo "--> stop containers ..."
	@docker-compose down

.PHONY: restart
restart: ## Restart containers
	@make stop
	@make start

.PHONY: volume-db-remove
volume-db-remove: ## Remove volume database
	@echo "--> remove volume ..."
	@docker volume rm ${DB_VOLUME}

.PHONY: reset
reset: ## Remove containers and volumes and start all
	@echo "--> reset containers ..."
	@make stop
	@make volume-db-remove
	@make start

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
fixtures-all: ## Create all fixtures and keep data
	@echo "--> Make fixtures ..."
	@${DOCKER_PHP_EXEC} bin/console doctrine:fixtures:load --append --no-interaction

.PHONY: fixtures-users
fixtures-users: ## Create users fixtures and keeping the data
	@echo "--> Make users fixtures ..."
	@${DOCKER_PHP_EXEC} bin/console doctrine:fixtures:load --append --no-interaction --group=users

.PHONY: fixtures-books
fixtures-books: ## Create books fixtures and keeping the data
	@echo "--> Make books fixtures ..."
	@${DOCKER_PHP_EXEC} bin/console doctrine:fixtures:load --append --no-interaction --group=books

.PHONY: tests
tests: ## Run tests
	@echo "--> Run tests ..."
	@${DOCKER_PHP_EXEC} bin/console doctrine:database:drop --env=test --force --if-exists
	@${DOCKER_PHP_EXEC} bin/console doctrine:database:create --env=test
	@${DOCKER_PHP_EXEC} bin/console doctrine:schema:create --env=test
	@${DOCKER_PHP_EXEC} bin/phpunit --testdox tests/

.PHONY: c-c
c-c: ## Clear cache
	@echo "--> Clear cache ..."
	@${DOCKER_PHP_EXEC} bin/console c:c