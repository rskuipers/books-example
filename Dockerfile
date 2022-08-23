FROM php:8.1-fpm-alpine AS php-fpm

RUN set -eux; \
    apk add --no-cache icu mysql-client acl make; \
    apk add --no-cache --virtual .phpize $PHPIZE_DEPS icu-dev; \
    docker-php-ext-install pdo_mysql intl; \
    apk del .phpize

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY dev/docker/php/development.ini $PHP_INI_DIR/conf.d/

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./

RUN set -eux; \
    composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
    composer clear-cache

COPY config config/
COPY bin bin/
COPY public public/
COPY src src/
COPY templates templates/
COPY .env ./

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    chmod +x bin/console; sync

COPY dev/docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

#------------------------------------

FROM php-fpm AS php-fpm-test

RUN set -eux; \
    composer install --prefer-dist --no-scripts --no-progress --no-suggest; \
    composer clear-cache

#------------------------------------

FROM php-fpm-test AS php-fpm-dev

RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install xdebug; \
    apk del --no-network .build-deps; \
    docker-php-ext-enable xdebug

COPY dev/docker/php/xdebug.ini $PHP_INI_DIR/conf.d/

#------------------------------------

FROM nginx:1.17-alpine AS nginx

COPY dev/docker/nginx/development.conf /etc/nginx/conf.d/default.conf

COPY --from=php-fpm /app/public /app/public
