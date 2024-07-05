FROM dunglas/frankenphp

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    gd \
    intl \
    zip \
    opcache
# Add other PHP extensions here...

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

COPY . /app

RUN composer --no-interaction install
RUN cp .env.example .env
RUN php artisan key:generate

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
