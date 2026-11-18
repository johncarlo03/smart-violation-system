#!/bin/sh
set -e

# Render provides PORT automatically
PORT="${PORT:-10000}"

# Configure Apache to listen on Render's port
sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Ensure Laravel directories exist
mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache

# Create public storage symlink if needed
php artisan storage:link || true

# Cache production configuration and views
php artisan config:cache
php artisan view:cache

# Start Apache
exec "$@"
