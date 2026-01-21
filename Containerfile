FROM docker.io/php:8.2-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY frontend/ /var/www/html/
COPY database/ /var/www/database/
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
ENV DB_USER=
ENV DB_PASS=
ENV DB_NAME=
ENV DB_HOST=
VOLUME /var/log/apache2
