FROM php:7.4-apache

# Instala las extensiones necesarias para PDO y MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Cambia el DocumentRoot de Apache a /var/www/html/public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri 's!<Directory /var/www/html>!<Directory /var/www/html/public>!' /etc/apache2/apache2.conf

# Habilita el módulo rewrite de Apache
RUN a2enmod rewrite

# Copia el código de la aplicación al contenedor
COPY . /var/www/html/

# Cambia los permisos para que Apache pueda acceder correctamente
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80

