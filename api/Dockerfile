FROM php:8.1.16-fpm

ARG MODE=development
ENV APP_ENV=$MODE

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
    libonig-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY composer*.json ./

RUN if [[ "$MODE" = "production" ]];  \
    then composer install --no-dev --no-scripts --no-autoloader --ansi --no-interaction;  \
    else composer install --no-scripts --no-autoloader --ansi --no-interaction;  \
    fi


COPY . .

RUN composer dump-autoload -o \
    && chown -R :www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000