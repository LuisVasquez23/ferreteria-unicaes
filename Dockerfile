# Utiliza la imagen oficial de PHP 8.1 con PHP-FPM
FROM php:8.1-fpm

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configura el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copia el código de tu aplicación Laravel al contenedor
COPY . /var/www/html

# Instala las dependencias de Laravel utilizando Composer
RUN composer install

# Inicia MySQL
CMD ["mysqld"]

# Configura MySQL
ENV MYSQL_ROOT_PASSWORD='admin'
ENV MYSQL_DATABASE=ferreteria_unicaes

# Exponer el puerto 9000 para PHP-FPM y el puerto 3306 para MySQL
EXPOSE 9000
EXPOSE 3306

# Inicia PHP-FPM
CMD ["php-fpm"]
