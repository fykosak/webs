#!/bin/bash
/usr/local/bin/docker-php-entrypoint apache2-foreground &

npm run "watch-$MODE"
