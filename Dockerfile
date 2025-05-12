# Používáme oficiální PHP image s Apachem
FROM php:8.1-apache

# Nainstalujeme potřebné rozšíření pro MySQL/MariaDB (pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# Povolení Apache mod_rewrite
RUN a2enmod rewrite

# Nastavíme pracovní adresář na /var/www/html
WORKDIR /var/www/html
