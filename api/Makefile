build: docker-build fixtures

docker-build:
	docker compose up --build

composer-install:
	docker compose exec -e XDEBUG_MODE=off php composer install

fix:
	docker compose exec -e XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 php ./vendor/bin/php-cs-fixer fix --allow-risky yes

phpstan:
	docker compose exec -e XDEBUG_MODE=off php ./vendor/bin/phpstan analyze

fixtures:
	docker compose exec -e XDEBUG_MODE=off php ./bin/console doctrine:fixtures:load --no-interaction

test:
	docker compose exec -e SYMFONY_DEPRECATIONS_HELPER=disabled php bin/phpunit

paratest:
	docker compose exec -e SYMFONY_DEPRECATIONS_HELPER=disabled php vendor/bin/paratest --bootstrap=tests/paratest.php

reset-database:
	docker compose exec -e XDEBUG_MODE=off php ./bin/console doctrine:query:sql "SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = 'app' AND pid <> pg_backend_pid();" --quiet
	docker compose exec -e XDEBUG_MODE=off php ./bin/console doctrine:database:drop --if-exists --force --no-interaction
	docker compose exec -e XDEBUG_MODE=off php ./bin/console doctrine:database:create --no-interaction
	docker compose exec -e XDEBUG_MODE=off php ./bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec -e XDEBUG_MODE=off php ./bin/console doctrine:fixtures:load --no-interaction

migration:
	docker compose exec -e XDEBUG_MODE=off php ./bin/console doctrine:mig:diff