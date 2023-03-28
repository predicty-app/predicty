DOCKER_COMPOSE=docker compose
DOCKER_COMPOSE_EXEC=$(DOCKER_COMPOSE) exec
PHP_EXEC=$(DOCKER_COMPOSE_EXEC) -e XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 -e SYMFONY_DEPRECATIONS_HELPER=disabled php
PHP_EXEC_DEV=$(DOCKER_COMPOSE_EXEC) -e XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 -e SYMFONY_DEPRECATIONS_HELPER=disabled -e APP_ENV=dev php
SYMFONY_CONSOLE_EXEC=$(PHP_EXEC) ./bin/console

default: help

help: # Show help for each of the Makefile recipes.
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done

build: docker-down docker-build start # Build or rebuild whole environment (this will DESTROY everything, also your database)

stop: # Stop all containers
	$(DOCKER_COMPOSE) stop

start: # Start all containers
	$(DOCKER_COMPOSE) up -d

restart: stop start # Restart all containers

fixtures:
	@$(PHP_EXEC_DEV) ./bin/console doctrine:fixtures:load --no-interaction

docker-down:
	$(DOCKER_COMPOSE) down --remove-orphans -v

docker-build:
	$(DOCKER_COMPOSE) up -d --build --remove-orphans