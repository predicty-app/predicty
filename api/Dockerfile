#syntax=docker/dockerfile:1.4
# Adapted from https://github.com/dunglas/symfony-docker
FROM php:8.2-fpm-alpine AS app_php_base

ENV COMPOSER_HOME=/.composer
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=mlocati/php-extension-installer --link /usr/bin/install-php-extensions /usr/local/bin/
WORKDIR /app

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;

RUN set -eux; \
    install-php-extensions \
    	intl \
    	xsl \
    	zip \
    	apcu \
		opcache \
        pdo_pgsql \
    ;

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --link docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link docker/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/
COPY --link docker/php/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN mkdir -p /.composer/cache
RUN chmod -R 777 $COMPOSER_HOME
COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer

COPY --link docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck
HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY --link docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# Prod image
FROM app_php_base AS app_php_prod

ENV APP_ENV=prod

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.* symfony.* ./
RUN set -eux; \
	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
	composer clear-cache

# copy sources
COPY --link . .
RUN rm -Rf docker/

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync

# Dev image
FROM app_php_base AS app_php_dev

ENV APP_ENV=dev XDEBUG_MODE=off
RUN rm $PHP_INI_DIR/conf.d/app.prod.ini; \
	mv "$PHP_INI_DIR/php.ini" "$PHP_INI_DIR/php.ini-production"; \
	mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY --link docker/php/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/
RUN rm -f .env.local.php
RUN set -eux; \
	install-php-extensions xdebug

# Build Caddy with the Mercure and Vulcain modules
FROM caddy:2-builder-alpine AS app_caddy_builder

RUN xcaddy build \
	--with github.com/dunglas/mercure/caddy \
	--with github.com/dunglas/vulcain/caddy

# Caddy image
FROM caddy:2-alpine AS app_caddy

WORKDIR /app
COPY --from=app_caddy_builder --link /usr/bin/caddy /usr/bin/caddy
COPY --from=app_php_prod --link /app/public public/
COPY --link docker/caddy/Caddyfile /etc/caddy/Caddyfile
