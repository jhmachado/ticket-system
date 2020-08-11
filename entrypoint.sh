#!/bin/bash

set -e

composer install

if [ "${SHOULD_SKIP_MIGRATIONS}" ]; then
  echo "Skipping database migration"
else
  wait-for-it.sh mysql:3306 -t 600
  echo "MySQL connection established"
  php artisan migrate
fi

chown -R www-data:www-data /app/storage
chown -R www-data:www-data /app/bootstrap/cache

function start_service()
{
  case ${CONTAINER_ROLE} in
    queue)
      echo "Starting queue"
      php /app/artisan queue:work --verbose --tries=3 --timeout=90 --queue=high,medium,low
      ;;
    horizon)
      echo "Starting laravel horizon"
      php /app/artisan horizon
      ;;
    *)
      service inetutils-syslogd start
      apache2-foreground
      ;;
  esac
}

if [ "${SHOULD_SKIP_SERVICES}" ]; then
  echo "Skipping services start"
else
  start_service
fi
