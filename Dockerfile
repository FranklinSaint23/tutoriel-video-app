FROM php:8.3-cli-alpine

# 1. Installation des extensions minimales pour Laravel
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

# 4. Forcer les permissions à fond pour éviter tout blocage de cache
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080

# 5. Démarrage sécurisé en nettoyant les résidus de cache à la volée
CMD php artisan config:clear && php artisan route:clear && php artisan serve --host=0.0.0.0 --port=8080