FROM php:8.2-fpm-alpine

ARG WWWUSER=1000
ARG WWWGROUP=1000
ARG SKIP_OPTIMIZE=false

RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    nodejs \
    npm \
    mysql-client \
    redis

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    mysqli \
    mbstring \
    xml \
    zip \
    bcmath \
    intl \
    opcache

RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .phpize-deps

RUN set -eux; \
    EXPECTED_SIGNATURE="$(curl -fsSL https://composer.github.io/installer.sig)"; \
    curl -fsSL -o /tmp/composer-setup.php https://getcomposer.org/installer; \
    ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"; \
    [ "$EXPECTED_SIGNATURE" = "$ACTUAL_SIGNATURE" ]; \
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer; \
    rm -f /tmp/composer-setup.php

WORKDIR /var/www/html

RUN addgroup -g ${WWWGROUP} -S wwwgroup \
    && adduser -u ${WWWUSER} -S wwwuser -G wwwgroup \
    && sed -i 's/^user = .*/user = wwwuser/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^group = .*/group = wwwgroup/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^listen.owner = .*/listen.owner = wwwuser/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^listen.group = .*/listen.group = wwwgroup/' /usr/local/etc/php-fpm.d/www.conf

COPY . .

RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Cache Blade views so Tailwind can scan compiled views for classes.
# Set --build-arg SKIP_OPTIMIZE=true to skip these steps (e.g. when APP_KEY is not available at build time).
RUN if [ "$SKIP_OPTIMIZE" = "false" ]; then \
    php artisan view:cache && \
    php artisan filament:cache-components && \
    php artisan icons:cache; \
fi

# Install npm deps and build
# NOTE: Run build TWICE because Tailwind needs compiled views (from view:cache above)
# to generate complete CSS. The first build compiles assets that Blade references,
# the second build ensures Tailwind picks up all classes from compiled views.
RUN npm install && npm run build && npm run build

RUN chown -R wwwuser:wwwgroup /var/www/html

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir -p /var/log/supervisor \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R wwwuser:wwwgroup /var/www/html/storage \
    && chown -R wwwuser:wwwgroup /var/www/html/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]