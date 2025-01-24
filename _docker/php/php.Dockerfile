FROM php:8.2-fpm

# Устанавливаем зависимости и PHP расширения
RUN apt-get update && apt-get install -y \
      supervisor \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install mysqli && \
      docker-php-ext-install bcmath && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Копируем настройки php
COPY ./_docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# Копируем код проекта
COPY ./ /var/www/test_catalog

# Устанавливаем Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

RUN curl -sSL https://github.com/vishnubob/wait-for-it/releases/download/v2.4.2/wait-for-it.sh -o /usr/local/bin/wait-for-it && \
    chmod +x /usr/local/bin/wait-for-it

# Устанавливаем зависимости Laravel
WORKDIR /var/www/test_catalog
RUN composer install --no-dev --optimize-autoloader

# Разрешаем запись в storage и bootstrap/cache
RUN chown -R www-data:www-data /var/www/test_catalog/storage /var/www/test_catalog/bootstrap/cache

# Копируем entrypoint.sh
COPY _docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY _docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Устанавливаем entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
