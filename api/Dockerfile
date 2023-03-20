FROM php:8.1.16-fpm

ARG MODE=development
ENV APP_ENV=$MODE
ARG USER=www-data
ARG GROUP=www-data

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    supervisor \
    cron

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
# setup redis
RUN pecl install redis && docker-php-ext-enable redis

# set php variables
COPY local.ini /usr/local/etc/php/conf.d/local.ini

# set crontab
COPY ./crontab /etc/cron.d/cronfile
RUN touch /var/log/cron.log
RUN chmod 0644 /etc/cron.d/cronfile && \
    crontab /etc/cron.d/cronfile


# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY composer*.json ./

RUN if [[ "$MODE" = "production" ]];  \
    then composer install --no-dev --no-scripts --no-autoloader --ansi --no-interaction;  \
    else composer install --no-scripts --no-autoloader --ansi --no-interaction;  \
    fi


COPY . .

RUN composer dump-autoload -o \
    && chown -R $USER:$GROUP /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 777 /var/www/storage

COPY start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["/usr/local/bin/start"]