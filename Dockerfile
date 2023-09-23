# Usa una imagen base de PHP 8.1
FROM php:8.1-fpm

# Establece el directorio de trabajo en la raíz de Laravel
WORKDIR /var/www/html

# Copia los archivos de tu proyecto Laravel al contenedor
COPY . .

# Instala las dependencias de Laravel y Composer
RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev zip unzip && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install

# Copia el archivo .env de ejemplo y configura la clave de aplicación
COPY .env.example .env
RUN php artisan key:generate

# Instala el servidor de MySQL
RUN apt-get install -y mariadb-server

# Configura la base de datos MySQL
ENV MYSQL_ROOT_PASSWORD=root_password

# Expone el puerto 3306 para MySQL
EXPOSE 3306

# Define el comando para ejecutar PHP-FPM
CMD ["php-fpm"]
