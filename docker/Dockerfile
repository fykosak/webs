FROM php:8.1-apache

# install needed packages
RUN apt update && apt install -y \
	gettext \
	libxml2-dev \
	locales \
	npm \
	unzip

# install locales for i18n
RUN echo 'cs_CZ.UTF-8 UTF-8' >> /etc/locale.gen && \
	echo 'en_US.UTF-8 UTF-8' >> /etc/locale.gen && \
	locale-gen

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install apache EXTENSIONS
RUN EXTENSIONS="gettext soap opcache" \
	&& docker-php-ext-install $EXTENSIONS \
	&& docker-php-ext-enable $EXTENSIONS
RUN a2enmod rewrite proxy proxy_http 

ENV MODE=prod
ENV NETTE_DEVEL=0

COPY ./entrypoint.sh /
CMD /entrypoint.sh
