FROM php:8.1-apache

# install needed packages
RUN apt update && apt install -y \
	gettext \
	libfreetype-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	libxml2-dev \
	locales \
	msmtp \
	msmtp-mta \
	npm \
	unzip

# install locales for i18n
RUN echo 'cs_CZ.UTF-8 UTF-8' >> /etc/locale.gen && \
	echo 'en_US.UTF-8 UTF-8' >> /etc/locale.gen && \
	locale-gen

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install apache EXTENSIONS
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN EXTENSIONS="gettext soap opcache gd" \
	&& docker-php-ext-install $EXTENSIONS \
	&& docker-php-ext-enable $EXTENSIONS
RUN a2enmod rewrite proxy proxy_http

# install recommended config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# copy and execute entrypoint
COPY ./entrypoint.sh /
CMD /entrypoint.sh

EXPOSE 80

ENV MODE=prod
ENV NETTE_DEVEL=0
ENV npm_config_cache=/var/www/html/temp/npm
