FROM php:8.3-fpm-alpine

# pdo_sqlite/sqlite3 are compiled into the official image; unzip for composer dist installs
RUN apk add --no-cache unzip \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && sed -i \
        -e 's/^pm = dynamic/pm = ondemand/' \
        -e 's/^pm.max_children = 5/pm.max_children = 10/' \
        -e 's/^;pm.max_requests = 500/pm.max_requests = 500/' \
        /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist

COPY . .
RUN composer dump-autoload --optimize

COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 9000
ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]