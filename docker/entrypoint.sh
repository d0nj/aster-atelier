#!/bin/sh
set -e

# migrate on start; --graceful tolerates concurrent/restart races
php artisan migrate --force --graceful

exec "$@"