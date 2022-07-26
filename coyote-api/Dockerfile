# Use this docker container to build from
FROM php:7.0.28-apache

# install all the system dependencies and enable PHP modules
RUN apt-get update && apt-get install -y 
  libicu-dev 
  libpq-dev 
  libpng-dev 
  libmcrypt-dev 
  mysql-client 
  git 
  zip 
  unzip 
  && rm -r /var/lib/apt/lists/* 
  && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd 
  && docker-php-ext-install 
  intl 
  mbstring 
  mcrypt 
  pcntl 
  pdo_mysql 
  pdo_pgsql 
  pgsql 
  zip 
  gd 
  opcache

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# set our application folder as an environment variable
ENV APP_HOME /var/www/html

# change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# enable apache module rewrite
RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2enmod headers

# apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# copy source files and run composer
COPY . $APP_HOME

# install all PHP dependencies
RUN composer install --no-interaction

# change ownership of our applications
RUN chown -R www-data:www-data $APP_HOME