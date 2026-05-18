#!/bin/bash
# Update apache port to match Render's assigned port or 80
PORT=${PORT:-80}
sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Optional: You can uncomment these if you want to run cache/migrate on startup
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache
# php artisan migrate --force

# Start apache
apache2-foreground
