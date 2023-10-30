FROM php:8.1-apache

# install needed packages
RUN apt update && apt install -y \
	gettext \
	libxml2-dev \
	npm

# install apache EXTENSIONS
RUN EXTENSIONS="gettext soap" \
	&& docker-php-ext-install $EXTENSIONS \
	&& docker-php-ext-enable $EXTENSIONS

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf
