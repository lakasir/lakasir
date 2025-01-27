FROM nginx:1.26.2-bookworm AS base

SHELL ["/bin/bash", "-c"]

ENV PATH="/apps:/apps/vendor/bin:$PATH" \ 
    COMPOSER_MODE=dev

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl wget ca-certificates gnupg2 \
    && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb https://packages.sury.org/php/ bookworm main" | tee /etc/apt/sources.list.d/php.list \
    && apt-get update && apt-get install -y --no-install-recommends \
        curl \
        tzdata \
        sysstat openssl make \
        zip unzip \
        vim \
        php8.2-fpm \
        php8.2-cli \
        php8.2-opcache \
        php8.2-mysqli \
        php8.2-curl \
        php8.2-soap \
        php8.2-xml \
        php8.2-fileinfo \
        php8.2-phar \
        php8.2-intl \
        php8.2-dom \
        php8.2-xmlreader \
        php8.2-ctype \
        php8.2-iconv \
        php8.2-tokenizer \
        php8.2-zip \
        php8.2-simplexml \
        php8.2-mbstring \
        php8.2-gd \
        php8.2-pdo \
        php8.2-xmlwriter \
        php8.2-sockets \
        php8.2-bcmath \
        php8.2-intl \
        php8.2-xml \
        php8.2-amqp \
        php8.2-apcu \
        php8.2-redis \
        php8.2-uploadprogress \
        php8.2 \
        supervisor \
    && ln -s /bin/ss /bin/netstat \
    && mkdir -p /run/php \
    && ln -s /usr/sbin/php-fpm8.2 /usr/sbin/php-fpm \
    && groupadd -g 1000 apps \
    && useradd -u 1000 -g 1000 apps \
    && apt-get remove --purge -y software-properties-common gnupg2 \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /etc/nginx/conf.d /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/composer-setup.php

WORKDIR /apps
COPY --chown=apps:apps . /apps
RUN --mount=type=cache,target=/root/.composer/ composer install --${COMPOSER_MODE}

WORKDIR /apps

# -----------------------------------------------------
# I have some issue when build vite using docker
# the result seem like build not correctly and give style issue
# build outside from container is working well without any problem

# build frontend
# FROM node:22.12.0 AS frontend
# COPY --from=base /apps /apps
# WORKDIR /apps
# RUN npm install; \
#     npm run build

# # setup apps
# FROM base

# COPY --chown=apps:apps --from=frontend /apps /apps
# -----------------------------------------------------

RUN rm -r infra

# setup nginx
COPY ./infra/nginx/nginx.conf /etc/nginx/
COPY ./infra/nginx/http.d /etc/nginx/http.d
COPY ./infra/nginx/conf.d /etc/nginx/conf.d

# setup php
COPY ./infra/php/php.ini /etc/php/8.2/cli/
COPY ./infra/php/php.ini /etc/php/8.2/fpm/
COPY ./infra/php/php-fpm.conf /etc/php/8.2/fpm/
COPY ./infra/php/www.conf /etc/php/8.2/fpm/pool.d/

# setup supervisord
COPY ./infra/supervisord.conf /etc/supervisord.conf

# setup storage
USER apps
RUN if [ ! -f /apps/public/storage ] && [ ! -d /apps/public/storage ]; then artisan storage:link; fi; \
    artisan filament:assets
USER root

CMD [ "supervisord", "-n", "-c", "/etc/supervisord.conf" ]
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 CMD [ "curl", "--fail", "localhost/healt" ]