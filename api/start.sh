#!/usr/bin/env bash
set -e

ROLE=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

echo "Starting ...... $ROLE/$env"

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
#    (cd /var/www && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi

if [ "$ROLE" = "app" ]; then
    exec php-fpm
elif [ "$ROLE" = "worker" ]; then
    echo "Running Cron....."
    crontab /etc/cron.d/my-cron && cron

    echo "Running queue workers..."
    /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
else
    echo "Could not match the container ROLE \"$ROLE\""
    exit 1
fi