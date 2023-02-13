fix:
	docker compose exec -e PHP_CS_FIXER_IGNORE_ENV=1 php ./vendor/bin/php-cs-fixer fix --allow-risky yes

phpstan:
	docker compose exec php ./vendor/bin/phpstan analyze
