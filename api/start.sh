#!/bin/sh


# To ensure the current Docker container environment is passed into the cron sub-processes
env >> /etc/environment
#
set -e
#
ROLE=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}
#
echo "Starting ...... $ROLE/$env"
#
#
#
if [ "$ROLE" = "app" ]; then
    exec php-fpm
elif [ "$ROLE" = "worker" ]; then
    echo "Running Cron....." && cron

    echo "Running queue workers..." && /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
else
    echo "Could not match the container ROLE \"$ROLE\""
    exit 1
fi
