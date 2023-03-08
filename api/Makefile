DOCKER_COMPOSE=docker compose
DOCKER_COMPOSE_EXEC=$(DOCKER_COMPOSE) exec
PHP_EXEC=docker compose exec -e XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 -e SYMFONY_DEPRECATIONS_HELPER=disabled php
SYMFONY_CONSOLE_EXEC=$(PHP_EXEC) ./bin/console

build: docker-build fixtures

stop:
	$(DOCKER_COMPOSE) stop

start:
	$(DOCKER_COMPOSE) up -d

restart: stop start

docker-build:
	$(DOCKER_COMPOSE) up --build --remove-orphans

composer-install:
	$(PHP_EXEC) composer install

fix:
	$(PHP_EXEC) ./vendor/bin/php-cs-fixer fix --allow-risky yes

phpstan:
	$(PHP_EXEC) ./vendor/bin/phpstan analyze

fixtures:
	$(PHP_EXEC) ./bin/console doctrine:fixtures:load --no-interaction

test:
	$(PHP_EXEC) bin/phpunit

paratest:
	$(PHP_EXEC) vendor/bin/paratest --bootstrap=tests/paratest.php

reset-database:
	$(SYMFONY_CONSOLE_EXEC) doctrine:query:sql "SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = 'app' AND pid <> pg_backend_pid();" --quiet
	$(SYMFONY_CONSOLE_EXEC) doctrine:database:drop --if-exists --force --no-interaction
	$(SYMFONY_CONSOLE_EXEC) doctrine:database:create --no-interaction
	$(SYMFONY_CONSOLE_EXEC) doctrine:migrations:migrate --no-interaction
	$(SYMFONY_CONSOLE_EXEC) doctrine:fixtures:load --no-interaction

migration:
	$(SYMFONY_CONSOLE_EXEC) doctrine:mig:diff --no-interaction