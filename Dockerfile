FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libmcrypt-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install Composer if not installed
RUN if [ ! -f "/usr/local/bin/composer" ]; then \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    fi

# Git ownership fix
RUN git config --global --add safe.directory /var/www/html

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/writable/

# Configure Apache document root
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-available/000-default.conf

# Run Composer Install
RUN composer install --no-dev --optimize-autoloader


# Expose port 80
EXPOSE 80
