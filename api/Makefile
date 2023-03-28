DOCKER_COMPOSE=docker compose
DOCKER_COMPOSE_EXEC=$(DOCKER_COMPOSE) exec
PHP_EXEC=docker compose exec -e XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 -e SYMFONY_DEPRECATIONS_HELPER=disabled php
SYMFONY_CONSOLE_EXEC=$(PHP_EXEC) ./bin/console

default: help

help: # Show help for each of the Makefile recipes.
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done

build: docker-down docker-build start # Build or rebuild whole environment (this will DESTROY your database)

stop: # Stop all containers
	$(DOCKER_COMPOSE) stop

start: # Start all containers
	$(DOCKER_COMPOSE) up -d

restart: stop start # Restart all containers

docker-down:
	$(DOCKER_COMPOSE) down --remove-orphans

docker-build:
	$(DOCKER_COMPOSE) up -d --build --remove-orphans

composer-install:
	$(PHP_EXEC) composer install

fix:
	@$(PHP_EXEC) ./vendor/bin/php-cs-fixer fix --allow-risky yes
	@$(PHP_EXEC) ./vendor/bin/phpcbf

phpstan:
	@$(PHP_EXEC) ./vendor/bin/phpstan analyze --memory-limit=1G

fixtures:
	@$(PHP_EXEC) ./bin/console doctrine:fixtures:load --no-interaction

test: reset-test-database # Run tests
	@$(PHP_EXEC) bin/phpunit

paratest:
	$(PHP_EXEC) vendor/bin/paratest --bootstrap=tests/paratest.php

run-worker: # Run worker that will consume async messages
	@$(SYMFONY_CONSOLE_EXEC) --env=dev messenger:consume async -vv

reset-database:
	@$(SYMFONY_CONSOLE_EXEC) --env=dev doctrine:query:sql "SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = 'app' AND pid <> pg_backend_pid();" --quiet
	@$(SYMFONY_CONSOLE_EXEC) --env=dev doctrine:database:drop --if-exists --force --no-interaction
	@$(SYMFONY_CONSOLE_EXEC) --env=dev doctrine:database:create --no-interaction
	@$(SYMFONY_CONSOLE_EXEC) --env=dev doctrine:migrations:migrate --no-interaction
	@$(SYMFONY_CONSOLE_EXEC) --env=dev doctrine:fixtures:load --no-interaction -q
	@echo "Database was restored to its initial state"

reset-test-database:
	@$(SYMFONY_CONSOLE_EXEC) --env=test doctrine:query:sql "SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = 'app' AND pid <> pg_backend_pid();" --quiet
	@$(SYMFONY_CONSOLE_EXEC) --env=test doctrine:database:drop --if-exists --force --no-interaction -q
	@$(SYMFONY_CONSOLE_EXEC) --env=test doctrine:database:create --no-interaction -q
	@$(SYMFONY_CONSOLE_EXEC) --env=test doctrine:migrations:migrate --no-interaction -q
	@$(SYMFONY_CONSOLE_EXEC) --env=test doctrine:fixtures:load --no-interaction -q
	@echo "Database was restored to its initial state"

migration:
	$(SYMFONY_CONSOLE_EXEC) doctrine:mig:diff --no-interaction

migrate: do-migration
do-migration:
	$(SYMFONY_CONSOLE_EXEC) doctrine:mig:mig --no-interaction