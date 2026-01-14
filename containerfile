FROM docker.io/php:8.2-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY . /var/www/html/
EXPOSE 80
ENV DB_USER userphp
ENV DB_PASS 123456
ENV DB_NAME usuarios
ENV DB_HOST db
VOLUME /var/log/apache2