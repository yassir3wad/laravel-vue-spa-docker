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
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Run Composer
COPY composer*.json ./

RUN if [[ "$MODE" = "production" ]];  \
    then composer install --no-dev --no-scripts --no-autoloader;  \
    else composer install --no-scripts --no-autoloader;  \
    fi


# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN chown -R www:www /var/www


# Copy existing application directory contents
COPY . .

RUN composer dump-autoload

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000