FROM php:7.4-fpm-alpine
WORKDIR /var/www/html
RUN apk update && apk add nginx bash libzip-dev libpng-dev
RUN docker-php-ext-install zip && docker-php-ext-install gd && docker-php-ext-install pdo_mysql

RUN rm /etc/nginx/nginx.conf
COPY ./conf/nginx/linux/nginx.conf /etc/nginx/
COPY ./src/ /var/www/html

CMD ["/bin/bash", "-c", "php-fpm -D && nginx -g 'daemon off;'"]