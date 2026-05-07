#!/bin/sh
set -e

# Ensure storage and cache directories exist with correct permissions
chown -R wwwuser:wwwgroup /var/www/html/storage
chown -R wwwuser:wwwgroup /var/www/html/bootstrap/cache

# Cache Blade views before starting the app
# This ensures Tailwind CSS classes from compiled views are available
# (important after container restart when storage may be empty)
if ! php artisan view:cache; then
    echo "WARNING: 'php artisan view:cache' failed during container startup; continuing without cached views." >&2
fi

# Start supervisor (which starts php-fpm + nginx)
exec "$@"