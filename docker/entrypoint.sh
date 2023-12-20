#!/bin/bash

CONFIG=/var/www/html/app/config
LOCAL=$CONFIG/local

if [ ! -d $LOCAL ]; then
    mkdir $LOCAL
fi

WEBS="dsef fof fol fykos vyfuk"
for web in $WEBS; do
    if [ ! -f $LOCAL/$web.neon ]; then
        cp "$CONFIG/config.local.neon.example" "$LOCAL/$web.neon"
    fi
done

/usr/local/bin/docker-php-entrypoint apache2-foreground
