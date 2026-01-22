# Dockerfile untuk Laravel di Railway
FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libsqlite3-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy package files for Node
COPY package.json package-lock.json* ./

# Install Node dependencies (use npm install if no lock file, npm ci if exists)
RUN if [ -f package-lock.json ]; then npm ci --production=false; else npm install; fi

# Copy the rest of the application
COPY . .

# Build frontend assets
RUN npm run build

# Run composer scripts after copying all files
RUN composer dump-autoload --optimize

# Create necessary directories
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache database

# Create SQLite database file
RUN touch database/database.sqlite

# Set proper permissions
RUN chmod -R 775 storage bootstrap/cache database
RUN chmod +x railway-start.sh

# Expose port
EXPOSE ${PORT:-8080}

# Start command using startup script
CMD ["./railway-start.sh"]
