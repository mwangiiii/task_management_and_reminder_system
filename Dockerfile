FROM php:8.2-cli

# Set timezone
ENV TZ=Africa/Nairobi
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    default-mysql-client \
    tzdata && \
    cp /usr/share/zoneinfo/Africa/Nairobi /etc/localtime && \
    echo "Africa/Nairobi" > /etc/timezone

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8000 8001 8002

CMD php artisan key:generate && \
    php artisan migrate --seed && \
    php artisan serve --host=0.0.0.0 --port=8000 & \
    php artisan serve --host=0.0.0.0 --port=8001 & \
    php artisan serve --host=0.0.0.0 --port=8002 & \
    npm run dev & \
    while true; do php artisan tasks:send-alerts; sleep 10; done
