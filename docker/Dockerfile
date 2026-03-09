FROM php:8.3-fpm

# Install nginx + dependencies + nodejs
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    unzip \
    zip \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
    && rm -rf /var/lib/apt/lists/*

# Copy composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project files
COPY . /var/www

# Install dependencies during build (BEST PRACTICE)
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Fix permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 80

CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]