# Utiliza una imagen base de PHP
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

# Configura la base de datos MySQL (puede variar según tu entorno)
ENV DB_CONNECTION=mysql
ENV DB_HOST=mysql
ENV DB_PORT=3306
ENV DB_DATABASE=ferreteria-unicaes
ENV DB_USERNAME=root
ENV DB_PASSWORD=

# Expone el puerto 9000 para PHP-FPM (ajusta según sea necesario)
EXPOSE 9000

# Define el comando para ejecutar PHP-FPM
CMD ["php-fpm"]
