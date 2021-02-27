FROM php:7.3-fpm

RUN apt-get update && apt-get install -y libpq-dev
# Install pdo_mysql
# install the PHP extensions we need
RUN docker-php-ext-install pdo pdo_pgsql pgsql

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

COPY ./App /var/www
COPY ./php-fpm/php.ini /usr/local/etc/php/php.ini
COPY ./php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN sed -i -e 's/;extension=pgsql/extension=pgsql/' /usr/local/etc/php/php.ini
RUN sed -i -e 's/;extension=pdo_pgsql/extension=pdo_pgsql/' /usr/local/etc/php/php.ini

EXPOSE 9001

RUN chmod 775 /var/www/logs