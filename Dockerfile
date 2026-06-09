FROM php:8.2-cli

# System dependencies + ext-gd
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libxml2-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring xml zip bcmath opcache exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Node.js 20 (para compilar Vite/Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# PHP deps primero (capa cacheada)
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-scripts --no-interaction --no-dev

# Node deps y build de assets
COPY package.json ./
RUN npm install

COPY . .

RUN php artisan package:discover --ansi || true
RUN npm run build && rm -rf node_modules

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
