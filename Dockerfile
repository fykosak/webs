# Compile Javascript and CSS
FROM node:latest AS node-builder
WORKDIR /usr/src/webs
COPY . .
RUN npm install && npm run build

# Install PHP dependencies and init translations
FROM composer as composer-builder
WORKDIR /usr/src/webs
COPY --from=node-builder /usr/src/webs /usr/src/webs
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --ignore-platform-reqs

# Prepare files for the final image
RUN mkdir /usr/src/final
RUN cp -r /usr/src/webs/app /usr/src/webs/www /usr/src/webs/vendor /usr/src/final/

# Final image
FROM php:8.2-apache
RUN apt-get update && apt install -y \
    gettext \
    locales \
&& docker-php-ext-configure gettext \
&& docker-php-ext-install \
    gettext \
&& a2enmod rewrite \
&& sed -i -e 's/# cs_CZ.UTF-8/cs_CZ.UTF-8/' /etc/locale.gen \
&& sed -i -e 's/# en_US.UTF-8/en_US.UTF-8/' /etc/locale.gen \
&& dpkg-reconfigure --frontend=noninteractive locales

USER www-data
WORKDIR /var/www/webs
COPY --chown=www-data --from=composer-builder /usr/src/final /var/www/webs/
