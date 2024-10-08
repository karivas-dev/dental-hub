# Base image: Official FrankenPHP 8.3 (Alpine) with Node.js 22.
# Note: Alpine uses musl libc instead of glibc, which may cause compatibility issues
# with certain PHP extensions and can impact the performance of some PHP applications.
FROM dunglas/frankenphp:php8.3-alpine

# Move the production php.ini file to the active configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install necessary PHP extensions
RUN install-php-extensions gd bz2 pcntl opcache mbstring exif bcmath curl \
    fileinfo intl gettext pgsql pdo_pgsql zip ldap redis

# Enable and configure opcache in the php.ini file
RUN echo "opcache.enable=1" >> $PHP_INI_DIR/php.ini && \
    echo "opcache.memory_consumption=128" >> $PHP_INI_DIR/php.ini && \
    echo "opcache.interned_strings_buffer=8" >> $PHP_INI_DIR/php.ini && \
    echo "opcache.max_accelerated_files=10000" >> $PHP_INI_DIR/php.ini && \
    echo "opcache.revalidate_freq=2" >> $PHP_INI_DIR/php.ini

# Copy Composer LTS binaries
COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

# Copy the application files to the image
COPY . /app

# Install composer and npm dependencies; build assets
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Link storage and optimize the application
RUN php artisan storage:link
RUN php artisan optimize
RUN php artisan filament:optimize

# Set the correct permissions for the storage and bootstrap/cache directories
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache
