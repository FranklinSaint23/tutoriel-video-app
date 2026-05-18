FROM php:8.3-cli-alpine

# 1. Installation des extensions minimales pour Laravel (ultra rapide sur Alpine)
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# 2. Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

# 3. Build de l'application (Composer + Vite)
RUN composer install --no-dev --optimize-autoloader \
    && if [ -f package.json ]; then npm install && npm run build; fi

# 4. Permissions pour Laravel
RUN chown -R rw-data:rw-data /app/storage /app/bootstrap/cache 2>/dev/null || true

EXPOSE 8080

# 5. Commande de démarrage direct sans Apache
CMD php artisan serve --host=0.0.0.0 --port=8080