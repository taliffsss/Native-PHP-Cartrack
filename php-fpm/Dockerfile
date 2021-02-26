FROM php:7.3-fpm

# Install pdo_mysql
# install the PHP extensions we need
RUN docker-php-ext-install mysqli pdo_mysql

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

COPY ./App /var/www
COPY ./php-fpm/php.ini /usr/local/etc/php/php.ini
COPY ./php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9001

RUN chmod 775 /var/www/logs