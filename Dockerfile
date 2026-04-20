FROM webdevops/php-nginx:8.2

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install JS dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_DISPLAY_ERRORS=0

EXPOSE ${PORT:-8080}

CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan db:seed --class=AdminUserSeeder --force && \
    php artisan storage:link && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
