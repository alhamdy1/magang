#!/bin/bash

echo "🔧 Fixing Laravel Server Issues..."
echo ""

# Update APP_URL for Codespaces
if [ ! -z "$CODESPACE_NAME" ]; then
    echo "0. Updating Codespaces URL..."
    CODESPACE_URL="https://${CODESPACE_NAME}-8000.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}"
    sed -i "s|^APP_URL=.*|APP_URL=$CODESPACE_URL|" .env
    echo "   ✓ APP_URL: $CODESPACE_URL"
fi

# Kill existing processes
echo ""
echo "1. Stopping existing server processes..."
fuser -k 8000/tcp 2>/dev/null
pkill -9 -f "php artisan serve" 2>/dev/null
sleep 2
echo "   ✓ Port 8000 cleared"

# Clear cache
echo ""
echo "2. Clearing Laravel cache..."
php artisan config:clear --quiet
php artisan cache:clear --quiet
php artisan view:clear --quiet
php artisan route:clear --quiet
echo "   ✓ Cache cleared"

# Check database
echo ""
echo "3. Verifying database connection..."
USERS=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ ! -z "$USERS" ]; then
    echo "   ✓ Database OK ($USERS users found)"
else
    echo "   ⚠ Database check failed, but continuing..."
fi

# Check storage permissions
echo ""
echo "4. Checking storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null
echo "   ✓ Permissions set"

# Start server
echo ""
echo "5. Starting Laravel development server..."
echo ""
echo "=========================================="
echo "  Server will run on http://0.0.0.0:8000"
echo "=========================================="
echo ""
echo "📌 IMPORTANT for GitHub Codespaces users:"
echo "   1. Wait 5-10 seconds after server starts"
echo "   2. Look for notification about port 8000"
echo "   3. Click 'Open in Browser' from notification"
echo "   4. Or go to PORTS tab and click globe icon"
if [ ! -z "$CODESPACE_NAME" ]; then
    echo ""
    echo "   Your Codespace URL:"
    echo "   $CODESPACE_URL"
fi
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

php artisan serve --host=0.0.0.0 --port=8000
