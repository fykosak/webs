FROM ghcr.io/fykosak/webs:dev-docker

# install xdebug for local development
RUN pecl install xdebug && docker-php-ext-enable xdebug

# install config for local dev
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

ENV MODE=dev
ENV NETTE_DEVEL=1
