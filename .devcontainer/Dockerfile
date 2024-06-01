FROM mcr.microsoft.com/devcontainers/php:0-8.1-bullseye

RUN apt-get update && export DEBIAN_FRONTEND=noninteractive && apt-get -y upgrade \
    && apt-get install -y mariadb-client libxml2-dev libxslt-dev dnsmasq libpng-dev  \
    gettext libfreetype-dev libjpeg62-turbo-dev locales msmtp msmtp-mta unzip \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install mysqli pdo pdo_mysql gettext soap xsl gd opcache

COPY ./apache-site.conf /etc/apache2/sites-enabled/000-default.conf
RUN sudo a2enmod rewrite proxy proxy_http proxy_balancer lbmethod_byrequests